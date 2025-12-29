FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libsqlite3-dev \
    curl \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_sqlite \
    && a2dismod mpm_prefork \
    && a2enmod mpm_prefork \
    && a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --no-scripts

# Set permissions - Laravel storage & bootstrap/cache harus writable oleh web server
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 775 /var/www/html/storage
RUN chmod -R 775 /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/database

# Run composer post-install scripts
RUN composer run-script post-autoload-dump --no-interaction || true

# Remove default Apache configs that might conflict
RUN rm -f /etc/apache2/sites-enabled/*.conf /etc/apache2/mods-enabled/mpm*.load

# Configure Apache virtual host
RUN cat > /etc/apache2/sites-available/laravel.conf <<'EOF'
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options -Indexes +FollowSymLinks +MultiViews
        AllowOverride All
        Require all granted
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteBase /
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^ index.php [QSA,L]
        </IfModule>
    </Directory>

    <Directory /var/www/html>
        Require all denied
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

# Enable the Laravel site
RUN a2ensite laravel.conf
RUN a2dissite 000-default.conf || true

# Create startup script with proper error handling
RUN cat > /app-start.sh <<'SCRIPT'
#!/bin/bash
set -e

# Ensure storage and cache directories are writable
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html

# Create database directory if not exists
mkdir -p /var/www/html/database
chmod -R 777 /var/www/html/database

# Create SQLite database file if not exists
touch /var/www/html/database/app.sqlite
chmod 666 /var/www/html/database/app.sqlite

# Run migrations
php artisan migrate --force || true

# Clear and optimize cache
php artisan config:cache || true
php artisan view:clear || true
php artisan cache:clear || true

# Start Apache
apache2-foreground
SCRIPT

RUN chmod +x /app-start.sh

# Expose port
EXPOSE 80

# Start application
CMD ["/app-start.sh"]

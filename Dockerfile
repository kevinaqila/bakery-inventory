FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libsqlite3-dev \
    libpq-dev \
    libzip-dev \
    curl \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite pdo_pgsql pdo_mysql zip

# Disable all MPM modules first, then enable only mpm_prefork
RUN a2dismod mpm_event mpm_worker || true
RUN a2enmod mpm_prefork rewrite

# Set Apache ServerName to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --no-scripts

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && apt-get install -y nodejs

# Install npm dependencies and build Vue frontend
COPY package.json package-lock.json ./
RUN npm install --omit=dev
RUN npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/database

# Run composer post-install scripts
RUN composer run-script post-autoload-dump --no-interaction || true

# Configure Apache DocumentRoot to Laravel public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Enable .htaccess rewrite
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Create startup script
RUN cat > /app-start.sh <<'SCRIPT'
#!/bin/bash
set -e

# Ensure directories exist and are writable
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
chmod -R 777 /var/www/html/storage
chmod -R 777 /var/www/html/bootstrap/cache

# If using SQLite, create database file
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    mkdir -p /var/www/html/database
    chmod -R 777 /var/www/html/database
    touch /var/www/html/database/database.sqlite
    chmod 666 /var/www/html/database/database.sqlite
fi

# Create .env if it doesn't exist (use environment variables)
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env || true
fi

# Run migrations (will only create new tables if they don't exist)
php artisan migrate --force || true

# Check if database is empty (users table has no records)
USER_COUNT=$(php artisan tinker --execute="echo App\\Models\\User::count();" 2>/dev/null | tail -n 1 | tr -d '[:space:]' || echo "0")

# Run seeders ONLY if database is empty
if [ "$USER_COUNT" -eq "0" ]; then
    echo "Database is empty, running seeders..."
    php artisan db:seed --force || true
else
    echo "Database already has data, skipping seeders..."
fi

# Clear and cache config
php artisan config:clear || true
php artisan config:cache || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Create storage link
php artisan storage:link || true

# Start Apache in foreground
exec apache2-foreground
SCRIPT

RUN chmod +x /app-start.sh

# Expose port
EXPOSE 80

# Start application
CMD ["/app-start.sh"]
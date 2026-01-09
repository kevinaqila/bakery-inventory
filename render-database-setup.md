# Setup Database Persistent di Render

## Opsi 1: PostgreSQL (Render Native) - RECOMMENDED ✅

### Langkah-langkah:

#### 1. Buat PostgreSQL Database di Render
1. Login ke Render Dashboard
2. Klik "New +" → "PostgreSQL"
3. Beri nama: `bakery-inventory-db`
4. Pilih region yang sama dengan web service
5. Klik "Create Database"

#### 2. Copy Database Credentials
Setelah database dibuat, copy:
- Internal Database URL (untuk production) postgresql://bakery_inventory_klge_user:ddyRwKRjaR1oYsiTqpRbyYanEnTDlCrH@dpg-d58s8lje5dus73e4vv0g-a/bakery_inventory_klge
- Hostname dpg-d58s8lje5dus73e4vv0g-a
- Port 5432
- Database Name bakery_inventory_klge
- Username bakery_inventory_klge_user
- Password ddyRwKRjaR1oYsiTqpRbyYanEnTDlCrH

#### 3. Update Environment Variables di Render Web Service
Di web service kamu, tambahkan environment variables:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE

# Database PostgreSQL
DB_CONNECTION=pgsql
DB_HOST=<hostname-dari-render>
DB_PORT=5432
DB_DATABASE=<database-name>
DB_USERNAME=<username>
DB_PASSWORD=<password>

# Atau langsung pakai DATABASE_URL
DATABASE_URL=<internal-database-url-dari-render>

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database

# Queue
QUEUE_CONNECTION=database
```

---

## Opsi 2: MySQL (External Service) ✅

### Pilihan MySQL Host Gratis:

#### A. PlanetScale (Recommended untuk MySQL)
1. Daftar di https://planetscale.com/
2. Create new database: `bakery-inventory`
3. Get connection string
4. Copy credentials (host, username, password, database)

#### B. Railway.app
1. Daftar di https://railway.app/
2. New Project → MySQL
3. Copy credentials dari Variables tab

#### C. Aiven
1. Daftar di https://aiven.io/
2. Create MySQL service (free tier $0)
3. Copy credentials

### Setup MySQL di Render:

Di Render web service, set environment variables:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE

# Database MySQL
DB_CONNECTION=mysql
DB_HOST=<mysql-host>
DB_PORT=3306
DB_DATABASE=<database-name>
DB_USERNAME=<username>
DB_PASSWORD=<password>

# Jika pakai PlanetScale, tambahkan SSL
MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_STORE=database

# Queue
QUEUE_CONNECTION=database
```

---

## Catatan Penting:

### PostgreSQL vs MySQL di Render:
- **PostgreSQL**: Terintegrasi langsung dengan Render, setup lebih mudah
- **MySQL**: Perlu external service, tapi familiar jika sudah terbiasa

### Free Tier Limitations:
- **Render PostgreSQL**: 90 hari expire (bisa extend), 1GB storage
- **PlanetScale**: 5GB storage, 1 billion reads/month
- **Railway**: $5 credit/month (cukup untuk small apps)

### Dockerfile Support:
✅ Dockerfile sudah diupdate support: PostgreSQL, MySQL, dan SQLite
✅ Startup script otomatis detect database type
✅ Seed hanya jalan jika database kosong

---

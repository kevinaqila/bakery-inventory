# 🍰 Bakery Inventory Management System

A modern, full-stack web application for managing bakery inventory, transactions, and operations. Built with **Laravel 11**, **Vue 3**, **Inertia.js**, and **Tailwind CSS**.

> **Portfolio Project** - Demonstrating professional-grade Laravel and Vue.js development practices

---

## ✨ Features

### 🔐 **Authentication & Authorization**
- Multi-role user system (Admin, Kasir, Karyawan)
- Role-based access control (RBAC)
- Secure login with two-factor authentication support
- User management dashboard

### 📦 **Inventory Management**
- Product CRUD with categories
- SKU-based product tracking
- Real-time stock management (In/Out)
- Stock reorder level alerts
- Purchase & selling price tracking with margin calculation

### 💳 **Transaction Management**
- Point-of-sale (POS) system for cashiers
- Shopping cart with real-time calculations
- Multiple payment methods support
- Discount & change calculation
- Transaction history & invoices
- Auto stock deduction on transaction

### 📊 **Dashboard & Analytics**
- **Admin Dashboard**: Overall business metrics, top products, sales trends
- **Kasir Dashboard**: Daily transactions, top products, revenue metrics
- **Karyawan Dashboard**: Stock input history, recent activities
- Real-time data visualization

### 🔍 **Search & Filter**
- Product search by name/SKU
- Filter by category
- Pagination for better performance

---

## 🛠️ Tech Stack

### Backend
- **Laravel 11** - Modern PHP framework
- **PostgreSQL/SQLite** - Database
- **Eloquent ORM** - Database abstraction
- **Inertia.js** - Server-side rendering

### Frontend
- **Vue 3** - Progressive JavaScript framework
- **Composition API** - Modern Vue patterns
- **TypeScript** - Type safety
- **Tailwind CSS v4** - Utility-first CSS
- **Shadcn/ui** - Professional UI components

### Tools & DevOps
- **Vite** - Lightning-fast build tool
- **Pest PHP** - Modern testing framework
- **Git** - Version control
- **Railway/Heroku** - Cloud deployment

---

## 📋 Requirements

- **PHP** >= 8.2
- **Node.js** >= 18
- **Composer** >= 2.0
- **NPM** or **PNPM**

---

## 🚀 Installation

### 1. Clone Repository
```bash
git clone https://github.com/kevinaqila/bakery-inventory.git
cd bakery-inventory
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Setup Environment
```bash
# Copy example env file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file:
```env
DB_CONNECTION=sqlite
# OR for PostgreSQL:
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=bakery_inventory
DB_USERNAME=postgres
DB_PASSWORD=
```

### 5. Run Migrations & Seeders
```bash
# Run migrations
php artisan migrate

# Seed demo data
php artisan db:seed
```

### 6. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Start Application
```bash
php artisan serve
```

Visit: http://localhost:8000

---

## 👤 Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@manis-legit.test | password |
| Kasir | kasir@manis-legit.test | password |
| Karyawan | karyawan@manis-legit.test | password |

---

## 📁 Project Structure

```
bakery-inventory/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Inventory/       # Product, Category, Stock controllers
│   │   │   ├── Transaction/     # Transaction controllers
│   │   │   └── User/            # User management
│   │   └── Requests/            # Form validation
│   ├── Models/                  # Eloquent models
│   └── Actions/                 # Business logic
├── database/
│   ├── migrations/              # Database schema
│   └── seeders/                 # Demo data
├── resources/
│   ├── js/
│   │   ├── pages/              # Vue page components
│   │   ├── components/         # Reusable components
│   │   ├── layouts/            # Layout templates
│   │   └── app.ts              # Application entry
│   └── css/                    # Tailwind styles
├── routes/                     # API & web routes
└── tests/                      # Unit & feature tests
```

---

## 🎯 Key Features in Detail

### Product Management
- ✅ Full CRUD operations
- ✅ Category-based organization
- ✅ Price tracking (purchase & selling)
- ✅ Stock quantity management
- ✅ Reorder level alerts
- ✅ Search & filter functionality

### Transaction Processing
- ✅ Modern POS interface
- ✅ Real-time cart management
- ✅ Automatic stock deduction
- ✅ Payment calculation with discounts
- ✅ Invoice generation
- ✅ Transaction history

### Stock Management
- ✅ Stock in/out tracking
- ✅ Type-based recording (purchase, adjustment, return)
- ✅ Activity logging
- ✅ Notes & documentation

### Dashboard Analytics
- ✅ Role-specific dashboards
- ✅ Revenue metrics
- ✅ Top selling products
- ✅ Transaction statistics
- ✅ Stock activity monitoring

---

## 🧪 Testing

Run tests with:
```bash
php artisan test
```

Test categories:
- Feature tests for CRUD operations
- Authorization tests for role-based access
- Transaction calculation tests
- Stock management tests

---

## 📈 Performance Optimizations

- ✅ Database query optimization with eager loading
- ✅ Pagination for large datasets
- ✅ Asset minification & bundling
- ✅ Efficient component rendering

---

## 🔒 Security Features

- ✅ CSRF protection
- ✅ SQL injection prevention
- ✅ Role-based access control
- ✅ Password hashing with bcrypt
- ✅ Two-factor authentication support
- ✅ Secure session management

---

## 🌐 Deployment

### Deploy to Railway (Recommended)
1. Push to GitHub
2. Connect GitHub to Railway
3. Railway auto-detects Laravel
4. Configure environment variables
5. Run migrations automatically
6. App is live! 🚀

### Deploy to Heroku
```bash
heroku create bakery-inventory
git push heroku main
heroku run php artisan migrate
```

---

## 📚 API Endpoints

### Products
- `GET /products` - List products with pagination
- `POST /products` - Create product (admin only)
- `PUT /products/{id}` - Update product (admin only)
- `DELETE /products/{id}` - Delete product (admin only)

### Transactions
- `GET /transactions` - List transactions
- `POST /transactions` - Create transaction (kasir only)
- `GET /transactions/{id}` - View transaction details

### Stocks
- `GET /stocks` - List stock activities
- `POST /stocks` - Record stock in/out (karyawan)

### Users
- `GET /users` - List users (admin only)
- `POST /users` - Create user (admin only)
- `PUT /users/{id}` - Update user (admin only)
- `DELETE /users/{id}` - Delete user (admin only)

---

## 🐛 Known Issues & Future Improvements

### Future Enhancements
- [ ] Multi-store support
- [ ] Advanced analytics & reports
- [ ] Barcode scanning integration
- [ ] Receipt printing
- [ ] Email notifications
- [ ] Mobile app (React Native/Flutter)
- [ ] API documentation (OpenAPI/Swagger)

---

## 🤝 Contributing

This is a portfolio project, but contributions are welcome!

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## 📄 License

This project is open source and available under the MIT License.

---

## 👨‍💻 Author

**Kevin Aqila**
- GitHub: [@kevinaqila](https://github.com/kevinaqila)
- Email: your.email@gmail.com
- Portfolio: [your-portfolio.com](https://your-portfolio.com)

---

## 🙏 Acknowledgments

- Laravel community for excellent documentation
- Vue.js team for amazing framework
- Shadcn for beautiful UI components
- All contributors and supporters

---

## 📞 Support

For questions or issues:
1. Check GitHub Issues
2. Create new issue with detailed description
3. Include steps to reproduce bugs

---

## 🎓 Learning Resources

This project demonstrates:
- ✅ Professional Laravel architecture
- ✅ Vue 3 Composition API patterns
- ✅ Inertia.js integration
- ✅ Role-based authorization
- ✅ Modern UI/UX practices
- ✅ Database design & relationships
- ✅ RESTful API design
- ✅ Testing best practices

Perfect for learning full-stack development! 📚

---

**Last Updated:** December 28, 2025  
**Status:** ✅ Production Ready

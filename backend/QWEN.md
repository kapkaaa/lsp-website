# DistroZone-Web Backend Documentation

## Project Overview

DistroZone-Web is a Laravel-based e-commerce platform with a comprehensive admin panel, POS system, and customer-facing interface. The application supports multiple user roles (Admin, Cashier/Kasir, and Customer) and provides features for inventory management, order processing, payment handling, and customer service chat functionality.

### Key Technologies
- **Framework**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Tailwind CSS, Vite, JavaScript
- **Database**: SQLite (default, with support for MySQL)
- **Package Manager**: Composer (PHP), NPM (JavaScript)
- **Additional Libraries**: 
  - Laravel-dompdf for PDF generation
  - Intervention Image for image manipulation
  - Laravel-AdminLTE for admin interface
  - Concurrently for development workflow

### Architecture
The application follows Laravel's MVC pattern with the following key components:
- **Controllers**: Organized by user roles (Admin, Cashier, Customer)
- **Models**: Representing entities like User, Product, Order, Transaction
- **Middleware**: Custom role-based access control and operational hour checks
- **Routes**: Well-structured with role-based access control

## Features

### User Roles
1. **Admin**: Full access to all features including inventory management, user management, reports, and order processing
2. **Cashier (Kasir)**: Access to POS system, order management, and transaction processing
3. **Customer**: Product browsing, cart management, checkout, and order tracking

### Core Functionality
- **Product Management**: CRUD operations for products, variants, brands, types, colors, sizes
- **Order Processing**: Complete order lifecycle from creation to fulfillment
- **POS System**: Real-time point-of-sale functionality for cashiers
- **Inventory Management**: Stock tracking and management
- **Payment Handling**: Payment upload and verification system
- **Customer Service**: Chat functionality between customers and admins
- **Reporting**: Sales, stock, and profit reports with PDF/Excel export
- **Operational Hours**: Business hour restrictions for online ordering
- **Shipping Management**: Configurable shipping rates

## Building and Running

### Prerequisites
- PHP 8.2+
- Composer
- Node.js and npm
- SQLite (or MySQL)

### Setup Instructions

1. **Install Dependencies**:
```bash
composer install
npm install
```

2. **Environment Configuration**:
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**:
```bash
# For SQLite (default)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed the database (if needed)
php artisan db:seed
```

4. **Run Development Server**:
```bash
# Using Laravel's built-in dev command (from composer.json)
composer run dev

# Or separately:
php artisan serve
npm run dev
```

### Key Commands
- `composer install` - Install PHP dependencies
- `npm install` - Install JavaScript dependencies
- `php artisan migrate` - Run database migrations
- `php artisan db:seed` - Seed the database with sample data
- `php artisan serve` - Start development server
- `npm run dev` - Start Vite development server
- `npm run build` - Build production assets
- `composer run dev` - Run full development environment with concurrent processes

### Testing
- `php artisan test` - Run PHPUnit tests
- Uses Laravel's integrated testing framework

## Development Conventions

### Code Style
- Follows PSR-12 coding standards
- Uses Laravel Pint for automatic code formatting (`php artisan pint`)
- Consistent naming conventions for controllers, models, and routes

### Routing Structure
- Routes organized by user roles (customer, admin, cashier)
- RESTful resource controllers where appropriate
- Role-based middleware applied at route groups
- Operational hour checks for time-sensitive features

### Security
- Role-based access control implemented via custom middleware
- Password hashing using Laravel's default hasher
- CSRF protection enabled by default
- Input validation in controllers

### File Structure
```
app/
├── Console/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── Admin/
│   │   ├── Cashier/
│   │   └── Customer/
│   ├── Middleware/
├── Models/
├── Providers/
└── Services/
```

## Key Components

### Authentication
- Custom AuthController handles login/register/logout
- Role-based authentication using CheckRole middleware
- User model with helper methods (isAdmin(), isKasir(), isCustomer())

### Middleware
- `CheckRole`: Enforces role-based access control
- `CheckOperationalHours`: Restricts access based on business hours

### Models
- User relationships with roles, orders, transactions, and chats
- Comprehensive product model with variants, photos, and details
- Order and payment models with full transaction tracking

### Frontend Assets
- Tailwind CSS for styling
- Vite for asset bundling
- Integrated with Laravel's asset management

## Special Features

### Operational Hours Control
The system implements business hour restrictions using the `CheckOperationalHours` middleware, which prevents customers from placing orders outside operational hours.

### Multi-role Interface
Different user types (Admin, Cashier, Customer) have tailored interfaces with appropriate access levels and functionality.

### Real-time POS System
Cashiers have access to a dedicated POS system for in-person transactions with receipt printing capabilities.

### Reporting System
Comprehensive reporting dashboard with sales, stock, and profit reports exportable in PDF and Excel formats.
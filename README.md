# Multi-Tenant Flat & Bill Management System

A comprehensive Laravel-based multi-tenant property management system designed for house owners to manage their buildings, flats, tenants, and billing operations with complete data isolation and professional user interface.

## 🚀 Overview

This system provides a complete solution for property management with multi-tenant architecture, allowing house owners to manage their buildings and bills while ensuring complete data isolation between different tenants. Built with Laravel 12.x and Bootstrap 5.3, it offers a professional, human-like interface that's both functional and visually appealing.

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/tusharkhan/multitenant.git
   cd multitanant
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=multitenant_property
   DB_USERNAME=your_username
   DB_PASSWORD=your_password


   MAIL_MAILER=smtp
    MAIL_HOST=your_host
    MAIL_PORT=2525
    MAIL_USERNAME=name
    MAIL_PASSWORD=password
    MAIL_FROM_ADDRESS="hello@example.com"
    MAIL_FROM_NAME="${APP_NAME}"
   ```

5. **Run Migrations and Seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   
   Open http://localhost:8000 in your browser

## Demo Accounts

After running the seeders, you can use these demo accounts:

### Admin Account
- **Email**: `admin@demo.com`
- **Password**: `password`
- **Features**: Full system access, user management

### House Owner Account
- **Email**: `owner@demo.com`
- **Password**: `password`
- **Features**: Building and bill management for tenant ID 100001

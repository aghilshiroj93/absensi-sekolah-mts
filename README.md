# Laravel QR Code Attendance System

A Laravel-based attendance system that uses QR codes to allow users to clock in and out efficiently. Ideal for schools, offices, and events.

## 🚀 Features

- User check-in/check-out via QR code
- Unique QR code generation for each user
- Admin dashboard with attendance reports
- Daily, weekly, and monthly analytics
- Email notifications (optional)
- Role-based access control (Admin/User)

## 🛠 Tech Stack

- Laravel 10+
- MySQL / PostgreSQL
- Blade Templating
- Laravel QR Code (Simple QrCode)
- Laravel Breeze (Authentication)
- Tailwind CSS (optional UI framework)

---

## 📦 Installation

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL or PostgreSQL
- Node.js & npm (for assets)
- Git

### Steps

```bash
# Clone the repository
git clone https://github.com/yourusername/laravel-qrcode-attendance.git

cd laravel-qrcode-attendance

# Install dependencies
composer install

# Copy .env and generate app key
cp .env.example .env
php artisan key:generate

# Configure your .env with DB credentials

# Run migrations and seeders
php artisan migrate --seed

# Install front-end dependencies
npm install && npm run dev

# Start local development server
php artisan serve

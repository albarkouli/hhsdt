 # Libya Market

Multi-vendor e-commerce platform for Libya.

## Tech Stack
- Laravel 13
- Livewire 3
- Tailwind CSS 4
- PostgreSQL 17

## Features
- Customer storefront
- Vendor dashboard
- Admin panel
- Cash on Delivery
- Arabic PDF invoices
- Commission system

## Installation
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```n
## Testing
```bash
php artisan test
```n
76 tests passing.
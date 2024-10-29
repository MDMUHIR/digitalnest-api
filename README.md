# Zero Config e-Commerce API Boilerplate with Laravel
## Setup instructions
### Clone repository
```bash
git git@github.com:Mastering-Vue-js/complete-ecommerce-laravel.git
cd complete-ecommerce-laravel
```
### Install dependency
```bash
composer install
```
### Copy `.env.example` to `.env` file
```bash
cp .env.example .env
```
### Connect Database
- Create a database in your mysql. e.g. complete-ecommerce-laravel
- Place database credentials in you `.env` file
```env
DB_DATABASE=complete-ecommerce-laravel
DB_USERNAME=<DATABASE-USER> #this is database user name
DB_PASSWORD=<DATABASE-PASSWORD> #this is database password
```
### Run database migration
Clear database first if you already setup the project
```bash
php artisan db:wipe
```
```bash
php artisan migrate --seed
```
It will create default admin and customer users.
- Admin access: `admin@gmail.com`:`admin`
- Customer access: `customer@gmail.com`:`customer`
### Generate App key
```bash
php artisan key:generate
```
### Run Application
```bash
php artisan serve
```
# Subscription Billing API

A Laravel API for managing subscription billing workflows. The project is set up with Laravel Sail, MySQL, Vite, PHPUnit, and the standard Laravel application structure.

## Tech Stack

- PHP 8.3+
- Laravel 13
- Laravel Sail
- MySQL 8.4
- PHPUnit
- Vite / Tailwind CSS

## Requirements

- Docker Desktop
- Composer
- Node.js and npm

## Getting Started With Sail

Install PHP dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
```

For Sail, make sure the database host in `.env` points to the MySQL service:

```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=recurringp
DB_USERNAME=sail
DB_PASSWORD=password
```

Start the containers:

```bash
./vendor/bin/sail up -d
```

Generate the application key:

```bash
./vendor/bin/sail artisan key:generate
```

Run database migrations:

```bash
./vendor/bin/sail artisan migrate
```

Install frontend dependencies and build assets:

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

The application will be available at:

```text
http://localhost
```

## Common Commands

Start the application:

```bash
./vendor/bin/sail up -d
```

Stop the application:

```bash
./vendor/bin/sail down
```

Run Artisan commands:

```bash
./vendor/bin/sail artisan <command>
```

Open a shell inside the application container:

```bash
./vendor/bin/sail shell
```

Run the test suite:

```bash
./vendor/bin/sail test
```

Run Laravel Pint:

```bash
./vendor/bin/sail pint
```

Run the Vite development server:

```bash
./vendor/bin/sail npm run dev
```

## API Scope

This project is intended to support subscription billing features such as:

- Customers
- Plans and pricing
- Subscriptions
- Invoices
- Payments
- Billing events and status changes

API routes should be added in Laravel's route files as the billing features are implemented.

## Project Structure

```text
app/          Application code
config/       Laravel configuration
database/     Migrations, factories, and seeders
public/       Public entry point
resources/    Frontend assets and views
routes/       Application routes
tests/        Feature and unit tests
```

## Testing

Run all tests with:

```bash
./vendor/bin/sail test
```

You can also run Laravel's test command directly:

```bash
./vendor/bin/sail artisan test
```

## License

This project is open-sourced software licensed under the MIT license.

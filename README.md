# Subscription Billing API

A Laravel API for managing subscription billing workflows. The project is set up with Laravel Sail, MySQL, Laravel Passport OAuth authentication, PHPUnit, and API resource controllers.

## Tech Stack

- PHP 8.4+
- Laravel 13
- Laravel Sail
- Laravel Passport OAuth2
- MySQL 8.4
- PHPUnit

## Requirements

- Docker Desktop
- Composer

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

Set up Laravel Passport OAuth keys and create a personal access client:

```bash
./vendor/bin/sail artisan passport:keys
./vendor/bin/sail artisan passport:client --personal
```

The API will be available at:

```text
http://localhost/api
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

## API Scope

This project is intended to support subscription billing features such as:

- Companies / tenants
- Customers
- Plans and pricing
- Subscriptions
- Invoices
- Payments
- Billing events and status changes

Billing resources are scoped by `company_id` so the API can support multiple companies using the same application.

API routes are registered in `routes/api.php`.

## Authentication

The API uses the `laravel/passport` package for OAuth2 bearer token authentication. Login and registration return an access token that clients must send with protected API requests.

Open endpoints:

```text
POST /api/auth/register
POST /api/auth/login
```

Protected endpoints require this header:

```text
Authorization: Bearer <access_token>
```

Authenticated endpoints:

```text
GET  /api/auth/me
POST /api/auth/logout
```

## Project Structure

```text
app/          Application code
config/       Laravel configuration
database/     Migrations, factories, and seeders
public/       Public entry point
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

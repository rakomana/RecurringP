# Subscription Billing API

A Laravel API for managing subscription billing workflows. The project is set up with Laravel Sail, MySQL, Laravel Passport OAuth authentication, Laravel Scramble API documentation, PHPUnit, and API resource controllers.

## Tech Stack

- PHP 8.4+
- Laravel 13
- Laravel Sail
- Laravel Passport OAuth2
- Laravel Scramble OpenAPI docs
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

## API Documentation

The API documentation is generated with the `dedoc/scramble` package.

```text
GET /docs/api
GET /docs/api.json
```

Scramble is configured in `app/Providers/AppServiceProvider.php` to document routes under `/api` and include bearer token authentication in the OpenAPI schema.

In deployed environments, Scramble protects the docs by default. To make the docs public, set:

```dotenv
SCRAMBLE_DOCS_PUBLIC=true
```

After changing this value in production, clear the config cache:

```bash
php artisan config:clear
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

## CI/CD

This project uses GitHub Actions for CI. The workflow is defined in `.github/workflows/ci.yml` and runs on pushes to `main` / `master` and on pull requests.

The pipeline:

- Sets up PHP 8.4
- Installs Composer dependencies
- Prepares the Laravel environment
- Generates Passport keys
- Runs the test suite

The application is deployed to Laravel Cloud:

```text
https://recurringp-main-lpmv55.laravel.cloud
```

After deployment, make sure the Laravel Cloud environment variables are configured, including database credentials, Passport keys/client setup, and:

```dotenv
SCRAMBLE_DOCS_PUBLIC=true
```

## License

This project is open-sourced software licensed under the MIT license.

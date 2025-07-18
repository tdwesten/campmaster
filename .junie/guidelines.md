# Architecture Guidelines for Campmaster

## Purpose and Overview

- Professional and efficient campsite management application
- Handles reservations, places, guests, payments, reports etc.

## Event Sourcing Implementation

- Uses Spatie Event Sourcing package
- Reservations/bookings stored as events (e.g. ReservationCreated, PaymentReceived)
- Aggregates manage reservation status
- Projections/read models enable fast reporting

## Data Model Structure
- Use UUIDs for unique identifiers
- Database migrations for schema management
- Create factories for test data generation
- Use Action pattern for business logic

### Tenants
- Multi-tenancy via subdomains eg. `campingdenachtegaal.campmaster.nl`
- Fields in `tenants` table:
    - `id`: UUID primary key
    - `name`: Name of the campsite
    - `domain`: Subdomain for the tenant (e.g. `campingdenachtegaal`), automatically generated as a slug from the name and cannot be overwritten
    - `created_at`, `updated_at`: Timestamps

### Accommodations

- Generic `accommodations` tabl
    - Contains all rental units
    - Includes common fields like name, description, capacity, etc.
- Supports different accommodation types:
    - Camping places
    - Tents
    - Cottages
    - Lodges
- Flexibly expandable with new types

### Bookings and Guests

- `bookings` table contains main reservation data:
    - Guest details
    - Period
    - Rental unit
- `guests` table stores main guest/contact person data


### Extra Costs and Options

- `products` table for all possible extras:
    - Electricity
    - Campfire wood
    - Bed linen
    - Extra guests
    - Pets
- `booking_items` table links extras to reservations
- Flexible model requires no changes for new products/services

### Tourist Tax Handling

- Separate table for tourist tax rates by:
    - Campsite/municipality
    - Validity dates
- Automatic per night/person calculation
- Added to reservation as regular booking item

## Key Advantages

- Flexible, scalable and future-proof architecture
- Simplified reporting and invoicing
- Complete audit trail via event sourcing
# Development Guidelines for Campmaster Frontend

This document provides essential information for developers working on the Campmaster project.


## Build & Configuration

### Requirements
- PHP 8.3+
- Node.js (latest LTS recommended)
- Composer
- npm
- Laravel 12
- Inertia.js
- React 19
- Tailwind CSS
- Shadcn UI
- Vite
- Pest PHP for testing

### Laravel specifics
- Uses Laravel 12 with Inertia.js for server-side rendering
- Multi-tenancy implemented using a tenants table and subdomain routing
- Event sourcing for reservations and bookings
- Use UUID for unique identifiers
- Database migrations for schema management
- Use Action pattern for business logic
- Use Form Requests for validation
- Use Policies for authorization
- Use Jobs for background processing
- Use Events and Listeners for decoupled event handling
- Use Service Providers for bootstrapping application services
- Use Spatie Settings for configuration management
- Use Spatie Media Library for file uploads and media management
- Use Spatie Permissions for role-based access control
- Use Spatie Activity Log for auditing changes

### Setup
1. Clone the repository
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install JavaScript dependencies:
   ```bash
   npm install
   ```
4. Set up environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

### Development Server
Start the development server with:
```bash
composer dev
```

This command runs multiple processes concurrently:
- Laravel development server
- Queue worker
- Log watcher
- Vite development server

For server-side rendering, use:
```bash
composer dev:ssr
```

### Building for Production
```bash
npm run build       # Standard build
npm run build:ssr   # Build with server-side rendering
```

## Testing
- We use Pest PHP for testing, which is built on top of PHPUnit.

### Test Structure
- **Feature Tests**: `tests/Feature/` - Test HTTP endpoints and application features
- **Unit Tests**: `tests/Unit/` - Test individual components and functions

### Running Tests
Run all tests:
```bash
composer test
# or
php artisan test
```

Run specific tests:
```bash
php artisan test tests/Feature/ExampleTest.php
php artisan test tests/Unit/SimpleCalculatorTest.php
```

### Writing Tests
This project uses [Pest PHP](https://pestphp.com/) for testing, which provides a more expressive syntax on top of PHPUnit.
- Use `act`, `assert`, and `expect` methods for assertions
- Keep tests isolated and independent
- Use descriptive names for test methods

#### Example Unit Test
```php
<?php

class Calculator
{
    public function add($a, $b)
    {
        return $a + $b;
    }
}

test('calculator can add two numbers', function () {
    $calculator = new Calculator();
    expect($calculator->add(2, 3))->toBe(5);
});
```

#### Example Feature Test
```php
<?php

it('returns a successful response', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});
```

### Test Configuration
- Tests use SQLite in-memory database
- The `RefreshDatabase` trait can be uncommented in `tests/Pest.php` if database tests need a fresh database for each test

## Code Style & Development Practices

### Code Formatting
- The project uses Prettier for JavaScript/TypeScript formatting
- 4 spaces for indentation (2 spaces for YAML files)
- Single quotes for strings
- 150 character line length
- Semicolons required

Format code with:
```bash
npm run format
```

Check formatting without making changes:
```bash
npm run format:check
```

### Linting
Run ESLint:
```bash
npm run lint
```

### Type Checking
Check TypeScript types:
```bash
npm run types
```

### Static Analysis
The project uses PHPStan for static analysis of PHP code. PHPStan helps identify potential bugs and issues before they occur in production.

Run PHPStan:
```bash
composer phpstan
```

PHPStan is configured with level 5 (medium strictness) and analyzes the following directories:
- app
- config
- database
- routes

The configuration is stored in `phpstan.neon` in the project root.

### Frontend Architecture
- React 19 with TypeScript
- Inertia.js for connecting Laravel with React
- Tailwind CSS for styling, use cn utility for conditional class names
- Shadcn UI for reusable components
- Vite for building and bundling

### Backend Architecture
- Laravel 12
- Multi-tenancy support via Spatie's package
- Inertia.js for server-side rendering

## Multi-tenancy

The application uses Spatie's Laravel Multitenancy package to implement subdomain-based multi-tenancy.

### How It Works

- Each tenant has a unique subdomain (e.g., `campingdenachtegaal.campmaster.nl`)
- The base domain is configured in `.env` with the `APP_DOMAIN` variable (defaults to `campmaster.nl`)
- When a request comes in, the application identifies the tenant based on the subdomain
- Tenant-specific data is isolated using cache prefixing

### Creating Tenants

You can create tenants programmatically:

```php
use App\Models\Tenant;

$tenant = Tenant::create([
    'name' => 'Camping De Nachtegaal',
]);
```

Or using the artisan command:

```bash
php artisan tenants:create --name="Camping De Nachtegaal" 
```

### Accessing Tenant Information

In your application code, you can access the current tenant:

```php
$currentTenant = Tenant::current();

// Get the tenant's name
$tenantName = $currentTenant->name;

// Get the tenant's domain
$tenantDomain = $currentTenant->domain;

// Get the tenant's full domain (including base domain)
$tenantFullDomain = $currentTenant->full_domain; // e.g., campingdenachtegaal.campmaster.nl
```

### Testing Multi-tenancy

The test suite includes tests for the multi-tenancy functionality:

```bash
php artisan test tests/Feature/Multitenancy
```

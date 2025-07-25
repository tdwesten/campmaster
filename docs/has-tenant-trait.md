# HasTenant Trait

The `HasTenant` trait provides tenant filtering capabilities for models in the Campmaster application. It allows you to
easily filter records by the current tenant or a specific tenant.

## Implementation

The trait is implemented in the following models:

- `Booking` - Uses direct tenant_id column
- `Guest` - Uses indirect relationship through bookings
- `User` - Uses many-to-many relationship through tenant_user pivot table

## Usage

### Basic Usage

To filter records by the current tenant:

```php
// Get all bookings for the current tenant
$bookings = Booking::tenant()->get();

// Get all guests for the current tenant
$guests = Guest::tenant()->get();

// Get all users for the current tenant
$users = User::tenant()->get();
```

### Filtering by a Specific Tenant

You can also filter records by a specific tenant:

```php
$tenant = Tenant::find($tenantId);

// Get all bookings for the specified tenant
$bookings = Booking::tenant($tenant)->get();

// Get all guests for the specified tenant
$guests = Guest::tenant($tenant)->get();

// Get all users for the specified tenant
$users = User::tenant($tenant)->get();
```

### Combining with Other Query Conditions

The `tenant()` scope can be combined with other query conditions:

```php
// Get all active bookings for the current tenant
$activeBookings = Booking::tenant()
    ->where('status', BookingStatus::Active)
    ->get();

// Get all guests with email from a specific domain for the current tenant
$guests = Guest::tenant()
    ->where('email', 'like', '%@example.com')
    ->get();

// Get all users with a specific role for the current tenant
$users = User::tenant()
    ->whereHas('roles', function ($query) {
        $query->where('name', 'admin');
    })
    ->get();
```

## How It Works

The `HasTenant` trait handles different types of tenant relationships:

1. **Direct Relationship** (e.g., Booking):
    - Filters by the `tenant_id` column directly

2. **Many-to-Many Relationship** (e.g., User):
    - Filters by the `tenants` relationship using a whereHas query

3. **Indirect Relationship** (e.g., Guest):
    - Filters by the `bookings.tenant_id` column using a whereHas query

## Testing

To test the implementation, you can create tests that verify the correct records are returned for each model type:

```php
public function test_booking_tenant_scope()
{
    // Create tenants
    $tenant1 = Tenant::factory()->create();
    $tenant2 = Tenant::factory()->create();
    
    // Create bookings for each tenant
    $booking1 = Booking::factory()->create(['tenant_id' => $tenant1->id]);
    $booking2 = Booking::factory()->create(['tenant_id' => $tenant2->id]);
    
    // Set current tenant
    Tenant::current($tenant1);
    
    // Assert that only bookings for tenant1 are returned
    $this->assertCount(1, Booking::tenant()->get());
    $this->assertTrue(Booking::tenant()->get()->contains($booking1));
    $this->assertFalse(Booking::tenant()->get()->contains($booking2));
}

public function test_guest_tenant_scope()
{
    // Create tenants
    $tenant1 = Tenant::factory()->create();
    $tenant2 = Tenant::factory()->create();
    
    // Create guests
    $guest1 = Guest::factory()->create();
    $guest2 = Guest::factory()->create();
    
    // Create bookings linking guests to tenants
    Booking::factory()->create([
        'tenant_id' => $tenant1->id,
        'guest_id' => $guest1->id,
    ]);
    
    Booking::factory()->create([
        'tenant_id' => $tenant2->id,
        'guest_id' => $guest2->id,
    ]);
    
    // Set current tenant
    Tenant::current($tenant1);
    
    // Assert that only guests for tenant1 are returned
    $this->assertCount(1, Guest::tenant()->get());
    $this->assertTrue(Guest::tenant()->get()->contains($guest1));
    $this->assertFalse(Guest::tenant()->get()->contains($guest2));
}

public function test_user_tenant_scope()
{
    // Create tenants
    $tenant1 = Tenant::factory()->create();
    $tenant2 = Tenant::factory()->create();
    
    // Create users
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    // Associate users with tenants
    $user1->tenants()->attach($tenant1);
    $user2->tenants()->attach($tenant2);
    
    // Set current tenant
    Tenant::current($tenant1);
    
    // Assert that only users for tenant1 are returned
    $this->assertCount(1, User::tenant()->get());
    $this->assertTrue(User::tenant()->get()->contains($user1));
    $this->assertFalse(User::tenant()->get()->contains($user2));
}
```

## Global Scope

If you want to automatically apply the tenant scope to all queries for a model, you can uncomment the global scope in
the `bootHasTenant` method in the trait. This will ensure that all queries for the model are automatically filtered by
the current tenant.

```php
public static function bootHasTenant(): void
{
    static::addGlobalScope('tenant', function (Builder $builder) {
        $builder->tenant();
    });
}
```

Be careful with this approach, as it can make it difficult to query across tenants when needed.

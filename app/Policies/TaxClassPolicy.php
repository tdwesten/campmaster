<?php

namespace App\Policies;

use App\Models\TaxClass;
use App\Models\User;

class TaxClassPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // TODO: integrate roles (Admin/Manager)
    }

    public function view(User $user, TaxClass $taxClass): bool
    {
        return true; // TODO: integrate roles
    }

    public function create(User $user): bool
    {
        return true; // TODO: restrict to Admin when roles exist
    }

    public function update(User $user, TaxClass $taxClass): bool
    {
        return true; // TODO: restrict to Admin when roles exist
    }

    public function delete(User $user, TaxClass $taxClass): bool
    {
        return true; // TODO: restrict to Admin when roles exist
    }
}

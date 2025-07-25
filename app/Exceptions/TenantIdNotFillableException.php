<?php

namespace App\Exceptions;

use Exception;

class TenantIdNotFillableException extends Exception
{
    public function __construct(string $modelClass)
    {
        parent::__construct("Model {$modelClass} must include 'tenant_id' in its \$fillable property when using the HasTenant trait.");
    }
}

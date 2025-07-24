<?php

namespace App\Models;

use Spatie\LaravelSettings\Models\SettingsProperty;

class Settings extends SettingsProperty
{
    protected $fillable = [
        'tenant_id',
        'group',
        'name',
        'payload',
        'locked',
    ];
}

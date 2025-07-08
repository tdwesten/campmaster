<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tenant extends Model
{
    use HasFactory;
    use HasSlug;
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('domain')
            ->preventOverwrite();
    }

    /**
     * Get the current tenant based on the request's subdomain.
     *
     * @return \App\Models\Tenant|null
     */
    public static function current(): ?self
    {
        $host = Request::getHost();
        $baseDomain = config('app.domain');

        // If we're not using a subdomain or we're on localhost, return null
        if ($host === $baseDomain || $host === 'localhost' || $host === '127.0.0.1') {
            return null;
        }

        // Extract the subdomain from the host
        $subdomain = str_replace('.' . $baseDomain, '', $host);

        // Find the tenant by domain
        return self::where('domain', $subdomain)->first();
    }
}

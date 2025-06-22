<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class CreateTenantCommand extends Command
{
    protected $signature = 'tenants:create {--name= : The name of the tenant} {--domain= : The domain of the tenant}';
    protected $description = 'Create a new tenant with the given name and domain';

    public function handle()
    {
        $name = $this->option('name');
        $domain = $this->option('domain');

        if (!$name) {
            $name = $this->ask('What is the name of the tenant?');
        }

        if (!$domain) {
            $domain = $this->ask('What is the domain of the tenant? (e.g., campingdenachtegaal)');
        }

        $tenant = Tenant::create([
            'name' => $name,
            'domain' => $domain,
        ]);

        $this->info("Tenant created successfully!");
        $this->table(
            ['ID', 'Name', 'Domain', 'Full Domain'],
            [[$tenant->id, $tenant->name, $tenant->domain, $tenant->full_domain]]
        );
    }
}

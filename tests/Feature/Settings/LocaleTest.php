<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    Config::set('app.domain', 'campmaster.nl');
    $this->runLandlordMigrations();
});

it('can switch language to Dutch and reflects in html', function () {
    $tenant = Tenant::factory()->create(['name' => 'Camping De Nachtegaal']);
    $tenant->makeCurrent();

    $user = User::factory()->create(['email_verified_at' => now()]);

    // Post the locale change
    $response = $this->actingAs($user)->post('/settings/appearance/locale', [
        'locale' => 'nl',
    ]);

    $response->assertRedirect();
    $response->assertPlainCookie('locale', 'nl');

    // Next request should render with lang="nl"
    $page = $this->withUnencryptedCookies(['locale' => 'nl'])
        ->actingAs($user)
        ->get('/settings/appearance');

    $page->assertOk();
    $page->assertSee('lang="nl"', false);
});

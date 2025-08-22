<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class AppearanceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('settings/appearance');
    }

    public function updateLocale(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'in:en,nl'],
        ]);

        // Persist as a cookie for 1 year
        return Redirect::back()->withCookies([
            cookie('locale', $validated['locale'], 60 * 24 * 365, null, null, false, false, false, 'Lax'),
        ]);
    }
}

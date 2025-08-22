<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App as LaravelApp;
use Symfony\Component\HttpFoundation\Response;

class HandleLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale') ?? config('app.locale');

        if (is_string($locale) && in_array($locale, ['en', 'nl'], true)) {
            LaravelApp::setLocale($locale);
        }

        return $next($request);
    }
}

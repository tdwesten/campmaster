<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class BookingsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('bookings/index', [
            'bookings' => [
                // Future: provide bookings projection data
            ],
        ]);
    }
}

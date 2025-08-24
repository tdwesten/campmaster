<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel money
     |--------------------------------------------------------------------------
     */
    'locale' => config('app.locale', 'nl_NL'),
    'defaultCurrency' => config('app.currency', 'EUR'),
    'defaultFormatter' => null,
    'defaultSerializer' => null,
    'isoCurrenciesPath' => is_dir(__DIR__.'/../vendor')
        ? __DIR__.'/../vendor/moneyphp/money/resources/currency.php'
        : __DIR__.'/../../../moneyphp/money/resources/currency.php',
    'currencies' => [
        'iso' => ['EUR'],
        'bitcoin' => [],
        'custom' => [
            // 'MY1' => 2,
            // 'MY2' => 3
        ],
    ],
];

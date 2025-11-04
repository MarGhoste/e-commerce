<?php

use App\Models\User;
use Laravel\Cashier\Cashier;

return [

    // Debe usar env('STRIPE_KEY')
    'key' => env('STRIPE_KEY'),

    // Debe usar env('STRIPE_SECRET')
    'secret' => env('STRIPE_SECRET'),

    // ...
];

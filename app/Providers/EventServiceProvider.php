<?php

namespace App\Providers;

use App\Events\OrderPaid;
use App\Listeners\UpdateProductStock;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        OrderPaid::class => [
            UpdateProductStock::class,
        ],
    ];
}

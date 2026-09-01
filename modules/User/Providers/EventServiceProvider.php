<?php

namespace Mod\User\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Mod\User\Events\UserRegistered;
use Mod\User\Listeners\SendWelcomeEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Mapa zdarzeń i przypisanych do nich słuchaczy dla modułu User.
     */
    protected $listen = [
        UserRegistered::class => [
            SendWelcomeEmail::class,            
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}

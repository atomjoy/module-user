<?php

namespace Mod\User\Listeners;

use Mod\User\Events\UserRegistered;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail
{
    public function handle(UserRegistered $event): void
    {
        Log::info("Wysłano powitanie do użytkownika: " . $event->user->email);
    }
}

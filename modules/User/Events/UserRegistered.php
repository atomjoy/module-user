<?php

namespace Mod\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Mod\User\Models\User;

class UserRegistered
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user) {}
}

<?php

namespace Mod\User\Controllers;

use App\Http\Controllers\Controller;
use Mod\User\Events\UserRegistered;
use Mod\User\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $user = User::factory()->create();

        UserRegistered::dispatch($user);

        return view('module-user::profile', [
            'name' => 'Jan Kowalski'
        ]);
    }

    public function show()
    {
        return Inertia::render('User::Profile', [
            'name' => 'Jan Kowalski'
        ]);
    }

    // Wstrzyknięty $user to instancja Mod\User\Models\User
    public function settings(User $user)
    {
        return view('module-user::settings', ['user' => $user]);
    }
}

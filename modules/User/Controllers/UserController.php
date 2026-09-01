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

        // Dispatch events
        UserRegistered::dispatch($user);

        return view('module-user::profile', [
            'name' => 'Jan Kowalski'
        ]);
    }

    public function show()
    {
        // "User::Profile" skieruje Vite do: modules/User/Resources/Pages/Profile.vue
        return Inertia::render('User::Profile', [
            'name' => 'Jan Kowalski'
        ]);
    }

    public function settings(User $user)
    {
        // Wstrzyknięty $user to instancja Mod\User\Models\User
        return view('module-user::settings', ['user' => $user]);

        // test('service container prawidłowo wstrzykuje model użytkownika do kontrolera', function () {
        //     // Tworzymy usera w bazie
        //     $user = \Mod\User\Models\User::factory()->create();
        //     // Odpytujemy trasę z ID użytkownika
        //     $this->get("/user/settings/{$user->id}")
        //         ->assertStatus(200);
        // });
    }
}

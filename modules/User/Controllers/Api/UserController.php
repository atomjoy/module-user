<?php

namespace Mod\User\Controllers\Api;

use App\Http\Controllers\Controller;
use Mod\User\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // Pobieramy użytkowników z bazy (wybieramy tylko id i name)
        $users = User::select('id', 'name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);

        // return response()->json([
        //     'status' => 'success',
        //     'data' => [
        //         ['id' => 1, 'name' => 'Jan Kowalski'],
        //         ['id' => 2, 'name' => 'Anna Nowak']
        //     ]
        // ]);
    }
}

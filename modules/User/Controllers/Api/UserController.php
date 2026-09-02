<?php

namespace Mod\User\Controllers\Api;

use App\Http\Controllers\Controller;
use Mod\User\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }
}

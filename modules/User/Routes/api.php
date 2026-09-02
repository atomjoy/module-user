<?php

use Illuminate\Support\Facades\Route;

// Route /api/users
Route::get('/users', 'UserController@index');
// Route::get('/users/{id}', 'Api/UserController@show');
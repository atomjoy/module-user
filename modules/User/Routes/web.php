<?php

use Illuminate\Support\Facades\Route;

// Route /profil
Route::get('/blade/profile', 'UserController@index');
Route::get('/vue/profile', 'UserController@show');

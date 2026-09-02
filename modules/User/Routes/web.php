<?php

use Illuminate\Support\Facades\Route;

Route::get('/blade/profile', 'UserController@index');
Route::get('/vue/profile', 'UserController@show');

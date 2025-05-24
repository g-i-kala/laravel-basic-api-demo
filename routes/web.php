<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/setup', function () {

    $credentials = [
        'email' => 'admin@example.com',
        'password' => 'password'
    ];

    if (!Auth::attempt($credentials)) {
        $user = new \App\Models\User();

        $user->name = 'Admin';
        $user->email = $credentials['email'];
        $user->password = bcrypt($credentials['password']);

        $user->save();
    };

    $user = Auth::user();

    $adminToken = $user->createToken('admin-token', ['store', 'update', 'delete']);
    $updateToken = $user->createToken('update-token', ['store', 'update']);
    $basicToken = $user->createToken('basic-token', []);

    return [
        'admin' => $adminToken->plainTextToken,
        'update' => $updateToken->plainTextToken,
        'basic' => $basicToken->plainTextToken
    ];
});

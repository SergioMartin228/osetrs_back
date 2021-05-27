<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;


Route::post('/get_token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required'
    ]);
 
    $user = User::where('email', $request->email)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
 
    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/get_all', 'GoodController@getAll');
Route::middleware('auth:sanctum')->get('/change_price', 'GoodController@changePrice');
Route::middleware('auth:sanctum')->get('/delete', 'GoodController@delete');
Route::middleware('auth:sanctum')->post('/create', 'GoodController@create');
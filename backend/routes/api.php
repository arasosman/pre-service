<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::post('/tokens/create', function (Request $request) {
            $token = $request->user()->createToken($request->input('token_name', 'default'));

            return ['token' => $token->plainTextToken];
        });
    });
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/curricula', function () {
    return response()->json(\App\Models\Curriculum::with('sme:id,name')->get());
});

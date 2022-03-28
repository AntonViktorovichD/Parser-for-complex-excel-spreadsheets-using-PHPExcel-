<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UploadController;

Route::get('/', [UploadController::class, 'form']);
Route::post('/ul', [UploadController::class, 'upload']);

use App\Http\Controllers\jsonController;

Route::get('/json', [jsonController::class, 'arrayToJson']);
Route::get('/tables/{name}', [jsonController::class, 'tables']);

use App\Http\Controllers\AddController;

Route::get('/add/{name}', [AddController::class, 'add']);

use App\Http\Controllers\UserUploadController;
Route::post('/user_upload', [UserUploadController::class, 'user_upload']);

use App\Http\Controllers\EditController;
Route::get('/edit/{name}', [EditController::class, 'edit']);

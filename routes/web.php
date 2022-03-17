<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UploadController;

Route::get('/', [UploadController::class, 'form']);
Route::post('/ul', [UploadController::class, 'upload']);

use App\Http\Controllers\jsonController;

Route::get('/json', [jsonController::class, 'arrayToJson']);
Route::get('/tables/{name}', [jsonController::class, 'tables']);

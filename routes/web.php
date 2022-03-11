<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\UploadController;

Route::get('/', [UploadController::class, 'form']);
Route::post('/ul', [UploadController::class, 'upload']);
Route::get('/ldb', [UploadController::class, 'loadDB']);

use App\Http\Controllers\jsonController;

Route::get('/json', [jsonController::class, 'arrayToJson']);
Route::get('/db', [jsonController::class, 'tables']);
Route::get('/tables/{name}', [jsonController::class, 'tables']);

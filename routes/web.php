<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\UploadController;

Route::get('/', [UploadController::class, 'form']);
Route::post('/ul', [UploadController::class, 'upload']);
//Route::get('/re', [UploadController::class, 'excelToArray']);

//use App\Http\Controllers\ExcelController;
//
//Route::get('/re', [ExcelController::class, 'excelToArray']);

use App\Http\Controllers\jsonController;

Route::get('/json', [jsonController::class, 'arrayToJson']);
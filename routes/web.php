<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//
use App\Http\Controllers\ExcelController;

Route::get('/', [ExcelController::class, 'excelToArray']);

use App\Http\Controllers\jsonController;

Route::get('/json', [jsonController::class, 'arrayToJson']);

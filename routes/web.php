<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UploadController;

Route::get('/', [UploadController::class, 'form'])->middleware('auth');
Route::post('/ul', [UploadController::class, 'upload'])->middleware('auth');

use App\Http\Controllers\jsonController;

Route::get('/json', [jsonController::class, 'arrayToJson'])->middleware('auth');
Route::get('/tables/{name}', [jsonController::class, 'tables'])->middleware('auth');

use App\Http\Controllers\AddController;

Route::get('/add/{name}', [AddController::class, 'add'])->middleware('auth');

use App\Http\Controllers\UserUploadController;
Route::post('/user_upload', [UserUploadController::class, 'user_upload'])->middleware('auth');

use App\Http\Controllers\EditController;
Route::get('/edit/{name}', [EditController::class, 'edit'])->middleware('auth');

use App\Http\Controllers\UserUpgradeController;
Route::post('/user_upgrade', [UserUpgradeController::class, 'user_upgrade'])->middleware('auth');

//Route::redirect('/', 'admin/home');

Auth::routes(['register' => false]);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password')->middleware('auth');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password')->middleware('auth');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
    Route::resource('permissions', 'Admin\PermissionsController')->middleware('auth');
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy')->middleware('auth');
    Route::resource('roles', 'Admin\RolesController')->middleware('auth');
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy')->middleware('auth');
    Route::resource('users', 'Admin\UsersController')->middleware('auth');;
    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy')->middleware('auth');
});

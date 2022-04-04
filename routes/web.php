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

use App\Http\Controllers\UserUpgradeController;
Route::post('/user_upgrade', [UserUpgradeController::class, 'user_upgrade']);

//Route::redirect('/', 'admin/home');

Auth::routes(['register' => false]);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy');
    Route::resource('roles', 'Admin\RolesController');
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');
    Route::resource('users', 'Admin\UsersController');
    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy');
});

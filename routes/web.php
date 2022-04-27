<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UploadController;

Route::redirect('/', 'admin/home');

Route::get('/add', [UploadController::class, 'form'])->middleware('auth');
Route::post('/ul', [UploadController::class, 'upload'])->middleware('checkRole');

use App\Http\Controllers\showUserController;

Route::post('/show', [showUserController::class, 'showUser']);

use App\Http\Controllers\AddDBController;

Route::get('/id', [AddDBController::class, 'edit']);

use App\Http\Controllers\jsonController;

Route::get('/json', [jsonController::class, 'arrayToJson'])->middleware('auth');
Route::get('/tables/{name}', [jsonController::class, 'tables'])->middleware('auth');

use App\Http\Controllers\AddController;

Route::get('/add/{name}', [AddController::class, 'add'])->middleware('checkRole');

use App\Http\Controllers\EditController;

Route::get('/edit/{name}', [EditController::class, 'edit'])->middleware('auth');
Route::get('/admin_edit/{name}', [EditController::class, 'edit'])->middleware('checkRole');

use App\Http\Controllers\UserUploadController;

Route::post('/user_upload', [UserUploadController::class, 'user_upload'])->middleware('auth');

use App\Http\Controllers\UserUpgradeController;

Route::post('/user_upgrade', [UserUpgradeController::class, 'user_upgrade'])->middleware('auth');

use App\Http\Controllers\AdminUserUpgradeController;

Route::post('/admin_user_upgrade', [AdminUserUpgradeController::class, 'admin_user_upgrade'])->middleware('auth');

Auth::routes(['register' => false]);

Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password')->middleware('auth');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password')->middleware('auth');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy');
    Route::resource('roles', 'Admin\RolesController');
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');
    Route::resource('users', 'Admin\UsersController');
    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy');
});

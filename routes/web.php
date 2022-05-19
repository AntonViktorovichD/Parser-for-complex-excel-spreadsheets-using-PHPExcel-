<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', 'home');
Route::get('/home', 'HomeController@index');

use App\Http\Controllers\UploadController;

Route::get('/add', [UploadController::class, 'form'])->middleware('auth');
Route::post('/ul', [UploadController::class, 'upload'])->middleware('auth');

use App\Http\Controllers\ShowUserController;

Route::post('/show', [ShowUserController::class, 'showUser']);

use App\Http\Controllers\AddDBController;

Route::get('/id', [AddDBController::class, 'edit']);

use App\Http\Controllers\JsonController;

Route::get('/json', [JsonController::class, 'arrayToJson'])->middleware('auth');
Route::get('/tables/{name}', [JsonController::class, 'tables'])->middleware('auth');
Route::post('/handler', array(JsonController::class, 'handler'));

use App\Http\Controllers\AddController;

Route::get('/add/{name}', [AddController::class, 'add'])->middleware('checkAdmin');

use App\Http\Controllers\EditController;

Route::get('/edit/{name}', [EditController::class, 'edit'])->middleware('auth');
Route::get('/admin_edit/{name}', [EditController::class, 'edit'])->middleware('checkAdmin');

use App\Http\Controllers\UserUploadController;

Route::post('/user_upload', [UserUploadController::class, 'user_upload'])->middleware('auth');

use App\Http\Controllers\UserUpgradeController;

Route::post('/user_upgrade', [UserUpgradeController::class, 'user_upgrade'])->middleware('auth');

use App\Http\Controllers\AdminUserUpgradeController;

Route::post('/admin_user_upgrade', [AdminUserUpgradeController::class, 'admin_user_upgrade'])->middleware('checkAdmin');

Auth::routes(['register' => false]);

Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password')->middleware('checkAdmin');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password')->middleware('checkAdmin');

Route::group(['middleware' => ['auth'], 'prefix' => 'administrator', 'as' => 'admin.'], function () {
    Route::get('/', 'AdminController@index')->name('home')->middleware('checkAdmin');;
    Route::resource('permissions', 'Admin\PermissionsController')->middleware('checkAdmin');;
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy')->middleware('checkAdmin');;
    Route::resource('roles', 'Admin\RolesController')->middleware('checkAdmin');;
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy')->middleware('checkAdmin');;
    Route::resource('users', 'Admin\UsersController')->middleware('checkAdmin');;
    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy')->middleware('checkAdmin');;
});

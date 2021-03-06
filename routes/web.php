<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', 'home');
Route::get('/home', 'HomeController@index');

use App\Http\Controllers\MonthlyReportsController;

Route::get('/monthly_report/{name}/{year}', [MonthlyReportsController::class, 'monthly_report'])->middleware('auth');
Route::get('/monthly_user_report/{name}/{year}/{quarter}/{department}', [MonthlyReportsController::class, 'monthly_user_report'])->middleware('auth');
Route::post('/monthly_upload', [MonthlyReportsController::class, 'monthly_upload'])->middleware('auth');
Route::post('/monthly_update', [MonthlyReportsController::class, 'monthly_update'])->middleware('auth');

use App\Http\Controllers\AdminReportsController;

Route::get('/admin_reports', [AdminReportsController::class, 'admin_reports'])->middleware('checkAdmin');

use App\Http\Controllers\AdminDailyReportController;

Route::get('/admin_daily_report/{name}', [AdminDailyReportController::class, 'admin_daily_report'])->middleware('checkAdmin');
Route::post('/admin_daily_update', [AdminDailyReportController::class, 'admin_daily_update'])->middleware('checkAdmin');

use App\Http\Controllers\AdminWeeklyReportController;

Route::get('/admin_weekly_report/{name}', [AdminWeeklyReportController::class, 'admin_weekly_report'])->middleware('checkAdmin');
Route::post('/admin_weekly_update', [AdminWeeklyReportController::class, 'admin_weekly_update'])->middleware('checkAdmin');

use App\Http\Controllers\DailyReportsController;

Route::get('/daily_reports', [DailyReportsController::class, 'daily_reports'])->middleware('auth');

use App\Http\Controllers\DailyReportController;

Route::get('/daily_report/{name}', [DailyReportController::class, 'daily_report'])->middleware('auth');
Route::post('/daily_upload', [DailyReportController::class, 'daily_upload'])->middleware('auth');
Route::post('/daily_update', [DailyReportController::class, 'daily_update'])->middleware('auth');

use App\Http\Controllers\WeeklyReportController;

Route::get('/weekly_report/{name}', [WeeklyReportController::class, 'weekly_report'])->middleware('auth');
Route::post('/weekly_upload', [WeeklyReportController::class, 'weekly_upload'])->middleware('auth');
Route::post('/weekly_update', [WeeklyReportController::class, 'weekly_update'])->middleware('auth');

use App\Http\Controllers\QuarterlyReportsController;
Route::get('/quarterly_reports', [QuarterlyReportsController::class, 'quarterly_reports'])->middleware('auth');
Route::get('/quarterly_report/{name}/{year}', [QuarterlyReportsController::class, 'quarterly_report'])->middleware('auth');
Route::get('/quarterly_user_report/{name}/{year}/{quarter}/{department}', [QuarterlyReportsController::class, 'quarterly_user_report'])->middleware('auth');
Route::post('/quarterly_upload', [QuarterlyReportsController::class, 'quarterly_upload'])->middleware('auth');
Route::post('/quarterly_update', [QuarterlyReportsController::class, 'quarterly_update'])->middleware('auth');

use App\Http\Controllers\TestController;

Route::get('/test', [TestController::class, 'test']);
Route::post('/testul', [TestController::class, 'ultest']);

use App\Http\Controllers\UploadController;

Route::get('/user_add', [UploadController::class, 'form'])->middleware('auth');

use App\Http\Controllers\DeleteTablesController;

Route::get('/delete_tables', [DeleteTablesController::class, 'delete_tables'])->middleware('checkAdmin');
Route::get('/delete_table/{name}/{stat}', [DeleteTablesController::class, 'delete_table'])->middleware('checkAdmin');

use App\Http\Controllers\ShowUserController;

Route::post('/show', [ShowUserController::class, 'showUser'])->middleware('auth');

use App\Http\Controllers\AddDBController;

Route::get('/id', [AddDBController::class, 'edit'])->middleware('auth');

use App\Http\Controllers\JsonController;

Route::get('/json', [JsonController::class, 'arrayToJson'])->middleware('auth');
Route::get('/tables/{name}', [JsonController::class, 'tables'])->middleware('auth');
Route::post('/handler', array(JsonController::class, 'handler'))->middleware('auth');

use App\Http\Controllers\AddController;

Route::get('/add/{name}', [AddController::class, 'add'])->middleware('checkAdmin');

use App\Http\Controllers\EditController;

Route::get('/edit/{name}', [EditController::class, 'edit'])->middleware('auth');
Route::get('/admin_edit/{name}', [EditController::class, 'edit'])->middleware('checkAdmin');
Route::get('/admin_view/{name}', [EditController::class, 'edit'])->middleware('checkAdmin');

use App\Http\Controllers\UserUploadController;

Route::post('/user_upload', [UserUploadController::class, 'user_upload'])->middleware('auth');

use App\Http\Controllers\UserUpgradeController;

Route::post('/user_upgrade', [UserUpgradeController::class, 'user_upgrade'])->middleware('auth');

use App\Http\Controllers\AdminUserUpgradeController;

Route::post('/router', [AdminUserUpgradeController::class, 'admin_user_upgrade'])->middleware('checkAdmin');

use App\Http\Controllers\ExportSheetController;

Route::get('/export/{name}', [ExportSheetController::class, 'export'])->middleware('auth');

use App\Http\Controllers\AdminExportSheetController;

Route::get('/admin_export/{name}', [AdminExportSheetController::class, 'admin_export'])->middleware('checkAdmin');

use App\Http\Controllers\SendUsersController;

Route::get('/send_users', [SendUsersController::class, 'send'])->middleware('checkAdmin');
Route::post('/router', [SendUsersController::class, 'get_options'])->middleware('checkAdmin');

use App\Http\Controllers\MailController;

Route::get('/mail', [MailController::class, 'send_mail']);

use App\Http\Controllers\SmsController;

Route::get('/sms', [SmsController::class, 'send_sms']);

Auth::routes(['register' => false]);

Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password')->middleware('checkAdmin');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password')->middleware('checkAdmin');

Route::group(['middleware' => ['auth'], 'prefix' => 'administrator', 'as' => 'admin.'], function () {
    Route::get('/', 'AdminController@index')->name('home')->middleware('checkAdmin');
    Route::resource('permissions', 'Admin\PermissionsController')->middleware('checkAdmin');
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy')->middleware('checkAdmin');
    Route::resource('roles', 'Admin\RolesController')->middleware('checkAdmin');
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy')->middleware('checkAdmin');
    Route::resource('users', 'Admin\UsersController')->middleware('checkAdmin');
    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy')->middleware('checkAdmin');
});

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserUploadController extends Controller {
    public function user_upload(Request $request) {
        date_default_timezone_set('Europe/Moscow');
        $created_at = date('Y-m-d, H:i:s');
        try {
            DB::connection()->getPdo();
            $input = $request->all();
            list($table_name, $table_uuid, $row_uuid) = explode(' + ', $request->input('table_information'));
            unset($input['_token'], $input['table_information']);
            $json_val = json_encode($input, JSON_UNESCAPED_UNICODE);
            DB::insert('insert into report_values (table_name, table_uuid, row_uuid, json_val, created_at) values (?, ?, ?, ?, ?)', [$table_name, $table_uuid, $row_uuid, $json_val, $created_at]);
            return view('user_upload', ['name' => $table_name, 'alert' => 'Запись успешно добавлена']);
        } catch (\Exception $e) {
            die("Нет подключения к базе данных.");
        }
    }
}

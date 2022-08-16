<?php

namespace App\Http\Controllers;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserUploadController extends Controller {
    public function user_upload(Request $request) {
       if(empty(Auth::id())) {
          return redirect()->route('login');
       }
        date_default_timezone_set('Europe/Moscow');
        $created_at = date('Y-m-d H:i:s');
        try {
            DB::connection()->getPdo();
            $input = $request->except('_token', 'table_information');
            list($table_name, $table_uuid, $row_uuid, $user_id, $user_dep) = explode(' + ', $request->input('table_information'));
            $json_val = json_encode($input, JSON_UNESCAPED_UNICODE);
            DB::insert('insert into report_values (table_name, table_uuid, row_uuid, user_id, user_dep, json_val, created_at) values (?, ?, ?, ?, ?, ?, ?)', [$table_name, $table_uuid, $row_uuid, $user_id, $user_dep, $json_val, $created_at]);
            return view('router', ['alert' => 'Запись успешно добавлена', 'route' => '/json']);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}

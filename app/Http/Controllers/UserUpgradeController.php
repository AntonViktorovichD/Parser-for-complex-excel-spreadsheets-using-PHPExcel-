<?php

namespace App\Http\Controllers;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserUpgradeController extends Controller {
    public function user_upgrade(Request $request) {
       if(empty(Auth::id())) {
          return redirect()->route('login');
       }
        date_default_timezone_set('Europe/Moscow');
        $created_at = date('Y-m-d, H:i:s');
        try {
            DB::connection()->getPdo();
            $input = $request->except('_token', 'table_information');
            list($table_name, $table_uuid, $row_uuid, $user_id) = explode(' + ', $request->input('table_information'));
            $json_val = json_encode($input, JSON_UNESCAPED_UNICODE);
            DB::table('report_values')->where('table_uuid', $table_uuid)->where('row_uuid', $row_uuid)->where('user_id', $user_id)->update(['json_val' => $json_val, 'created_at' => $created_at]);
            return view('router', ['alert' => 'Запись успешно отредактирована', 'route' => '/json']);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}

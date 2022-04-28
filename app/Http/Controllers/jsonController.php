<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class jsonController extends Controller {
    public function arrayToJson() {
        try {
            $user_role = Auth::user()->roles->first()->id;
            $user_id = Auth::id();
            $arr = json_encode(DB::select('select * from tables'));
            $arr_rows = json_encode(DB::select('select * from report_values'));
            return view('arrayToJson', ['arr' => $arr, 'tableload' => '', 'arr_rows' => $arr_rows, 'user_id' => $user_id, 'user_role' => $user_role]);
        } catch (\Exception $e) {
            die("Нет подключения к базе данных.");
        }
    }

    public function tables($name) {
        $json = json_encode(DB::table('tables')->where('table_name', $name)->value('json_val'));
        $highest_column_index = DB::table('tables')->where('table_name', $name)->value('highest_column_index');
        $highest_row = DB::table('tables')->where('table_name', $name)->value('highest_row');
        $radio = DB::table('tables')->where('table_name', $name)->value('radio');
        return view('table', compact('json', 'highest_row', 'highest_column_index', 'radio'));
    }
}


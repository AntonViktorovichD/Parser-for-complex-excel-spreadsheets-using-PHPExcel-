<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class jsonController extends Controller {
    public function arrayToJson() {
        try {
            $arr = json_encode(DB::select('select * from tables'));
        } catch (\Exception $e) {
            die("Нет подключения к базе данных.");
        }
        return view('arrayToJson', ['arr' => $arr, 'tableload' => '']);
    }

    public function tables($name) {
        $json = json_encode(DB::table('tables')->where('table_name', $name)->value('json_val'));
        $highest_column_index = DB::table('tables')->where('table_name', $name)->value('highest_column_index');
        $highest_row = DB::table('tables')->where('table_name', $name)->value('highest_row');
        return view('table', ['json' => $json, 'highest_row' => $highest_row, 'highest_column_index' => $highest_column_index]);
    }
}


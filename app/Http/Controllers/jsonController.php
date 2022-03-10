<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class jsonController extends Controller {
    public function arrayToJson() {
        $arr = DB::select('select json_val from tables');
        $hr = DB::select('select highest_row from tables');
        $hci = DB::select('select highest_column_index from tables');
        $json = json_encode($arr);
        $highest_row = json_encode($hr);
        $highest_column_index = json_encode($hci);
        return view('arrayToJson', ['json' => $json, 'highest_row' => $highest_row, 'highest_column_index' => $highest_column_index]);
    }

    public function tables() {
        $tables = DB::table('tables')->get();
        $tables_id = DB::table('tables')->pluck('id');
        $tables_creat = DB::table('tables')->pluck('created_at');
        return view('tables', ['tables' => $tables, 'tables_id' => $tables_id, 'tables_creat' => $tables_creat]);
    }
}


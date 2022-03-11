<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class jsonController extends Controller {
    public function arrayToJson() {
        $arr = json_encode(DB::select('select * from tables'));
        return view('arrayToJson', ['arr' => $arr]);
    }

    public function tables($name) {
        $val = json_encode(DB::table('tables')->where('table_name', $name)->value('json_val'));
        $colind = DB::table('tables')->where('table_name', $name)->value('highest_column_index');
        $highrow = DB::table('tables')->where('table_name', $name)->value('highest_row');
        return view('table', ['json' => $val, 'highest_row' => $highrow, 'highest_column_index' => $colind]);
    }
}


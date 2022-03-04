<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class jsonController extends Controller {
    public function arrayToJson() {
        $arr = DB::select('select json_val from tables');
        $hr = DB::select('select highest_row from tables');
        $hci = DB::select('select highest_column_index from tables');
//        $json = json_decode(json_decode(json_encode($table), true)[0]['json_val'], true);
        $json = json_encode($arr);
        $highest_row = json_encode($hr);
        $highest_column_index = json_encode($hci);
//        var_dump(json_decode(json_decode($highest_column_index, true)[0]['highest_column_index']));

        return view('arrayToJson', ['json' => $json, 'highest_row' => $highest_row, 'highest_column_index' => $highest_column_index]);

    }
}

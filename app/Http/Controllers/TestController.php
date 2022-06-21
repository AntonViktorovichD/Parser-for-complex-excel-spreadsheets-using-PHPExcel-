<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PHPExcel_Cell;
use PHPExcel_IOFactory;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class TestController extends Controller {
    public function test(Request $request) {
        $table_uuid = $request->get('table_uuid');
        $table = DB::table('tables')->where('table_uuid', '=', $table_uuid)->get();
        $departments = json_decode($table[0]->departments, true);
        var_dump($departments);
//        foreach ($table as $key => $tab) {
//            echo '<pre>';
//            var_dump($tab);
//            echo '</pre>';
//        }
    }
}

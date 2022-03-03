<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JSONController extends Controller {
    public function arrayToJSON() {
        $table = DB::select('select json_val from tables');
        $json = json_decode(json_decode(json_encode($table), true)[0]['json_val'], true);
        echo '<pre>';
        var_dump($json);
        echo '</pre>';
    }
}

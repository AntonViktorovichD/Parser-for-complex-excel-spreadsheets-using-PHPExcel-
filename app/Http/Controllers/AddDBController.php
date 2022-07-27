<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class AddDBController extends Controller {
    public function edit() {
        $table = DB::table('tables')->get();
//        echo '<pre>';
//        var_dump(count($table));
//        echo '</pre>';

        echo '1c311586-c2ce-4698-8061-0c88ad0aae38';


        foreach ($table as $item) {
            $table_uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
            DB::table('tables')->where('id', $item->id)->update(['table_uuid' => $table_uuid]);
//            echo '<pre>';
//            var_dump($item->id);
//            echo '</pre>';
        }
//        $depart_helper = DB::table('depart_helper')->pluck('title');
//        $depart_helper = (json_decode(json_encode($depart_helper, JSON_UNESCAPED_UNICODE), true));
//        array_unshift($depart_helper, '');
//        unset($depart_helper[0]);
//        $org_helper = DB::table('org_helper')->pluck('depart_title');
//        $org_helper = (json_decode(json_encode($org_helper, JSON_UNESCAPED_UNICODE), true));
//        array_unshift($org_helper, '');
//        unset($org_helper[0]);
//
//
//        foreach ($depart_helper as $k => $val) {
//            foreach ($org_helper as $key => $item) {
//                if (rtrim($val) == rtrim($item)) {
//                    DB::table('org_helper')->where(['depart_title' => $val])->update(['depart_id' => $k]);
//                }
//            }
//        }


        return view('id');
    }
}

//$distr_helper = DB::table('distr_helper')->pluck('title');
//$distr_helper = (json_decode(json_encode($distr_helper, JSON_UNESCAPED_UNICODE), true));
//array_unshift($distr_helper, '');
//unset($distr_helper[0]);
//$org_helper = DB::table('org_helper')->pluck('distr_title');
//$org_helper = (json_decode(json_encode($org_helper, JSON_UNESCAPED_UNICODE), true));
//array_unshift($org_helper, '');
//unset($org_helper[0]);
//
//
//foreach ($distr_helper as $k => $val) {
//    foreach ($org_helper as $key => $item) {
//        if (rtrim($val) == rtrim($item)) {
//            DB::table('org_helper')->where(['distr_title' => $val])->update(['distr_id' => $k]);
//        }
//    }
//}
//
//
//return view('id');
//}
//}




<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class AddDBController extends Controller {
    public function edit() {

        $depart_helper = DB::table('depart_helper')->pluck('title');
        $depart_helper = (json_decode(json_encode($depart_helper, JSON_UNESCAPED_UNICODE), true));
        array_unshift($depart_helper, '');
        unset($depart_helper[0]);
        $org_helper = DB::table('org_helper')->pluck('depart_title');
        $org_helper = (json_decode(json_encode($org_helper, JSON_UNESCAPED_UNICODE), true));
        array_unshift($org_helper, '');
        unset($org_helper[0]);


        foreach ($depart_helper as $k => $val) {
            foreach ($org_helper as $key => $item) {
                if (rtrim($val) == rtrim($item)) {
                    DB::table('org_helper')->where(['depart_title' => $val])->update(['depart_id' => $k]);
                }
            }
        }


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




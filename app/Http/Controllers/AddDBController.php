<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class AddDBController extends Controller {
    public function edit() {

        $distr_helper = DB::table('distr_helper')->pluck('title');
        $distr_helper = (json_decode(json_encode($distr_helper, JSON_UNESCAPED_UNICODE), true));
        array_unshift($distr_helper, '');
        unset($distr_helper[0]);
        $org_helper = DB::table('org_helper')->pluck('distr_title');
        $org_helper = (json_decode(json_encode($org_helper, JSON_UNESCAPED_UNICODE), true));
        array_unshift($org_helper, '');
        unset($org_helper[0]);




        foreach ($distr_helper as $k => $val) {
            foreach ($org_helper as $key => $item) {
                if (rtrim($val) == rtrim($item)) {
                    DB::table('org_helper')->where(['distr_title' => $val])->update(['distr_id' => $k]);
                }
            }
        }


        return view('id');
    }
}


//        $distr_title = DB::table('distr_helper')->pluck('title');
//        $distr_title = (json_decode(json_encode($distr_title, JSON_UNESCAPED_UNICODE), true));
//        array_unshift($distr_title, '');
//        unset($distr_title[0]);
//        $depart_title = DB::table('depart_helper')->pluck('depart_title');
//        $depart_title = (json_decode(json_encode($depart_title, JSON_UNESCAPED_UNICODE), true));
//        array_unshift($depart_title, '');
//        unset($depart_title[0]);
//        foreach ($distr_title as $k => $val) {
//            foreach ($depart_title as $key => $item) {
//                if ($val == $item) {
//                    DB::table('depart_helper')->where(['depart_title' => $val])->update(['distr_id' => $k]);
//                }
//            }
//        }

?>

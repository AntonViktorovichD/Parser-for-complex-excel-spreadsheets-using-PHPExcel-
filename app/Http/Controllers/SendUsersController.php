<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class SendUsersController extends Controller {
    public function send() {
        $orgs = json_decode(DB::table('org_helper')->orderBy('distr_id', 'asc')->orderBy('depart_id', 'asc')->get(), true);
        $districts = DB::table('distr_helper')->orderBy('id', 'asc')->get();
        $departments = DB::table('depart_helper')->orderBy('id', 'asc')->get();
        $notification_rights = DB::table('notification_rights')->get();
        $users = [];
        for ($i = 0; $i < count($orgs); $i++) {
            $users[$i]["org"] = $orgs[$i]["title"];
            $users[$i]["org_id"] = $orgs[$i]["id"];
            $users[$i]["district_id"] = $orgs[$i]["distr_id"];
            $users[$i]["district"] = $orgs[$i]["distr_title"];
            $users[$i]["department_id"] = $orgs[$i]["depart_id"];
            $users[$i]["department"] = $orgs[$i]["depart_title"];
        }
        $users = json_encode($users, JSON_UNESCAPED_UNICODE);
        return view('send_users', compact('users', 'departments', 'districts', 'notification_rights'));
    }

    public function get_options(Request $request) {
        $alinements = $request->except('_token', 'e_mail', 'global_sms');
        $global_email = $request->input('global_email');
        if (isset($global_email)) {
            $e_mail = 1;
        } else {
            $e_mail = 0;
        }
        $global_sms = $request->input('global_sms');
        if (isset($global_sms)) {
            $mobile_phone = 1;
        } else {
            $mobile_phone = 0;
        }
        DB::table('notification_rights')->where('id', 1)->update(['e_mail' => $e_mail, 'mobile_phone' => $mobile_phone]);
//        foreach ($alinements as $key => $alinement) {
////            var_dump(str_contains($key, 'global_email'));
//            var_dump($key);
//
//        }
        return view('send_users_upgrade');
    }
}

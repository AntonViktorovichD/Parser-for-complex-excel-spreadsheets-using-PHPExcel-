<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class SendUsersController extends Controller {
    public function send() {
        $orgs = json_decode(DB::table('org_helper')->orderBy('distr_title', 'asc')->orderBy('depart_title', 'asc')->get(), true);
        $districts = DB::table('distr_helper')->orderBy('id', 'asc')->get();
        $departments = DB::table('depart_helper')->orderBy('id', 'asc')->get();
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
        return view('send_users', compact('users', 'departments', 'districts'));
    }

    public function get_options(Request $request) {
//        $alinements = $request->except('_token');
//        foreach ($alinements as $key => $alinement) {
//            var_dump($key .  ' => ' . $alinement);
//
//        }
        return view('send_users_upgrade');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class SendUsersController extends Controller {
    public function send() {
        $users = json_decode(DB::table('users')->get(), true);
        for ($i = 0; $i < count($users); $i++) {
            $users[$i]["org"] = DB::table('org_helper')->where('depart_id', '=', $users[$i]["department"])->where('distr_id', '=', $users[$i]["district"])->value('title');
            $users[$i]["district"] = DB::table('distr_helper')->where('id', '=', $users[$i]["district"])->value('title');
            $users[$i]["department"] = DB::table('depart_helper')->where('id', '=', $users[$i]["department"])->value('title');
            unset($users[$i]["remember_token"], $users[$i]["password"], $users[$i]["created_at"], $users[$i]["updated_at"]);
        }
        echo '<pre>';
        var_dump($users);
        echo '</pre>';
        return view('send_users', compact('users'));
    }
}

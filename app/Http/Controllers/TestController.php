<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class TestController extends Controller {
    public function test(Request $request) {
        $notification_rights = DB::table('notification_rights')->where('id', '=', 1)->get()[0];
        $notifications = [];
        if ($notification_rights->e_mail != 0 || $notification_rights->mobile_phone != 0) {
            $table_uuid = $request->get('table_uuid');
            $table = DB::table('tables')->where('table_uuid', '=', $table_uuid)->get();
            $table_name = $table[0]->table_name;
            $departments = json_decode($table[0]->departments, true);
            foreach ($departments as $department) {
                $notification = DB::table('user_noifications')->where('org_id', '=', $department)->get()[0];
                $notifications[$department]['e_mail'] = $notification->e_mail;
                $notifications[$department]['specialist_mobile_phone'] = $notification->specialist_mobile_phone;
                $notifications[$department]['directors_mobile_phone'] = $notification->directors_mobile_phone;
            }
//        foreach ($table as $key => $tab) {
//            echo '<pre>';
//            var_dump($tab);
//            echo '</pre>';
//        }
        }
        foreach ($notifications as $key => $notification) {
            if($notification['e_mail'] == 1) {
                var_dump(DB::table('users')->where('department', '=', $key)->value('email'));
            }
        }
    }
}

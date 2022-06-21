<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Database\QueryException;

class SendUsersController extends Controller {
    public function send() {
        try {
            $orgs = json_decode(DB::table('org_helper')->orderBy('distr_id', 'asc')->orderBy('depart_id', 'asc')->get(), true);
            $districts = DB::table('distr_helper')->orderBy('id', 'asc')->get();
            $departments = DB::table('depart_helper')->orderBy('id', 'asc')->get();
            $notification_rights = DB::table('notification_rights')->get();
            $checked = DB::table('user_noifications')->get();
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
            return view('send_users', compact('users', 'departments', 'districts', 'notification_rights', 'checked'));
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }

    public function get_options(Request $request) {
        try {
            $arr_checked = [];
            $alinements = $request->except('_token', 'global_email', 'global_sms', 'global_time');
            $global_email = $request->input('global_email');
            $e_mail = isset($global_email) ? 1 : 0;
            $global_sms = $request->input('global_sms');
            $mobile_phone = isset($global_sms) ? 1 : 0;
            DB::table('notification_rights')->where('id', 1)->update(['e_mail' => $e_mail, 'mobile_phone' => $mobile_phone, 'time_delay' => $request->input('global_time')]);
            foreach ($alinements as $key => $alinement) {
                if (preg_replace('#_\d+#', '', $key) == 'email') {
                    $arr_checked[$alinement]['email'] = 1;
                }
                if (preg_replace('#_\d+#', '', $key) == 'directors_mobile_phone') {
                    $arr_checked[$alinement]['directors_mobile_phone'] = 1;
                }
                if (preg_replace('#_\d+#', '', $key) == 'specialist_mobile_phone') {
                    $arr_checked[$alinement]['specialist_mobile_phone'] = 1;
                }
            }
            foreach ($arr_checked as $key => $value) {
                if (empty($arr_checked[$key]['email'])) {
                    $arr_checked[$key]['email'] = 0;
                }
                if (empty($arr_checked[$key]['directors_mobile_phone'])) {
                    $arr_checked[$key]['directors_mobile_phone'] = 0;
                }
                if (empty($arr_checked[$key]['specialist_mobile_phone'])) {
                    $arr_checked[$key]['specialist_mobile_phone'] = 0;
                }
                DB::table('user_noifications')->where('org_id', $key)->update(['e_mail' => $arr_checked[$key]['email'], 'directors_mobile_phone' => $arr_checked[$key]['directors_mobile_phone'], 'specialist_mobile_phone' => $arr_checked[$key]['specialist_mobile_phone']]);
            }
            return view('send_users_upgrade', ['alert' => "Настройки успешно сохранены"]);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}

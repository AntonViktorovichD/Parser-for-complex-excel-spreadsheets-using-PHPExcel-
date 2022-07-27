<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller {
    public function send_notification(Request $request) {
        date_default_timezone_set('Europe/Moscow');
        try {
            $notification_rights = DB::table('notification_rights')->where('id', '=', 1)->get()[0];
            $notifications = [];
            if ($notification_rights->e_mail != 0 || $notification_rights->mobile_phone != 0) {
                $table_uuid = $request->get('table_uuid');
                $table = DB::table('tables')->where('status', 0)->where('table_uuid', '=', $table_uuid)->get();
                $table_name = $table[0]->table_name;
                $updated_at = $table[0]->updated_at;
                $table_creator = DB::table('users')->where('id', '=', $table[0]->user_id)->value('name');
                $departments = json_decode($table[0]->departments, true);
                foreach ($departments as $department) {
                    $notification = DB::table('user_noifications')->where('org_id', '=', $department)->get()[0];
                    $notifications[$department]['e_mail'] = $notification->e_mail;
                    $notifications[$department]['specialist_mobile_phone'] = $notification->specialist_mobile_phone;
                    $notifications[$department]['directors_mobile_phone'] = $notification->directors_mobile_phone;
                }
            }
            $objDemo = new \stdClass();
            $objDemo->table_name = $table_name;
            $objDemo->updated_at = $updated_at;
            $objDemo->responsible_specialist = $table_creator;
            foreach ($notifications as $key => $notification) {
                if ($notification['e_mail'] == 1) {
                    $mail = DB::table('users')->where('department', '=', $key)->value('email');
                    if (isset($mail)) {
                        Mail::to($mail)->send(new Email($objDemo));
                    }
                }
            }
            return redirect()->action([JsonController::class, 'arrayToJson']);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}

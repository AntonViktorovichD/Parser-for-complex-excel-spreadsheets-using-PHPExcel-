<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SmsController extends Controller {
    public function send_sms(Request $request) {
        date_default_timezone_set('Europe/Moscow');
        try {
            $notification_rights = DB::table('notification_rights')->where('id', '=', 1)->get()[0];
            $notifications = [];
            if ($notification_rights->e_mail != 0 || $notification_rights->mobile_phone != 0) {
                $table_uuid = $request->get('table_uuid');
                $table = DB::table('tables')->where('table_uuid', '=', $table_uuid)->get();
                $updated_at = $table[0]->updated_at;
                $departments = json_decode($table[0]->departments, true);
                foreach ($departments as $department) {
                    $notification = DB::table('user_noifications')->where('org_id', '=', $department)->get()[0];
                    $notifications[$department]['e_mail'] = $notification->e_mail;
                    $notifications[$department]['specialist_mobile_phone'] = $notification->specialist_mobile_phone;
                    $notifications[$department]['directors_mobile_phone'] = $notification->directors_mobile_phone;
                }
            }
            foreach ($notifications as $key => $notification) {
                if ($notification['specialist_mobile_phone'] == 1) {
                    $specialist_mobile_phone = DB::table('users')->where('department', '=', $key)->value('specialist_mobile_phone');
                    if (isset($specialist_mobile_phone)) {
                        // Метод отправки смс
                        $sms_sender_specialist_mobile = "Срочный запрос до:" . $updated_at;
                    }
                }
                if ($notification['directors_mobile_phone'] == 1) {
                    $directors_mobile_phone = DB::table('users')->where('department', '=', $key)->value('directors_mobile_phone');
                    if (isset($directors_mobile_phone)) {
                        // Метод отправки смс
                        $sms_sender_directors_mobile = "Срочный запрос до:" . $updated_at;
                    }
                }
            }
            return redirect()->action([MailController::class, 'send_mail']);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}

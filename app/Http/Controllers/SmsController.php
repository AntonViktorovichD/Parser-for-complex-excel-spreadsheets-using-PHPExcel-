<?php

namespace App\Http\Controllers;

use CooperAV\SmsAero\SmsAero;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SmsController extends Controller {
    public function send_sms(Request $request) {
        date_default_timezone_set('Europe/Moscow');
        try {
            $SMSAero = new SmsAero('email', 'api_key');

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

            $responses_array = [];
            preg_match('#(\d+)\-(\d+)-(\d+) (\d+)\:(\d+)#', $updated_at, $res);
            $message = 'Срочный запрос до: ' . $res[4] . ':' . $res[5] . '-' . $res[3] . '.' . $res[2] . '.' . $res[1];
            $type = 'DIRECT';
            foreach ($notifications as $key => $notification) {
                if ($notification['specialist_mobile_phone'] == 1) {
                    $specialist_mobile_phone = DB::table('users')->where('department', '=', $key)->value('mobile_phone');
                    if (isset($specialist_mobile_phone)) {
                        $response = $SMSAero->send('$specialist_mobile_phone', $message, $type);
                        $responses_array[] = $specialist_mobile_phone;
                    }
                }
                if ($notification['directors_mobile_phone'] == 1) {
                    $directors_mobile_phone = DB::table('users')->where('department', '=', $key)->value('directors_phone');
                    if (isset($directors_mobile_phone)) {
                        $response = $SMSAero->send('directors_mobile_phone', $message, $type);
                        $responses_array[] = $directors_mobile_phone;
                    }
                }
            }

            $log = date("d-m H:i:s") . ' ' . $table_uuid . ' ' . $table[0]->id . ' ' . implode(', ', $responses_array) . PHP_EOL;

            if (Storage::exists('/folder/' . 0 . (date("m") - 1) . '-' . date("Y") . '-log-sendsms.txt')) {
                Storage::delete('/folder/' . 0 . (date("m") - 1) . '-' . date("Y") . '-log-sendsms.txt');
            }
            if (Storage::exists('/folder/' . date("m-Y") . '-log-sendsms.txt')) {
                Storage::append('/folder/' . date("m-Y") . '-log-sendsms.txt', $log);
            } else {
                Storage::disk('local')->put('/folder/' . date("m-Y") . '-log-sendsms.txt', $log);
            }

            return redirect()->action([MailController::class, 'send_mail'], ['table_uuid' => $table_uuid]);

        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}

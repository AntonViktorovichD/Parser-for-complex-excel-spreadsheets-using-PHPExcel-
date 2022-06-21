<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\Email;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller {
    public function send_mail() {

        date_default_timezone_set('Europe/Moscow');

        $objDemo = new \stdClass();
        $objDemo->table_name = "Имя Таблицы";
        $objDemo->updated_at = date('H:i - d.m.Y');
        $objDemo->responsible_specialist = "Ответственный: ";
        Mail::to("receiver@example.com")->send(new Email($objDemo));
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Email extends Mailable {
    use Queueable, SerializesModels;

    /**
     * The demo object instance.
     *
     * @var Mail
     */
    public $mail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mail) {
        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->from('monitoring@minsocium.ru', 'Информационно-аналитический сервис "Автоматизированный сбор показателей работы социальных учреждений Нижегородской области"')
            ->subject('Запрос от министерства')
            ->view('mails.mail')
            ->text('mails.mail_plain');
//            ->with([
//                'testVarOne' => '1',
//                'testVarTwo' => '2',
//            ]);
    }
}

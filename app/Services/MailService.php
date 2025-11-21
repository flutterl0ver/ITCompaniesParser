<?php

namespace App\Services;

use App\Mail\ChangesReport;
use App\Models\Company;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Resend;

class MailService
{
    public function reportAllChanges()
    {
        $resend = Resend::client('re_6jFCRQ3e_4JRyDkm4f8TboSbGw8Zmh9sP');



        $now = new \DateTime();
        $now = $now->format('j.m.Y H:i');

        $subject = 'Отчёт об обновлении реестра за '.$now;
        $mails = User::all('email')->pluck('email')->toArray();
        $message = view('changesReport')->render();

        $resend->emails->send([
            'from' => 'parserfella@resend.dev',
            'to' => $mails,
            'subject' => $subject,
            'html' => $message
        ]);
    }
}

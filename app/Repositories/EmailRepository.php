<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Mail;
use App\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\ExceptionOccured;
use Carbon\Carbon;
use View;

class EmailRepository
{
    use Queueable, SerializesModels;

    protected $email;

    public function __construct()
    {
        $this->email = new Email();
    }

    public function send($view, $to, $subject, $body, $toName = '', $cc = '', $bcc = '')
    {
        if (empty($view)) {
            return response()->json([
                'code' => 401,
                'msg'  => 'Please provide email view.'
            ]);
        }

        $validator = $this->email->validator(['to' => $to, 'subject' => $subject, 'body' => $body]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 401,
                'msg'  => $validator->errors()->first()
            ]);
        }

        $bodyContent = View::make('Emails.'. $view, compact('body'))->render();
        Mail::send('Emails.'. $view, compact('body'), function($message) use ($to, $subject, $toName, $cc, $bcc) {
            $message->to($to, $toName)
                    ->subject($subject);
            if (!empty($cc)) {
                $message->cc($cc);
            }

            if (!empty($bcc)) {
                $message->bcc($bcc);
            }
        });

        if (Mail::failures()) {
            return response()->json([
                'code' => 401,
                'msg'  => 'Email not sent'
            ]);
        } else {
            $this->insertEmail([
                'from'           => env('MAIL_FROM_ADDRESS', ''),
                'to'             => $toName . ' ' . $to,
                'cc'             => $cc,
                'bcc'            => $bcc,
                'subject'        => $subject,
                'body'           => $bodyContent,
                'exception_info' => NULL,
                'created_at'     => Carbon::now()
            ]);

            return response()->json([
                'code' => 200,
                'msg'  => 'Email sent successfully !'
            ]);
        }
    }

    public function sendException($css, $content)
    {

        $allMails  = config('mail.mailers.exception.emails');
        $mailArray = (!empty($allMails)) ? explode(",", $allMails) : ['it.jaydeep.mor@gmail.com'];
        $subject   = 'Terra Heal Exception: ' . \Request::fullUrl();

        return $this->send('exception', $mailArray, $subject, ['css' => $css, 'content' => $content]);
    }

    public function insertEmail(array $data)
    {
        return $this->email->insert($data);
    }

    public function sendOtp(string $email)
    {
        $subject = 'Terra Heal signup request.';
        $otp     = mt_rand(1000,9999);

        $return = $this->send('user-otp', $email, $subject, ['otp' => $otp]);

        if ($return->getData()->code == 200) {
            return response()->json([
                'code' => 200,
                'msg'  => 'Email sent successfully !',
                'otp'  => $otp
            ]);
        } else {
            return $return;
        }
    }
}

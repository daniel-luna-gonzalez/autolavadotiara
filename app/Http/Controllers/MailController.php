<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send(){
        $amount = 400;
        Mail::send('email.subscription', ["amount" => "$" . $amount], function($message) {
            $message->to("danielunag@live.com", "Daniel Luna")->subject('Gracias por hacerlo realidad');
        });
    }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\api\v1;

use \Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \Illuminate\Support\Facades\Mail;
use App\ContactInfo;

/**
 * Description of Contacto
 *
 * @author Daniel Luna <dluna>
 */
class ContactoController extends Controller {

    public function index(Request $request) {
        try {

            Mail::send('email.contacto', [
                "name" => $request->input('name'),
                "email" => $request->input('email'),
                "messageText" => $request->get('message'),
                "subject" => $request->input('subject')
                    ], function($message) use ($request) {
                $to = env("CONTACTO_EMAIL", "info@hazlorealidad.org");
                $message->to($to, "Contacto Hazlo Realidad")->subject('Contacto Hazlo Realidad :: ' . $request->input("subject"));
            });

            ContactInfo::create($request->all());

            return response()->json(["status" => 1, "message" => "¡Gracias! Hemos recibido tu comentario, lo atenderemos a la brevedad"]);
        } catch (\Exception $e) {
            logger("Error al enviar correo transaccional al usuario: ". $request->input("email").". " . $e->getMessage());
            return response()->json(["status" => 0, "message" => "Error al enviar correo transaccional."]);
        }
    }

}

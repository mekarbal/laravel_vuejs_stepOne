<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $data = [
            'email' => $request->input('email'),
            'name' => $request->input('name'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
        ];
        Mail::to($data['email'])->send(new \App\Mail\ContactFormMail($data));
        return response()->json(['message' => 'Email Sent!'], 200);
    }
}

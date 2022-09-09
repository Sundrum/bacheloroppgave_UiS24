<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    public function index()
    {
        return view('email');
    }
    public function specific()
    {
        $name = \request('name');
        $email = \request('email');

        $data = array(
            'name'=>$name, 
            'email'=>$email
        );
        Mail::send(['text'=>'message'], $data, function($message) use ($email, $name)
        {
            $message->from('testtestersen482@gmail.com', 'Leif');
            $message->to($email, $name)->subject('Test e-mail from Laravel');
        });
        echo 'Email sent to '.$email;
    }
}

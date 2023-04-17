<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Emailverification;
use Illuminate\Support\Str;
use Auth, Mail;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email & SMS Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email and sms verification for any
    | user that recently registered with the application. Emails and SMS may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    public function requestValidation() 
    {
        $validation = Emailverification::where('verify_user_id', Auth::user()->user_id)->first();

        if($validation) {
            self::sendVerificationEmail($validation);
            dd('Requested', $validation);
        } else {
            $verify = new Emailverification;
            $verify->verify_email = Auth::user()->user_email;
            $verify->verify_token = Str::random(30);
            $verify->verify_user_id = Auth::user()->user_id;
            $verify->save();

            self::sendVerificationEmail($verify);
            dd('Not requested', $verify);
        }
        dd($validation);
    }

    public static function sendVerificationEmail ($verify) 
    {
        $data = array(
            'verify_email'=>$verify->verify_email, 
            'verify_token'=>$verify->verify_token
        );

        $email = Auth::user()->user_email;
        $name = Auth::user()->user_name;

        Mail::send(['html' => 'email.verifyemail'], $data, function($message) use ($email, $name)
        {
            $message->from(env('MAIL_FROM_ADDRESS', 'no-replay@portal.7sense.no'), env('MAIL_FROM_NAME', '7Sense Portal'));
            $message->to($email, $name)->subject('Verify your email');
        });
    }

    public function testmail () 
    {
        $verify = Emailverification::find(1);
        $data = array(
            'verify_email'=>$verify->verify_email, 
            'verify_token'=>$verify->verify_token
        );
        return view('email.verifyemail')->with('data');
    }
    
}

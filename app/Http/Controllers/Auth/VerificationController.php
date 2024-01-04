<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Emailverification;
use App\Models\Smsverification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use Auth, Mail, Log;

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
    public function getValidation() {
        $validation['mail'] = Emailverification::where('verify_user_id', Auth::user()->user_id)->first();
        $validation['sms'] = Smsverification::where('verify_user_id', Auth::user()->user_id)->first();
        $validation['user'] = User::where('user_id', Auth::user()->user_id)->first();
        return $validation;
    }

    public function requestValidationMail(Request $req) 
    {
        $validation = Emailverification::where('verify_user_id', $req->user_id)->first();
        $user = User::find($req->user_id);

        if($validation) {
            $validation->verify_email = $user->user_email;
            $validation->verify_token = mt_rand(1000,9999);
            $validation->save();
            self::sendVerificationEmail($validation);
        } else {
            $verify = new Emailverification;
            $verify->verify_email = $user->user_email;
            $verify->verify_token = mt_rand(1000,9999);
            $verify->verify_user_id = $user->user_id;
            $verify->save();

            self::sendVerificationEmail($verify);
        }
        return 1;
    }

    public function requestValidationSMS(Request $req) 
    {
        $validation = Smsverification::where('verify_user_id', $req->user_id)->first();
        $user = User::find($req->user_id);
        if($validation) {
            $validation->verify_phonenumber = trim($user->user_phone_work);
            $validation->verify_token = mt_rand(1000,9999);
            $validation->save();
            
            $string = "Verification code: ". $validation->verify_token;
            return self::sendSMS($user->user_phone_work, $string);
        } else {
            $verify = new Smsverification;
            $verify->verify_phonenumber = trim($user->user_phone_work);
            $verify->verify_token = mt_rand(1000,9999);
            $verify->verify_user_id = $user->user_id;
            $verify->save();
            
            $string = "Verification code: ". $verify->verify_token;
            return self::sendSMS($user->user_phone_work, $string);
        }
    }

    public function verifySMS(Request $req) {
        $validation = Smsverification::where('verify_user_id', $req->user_id)->first();
        $user = User::where('user_id', Auth::user()->user_id)->first();
        if($validation) {
            if($req->code) {
                if($req->code == $validation->verify_token) {
                    $validation->phonenumber_verified = true;
                    $user->phone_verified = true;
                    $user()->save();
                    return $validation->save();
                } else {
                    return '4';
                }
            } else {
                return '3';
            }
        } else {
            return '2';
        }
    }

    public function verifyMail(Request $req) {
        $validation = Emailverification::where('verify_user_id', $req->user_id)->first();
        $user = User::where('user_id', Auth::user()->user_id)->first();
        if($validation) {
            if($req->code) {
                if($req->code == $validation->verify_token) {
                    $validation->email_verified = true;
                    $user->email_verified = true;
                    $user()->save();
                    return $validation->save();
                } else {
                    return '4';
                }
            } else {
                return '3';
            }
        } else {
            return '2';
        }
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
            $message->from(env('MAIL_FROM_ADDRESS', 'no-reply@portal.7sense.no'), env('MAIL_FROM_NAME', '7Sense Portal'));
            $message->to($email, $name)->subject('Verify your email');
        });
        Log::info('User ID: '.Auth::user()->user_id. ' asked for verification of email ('.$verify->verify_email.')');
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

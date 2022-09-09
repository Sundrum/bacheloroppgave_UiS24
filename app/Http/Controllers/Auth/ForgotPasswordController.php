<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;
use App\Models\User;
use App\Models\PasswordReset;
use Redirect, Session;



class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {
        request()->validate([
            'email' => 'required',
        ]);
        $user_email = strtolower($request->input('email'));
        $user = User::where('user_email',$user_email)->first();

        // dd($user->user_name);

        if($user){
            $email = strtolower($request->input('email'));

            $name = $user->user_name;
            $token = Str::random(60);

            $passwordreset = PasswordReset::where('user_email',$email)->first();
            if ($passwordreset) {
                $passwordreset = PasswordReset::where('user_email',$email)->delete();
                ForgotPasswordController::create($email, $token);
            } else {
                ForgotPasswordController::create($email, $token);
            }

            $data = array(
                'name'=>$name, 
                'email'=>$email,
                'token'=>$token
            );

            Mail::send(['html' => 'email.forgotpassword'], $data, function($message) use ($email, $name)
            {
                $message->from(env('MAIL_FROM_ADDRESS', 'no-replay@portal.7sense.no'), env('APP_NAME', '7Sense Portal'));
                $message->to($email, $name)->subject('Reset Password');
            });

            $message = 'E-mail has been sent, please follow the link in the mail';
            return view('auth.login')->with('message', $message);

        } else {
            $message = 'E-mail does not exist';
            return view('auth.login')->with('errormessage', $message);
        }
    }

    
    public static function create($email, $token)
    {
        return PasswordReset::create([
            'token' => $token,
            'user_email' => $email,
        ]);
    }

}
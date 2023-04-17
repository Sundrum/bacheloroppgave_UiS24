<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\User;
use App\PasswordReset;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function reset(Request $request) 
    {
        $email = strtolower($request->input('email'));
        $token_input = $request->input('token');
        $password = $request->input('password');
        $passwordconfirm = $request->input('password_confirmation');

        if ($password == $passwordconfirm) {
            $passwordreset = PasswordReset::where('user_email',$request->input('email'))->first();
            if ($passwordreset) {
                $current_date_time = Carbon::now()->toDateTimeString();
                $currenttime = strtotime($current_date_time);
                $created_at = strtotime($passwordreset->created_at);
                $diff = ($currenttime - $created_at)/3600;

                if ($diff < 1 && $diff > 0) {
                    $token = trim($passwordreset->token);
                    if (strcmp($token_input,$token) === 0){
                        User::updatePassword($email,md5($password));
                        return view('auth.login')->with('message', 'Your password is updated!'); //With succsessfull update of password
                    } else {
                        //Token not equal error
                        return redirect('/password/reset')->with('errormessage', 'Your token is not equal - Please try again.');
                    }
                } else {
                    // TTL over. Error
                    return redirect('/password/reset')->with('errormessage', 'Your token has expired - Please try again.');
                }
            } else {
                // Email dosen't exsist
                return back()->with('errormessage', 'This account has no request for new password')->withInput();
            }
        } else {
            // Password are not equal. Error
            return back()->with('errormessage', 'The passwords is not equal')->withInput();
        }
    }
}

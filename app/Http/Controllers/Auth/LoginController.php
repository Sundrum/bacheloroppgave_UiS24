<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Redirect, Session;
 

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    | */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $user_email = strtolower($request->input('email'));
        
        $user = User::where('user_email',$user_email)->first();

        // check if user (e-mail) is correct
        if (!$user) {
            $message = 'Username or password is incorrect';
            return view('auth.login')->with('errormessage', $message);
        }

        $password = $user->user_password;
        $input_password = $request->input('password');
        // check if password is correct
        if ($password == md5($input_password))
        {
            Auth::login($user, $request->input('remember'));
            Session::put('user_id', Auth::user()->user_id);
            Session::put('customernumber', Auth::user()->customernumber);
            return Redirect::to('dashboard');
        } else {
            $message = 'Username or password is incorrect';
            return view('auth.login')->with('errormessage', $message);
        }
    }

    public function showLoginForm(){
        return view('auth.login');
    }

    public function logout(Request $request) {
        //dd($request);
        Session::flush();
        Auth::logout();
        return Redirect::to('login');
    }

}

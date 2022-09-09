<?php

namespace App\Http\Middleware;

use Closure, Session, Auth;
use Illuminate\Http\Request;

class CheckUsername
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $email = trim(Auth::user()->user_email);
        $user_id = trim(Auth::user()->user_id);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            if ($user_id == '279') {
                Session::put('updateemail', '0');
            } else if ($user_id == '302'){
                Session::put('updateemail', '0');
            } else {
                Session::put('updateemail', '1');
            }
        } else {
            Session::put('updateemail', '0');
        }
        
        return $next($request);
    }
}

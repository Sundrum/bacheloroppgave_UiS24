<?php

namespace App\Http\Middleware;

use Closure, App, Auth;
use Illuminate\Http\Request;

class SetLanguage
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
        if (Auth::user()->user_language === 1) {
            App::setLocale('no');
        } else if (Auth::user()->user_language === 3){
            App::setLocale('fr');
        } else {
            App::setLocale('en');
        }
        
        return $next($request);
    }
}

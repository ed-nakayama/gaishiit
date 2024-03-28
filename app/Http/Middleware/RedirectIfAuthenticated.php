<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        if (Auth::guard($guard)->check()) {
//            return redirect(RouteServiceProvider::HOME);
//        }

        if (Auth::guard($guard)->check() && $guard === 'user') {
            return redirect(RouteServiceProvider::USER_MYPAGE);
        } elseif (Auth::guard($guard)->check() && $guard === 'admin') {
            return redirect(RouteServiceProvider::ADMIN_MYPAGE);
        } elseif (Auth::guard($guard)->check() && $guard === 'comp') {
            return redirect(RouteServiceProvider::COMP_MYPAGE);
        }

     return $next($request);
    }
}

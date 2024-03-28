<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
//    protected $user_route  = 'user.login';
    protected $user_route  = 'users.index';
    protected $admin_route = 'admin.login';
    protected $comp_route = 'comp.login';

    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectToByGuards($request ,$guards)
        );
    }


    protected function redirectToByGuards($request, $guards) {
        if (!$request->expectsJson()) {
            if ($guards[0] === 'admin') {
                return route($this->admin_route);
            } elseif ($guards[0] === 'comp') {
                return route($this->comp_route);
            } elseif ($guards[0] === 'user') {
                return route($this->user_route);
            }
        }
    }
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
//        if (! $request->expectsJson()) {
//            return route('login');
//        }


        // ルーティングに応じて未ログイン時のリダイレクト先を振り分ける
        if (!$request->expectsJson()) {
            if (Route::is('user.*')) {
                return route($this->user_route);
            } elseif (Route::is('admin.*')) {
                return route($this->admin_route);
            } elseif (Route::is('comp.*')) {
                return route($this->comp_route);
            }
        }

    }
}

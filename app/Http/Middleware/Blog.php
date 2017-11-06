<?php

namespace App\Http\Middleware;

use Closure;

class Blog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session('home_user_id')) {
            return $next($request);
        } else {
            return redirect('/login') ->withInput() -> with('error','登陆信息已过期,请重新登录!');
        }
    }
}

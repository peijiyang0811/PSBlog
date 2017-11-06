<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class Admin
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
        $id = session('admin_user_id');
        if (Cache::has('admin_user_id'.$id)) {
            return $next($request);
        } else {
            return redirect('admin/login') ->withInput() -> with('error','登陆信息已过期,请重新登录!');
        }
    }
}

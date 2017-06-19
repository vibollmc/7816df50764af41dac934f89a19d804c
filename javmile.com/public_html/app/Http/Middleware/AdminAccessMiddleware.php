<?php

namespace App\Http\Middleware;

use Closure;

class AdminAccessMiddleware
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
        $checker = \Session::get('admin');
        if (is_null($checker)) {
            \Session::set('current_url', \URL::current());
            return \Redirect::action('Admin\AuthController@getLogin');
        }elseif ($checker->group_id != 1) {
            return response()->view('admin.home.denie');
            // return \Redirect::route('admin_home');
        }
        return $next($request);
    }
}

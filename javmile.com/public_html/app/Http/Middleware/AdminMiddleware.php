<?php

namespace App\Http\Middleware;

use Closure, Cache, Session;

class AdminMiddleware
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
        $auth = \Session::get('admin');
        if (is_null($auth)) {
            \Session::set('current_url', \URL::current());
            return \Redirect::action('Admin\AuthController@getLogin');
        }else{
            if($auth->status != 1){
                \Session::set('current_url', \URL::current());
                return \Redirect::action('Admin\AuthController@getLogin');
            }
            if(!Cache::has('admin_id_'.$auth->id)){
                Session::forget('admin');
                return \Redirect::action('Admin\AuthController@getLogin');
            }elseif(Cache::get('admin_id_'.$auth->id) != $auth->password){
                Session::forget('admin');
                return \Redirect::action('Admin\AuthController@getLogin');
            }
        }
        return $next($request);
    }
}

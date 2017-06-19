<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use App\Models\User;
use App\Models\User_type;
use Cache, Storage;

class AuthController extends AdminController
{
    public function getLogin() {
        return view('admin.auth.login');
    }

    public function postLogin(Request $request) {
        $username = \Request::input('username');
        $password = \Request::input('password');
        $auth = User::where(['username' => $username, 'status' => 1])->where('password', md5($password))->first();
        if (!is_null($auth)) {
            \Session::set('admin', $auth);
            Cache::forever('admin_id_'.$auth->id, $auth->password);
            /*Log*/
            $path = 'adminlog/login_'.date('Y-m', time()).'.txt';
            if(!Storage::disk('local')->exists($path)){
                $create = Storage::disk('local')->put($path, json_encode(['name' => $auth->username, 'time' => date('Y-m-d H:i:s', time()), 'ip' => \Request::ip()]));
            }else{
                Storage::prepend($path, json_encode(['name' => $auth->username, 'time' => date('Y-m-d H:i:s', time()), 'ip' => \Request::ip()]).',');
            }
            if (\Session::has('current_url') and \Session::get('current_url') != route('admin_logout')) {
                $url = \Session::get('current_url');
                \Session::forget('current_url');
                return \Redirect::to($url);
            }
            return \Redirect::action('Admin\HomeController@index');
        }
        \Session::flash('message', '<p class="alert alert-warning">Not correct!</p>');
        return \Redirect::back()->withInput();
    }

    public function getLogout() {
        \Session::forget('admin');
        return \Redirect::action('Admin\AuthController@getLogin');
    }
}

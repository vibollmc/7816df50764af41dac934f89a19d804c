<?php
namespace App\Http\ViewComposers;
use App\Model\User;

class UserComposer {
    public function compose($view) {
        $log_user = \Session::get('user');
        $view->with(['user' => $log_user]);
    }
}
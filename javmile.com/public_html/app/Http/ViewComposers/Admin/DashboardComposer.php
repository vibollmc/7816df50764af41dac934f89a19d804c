<?php
namespace App\Http\ViewComposers\admin;
use App\Models\User;
use App\Models\Lesson;

class DashboardComposer {
    public function compose($view) {
        $users = User::all();
        $lessons = Lesson::all();
        $view->with([
            'users'  => $users,
            'lessons' => $lessons
        ]);
    }
}
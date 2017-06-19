<?php
namespace App\Http\ViewComposers\Admin;

class SidebarComposer {
    public function compose($view) {
        $view->with([
            'feed_count' => \App\Models\Feedback::where('status', 'pending')->count()> 0? \App\Models\Feedback::where('status', 'pending')->count(): '',
            'recent_time' => \App\Models\Feedback::where('status', 'pending')->count()> 0? \App\Models\Feedback::where('status', 'pending')->orderBy('id', 'desc')->first()->updated_at: ''
        ]);
    }
}
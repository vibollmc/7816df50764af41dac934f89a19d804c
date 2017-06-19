<?php
namespace App\Http\ViewComposers\Client;
use App\Models\Article;
use App\Models\Setting;
use Cache;

class CalendarComposer {
    public function compose($view) {
        if (!Cache::has('article')) {
            $result = Article::where(['type' => 'new', 'slug' => 'thong-bao'])->first();
            Cache::put('article', $result, 5);
        }
        if (!Cache::has('job_article')) {
            $result = Article::where(['type' => 'new', 'slug' => 'tuyen-dung'])->first();
            Cache::put('job_article', $result, 5);
        }
        if (!Cache::has('calendar')) {
            $calendar = Setting::where(['type' => 'calendar'])->first();
            Cache::put('calendar', $calendar, 5);
        }
        $view->with([
            'article' => Cache::get('article'),
            'job_article' => Cache::get('job_article'),
            'calendar' => Cache::get('calendar'),
        ]);
    }
}
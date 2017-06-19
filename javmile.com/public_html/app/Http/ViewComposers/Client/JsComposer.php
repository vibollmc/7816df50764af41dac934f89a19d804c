<?php
namespace App\Http\ViewComposers\Client;

use App\Models\Setting;
use Cache;

class JsComposer {
    public function compose($view) {
        if (!Cache::has('analytic')) {
            $analytic = Setting::where('type', 'analytic')->first();
            Cache::put('analytic', $analytic, env('CACHE_TIME'));
        }
        $view->with([
            'analytic'         => Cache::get('analytic')
        ]);
    }
}
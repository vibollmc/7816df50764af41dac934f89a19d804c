<?php
namespace App\Http\ViewComposers\Client;
use App\Models\Star;
use Cache;

class ActorComposer {
    public function compose($view) {
        if (!Cache::has('actor_block')) {
            $actor = Star::where(['hot' => 1])->with('image_server')->orderByRaw("RAND()")->take(6)->get();
            Cache::put('actor', $actor, env('CACHE_TIME'));
        }
        $view->with([
            'actor' => Cache::get('actor')
        ]);
    }
}
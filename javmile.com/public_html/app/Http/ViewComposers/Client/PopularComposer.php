<?php
namespace App\Http\ViewComposers\Client;
use App\Models\Film;
use Cache;

class PopularComposer {
    public function compose($view) {
        if (!\Cache::has('popular_block')) {
            $popular_block = Film::where(['online' => 1, 'member' => NULL])->with('image_server', 'category')->orderBy('viewed', 'desc')->take(env('PERBLOCK_SIDEBAR'))->get();
            \Cache::put('popular_block', $popular_block, env('CACHE_TIME'));
        }
        $view->with([
            'popular' => Cache::get('popular_block')
        ]);
    }
}
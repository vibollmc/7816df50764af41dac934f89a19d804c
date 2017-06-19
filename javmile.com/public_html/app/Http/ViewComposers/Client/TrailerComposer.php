<?php
namespace App\Http\ViewComposers\Client;
use App\Models\Film;
use Cache;

class TrailerComposer {
    public function compose($view) {
        if (!\Cache::has('trailer_block')) {
            $trailer_block = Film::where(['quality_id' => 11, 'online' => 1, 'member' => NULL])->with('image_server', 'category')->orderByRaw("RAND()")->take(5)->get();
            \Cache::put('trailer_block', $trailer_block, env('CACHE_TIME'));
        }
        $view->with([
            'trailer_block' => Cache::get('trailer_block')
        ]);
    }
}
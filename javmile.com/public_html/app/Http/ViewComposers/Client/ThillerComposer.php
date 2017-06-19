<?php
namespace App\Http\ViewComposers\Client;
use App\Models\Genre;
use Cache;

class ThillerComposer {
    public function compose($view) {
        if (!\Cache::has('thiller_block')) {
            $thiller = Genre::where('id', 11)->with('block')->first();
            \Cache::put('thiller_block', $thiller->block, env('CACHE_TIME'));
        }
        $view->with([
            'thiller' => Cache::get('thiller_block')
        ]);
    }
}
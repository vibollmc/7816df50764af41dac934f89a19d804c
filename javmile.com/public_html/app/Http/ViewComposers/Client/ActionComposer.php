<?php
namespace App\Http\ViewComposers\Client;
use App\Models\Genre;
use Cache;

class ActionComposer {
    public function compose($view) {
        if (!\Cache::has('action_block')) {
            $action = Genre::where('id', 10)->with('block')->first();
            \Cache::put('action_block', $action->block, env('CACHE_TIME'));
        }
        $view->with([
            'action' => Cache::get('action_block')
        ]);
    }
}
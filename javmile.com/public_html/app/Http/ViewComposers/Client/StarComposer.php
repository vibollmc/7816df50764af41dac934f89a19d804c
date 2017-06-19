<?php
namespace App\Http\ViewComposers\Client;
use App\Models\Star;

class StarComposer {
    public function compose($view) {
        $view->with([
            'stars' => Star::orderBy('updated_at', 'desc')->with(['avatar'])->get()
        ]);
    }
}
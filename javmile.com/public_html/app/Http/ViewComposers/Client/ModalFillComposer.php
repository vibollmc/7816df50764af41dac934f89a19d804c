<?php
namespace App\Http\ViewComposers\Client;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use Cache;

class ModalFillComposer {
    public function compose($view) {
        if(!Cache::has('categories')){
            $categories = Category::all();
            Cache::put('categories', $categories, 10);
        }
        $categories = Cache::get('categories');
        if(!Cache::has('genres')){
            $genres = Genre::orderBy('title', 'asc')->get();
            Cache::put('genres', $genres, 10);
        }
        $genres = Cache::get('genres');
        if(!Cache::has('countries')){
            $countries = Country::orderBy('title', 'asc')->get();
            Cache::put('countries', $countries, 10);
        }
        $countries = Cache::get('countries');

        $view->with([
            'categories' => $categories,
            'genres' => $genres,
            'countries' => $countries
        ]);
    }
}
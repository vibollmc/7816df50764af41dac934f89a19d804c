<?php
namespace App\Http\ViewComposers\Client;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Country;
use Cache;

class MenuComposer {
    public function compose($view) {
        if(!Cache::has('categories')){
            $categories = Category::all();
            Cache::put('categories', $categories,10);
        }
        $categories = Cache::get('categories');
        if(!Cache::has('menu_genres')){
            $menu_genres = Genre::where('menu', 1)->orderBy('title')->get();
            Cache::put('menu_genres', $menu_genres, 10);
        }
        $menu_genres = Cache::get('menu_genres');
        if(!Cache::has('menu_countries')){
            $menu_countries = Country::where('menu', 1)->get();
            Cache::put('menu_countries', $menu_countries, 10);
        }
        $menu_countries = Cache::get('menu_countries');

        $view->with([
            'categories' => $categories,
            'menu_genres' => $menu_genres,
            'menu_countries' => $menu_countries
        ]);
    }
}
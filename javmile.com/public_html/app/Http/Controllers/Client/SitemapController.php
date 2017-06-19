<?php namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Articles;
use App\Models\Film;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Star;
use App\Models\Tags;
use Cache;

class SitemapController extends Controller {
    const RATE_HOME          = '1.0';
    const RATE_CATEGORY      = '0.9';
    const RATE_VIDEO         = '0.8';
    const RATE_ARTICLE       = '0.7';
    const FREQUENCY_HOME     = 'always';
    const FREQUENCY_CATEGORY = 'always';
    const FREQUENCY_VIDEO    = 'daily';

    public function index() {
        $sitemap = \App::make("sitemap");

        // Current time
        $currentTime = \Carbon\Carbon::now(new \DateTimeZone('UTC'));

        // Home
        $sitemap->add(route('home'), $currentTime, self::RATE_HOME, self::FREQUENCY_HOME);

        // Level 2
        $cache_time = env('CACHE_TIME');
        if(!Cache::has('categories')){
            $categories = Category::all();
            Cache::put('categories', $categories, $cache_time);
        }
        $categories = Cache::get('categories');
        foreach($categories as $k => $category){
            $sitemap->add(route('category', $category->slug), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);
        }
        if(!Cache::has('genres')){
            $genres = Genre::get();
            Cache::put('genres', $genres, $cache_time);
        }
        $genres = Cache::get('genres');
        foreach($genres as $k => $item){
            $sitemap->add(route('genre', $item->slug), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);
        }
        if(!Cache::has('countries')){
            $countries = Country::get();
            Cache::put('countries', $countries, $cache_time);
        }
        $countries = Cache::get('countries');
        foreach($countries as $k => $item){
            $sitemap->add(route('country', $item->slug), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);
        }
        $sitemap->add(route('year', date('Y', time())), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);
        $sitemap->add(route('hot'), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);
        $sitemap->add(route('stars'), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);
        return $sitemap->render('xml');
    }

    public function category($slug) {
        $sitemap = \App::make("sitemap");

        // Current time
        $currentTime = \Carbon\Carbon::now(new \DateTimeZone('UTC'));

        // Home
        $sitemap->add(route('home'), $currentTime, self::RATE_HOME, self::FREQUENCY_HOME);

        // Level 1
        $sitemap->add(route('category', $slug), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);

        // Level 2
        $category = Category::where('slug',  $slug)->with('films')->first();
        foreach ($category->films as $key => $value) {
            $sitemap->add(route('film_show',['category' => $category->slug, 'slug' => $value->slug]), $currentTime, self::RATE_VIDEO, self::FREQUENCY_VIDEO);
        }
        return $sitemap->render('xml');
    }

    public function genre($slug) {
        $sitemap = \App::make("sitemap");

        // Current time
        $currentTime = \Carbon\Carbon::now(new \DateTimeZone('UTC'));

        // Home
        $sitemap->add(route('home'), $currentTime, self::RATE_HOME, self::FREQUENCY_HOME);

        // Level 1
        $sitemap->add(route('genre', $slug), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);

        // Level 2
        $genre = Genre::where('slug',  $slug)->with('films')->first();
        foreach ($genre->films as $key => $value) {
            $sitemap->add(route('film_show',['category' => $value->category->slug, 'slug' => $value->slug]), $currentTime, self::RATE_VIDEO, self::FREQUENCY_VIDEO);
        }
        return $sitemap->render('xml');
    }

    public function country($slug) {
        $sitemap = \App::make("sitemap");

        // Current time
        $currentTime = \Carbon\Carbon::now(new \DateTimeZone('UTC'));

        // Home
        $sitemap->add(route('home'), $currentTime, self::RATE_HOME, self::FREQUENCY_HOME);

        // Level 1
        $sitemap->add(route('country', $slug), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);

        // Level 2
        $country = Country::where('slug',  $slug)->with('films')->first();
        foreach ($country->films as $key => $value) {
            $sitemap->add(route('film_show',['category' => $value->category->slug, 'slug' => $value->slug]), $currentTime, self::RATE_VIDEO, self::FREQUENCY_VIDEO);
        }
        return $sitemap->render('xml');
    }

    public function year($slug) {
        $sitemap = \App::make("sitemap");

        // Current time
        $currentTime = \Carbon\Carbon::now(new \DateTimeZone('UTC'));

        // Home
        $sitemap->add(route('home'), $currentTime, self::RATE_HOME, self::FREQUENCY_HOME);

        // Level 1
        $sitemap->add(route('year', $slug), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);

        // Level 2
        $year = Film::where('date', 'like', "%$slug")->with('category')->get();
        foreach ($year as $key => $value) {
            $sitemap->add(route('film_show',['category' => $value->category->slug, 'slug' => $value->slug]), $currentTime, self::RATE_VIDEO, self::FREQUENCY_VIDEO);
        }
        return $sitemap->render('xml');
    }

    public function stars() {
        $sitemap = \App::make("sitemap");

        // $sitemap->setCache('ngoaingu.cache_sitemap', \Config::get('ngoaingu.cache_sitemap', 60));

        // Current time
        $currentTime = \Carbon\Carbon::now(new \DateTimeZone('UTC'));

        // Home
        $sitemap->add(route('home'), $currentTime, self::RATE_HOME, self::FREQUENCY_HOME);

        // Level 1
        $sitemap->add(route('stars'), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);

        // Level 2
        $result = Star::pluck('slug');
        foreach ($result as $key => $slug) {
            $sitemap->add(route('star_show',$slug), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_VIDEO);
        }
        return $sitemap->render('xml');
    }

    public function tag($slug) {
        $sitemap = \App::make("sitemap");

        // $sitemap->setCache('ngoaingu.cache_sitemap', \Config::get('ngoaingu.cache_sitemap', 60));

        // Current time
        $currentTime = \Carbon\Carbon::now(new \DateTimeZone('UTC'));

        // Home
        $sitemap->add(route('home'), $currentTime, self::RATE_HOME, self::FREQUENCY_HOME);

        // Level 1
        $sitemap->add(route('tag', $slug), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_CATEGORY);

        // Level 2
        if(!Cache::has('film_tag_'.$slug)){
            $tag = Tags::where('slug', $slug)->first();
            $film_tag_ids = DB::table('film_tags')->where('tag_id', $tag->id)->pluck('film_id');
            Cache::put('film_tag_'.$slug, $film_tag_ids, env('CACHE_TIME'));
        }
        $film_ids = Cache::get('film_tag_'.$slug);
        $result = Film::whereIn('id', $film_ids)->with('category')->orderBy('order', 'desc')->get();
        foreach ($result as $key => $value) {
            $sitemap->add(route('film_show',['category' => $value->category->slug, 'slug' => $value->slug]), $currentTime, self::RATE_CATEGORY, self::FREQUENCY_VIDEO);
        }
        return $sitemap->render('xml');
    }
}

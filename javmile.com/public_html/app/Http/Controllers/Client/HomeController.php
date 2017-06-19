<?php
namespace App\Http\Controllers\Client;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Film;
use App\Models\Genre;
use App\Models\Star;
use App\Models\Video;
use App\Models\Server;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Bookmark;
use App\Models\Feedback;
use App\Models\Reporter;
use App\Models\Error;
use App\Models\Customer;
use App\Models\Google_drive_file;

use Validator, Session, Redirect, MetaTag, Cache, DB;

class HomeController extends Controller {

    public function index() {
        // recent movie
        if (!\Cache::has('recent_movie_block')) {
            $recent_movie_block = Film::where('category_id', 1)->where(['online' => 1, 'member' => NULL])->where('quality_id', '<>', 11)->with(['image_server', 'quality', 'category', 'episode_list'])->orderBy('order', 'desc')->take(34)->get();
            Cache::put('recent_movie_block', $recent_movie_block, env('CACHE_TIME'));
        }
        $recent_movie = Cache::get('recent_movie_block');
        // recent series
        if (!\Cache::has('recent_series_block')) {
            $recent_series_block = Film::where('category_id', 2)->where(['online' => 1, 'member' => NULL])->with(['image_server', 'quality', 'category', 'episode_list'])->orderBy('order', 'desc')->take(env('PERBLOCK_HOME'))->get();
            Cache::put('recent_series_block', $recent_series_block, env('CACHE_TIME'));
        }
        $recent_series = Cache::get('recent_series_block');
        // recent hot
        if (!\Cache::has('recent_hot_block')) {
            $recent_hot_block = Film::where('hot', 1)->where(['online' => 1, 'member' => NULL])->where('quality_id', '<>', 11)->with(['image_server', 'quality', 'category', 'episode_list'])->orderBy('order', 'desc')->take(env('PERBLOCK_HOME'))->get();
            Cache::put('recent_hot_block', $recent_hot_block, env('CACHE_TIME'));
        }
        $recent_hot = Cache::get('recent_hot_block');
        // recent Gameshow
        if (!\Cache::has('recent_gameshow_block')) {
            $genre = Genre::where(['id' => 23])->first();
            $films = $genre->films()->where(['online' => 1, 'member' => NULL])->where('quality_id', '<>', 11)->orderBy('order', 'desc')->take(env('PERBLOCK_HOME'))->get();
            Cache::put('recent_gameshow_block', ['films' => $films, 'genre' => $genre], env('CACHE_TIME'));
        }
        $gameshow_block = Cache::get('recent_gameshow_block');
        // recent Member's film
        if (!\Cache::has('member_film_block')) {
            $member_film = Film::where(['online' => 1, 'member' =>1])->with(['image_server', 'quality', 'category', 'episode_list'])->orderBy('order', 'desc')->take(env('PERBLOCK_HOME'))->get();
            Cache::put('member_film_block', $member_film, env('CACHE_TIME'));
        }
        $member_films = Cache::get('member_film_block');
        return view('client.home.index', compact('mini_slide_block', 'recent_movie', 'recent_series', 'recent_hot', 'gameshow_block', 'member_films'));
    }

    function page_notfound(){
        return view('client.home.404');
    }

    function search(Request $request){
        $key = Str::ascii(trim($_GET['key']));
        if (isset($_GET['sort'])) {
            $sort_arr = explode('-', $_GET['sort']);
            $sort = $sort_arr[1];
            $by = $sort_arr[0];
        }else{
            $sort = 'desc';
            $by = 'order';
        }

        $result = Film::where('title_en', 'like', "%$key%")->orWhere('title_ascii', 'like', "%$key%");
        $result->orderBy($by, $sort)->where(['online' => 1, 'member' => NULL])->with(['image_server', 'category', 'quality']);

        $result = $result->paginate(env('PERPAGE'));
        $uri = $request->all();
        $result->appends($uri);
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Results search: <b>'.$_GET['key'].'</b>'];
        return view('client.home.search-result', compact('result', 'uri', 'breadcrumb'));
    }

    function advance_fill(Request $request){
        $data = $request->all();
        if (isset($_GET['sort'])) {
            $sort_arr = explode('-', $_GET['sort']);
            $sort = $sort_arr[1];
            $by = $sort_arr[0];
        }else{
            $sort = 'desc';
            $by = 'order';
        }
        $result = Film::with(['image_server', 'category', 'quality'])->where(['online' => 1])->orderBy($by, $sort);
        if (isset($data['category_id'])  and $data['category_id'] != '') {
            $result->where('category_id', $data['category_id']);
        }
        if (isset($data['genre']) and $data['genre'] != '') {
            $ids = DB::table('film_genres')->where('genre_id', $data['genre'])->pluck('film_id');
            $result->whereIn('id', $ids);
        }
        if (isset($data['country']) and $data['country'] != '') {
            $ids = DB::table('film_countries')->where('country_id', $data['country'])->pluck('film_id');
            $result->whereIn('id', $ids);
        }
        if (isset($data['year']) and $data['year'] != '') {
            $year = $data['year'];
            $result->where('date', 'like', "%$year%");
        }
        if (isset($data['member']) and $data['member'] == 1) {
            $result->where('member', 1);
        }else{
             $result->whereNull('member');
        }
        $uri = $request->all();
        $result = $result->paginate(env('PERPAGE'));
        $result->appends($uri);
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Filter'];
        return view('client.home.advance-fill', compact('result', 'breadcrumb', 'uri'));
    }

    function bookmark(Request $request){
        $data = $request->all();
        $bookmark = Bookmark::where([
            'user_id' => Session::get('user')->id,
            'able_id' => $data['key'],
            'type' => 'film'
            ])->first();
        if (is_null($bookmark)) {
            $bookmark = Bookmark::insert([
                'user_id' => Session::get('user')->id,
                'able_id' => $data['key'],
                'type' => 'film'
                ]);
            $add = true;
        }else{
            $bookmark->delete();
            $add = false;
        }
        echo json_encode(['add' => $add]);
    }

    function report(Request $request){
        $data = $request->all();
        $user = Session::get('user');
        $reported = Reporter::where(['customer_id' => $user->id, 'error_id' => $data['id'], 'able_id' => $data['able_id']])->first();
        if ($user->baned_to < time() and is_null($reported)) {
            Reporter::create([
                'customer_id' => $user->id,
                'error_id' => $data['id'],
                'able_id' => $data['able_id'],
                'content' => $data['content']
                ]);
            $film = Film::where('id', $data['able_id'])->increment('reported', 1);
        }else{
            Session::flash('message', 'Thank you.');
        }
        return Redirect::back();
    }

}
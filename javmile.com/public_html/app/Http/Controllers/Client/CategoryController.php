<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Models\Film;
use App\Models\Country;
use App\Models\Genre;
use App\Models\Star;
use App\Models\Category;
use App\Models\Bookmark;
use Illuminate\Pagination\Paginator;
use Session, Redirect, MetaTag, Cache;

class CategoryController extends Controller {

    function index($slug){
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }
        if (isset($_GET['sort'])) {
            $uri = ['sort' => $_GET['sort']];
            $sort_arr = explode('-', $_GET['sort']);
            $sort = $sort_arr[1];
            $by = $sort_arr[0];
        }else{
            $uri = '';
            $sort = 'desc';
            $by = 'order';
        }
        if(!Cache::has('category_page_'.$slug.'_'.$current_page.$by.$sort)){
            if ($slug == 'phim-sap-chieu') {
                $films = Film::where(['quality_id' => 11, 'online' => 1, 'member' => NULL])->orderBy($by, $sort)->with(['category', 'image_server', 'quality'])->paginate(env('PERPAGE'));
                $films->appends($uri);
                $data = [
                    'result' => $films,
                    'pagination' => $films->render(),
                    'title' => 'Phim sắp chiếu',
                    ];
            }else{
                $category = Category::where(['slug' => $slug, 'status' => 1])->first();
                $per_page = env('PERPAGE');
                $minutes = env('CACHE_TIME');
                $films = Film::where('category_id', $category->id)->where('quality_id', '<>', 11)->where(['online' => 1, 'member' => NULL])->orderBy($by, $sort)->with(['category', 'image_server', 'quality'])->paginate($per_page);
                $films->appends($uri);
                $data = [
                    'result' => $films,
                    'pagination' => $films->render(),
                    'title' => $category->title,
                    'category' => $category
                    ];
            }
            Cache::put('category_page_'.$slug.'_'.$current_page.$by.$sort, $data, env('CACHE_TIME'));
        }
        $result = Cache::get('category_page_'.$slug.'_'.$current_page.$by.$sort);
        // Seo:
        if (isset($result['category'])) {
            MetaTag::set('title', json_decode($result['category']->seo)->title);
            MetaTag::set('keyword', json_decode($result['category']->seo)->keyword);
            MetaTag::set('description', $result['category']->description);
        }else{
            MetaTag::set('title', 'Phim sắp chiếu');
            MetaTag::set('keyword', 'trailer phim');
            MetaTag::set('description', 'Những bộ phim sắp chiếu được quan tâm nhất');
        }
        // Breadcrumb
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => $result['title']];
        return view('client.category.index', compact('result', 'breadcrumb'));
    }

    function bookmark(){
        $per_page = env('PERPAGE');
        $result = Bookmark::where([
                    'user_id' => Session::get('user')->id,
                    'type' => 'film'
                    ])->with('film')->orderBy('id', 'desc')->paginate($per_page);
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Danh sách teho dõi'];
        return view('client.auth.bookmark', compact('result', 'breadcrumb'));
    }

    function genres(){
        if(!Cache::has('genres')){
            $genres = Genre::orderBy('title', 'asc')->get();
            Cache::put('genres', $genres, env('CACHE_TIME'));
        }
        $result = Cache::get('genres');
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Category'];
        $route = 'genre';
        return view('client.category.list', compact('result', 'route', 'breadcrumb'));
    }

    function genre_detail($slug){
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }
        if (isset($_GET['sort'])) {
            $uri = ['sort' => $_GET['sort']];
            $sort_arr = explode('-', $_GET['sort']);
            $sort = $sort_arr[1];
            $by = $sort_arr[0];
        }else{
            $uri = '';
            $sort = 'desc';
            $by = 'order';
        }
        if(!Cache::has('genre_page_'.$slug.'_'.$current_page.$by.$sort)){
            $minutes = env('CACHE_TIME');
            $per_page = env('PERPAGE');
            $genre = Genre::where(['slug' => $slug, 'status' => 1])->first();
            $films = $genre->films()->where(['online' => 1, 'member' => NULL])->where('quality_id', '<>', 11)->orderBy($by, $sort)->paginate($per_page);
            $films->appends($uri);
            $data = [
                'result' => $films,
                'pagination' => $films->render(),
                'title' => $genre->title,
                'genre' => $genre
                ];
            Cache::put('genre_page_'.$slug.'_'.$current_page.$by.$sort, $data, $minutes);
        }
        $result = Cache::get('genre_page_'.$slug.'_'.$current_page.$by.$sort);
        // Seo:
        MetaTag::set('title', json_decode($result['genre']->seo)->title);
        MetaTag::set('keyword', json_decode($result['genre']->seo)->keyword);
        MetaTag::set('description', $result['genre']->description);
        // Breadcrumb
        $breadcrumb[] = ['link' => route('genres'), 'title' => 'Category'];
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => $result['title']];
        return view('client.category.index', compact('result', 'breadcrumb'));
    }

    function countries(){
        if(!Cache::has('countries')){
            $countries = Country::orderBy('title', 'desc')->get();
            Cache::put('countries', $countries, env('CACHE_TIME'));
        }
        $result = Cache::get('countries');
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Country'];
        $route = 'country';
        return view('client.category.list', compact('result', 'route', 'breadcrumb'));
    }

    function country($slug){
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }
        if (isset($_GET['sort'])) {
            $uri = ['sort' => $_GET['sort']];
            $sort_arr = explode('-', $_GET['sort']);
            $sort = $sort_arr[1];
            $by = $sort_arr[0];
        }else{
            $uri = '';
            $sort = 'desc';
            $by = 'updated_at';
        }
        if(!Cache::has('country_page_'.$slug.'_'.$current_page.$by.$sort)){
            $minutes = env('CACHE_TIME');
            $per_page = env('PERPAGE');
            $country  = Country::where(['slug' => $slug, 'status' => 1])->first();
            $films = $country->films()->where(['online' => 1, 'member' => NULL])->where('quality_id', '<>', 11)->orderBy($by, $sort)->paginate($per_page);
            $films->appends($uri);
            $data = [
                'result' => $films,
                'pagination' => $films->render(),
                'title' => $country->title,
                'country' => $country
                ];
            Cache::put('country_page_'.$slug.'_'.$current_page.$by.$sort, $data, $minutes);
        }
        $result = Cache::get('country_page_'.$slug.'_'.$current_page.$by.$sort);
        // Seo:
        MetaTag::set('title', json_decode($result['country']->seo)->title);
        MetaTag::set('keyword', json_decode($result['country']->seo)->keyword);
        MetaTag::set('description', $result['country']->description);
        // Breadcrumb
        $breadcrumb[] = ['link' => route('countries'), 'title' => 'Country'];
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => $result['title']];
        return view('client.category.index', compact('result', 'breadcrumb'));
    }

    function years(){
        $now = date('Y', time());
        for ($i=$now; $i >= 1920; $i--) {
            $years[] = ['slug' => $i, 'title' => $i];
        }
        $result = collect(json_decode(json_encode($years)));
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Public date'];
        MetaTag::set('title', 'Public date');
        $route = 'year';
        return view('client.category.list', compact('result', 'route', 'breadcrumb'));
    }

    function year($slug){
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }
        if (isset($_GET['sort'])) {
            $uri = ['sort' => $_GET['sort']];
            $sort_arr = explode('-', $_GET['sort']);
            $sort = $sort_arr[1];
            $by = $sort_arr[0];
        }else{
            $uri = '';
            $sort = 'desc';
            $by = 'updated_at';
        }
        if(!Cache::has('year_page_'.$slug.'_'.$current_page.$by.$sort)){
            $minutes = env('CACHE_TIME');
            $per_page = env('PERPAGE');
            $year  = Film::where('date', 'like', "%$slug")->where('quality_id', '<>', 11)->where(['online' => 1, 'member' => NULL])->with(['image_server', 'quality', 'category'])->orderBy($by, $sort)->paginate($per_page);
            $year->appends($uri);
            $data = [
                'result' => $year,
                'pagination' => $year->render(),
                'title' => $slug
                ];
            Cache::put('year_page_'.$slug.'_'.$current_page.$by.$sort, $data, $minutes);
        }
        $result = Cache::get('year_page_'.$slug.'_'.$current_page.$by.$sort);
        // Seo:
        MetaTag::set('title', 'Năm '.$slug);
        MetaTag::set('keyword', $slug);
        // Breadcrumb
        $breadcrumb[] = ['link' => route('years'), 'title' => 'Public date'];
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => $result['title']];
        return view('client.category.index', compact('result', 'breadcrumb'));
    }
    function popular(){
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }
        if(!Cache::has('popular_page_'.$current_page)){
            $minutes = env('CACHE_TIME');
            $per_page = env('PERPAGE');
            $films  = Film::with(['image_server', 'quality', 'category'])->where('quality_id', '<>', 11)->where(['online' => 1, 'member' => NULL])->orderBy('viewed', 'desc')->paginate($per_page);
            $data = [
                'result' => $films,
                'pagination' => $films->render(),
                'title' => 'popular'
                ];
            Cache::put('popular_page_'.$current_page, $data, $minutes);
        }
        $result = Cache::get('popular_page_'.$current_page);
        // Seo:
        MetaTag::set('title', 'popular');
        MetaTag::set('keyword', 'popular');
        // Breadcrumb
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => $result['title']];
        return view('client.category.index', compact('result', 'breadcrumb'));
    }
    function hot(){
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }
        if (isset($_GET['sort'])) {
            $uri = ['sort' => $_GET['sort']];
            $sort_arr = explode('-', $_GET['sort']);
            $sort = $sort_arr[1];
            $by = $sort_arr[0];
        }else{
            $uri = '';
            $sort = 'desc';
            $by = 'order';
        }
        if(!Cache::has('hot_page_'.$current_page.$by.$sort)){
            $minutes = env('CACHE_TIME');
            $per_page = env('PERPAGE');
            $films  = Film::where('hot', 1)->where(['online' => 1, 'member' => NULL])->where('quality_id', '<>', 11)->with(['image_server', 'quality', 'category'])->orderBy($by, $sort)->paginate($per_page);
            $films->appends($uri);
            $data = [
                'result' => $films,
                'pagination' => $films->render(),
                'title' => 'Phim Hot'
                ];
            Cache::put('hot_page_'.$current_page.$by.$sort, $data, $minutes);
        }
        $result = Cache::get('hot_page_'.$current_page.$by.$sort);
        // Seo:
        if (!Cache::has('hot_block_seo')) {
            $seo = \App\Models\Seo::where('slug', 'phim-chieu-rap')->first();
            Cache::put('hot_block_seo', $seo, 1000);
        }
        $seo = Cache::get('hot_block_seo');
        if(!is_null($seo)){
            MetaTag::set('title', $seo->title);
            MetaTag::set('keyword', $seo->keyword);
            MetaTag::set('description', $seo->description);
        }
        // Breadcrumb
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => $result['title']];
        return view('client.category.index', compact('result', 'breadcrumb'));
    }

    function stars(){
        $current_page = isset($_GET['page'])? $_GET['page']: 1;
        $minutes = env('CACHE_TIME');
        $per_page = env('PERPAGE');
        if(!Cache::has('stars_page'.$current_page)){
            $total_row = Star::count();
            $total_page = ceil($total_row/$per_page);
            $films = Star::with('image_server')->orderBy('title', 'asc')->paginate($per_page);
            Cache::put('stars_page'.$current_page, $films, $minutes);
        }
        $result = Cache::get('stars_page'.$current_page);
        if(isset($result->seo)){
            $seo = json_decode($result->seo);
            if($seo->title !=''){
                MetaTag::set('title',$seo->title);
            }
            if($seo->keyword !=''){
                MetaTag::set('keyword',$seo->keyword);
            }
        }
        if(isset($result->description) and $result->description !=''){
            MetaTag::set('description',$result->description);
        }
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Pornstar'];
        return view('client.category.star', compact('result', 'breadcrumb'));
    }

    function star_show($slug){
        $minutes = env('CACHE_TIME');
        if(!Cache::has('star_show_'.$slug)){
            $star = Star::where('slug', $slug)->with('image_server', 'films')->first();
            Cache::put('star_show_'.$slug, $star, $minutes);
        }
        $result = Cache::get('star_show_'.$slug);
        if(!is_null($result->image_server)){
            $image_data = json_decode($result->image_server->data);
            $image_prefix = $image_data->public_url.'/'.$image_data->dir;
        }else{
            $image_prefix = asset('themes/client/img/coming-soon.jpg');
        }
        MetaTag::set('image_src', $image_prefix.$result->thumb_name);
        $img = explode('.', $image_prefix.$result->thumb_name);
        MetaTag::set('image_type', array_pop($img));
        if(isset($result->seo)){
            $seo = json_decode($result->seo);
            if($seo->title !=''){
                MetaTag::set('title',$seo->title);
            }
            if($seo->keyword !=''){
                MetaTag::set('keyword',$seo->keyword);
            }
            if(isset($seo->description) and $seo->description !=''){
                MetaTag::set('description',$seo->description);
            }
        }
        $breadcrumb[] = ['link' => route('stars'), 'title' => 'Pornstar'];
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => $result->title];
        return view('client.category.star', compact('result', 'breadcrumb', 'image_prefix'));
    }
}
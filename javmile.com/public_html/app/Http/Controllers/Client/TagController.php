<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Tags;
use App\Models\Film;
use Illuminate\Pagination\Paginator;
use MetaTag, Cache, DB;

class TagController extends Controller
{
    function index(){
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }
        if(!Cache::has('tags_'.$current_page)){
            $tags = Tags::paginate(50);
            Cache::put('tags_'.$current_page, $tags, env('CACHE_TIME'));
        }
        $result = Cache::get('tags_'.$current_page);
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Từ khóa'];
        $route = 'tag';
        $paginate = true;
        return view('client.category.list', compact('result', 'route', 'paginate', 'breadcrumb'));
    }

    function detail($slug){
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

        $tag = Tags::where('slug', $slug)->first();
        if(!Cache::has('film_tag_'.$slug)){
            $film_tag_ids = DB::table('film_tags')->where('tag_id', $tag->id)->pluck('film_id');
            Cache::put('film_tag_'.$slug, $film_tag_ids, env('CACHE_TIME'));
        }
        $film_ids = Cache::get('film_tag_'.$slug);
        $result['title'] = $tag->title;
        $result['result'] = Film::whereIn('id', $film_ids)->where('online', 1)->with(['image_server', 'quality', 'category'])->orderBy($by, $sort)->paginate(env('PERPAGE'));
        $result['result']->appends($uri);
        $result['pagination'] = $result['result']->render();
        // Seo:
        // $seo = json_decode($tag->seo);
        // MetaTag::set('title', $seo->title);
        // MetaTag::set('keyword', $seo->keyword);
        // $desc_seo = isset($seo->desc)? $seo->desc: $seo->description;
        // MetaTag::set('description', $desc_seo);
        $breadcrumb[] = ['link' => route('tags'), 'title' => 'Từ khóa'];
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => $tag->title];
        return view('client.category.index', compact('result', 'title', 'breadcrumb'));
    }
}

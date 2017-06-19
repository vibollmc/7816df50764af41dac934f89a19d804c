<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\Controller;
use App\Models\Articles;
use App\Models\Setting;
use MetaTag, Cache;

class ArticleController extends Controller
{
    public function index()
    {
        if(!Cache::get('post_cache_time')){
            Cache::forever('post_cache_time', json_decode((Setting::where(['type' => 'constant', 'location' => 'post_cache_time'])->first()->data))->value);
        }
        if(!Cache::get('per_category_page')){
            Cache::forever('per_category_page', json_decode((Setting::where(['type' => 'constant', 'location' => 'per_category_page'])->first()->data))->value);
        }
        $minutes = Cache::get('post_cache_time');
        $per_page = Cache::get('per_category_page');
        if(isset($_GET['page'])){
            $current_page = $_GET['page'];
        }else{
            $current_page = 1;
        }
        if (!Cache::has('article_page_'.$current_page)) {
            $total_row = Articles::count();
            $total_page = ceil($total_row/$per_page);
            $article = Articles::with(['cover'])->orderBy('id', 'desc')->paginate($per_page);
            Cache::put('article_page_'.$current_page, $article, $minutes);
        }
        $result = Cache::get('article_page_'.$current_page);
        $breadcrumb[] = ['link' => 'javascript:void(0);', 'title' => 'Articles'];
        return view('client.article.index', compact('result', 'breadcrumb'));
    }

    function show($slug){
        $articles = Articles::with('tags', 'cover')->get();
        $result = $articles->where('slug', $slug)->first();
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
        $breadcrumb[] = ['link' => route('client_article'), 'title' => 'Articles'];
        $breadcrumb[] = ['link' => 'javascript:void(0);', 'title' => $result->title];
        return view('client.article.detail', compact('result', 'articles', 'breadcrumb'));
    }
}

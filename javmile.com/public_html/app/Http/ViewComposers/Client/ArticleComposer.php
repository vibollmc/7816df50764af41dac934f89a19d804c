<?php
namespace App\Http\ViewComposers\Client;
use App\Models\Article;
use Cache;

class ArticleComposer {
    public function compose($view) {
        if (!Cache::has('article_block')) {
            $article = Article::where(['status' => 1, 'type' => 'new'])->first();
            Cache::put('article_block', $article, env('CACHE_TIME'));
        }
        $view->with([
            'article' => Cache::get('article_block')
        ]);
    }
}
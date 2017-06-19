<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Tags extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title', 'title_ascii', 'slug', 'seo', 'status', 'created_at', 'updated_at', 'deleted_at');

    public function films() {
        return $this->belongsToMany('\App\Models\Film', 'film_tags', 'tag_id', 'film_id')->with(['thumb', 'videos', 'quality', 'category']);
    }
    function articles() {
        return $this->belongsToMany('\App\Models\Articles', 'article_tags', 'tag_id', 'article_id')->with('cover')->orderBy('id', 'desc');
    }
}

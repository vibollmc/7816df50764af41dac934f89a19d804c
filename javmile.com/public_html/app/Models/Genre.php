<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Genre extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title','title_ascii', 'description', 'status', 'slug', 'seo', 'deleted_at', 'updated_at', 'created_at');

    public function films() {
        return $this->belongsToMany('\App\Models\Film', 'film_genres', 'genre_id', 'film_id')->with(['image_server', 'quality', 'category', 'episode_list']);
    }

    public function block() {
        return $this->belongsToMany('\App\Models\Film', 'film_genres', 'genre_id', 'film_id')->with(['image_server', 'quality', 'category'])->where('online', 1)->where('quality_id', '<>', 11)->whereNull('member')->take(env('PERBLOCK_SIDEBAR'))->orderBy('order', 'desc');
    }

    function has_film(){
        return $this->hasOne('\App\Models\Film_genre', 'genre_id');
    }
}

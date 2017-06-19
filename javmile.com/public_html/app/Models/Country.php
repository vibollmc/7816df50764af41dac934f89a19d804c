<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Country extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title','title_ascii', 'description', 'status', 'slug', 'seo', 'deleted_at', 'updated_at', 'created_at');

    public function films() {
        return $this->belongsToMany('\App\Models\Film', 'film_countries', 'country_id', 'film_id')->with(['image_server', 'quality', 'category']);
    }

    public function block() {
        return $this->belongsToMany('\App\Models\Film', 'film_countries', 'country_id', 'film_id')->with(['image_server', 'quality', 'category'])->where('online', 1)->take(env('PERBLOCK_SIDEBAR'))->orderBy('order', 'desc');
    }

    function has_film(){
        return $this->hasOne('\App\Models\Film_country', 'country_id');
    }
}

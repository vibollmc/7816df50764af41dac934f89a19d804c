<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Base;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Film extends Base
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('category_id', 'thumb_name', 'cover_name', 'title', 'title_en', 'title_ascii', 'slug', 'date', 'order', 'online', 'hot', 'free', 'slide', 'quality_id', 'episodes', 'exist_episodes', 'runtime', 'imdb_rate', 'imdb_url', 'description', 'storyline', 'viewed', 'seo', 'user_id', 'created_at', 'updated_at', 'deleted_at', 'demo_filename', 'demo_sub_en', 'demo_sub_own', 'server_id', 'is_new', 'ftp_id', 'extend', 'created_by', 'updated_by', 'reported', 'fixing', 'calendar', 'customer_id', 'member', 'trailer');

    function user(){
        return $this->hasOne('\App\Models\User', 'id', 'user_id');
    }
    function customer(){
        return $this->hasOne('\App\Models\Customer', 'id', 'customer_id');
    }
    function cover(){
        return $this->hasOne('\App\Models\Image', 'id', 'cover_id');
    }
    function image_server(){
        return $this->hasOne('\App\Models\Server', 'id', 'ftp_id');
    }
    function quality(){
        return $this->hasOne('\App\Models\Quality', 'id', 'quality_id');
    }
    function category(){
        return $this->hasOne('\App\Models\Category', 'id', 'category_id');
    }
    public function stars() {
        return $this->belongsToMany('\App\Models\Star', 'film_stars', 'film_id', 'star_id')->with('image_server');
    }

    public function directors() {
        return $this->belongsToMany('\App\Models\Director', 'film_directors', 'film_id', 'director_id');
    }

    public function tags() {
        return $this->belongsToMany('\App\Models\Tags', 'film_tags', 'film_id', 'tag_id');
    }

    public function countries() {
        return $this->belongsToMany('\App\Models\Country', 'film_countries', 'film_id', 'country_id');
    }

    public function genres() {
        return $this->belongsToMany('\App\Models\Genre', 'film_genres', 'film_id', 'genre_id');
    }

    function episode_list(){
        return $this->hasMany('\App\Models\Episode', 'film_id');
    }
    function videos_frontend(){
        return $this->hasMany('\App\Models\Video', 'item_id')->where('type', 'video');
    }
    function demo(){
        return $this->hasOne('\App\Models\Video', 'item_id')->where('type', 'demo')->with(['backups']);
    }
    function demo_frontend(){
        return $this->hasOne('\App\Models\Video', 'item_id')->where('type', 'demo')->with(['backups_frontend']);
    }

    function reports(){
        return $this->hasMany('\App\Models\Reporter', 'able_id')->with('user');
    }
}
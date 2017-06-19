<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Star extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at', 'updated_at', 'created_at'];
    protected $fillable = array('title', 'slug', 'fullname', 'height', 'birth', 'home_town', 'story', 'thumb_name', 'ftp_id', 'created_at', 'updated_at', 'deleted_at');

    public function films() {
        return $this->belongsToMany('\App\Models\Film', 'film_stars', 'star_id', 'film_id')->with(['image_server', 'quality', 'category']);
    }

    function image_server(){
        return $this->hasOne('\App\Models\Server', 'id', 'ftp_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use App\Models\Base;

class Article extends Base
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title','title_ascii', 'data', 'status', 'online', 'type', 'seo', 'slug', 'viewed', 'description', 'content', 'created_at', 'updated_at', 'deleted_at');
    protected $guarded = ['id', '_token'];

    public function images() {
        return $this->hasMany('\App\Models\Image', 'id_article')->where('type', 'content');
    }

    function cover(){
        return $this->hasOne('\App\Models\Image', 'id_article')->where('type', 'cover');
    }

    public function tags() {
        return $this->belongsToMany('\App\Models\Tags', 'article_tags', 'article_id', 'tag_id');
    }
}

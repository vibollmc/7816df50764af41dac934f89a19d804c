<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use App\Models\Base;

class Tb_film extends Base
{
    protected $fillable = ['title', 'title_en', 'title_search', 'actor', 'thumb', 'viewed', 'viewed_day', 'category', 'director', 'duration', 'quality', 'year', 'producer', 'country', 'filmlb', 'timeupdate', 'trailer', 'big_image', 'decu', 'viewed_month', 'viewed_week', 'release_time', 'total_votes', 'total_value', 'error', 'slider', 'userpost', 'thuyetminh', 'link_down', 'active', 'is_sync'];

    public function episodes() {
        return $this->hasMany('\App\Models\Tb_episode', 'filmid');
    }
}

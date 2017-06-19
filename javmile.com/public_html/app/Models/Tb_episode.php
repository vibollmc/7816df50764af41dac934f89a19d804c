<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use App\Models\Base;

class Tb_episode extends Base
{
    protected $fillable = ['name', 'filmid', 'url', 'subtitle', 'error', 'thumb', 'userpost', 'datetime_post', 'default_subtitle_id', 'present', 'active', 'vietsub', 'download' ];
}

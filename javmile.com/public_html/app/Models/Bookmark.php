<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Bookmark extends Model
{
    protected $fillable = array('user_id', 'able_id', 'order', 'type');

    function film(){
        return $this->hasOne('\App\Models\Film', 'id', 'able_id')->with(['image_server', 'quality', 'category']);
    }
}

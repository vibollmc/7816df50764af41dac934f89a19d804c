<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Feedback extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title', 'type', 'user_id', 'able_id', 'type', 'status', 'reported', 'description', 'created_at', 'updated_at', 'deleted_at');

    function film(){
        return $this->hasOne('\App\Models\Film', 'id', 'able_id');
    }
}

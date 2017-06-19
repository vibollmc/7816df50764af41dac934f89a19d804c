<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class User_group extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('group_name', 'editble', 'deleted_at', 'updated_at', 'created_at');

    public function users() {
        return $this->hasMany('\App\Models\User', 'group_id');
    }

}

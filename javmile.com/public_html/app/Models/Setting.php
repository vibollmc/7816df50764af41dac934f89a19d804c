<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Setting extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = array('title','type', 'status', 'data', 'data_type', 'location', 'deleted_at', 'updated_at', 'created_at');
}

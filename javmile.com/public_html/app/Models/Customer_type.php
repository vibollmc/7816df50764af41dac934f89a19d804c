<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Customer_type extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title', 'slug', 'deleted_at', 'updated_at', 'created_at');

    public function customer() {
        return $this->hasMany('\App\Models\User', 'type_id');
    }

}

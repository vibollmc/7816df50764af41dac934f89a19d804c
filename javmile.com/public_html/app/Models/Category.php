<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Category extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title','title_ascii', 'description', 'seo', 'status', 'parent_id', 'slug', 'deleted_at', 'updated_at', 'created_at');

    public function films() {
        return $this->hasMany('\App\Models\Film', 'category_id');
    }

    function items(){
        return $this->hasMany('\App\Models\Category', 'parent_id');
    }
}

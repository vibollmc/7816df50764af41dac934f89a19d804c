<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Tag extends Model {

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('article_id','tag_id');
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at', 'updated_at', 'created_at'];
    protected $fillable = array('item_id', 'id_article', 'type', 'server_id', 'filename', 'link', 'created_at', 'updated_at', 'deleted_at');
}

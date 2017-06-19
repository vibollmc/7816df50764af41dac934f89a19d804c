<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Film_star extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at', 'updated_at', 'created_at'];
    protected $fillable = array('film_id', 'star_id', 'created_at', 'updated_at', 'deleted_at');
}

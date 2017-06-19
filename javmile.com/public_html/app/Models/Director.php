<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Director extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title', 'created_at', 'updated_at', 'deleted_at');

    public function films() {
        return $this->belongsToMany('\App\Models\Film', 'film_directors', 'director_id', 'film_id')->with(['thumb', 'videos', 'quality', 'category']);
    }
}

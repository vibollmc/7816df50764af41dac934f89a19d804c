<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Google_drive_file extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('able_id', 'file_name', 'status', 'file_id', 'film_video_id', 'created_at', 'updated_at', 'deleted_at');

    function folder(){
        return $this->hasOne('\App\Models\Google_drive', 'id', 'able_id');
    }
}

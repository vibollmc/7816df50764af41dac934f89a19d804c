<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Setting;

class Episode extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title', 'server_id', 'film_id', 'user_id', 'customer_id', 'file_name', 'status', 'sub_vi', 'sub_en', 'ftp_id', 'type', 'viewed', 'created_at', 'updated_at', 'deleted_at');

    public function film() {
        return $this->hasOne('\App\Models\Film', 'id', 'film_id')->with('image_server', 'category');
    }

    public function user() {
        return $this->hasOne('\App\Models\User', 'id', 'user_id');
    }
}

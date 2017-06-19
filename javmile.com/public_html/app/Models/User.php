<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class User extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = array('username','email', 'password','full_name','avatar','phone_number', 'group_id','ftp_id','role_id', 'status', 'address', 'deleted_at', 'updated_at', 'created_at', 'remember_token' );

    public function group() {
        return $this->hasOne('\App\Models\User_group', 'id', 'group_id');
    }

    public function avatar() {
        return $this->belongsTo('\App\Models\Image', 'id_item')->where('type','avatar')->whereNull('deleted_at');
    }
}

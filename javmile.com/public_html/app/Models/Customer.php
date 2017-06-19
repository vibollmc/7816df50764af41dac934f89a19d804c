<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Contracts\Auth\Authenticatable;

class Customer extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = array('username','email', 'password','full_name','avatar_id','phone', 'verified', 'type_id','status', 'data', 'deleted_at', 'updated_at', 'created_at', 'remember_token', 'baned_to', 'spamed');

    public function type() {
        return $this->hasOne('\App\Models\Customer_type', 'id', 'type_id');
    }

    public function avatar() {
        return $this->hasOne('\App\Models\Image', 'id', 'avatar_id');
    }

    function bookmark(){
        return $this->hasMany('\App\Models\Bookmark', 'user_id')->where('type', 'film');
    }
}

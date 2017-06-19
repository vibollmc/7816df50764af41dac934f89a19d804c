<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Message extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('customer_id', 'status', 'content', 'deleted_at', 'updated_at', 'created_at');

    public function customer() {
        return $this->hasOne('\App\Models\Customer', 'id', 'customer_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reporter extends Model
{
    protected $fillable = array('customer_id', 'able_id', 'error_id', 'content');

    function user(){
        return $this->hasOne('\App\Models\Customer', 'id', 'customer_id');
    }
}

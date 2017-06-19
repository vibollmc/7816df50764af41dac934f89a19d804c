<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Base extends Model {

    protected $fillable = [];
    protected $hidden   = [];
    protected $guarded  = [];

    public function getDatetimeFormat() {
        return 'Y-m-d H:i:s';
    }
}

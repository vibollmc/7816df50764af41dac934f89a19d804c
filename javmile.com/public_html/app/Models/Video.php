<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Setting;

class Video extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title', 'item_id','type', 'server_id', 'free_filename', 'slug', 'vip_filename', 'own_sub', 'en_sub', 'link', 'created_at', 'updated_at', 'deleted_at');

    function backups(){
        return $this->hasMany('\App\Models\Video', 'item_id')->where('type', 'backup');
    }

    function backups_frontend(){
        if (!\Cache::has('online_server')) {
            \Cache::put('online_server', json_decode(Setting::where(['type' => 'server', 'location' => 'online'])->first()->data, true), 1140);
        }
        $online_server = \Cache::get('online_server');
        return $this->hasMany('\App\Models\Video', 'item_id')->where('type', 'backup')->whereIn('server_id', $online_server);
    }

    public function film() {
        return $this->hasOne('\App\Models\Film', 'id', 'item_id')->with('image_server');
    }
}

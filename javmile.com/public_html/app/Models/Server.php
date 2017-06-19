<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cache;


class Server extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = array('title','title_ascii', 'description', 'data', 'status', 'type', 'default', 'deleted_at', 'updated_at', 'created_at');

    public static function uploadFtp($local_file = false, $upload_file = false, $server_id = false, $return_data = false){
        if (!$local_file OR (!file_exists($local_file))) return false;
        if (!$upload_file) {
            $filename = explode('/', $local_file);
            $upload_file = end($filename);
        }
        $servers = Server::all();
        if (!$server_id) {
            $server = $servers->where('type', 'ftp')->first();
        } else {
            $server = $servers->find($server_id);
        }
        if (is_null($server)) return false;
        if ($server->type != 'ftp') {
            $server = $servers->where('type', 'ftp')->first();
            if (is_null($server)) {
                return false;
            }
        }
        $server_data = json_decode($server->data);

        $server_name = "server_{$server->id}";
        \Config::set('ftp.connections.' . $server_name, array(
            'host'     => $server_data->host,
            'port'     => $server_data->port,
            'username' => $server_data->username,
            'password' => $server_data->password,
            'passive'  => true,
        ));
        $ftp = \FTP::connection($server_name);
        $result = $ftp->uploadFile($local_file, "{$server_data->dir}{$upload_file}");
        if ($return_data) {
            return [
                'server_id' => $server_id,
                'filename'  => $upload_file,
                'link'      => "{$server->data->public_url}/{$server_data->dir}{$upload_file}"
            ];
        } else {
            return ($result !== false) ? ("{$server_data->public_url}/{$server_data->dir}{$upload_file}") : false;
        }
    }

    public static function getFtpLink($file = null, $server_id = null) {
        if (!$server_id) {
            $server = Server::where('type', 'ftp')->first();
        } else {
            if (!\Cache::has('post_cache_time')) {
                Cache::forever('post_cache_time', json_decode(Setting::where('location', 'post_cache_time')->first()->data)->value);
            }
            $minutes = Cache::get('post_cache_time');
            if(!Cache::has('server_'.$server_id)){
                Cache::put('server_'.$server_id, Server::find($server_id), $minutes);
            }
            $server = Cache::get('server_'.$server_id);
        }
        if (is_null($server)) return false;
        if ($server->type != 'ftp') {
            $server = Server::where('type', 'ftp')->first();
            if (is_null($server)) {
                return false;
            }
        }
        $server_data = json_decode($server->data);
        return "{$server_data->public_url}/{$server_data->dir}/{$file}";
    }

    public static function getFileLink($file = null, $server_id = null) {
        if (!\Cache::has('post_cache_time')) {
            Cache::forever('post_cache_time', json_decode(Setting::where('location', 'post_cache_time')->first()->data)->value);
        }
        $minutes = Cache::get('post_cache_time');
        if(!Cache::has('server_'.$server_id)){
            Cache::put('server_'.$server_id, Server::find($server_id), $minutes);
        }
        $server = Cache::get('server_'.$server_id);
        if (is_null($server)) return false;

        $link = null;
        $server_data = json_decode($server->data);
        switch ($server->type) {
            case 'ftp':
                $link = "{$server_data->public_url}/{$server_data->dir}/{$file}";
                break;

            case 'wowza':
                $link .= $server_data->prefix . '/' . $file . '/' . $server_data->suffix;
                $link = trim($link, '/');
                break;
            default:
                $domain        = $server_data->domain;
                $secret        = $server_data->secret;
                $uri_prefix    = $server_data->prefix;
                $file_name     = "/{$file}";
                $timestamp     = time();
                $timestamp_hex = sprintf("%08x", $timestamp);
                $token         = md5($secret.$file_name.$timestamp_hex);
                $link          = "{$domain}{$uri_prefix}{$token}/{$timestamp_hex}{$file_name}";
                break;
        }
        return $link;
    }
}
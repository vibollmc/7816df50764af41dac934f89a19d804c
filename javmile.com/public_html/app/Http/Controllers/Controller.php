<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Setting;
use MetaTag, Cache, Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        // Defaults
        if (!Cache::has('home_seo')) {
            Cache::put('home_seo', json_decode(Setting::where('type', 'seo')->first()->data), env('CACHE_TIME'));
        }
        foreach (Cache::get('home_seo') as $key => $value) {
            MetaTag::set($key, $value);
        }
    }
}
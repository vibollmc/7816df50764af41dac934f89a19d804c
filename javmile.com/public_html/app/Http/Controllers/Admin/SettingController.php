<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use Validator, Session, Redirect, Cache;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\AdminController;
use App\Models\Setting;
use App\Models\Genre;
use App\Models\Server;
use App\Models\Image;
use App\Models\Customer_type;
use App\Models\Category;
use App\Models\Star;
use App\Models\Country;

class SettingController extends AdminController
{
    function analytic(){
        $result = Setting::firstOrCreate([
                'type' => 'analytic',
                'title' => 'analytic'
            ]);
        return view('admin.setting.analytic', compact('result'));
    }
    function update_analytic(Request $request){
        $data = $request->all();
        if (isset($data['data'])) {
            # code...
            $result = Setting::firstOrCreate([
                    'type' => 'analytic',
                    'title' => 'analytic'
                ]);
            $result->data = $data['data'];
            $result->save();
            Session::flash('message', '<div class="alert alert-success">Sửa thành công!</div>');
        }
        return Redirect::route('backend_analytic');
    }

    function calendar(){
        $result = Setting::firstOrCreate([
                'title' => 'Calendar',
                'type' => 'calendar'
            ]);
        return view('admin.setting.calendar', compact('result'));
    }

    function update_calendar(Request $request){
        $data = $request->all();
        $result = Setting::firstOrCreate([
                'title' => 'Calendar',
                'type' => 'calendar'
            ]);
        $result_data = NULL;
        if (isset($data['data'])) {
            foreach ($data['data'] as $key => $value) {
                if ($value['title'] != '') {
                    $result_data[] = $value;
                }
            }
        }
        $result->data = $result_data != NULL? json_encode($result_data): Null;
        $result->save();
        Session::flash('message', 'updated');
        return Redirect::back();
    }

    function slide(){
        $result = Setting::firstOrCreate([
                'type' => 'slide',
                'title' => 'Slide'
            ]);
        return view('admin.setting.slide', compact('result', 'servers'));
    }
    function update_slide(Request $request){
        $data = $request->all();
        if(count($data) > 1){

            if (isset($data['file'])) {
                if (!is_dir('data/uploads')) {
                    mkdir('data/uploads', 0777, true);
                }
                $uploadDir = 'data/uploads/';
                $fileName = $data['file']->getClientOriginalName();
                $extension = $data['file']->getClientOriginalExtension();
                $allowedExtensions = array('jpeg', 'jpg', 'png', 'bmp', 'gif');
                $file_rename   = 'slide-' . uniqid() . '.' . $extension;
                if(in_array($extension, $allowedExtensions)){
                    $data['file']->move($uploadDir, $fileName);
                    @rename($uploadDir.$fileName, $uploadDir.$file_rename);
                }
                $thumb = $uploadDir.$file_rename;
                $server = Server::where(['type' => 'ftp'])->first();
                $image_thumb = Server::uploadFtp($thumb, null, $server->id);
                @unlink($thumb);
                if (isset($data['data'])) {
                    array_push($data['data'], ['url' => $data['url'], 'img' => $image_thumb, 'content' => $data['content']]);
                }else{
                    $data['data'] = [['url' => $data['url'], 'img' => $image_thumb, 'content' => $data['content']]];
                }
            }
            if (isset($data['data'])) {
                $result = Setting::firstOrCreate([
                    'type' => 'slide',
                    'title' => 'Slide'
                ]);
                $result->data = json_encode($data['data']);
                $result->save();
                Session::flash('message', '<div class="alert alert-success">Sửa thành công!</div>');
            }
            Cache::forget('slide');
        }
        return Redirect::route('edit_slide');
    }

    function price(){
        $result = Setting::firstOrCreate([
                'type' => 'price',
                'title' => 'Price'
            ]);
        return view('admin.setting.price', compact('result'));
    }

    function update_price(Request $request){
        $data = $request->all();
        $result = Setting::firstOrCreate([
                'type' => 'price',
                'title' => 'Price'
            ]);
        $result->data = $data['data'];
        $result->save();
        return Redirect::back();
    }

    function seo(){
        $result = json_decode(Setting::where('type', 'seo')->first()->data);
        return view('admin.setting.seo', compact('result'));
    }
    function seo_update(Request $request){
        $data = $request->all();
        $icon_mes = '';
        if (isset($data['icon'])) {
            $fileName = $data['icon']->getClientOriginalName();
            $extension = $data['icon']->getClientOriginalExtension();
            if($extension=='ico'){
                if (file_exists('favicon.ico')){
                    @unlink('favicon.ico');
                }
                $icon = $data['icon']->move('.', 'favicon.ico');
            }else{
                $icon_mes = ' but <b>shortcut icon</b> type can not change, file type must be .ico';
            }
            unset($data['icon']);
        }
        unset($data['_token']);
        $seo = Setting::where('type', 'seo')->first();
        if (is_null($seo)) {
            $seo = Setting::create([
                'title' => 'home page seo',
                'type' => 'seo',
                'status' => 1,
                'data_type' => 'json',
                'data' => json_encode($data)
                ]);
        }else{
            $seo->data = json_encode($data);
            $seo->save();
        }
        Session::flash('message', '<p class="alert alert-success">Edited'.$icon_mes.'</p>');
        return Redirect::route('edit_seo');
    }

    function face(){
        return view('admin.setting.interface');
    }

    public function update_face(Request $request)
    {
        $data = $request->all();
        if (\Request::hasFile('logo')) {
            if (!is_dir('themes/client/img')) {
                mkdir('themes/client/img', 0777);
            }
            $uploadDir = 'themes/client/img/';
            $fileName = $data['logo']->getClientOriginalName();
            $extension = $data['logo']->getClientOriginalExtension();
            $file_rename   = 'logo.'.$extension;
            if($extension == 'png'){
                $data['logo']->move($uploadDir, $fileName);
                @rename($uploadDir.$fileName, $uploadDir.$file_rename);
                Session::flash('message', '<div class="alert alert-success">Thay đổi thành công!</div>');
            }else{
                Session::flash('message', '<div class="alert alert-danger">File không phải định dạng .png!</div>');
            }
        }
        if (\Request::hasFile('footer_logo')) {
            if (!is_dir('themes/client/img')) {
                mkdir('themes/client/img', 0777);
            }
            $uploadDir = 'themes/client/img/';
            $fileName = $data['footer_logo']->getClientOriginalName();
            $extension = $data['footer_logo']->getClientOriginalExtension();
            $file_rename   = 'footer-logo.'.$extension;
            if($extension == 'png'){
                $data['footer_logo']->move($uploadDir, $fileName);
                @rename($uploadDir.$fileName, $uploadDir.$file_rename);
                Session::flash('message', '<div class="alert alert-success">Thay đổi thành công!</div>');
            }else{
                Session::flash('message', '<div class="alert alert-danger">File không phải định dạng .png!</div>');
            }
        }
        return Redirect::route('edit_interface');
    }

    function social(){
        $facebook = Setting::where(['type' => 'social', 'location' => 'facebook'])->first();
        $google = Setting::where(['type' => 'social', 'location' => 'google'])->first();
        if (is_null($google)) {
            $google = Setting::create([
                'title' => 'Google',
                'type' => 'social',
                'location' => 'google',
                'status' => 1,
                'data_type' => 'json'
                ]);
        }
        if (is_null($facebook)) {
            $facebook = Setting::create([
                'title' => 'Facebook',
                'type' => 'social',
                'location' => 'facebook',
                'status' => 1,
                'data_type' => 'json'
                ]);
        }
        return view('admin.setting.social', compact('facebook', 'google'));
    }
    function update_social(Request $request){
        $data = $request->all();
        $facebook = Setting::where(['type' => 'social', 'location' => 'facebook'])->first();
        $google = Setting::where(['type' => 'social', 'location' => 'google'])->first();
        $facebook->data = json_encode($data['facebook']);
        $facebook->save();
        $google->data = json_encode($data['google']);
        $google->save();
        Session::flash('message', '<p class="alert alert-success">Edited!</p>');
        return Redirect::back();
    }

    function footer(){
        $top    = Setting::where('type', 'footer')->where('location', 'like', 'top%')->get();
        $second = Setting::where('type', 'footer')->where('location', 'like', 'second%')->get();
        $bottom = Setting::where(['location' => 'bottom', 'type' => 'footer'])->first();
        $categories = Category::orderBy('title', 'asc')->get();
        $genres = Genre::orderBy('title', 'asc')->get();
        $countries = Country::orderBy('title', 'asc')->get();
        $actors = Star::orderBy('title', 'asc')->where('hot', 1)->get();
        return view('admin.setting.footer', compact('top', 'second', 'bottom', 'categories', 'genres', 'countries', 'actors'));
    }
    function update_footer(Request $request){
        $data = $request->all();
        $footers = Setting::where('type', 'footer')->get();
        foreach ($data['top']['title'] as $key => $value) {
            $footer[$key] = $footers->where('location', 'top_'.$key)->first();
            if (!isset($footer[$key]) or is_null($footer[$key])) {
                $footer[$key] = Setting::firstOrCreate([
                    'title' => 'Top column '.$key,
                    'type' => 'footer',
                    'location' => 'top_'.$key,
                    'status' => 1,
                    'data_type' => 'json',
                    'data' => json_encode([
                        'title' => $value,
                        'value' => isset($data['top']['value'][$key])? $data['top']['value'][$key]: ''
                        ])
                    ]);
            }else{
                $footer[$key]->data = json_encode([
                        'title' => $value,
                        'value' => isset($data['top']['value'][$key])? $data['top']['value'][$key]: ''
                        ]);
                $footer[$key]->save();
            }
        }

        foreach ($data['second']['title'] as $key => $value) {
            $footer[$key] = $footers->where('location', 'second_'.$key)->first();
            if (!isset($footer[$key]) or is_null($footer[$key])) {
                $footer[$key] = Setting::firstOrCreate([
                    'title' => 'Second column '.$key,
                    'type' => 'footer',
                    'location' => 'second_'.$key,
                    'status' => 1,
                    'data_type' => 'json',
                    'data' => json_encode([
                        'title' => $value,
                        'value' => isset($data['second']['value'][$key])? $data['second']['value'][$key]: ''
                        ])
                    ]);
            }else{
                $footer[$key]->data = json_encode([
                        'title' => $value,
                        'value' => isset($data['second']['value'][$key])? $data['second']['value'][$key]: ''
                        ]);
                $footer[$key]->save();
            }
        }

        $bottom = $footers->where('location', 'bottom')->first();
        if (!isset($bottom) or is_null($bottom)) {
            $bottom = Setting::firstOrCreate([
                'title' => 'Bottom',
                'type' => 'footer',
                'location' => 'bottom',
                'status' => 1,
                'data_type' => 'html',
                'data' => $data['bottom']
                ]);
        }else {
            $bottom->data = $data['bottom'];
            $bottom->save();
        }
        Session::flash('message', '<p class="alert alert-success">Successfully!</p>');
        return Redirect::back();
    }

}

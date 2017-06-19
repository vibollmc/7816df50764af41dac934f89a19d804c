<?php

namespace App\Http\Controllers\client;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use Validator, Session, Redirect;
use Illuminate\Support\Str;
use App\Models\Film;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Quality;
use App\Models\Country;
use App\Models\Server;
use App\Models\Video;
use App\Models\Star;
use App\Models\Film_star;
use App\Models\Director;
use App\Models\Film_director;
use App\Models\Tags;
use App\Models\Film_tag;
use App\Models\Film_genre;
use App\Models\Episode;
use App\Models\Setting;
use App\Models\Payment;
use Intervention\Image\ImageManagerStatic as Img;
use Pinger, DB, Cache;

class FilmMemberController extends AdminController
{
    function index(Request $request){
        $result = Film::with(['image_server', 'customer', 'category'])->orderBy('order', 'desc');
        if (isset($_GET['k']) and trim($_GET['k']) != '') {
            $keyword = Str::ascii(trim($_GET['k']));
            $result->where('title_ascii', 'like', "%$keyword%");
        }
        $result->where('customer_id', Session::get('user')->id);
        $result = $result->paginate(20);
        $result->appends($request->all());
        $title = 'Phim của tôi';
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Phim của tôi'];
        return view('client.member.index', compact('result', 'title', 'breadcrumb'));
    }

    function overview(){
        $user = Session::get('user');
        $viewed = Film::where('customer_id', $user->id)->pluck('viewed')->toArray();
        $price = Setting::firstOrCreate([
                'type' => 'price',
                'title' => 'Price'
            ]);
        $payments = Payment::where('customer_id', $user->id)->get();
        return view('client.member.overview', compact('viewed', 'price', 'payments'));
    }

    function checkout(){
        $user = Session::get('user');
        $viewed = Film::where('customer_id', $user->id)->pluck('viewed')->toArray();
        $price = Setting::firstOrCreate([
                'type' => 'price',
                'title' => 'Price'
            ]);
        $payments = Payment::where('customer_id', $user->id)->get();
        if (count($payments) > 0) {
            $pay_view = $payments->pluck('viewed')->toArray();
            $pay_cash = array_sum($viewed) - array_sum($pay_view);
        }else{
            $pay_cash = array_sum($viewed);
        }
        $monney = $pay_cash*$price->data/1000;
        if ($monney >= 200000) {
            Payment::create([
                'customer_id'  => $user->id,
                'time_pending' => time(),
                'viewed'       => $pay_cash,
                'price'        => $price->data,
                'status'       => 'pending'
                ]);
        }
        return Redirect::route('user_static');
    }

    function drive_tool(){
        return view('client.member.drive');
    }

    function post_drive_tool(Request $request){
        $data = $request->all();
        $array = explode('/', $data['folder']);
        $id = end($array);
        $url = 'https://drive.google.com/folderview?id='.$id;
        $get = $this->curl($url);
        $needed = '[[[\x22';
        $cat = explode($needed, $get);
        unset($cat[0]);
        if(isset($cat[1])){
            $cat = explode('n,[\x22', $cat[1]);
            foreach ($cat as $key => $value) {
                $file_info = explode('\x22', str_replace('"', '', $value));
                if (count($file_info) >= 5 and strpos($value, 'video')) {
                    $files[$key]['name'] = trim($file_info[4]);
                    $files[$key]['link'] = 'https://drive.google.com/file/d/'.trim($file_info[0]);
                }
            }
            if (isset($files)) {
                $files = collect($files)->sortBy('name');
                $num = $data['num'];
                return view('client.member.drive', compact('files', 'num'));
            }
        }
        Session::flash('message', 'Nhập vào chưa chính xác hoặc folder chưa được chia sẻ');
        return Redirect::back();
    }

    function create(){
        $categories = Category::where('status', 1)->get();
        $qualities = Quality::all();
        $countries = Country::where('status', 1)->get();
        $genres = Genre::where('status', 1)->get();
        return view('client.member.create', compact('categories', 'qualities', 'countries', 'genres'));
    }

    function store(Request $request){
        $data = $request->all();
        if (!isset($data['country'])) {
            $data['country'] = Null;
        }
        if (!isset($data['genre'])) {
            $data['genre'] = Null;
        }
        if (!isset($data['thumb'])) {
            $data['thumb'] = Null;
        }
        if (!isset($data['cover'])) {
            $data['cover'] = Null;
        }
        $data['description'] = str_replace("\r\n", ' ', strip_tags($data['description']));
        $validator = Validator::make($data, [
            'title'          => 'required|max:255|unique:films,title',
            'title_en'       => 'required|max:255|unique:films,title_en',
            'cover'          => 'required',
            'thumb'          => 'required',
            "date"           => 'required',
            "director"       => 'required',
            "stars"          => 'required',
            "online"         => 'required',
            "category_id"    => 'required',
            "quality_id"     => 'required',
            "episodes"       => 'required',
            "exist_episodes" => 'required',
            "runtime"        => 'required',
            "storyline"      => 'required',
            'country'        => 'required',
            'genre'          => 'required',
            'trailer'        => 'required',
            'ep_title'       => 'required',
            'ep_type'        => 'required',
            'ep_link'        => 'required',
            ]);
        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $slug_unique = Validator::make($data, ['slug' => 'unique:films,slug']);
        if ($slug_unique->fails()) {
            $data['slug'] .= '-'.uniqid();
        }
        $server = Server::where(['type' => 'ftp', 'default' => 1])->first();
        $film_data = [
            'category_id'    => $data['category_id'],
            'title'          => trim($data['title']),
            'title_ascii'    => trim(Str::ascii($data['title'])),
            'title_en'       => trim($data['title_en']),
            'slug'           => $data['slug'],
            'date'           => trim($data['date']),
            'order'          => time(),
            'online'         => 1,
            'quality_id'     => $data['quality_id'],
            'episodes'       => $data['episodes'],
            'exist_episodes' => $data['exist_episodes'],
            'runtime'        => $data['runtime'],
            'description'    => $data['description'],
            'storyline'      => strip_tags($data['storyline'], '<img>'),
            'seo'            => json_encode($data['seo']),
            'ftp_id'         => $server->id,
            'calendar'       => $data['calendar'],
            'trailer'        => $data['trailer'],
            'customer_id'    => Session::get('user')->id,
            'member'         => 1,
            ];
            $film = Film::create($film_data);
        // Episode
            $episode = Episode::create([
                'film_id'     => $film->id,
                'customer_id' => Session::get('user')->id,
                'title'       => $data['ep_title'],
                'file_name'   => $data['ep_link'],
                'type'        => $data['ep_type'],
                ]);
        // Cover:
        if (\Request::hasFile('cover')) {
            $uploadDir = 'data/uploads/';
            $fileName = $data['cover']->getClientOriginalName();
            $extension = $data['cover']->getClientOriginalExtension();
            $allowedExtensions = array('jpeg', 'jpg', 'png', 'bmp', 'gif');
            $file_rename   = $data['slug'] . '-cover.' . $extension;
            if(in_array($extension, $allowedExtensions)){
                $data['cover']->move($uploadDir, $fileName);
                @rename($uploadDir.$fileName, $uploadDir.$file_rename);
            }
            $path = $uploadDir.$file_rename;
            // Image crop cover
            $width = Img::make($path)->width();
            $height = Img::make($path)->height();
            $max_width = 1280;
            $max_height = 620;
            if ($width >= $max_width and $height >= $max_height) {
                if ($width/$height < $max_width/$max_height) {
                    $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }
            }else{
                if ($width/$height < $max_width/$max_height) {
                    $height_crop = round($width*$max_height/$max_width, 0);
                    $width_crop = $width;
                    $image = Img::make($path)->crop($width_crop, $height_crop)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }else{
                    $height_crop = $height;
                    $width_crop = round($height*$max_width/$max_height, 0);
                    $image = Img::make($path)->crop($width_crop, $height_crop)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }
            }
            if (file_exists($path)){
                @unlink($path);
            }
            if (file_exists($path)){
                @unlink($path);
            }
            $film->cover_name = $file_rename;
            $film->ftp_id = $server->id;
        }
        // Thumb:
        if (\Request::hasFile('thumb')) {
            $uploadDir = 'data/uploads/';
            $fileName = $data['thumb']->getClientOriginalName();
            $extension = $data['thumb']->getClientOriginalExtension();
            $allowedExtensions = array('jpeg', 'jpg', 'png', 'bmp', 'gif');
            $file_rename   = $data['slug'] . '_thumb-' . uniqid() . '.' . $extension;
            if(in_array($extension, $allowedExtensions)){
                $data['thumb']->move($uploadDir, $fileName);
                @rename($uploadDir.$fileName, $uploadDir.$file_rename);
            }
            $path = $uploadDir.$file_rename;
            // Image crop cover
            $width = Img::make($path)->width();
            $height = Img::make($path)->height();
            $max_width = 300;
            $max_height = 450;
            if ($width > $max_width and $height > $max_height) {
                if ($width/$height < $max_width/$max_height) {
                    $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }
            }else{
                if ($width/$height < $max_width/$max_width) {
                    $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                    $constraint->upsize();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                    $constraint->upsize();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }
            }
            if (file_exists($path)){
                @unlink($path);
            }
            $film->thumb_name = $file_rename;
            $film->ftp_id = $server->id;
        }
        $film->save();
        // Stars:
        $stars = explode(',', trim($data['stars']));
        $stars = array_map('trim', $stars);
        $star_ids = [];
        if (!empty($stars)) {
            $star_exist = Star::whereIn('title', $stars)->get();
            $star_new_ids = [];
            foreach ($stars as $key => $value) {
                if ($star_exist->where('title', $value)->isEmpty()) {
                    $star = Star::firstOrCreate([
                        'title'       => $value,
                        'slug'       => str_slug($value)
                    ]);
                    $star_new_ids[] = $star->id;
                }
            }
            $star_exist_ids = $star_exist->toArray();
            $star_exist_ids = array_column($star_exist_ids, 'id');
            $star_ids = array_merge($star_exist_ids, $star_new_ids);
        }
        if (!empty($star_ids)) {
            $film->stars()->sync($star_ids);
        }
        // Directors:
        $directors = explode(',', trim($data['director']));
        $directors = array_map('trim', $directors);
        $director_ids = [];
        if (!empty($directors)) {
            $director_exist = Director::whereIn('title', $directors)->get();
            $director_new_ids = [];
            foreach ($directors as $key => $value) {
                if ($director_exist->where('title', $value)->isEmpty()) {
                    $director = Director::firstOrCreate([
                        'title'       => $value
                    ]);
                    $director_new_ids[] = $director->id;
                }
            }
            $director_exist_ids = $director_exist->toArray();
            $director_exist_ids = array_column($director_exist_ids, 'id');
            $director_ids = array_merge($director_exist_ids, $director_new_ids);
        }
        if (!empty($director_ids)) {
            $film->directors()->sync($director_ids);
        }
        // Tags
        $tags = $data['tags'];
        $tags = explode(',', trim($tags));
        $tags = array_map('trim', $tags);
        $tags = array_map('strtolower', $tags);
        $tag_ids = [];
        if (!empty($tags)) {
            $tag_exist = Tags::whereIn('title', $tags)->get();
            $tag_new_ids = [];
            foreach ($tags as $key => $value) {
                if ($tag_exist->where('title', $value)->isEmpty()) {
                    $tag = Tags::firstOrCreate([
                        'title'       => $value,
                        'slug'        => strtolower(str_slug($value)),
                    ]);
                    $tag_new_ids[] = $tag->id;
                }
            }
            $tag_exist_ids = $tag_exist->toArray();
            $tag_exist_ids = array_column($tag_exist_ids, 'id');
            $tag_ids = array_merge($tag_exist_ids, $tag_new_ids);
        }
        if (!empty($tag_ids)) {
            $film->tags()->sync($tag_ids);
        }
        // Country:
        if(isset($data['country']) and !empty($data['country'])){
            $film->countries()->sync($data['country']);
        }
        // Genre:
        if(isset($data['genre']) and !empty($data['genre'])){
            $film->genres()->sync($data['genre']);
        }
        Session::flash('message', '<p class="alert alert-success">Thêm mới phim thành công!</p>');
        return Redirect::route('member_films');
    }

    function film_ep($id){
        $film = Film::where(['id' => $id, 'customer_id' => Session::get('user')->id])->first();
        $result = Episode::where('film_id', $film->id)->orderBy('title', 'desc')->get();
        return view('client.member.film-ep', compact('result', 'film'));
    }

    function ep_error(){
        $result = Episode::where(['status' => 2, 'customer_id' => Session::get('user')->id])->with('film')->paginate(30);
        return view('client.member.film-ep-die', compact('result'));
    }

    function add_ep($id){
        $result = Film::where(['id' => $id, 'customer_id' => Session::get('user')->id])->first();
        return view('client.member.add-ep', compact('result'));
    }

    function store_ep(Request $request, $id){
        $data = $request->all();
        $film = Film::find($id);
        if (!is_null($film) and $film->customer_id == Session::get('user')->id) {
            foreach ($data['link'] as $key => $item) {
                if ($data['title'][$key] != '' and $item != '') {
                    $eps[] = [
                        'film_id'     => $film->id,
                        'customer_id' => Session::get('user')->id,
                        'title'       => $data['title'][$key],
                        'file_name'   => $data['link'][$key],
                        'type'        => $data['type'][$key],
                    ];
                }
            }
            if (isset($eps)) {
                $episodes = Episode::insert($eps);
                $end = end($eps);
                $film->exist_episodes = $end['title'];
                $film->order = time();
                $film->save();
                Session::flash('message', 'thành công!');
                return Redirect::route('member_episode', $film->id);
            }else{
                Session::flash('message', 'Tên tập phim hoặc link phim không để trống');
            }
        }else{
            Session::flash('message', 'Không phải phim của bạn!');
        }
        return Redirect::back();
    }

    function edit_ep($id){
        $result = Episode::where(['id' => $id, 'customer_id' => Session::get('user')->id])->first();
        return view('client.member.edit-ep', compact('result'));
    }

    function edit($id){
        $categories = Category::where('status', 1)->get();
        $qualities = Quality::all();
        $countries = Country::where('status', 1)->get();
        $genres = Genre::where('status', 1)->get();
        $result = Film::where(['id' => $id, 'customer_id' => Session::get('user')->id])->with(['image_server', 'stars', 'directors', 'tags', 'countries', 'genres'])->first();
        return view('client.member.edit', compact('result', 'categories', 'qualities', 'countries', 'genres'));
    }

    function update_ep(Request $request, $id){
        $data = $request->all();
        $result = Episode::where(['id' => $id, 'customer_id' => Session::get('user')->id])->first();
        if (!is_null($result)) {
            $result->title     = $data['title'];
            $result->file_name = $data['link'];
            $result->type      = $data['type'];
            $result->status    = NULL;
            $result->save();
            Session::flash('message', 'thành công!');
            return Redirect::route('member_episode', $result->film_id);
        }else{
            Session::flash('message', 'Không phải phim của bạn!');
        }
        return Redirect::back();
    }

    function update($id, Request $request){
        $data = $request->all();
        $data['description'] = str_replace("\r\n", ' ', strip_tags($data['description']));
        $film = Film::where(['id' => $id, 'customer_id' => Session::get('user')->id])->first();
        $validator = Validator::make($data, [
            'title'   => 'required|max:255',
            'title_en' => 'required|max:255',
            "date" => 'required',
            "director" => 'required',
            "stars" => 'required',
            "online" => 'required',
            "category_id" => 'required',
            "quality_id" => 'required',
            "episodes" => 'required',
            "exist_episodes" => 'required',
            "runtime" => 'required',
            "storyline" => 'required',
            'country' => 'required',
            'genre' => 'required',
            'trailer' => 'required',
        ]);
        $isset_title = Film::where('id', '<>', $id)->where(['title' => $data['title'], 'title_en' => $data['title_en']])->first();
        if (!is_null($isset_title)) {
            Session::flash('message_error', 'Trùng cả tên tiếng việt và Anh');
            return Redirect::back()->withInput();
        }

        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $slug_isset = Film::where('id', '<>', $id)->where('slug', $data['slug'])->first();
        if (!is_null($slug_isset)) {
            $data['slug'] .= uniqid();
        }
        $server = Server::where(['type' => 'ftp', 'default' => 1])->first();
        $film->category_id    = $data['category_id'];
        $film->title          = trim($data['title']);
        $film->title_ascii    = trim(Str::ascii($data['title']));
        $film->title_en       = trim($data['title_en']);
        $film->slug           = $data['slug'];
        $film->date           = trim($data['date']);
        $film->quality_id     = $data['quality_id'];
        $film->episodes       = $data['episodes'];
        $film->exist_episodes = $data['exist_episodes'];
        $film->runtime        = $data['runtime'];
        $film->description    = $data['description'];
        $film->storyline      = strip_tags($data['storyline'], '<img>');
        $film->seo            = json_encode($data['seo']);
        $film->ftp_id         = $server->id;
        $film->calendar       = $data['calendar'];
        $film->trailer        = $data['trailer'];
        $film->customer_id    = Session::get('user')->id;
        $film->member         = 1;
        // Cover:
        if (\Request::hasFile('cover')) {
            $uploadDir = 'data/uploads/';
            $fileName = $data['cover']->getClientOriginalName();
            $extension = $data['cover']->getClientOriginalExtension();
            $allowedExtensions = array('jpeg', 'jpg', 'png', 'bmp', 'gif');
            $file_rename   = $data['slug'] . '-cover.'. uniqid().'.' . $extension;
            if(in_array($extension, $allowedExtensions)){
                $data['cover']->move($uploadDir, $fileName);
                @rename($uploadDir.$fileName, $uploadDir.$file_rename);
            }
            $path = $uploadDir.$file_rename;
            // Image crop cover
            $width = Img::make($path)->width();
            $height = Img::make($path)->height();
            $max_width = 1280;
            $max_height = 620;
            if ($width >= $max_width and $height >= $max_height) {
                if ($width/$height < $max_width/$max_height) {
                    $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }
            }else{
                if ($width/$height < $max_width/$max_height) {
                    $height_crop = round($width*$max_height/$max_width, 0);
                    $width_crop = $width;
                    $image = Img::make($path)->crop($width_crop, $height_crop)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }else{
                    $height_crop = $height;
                    $width_crop = round($height*$max_width/$max_height, 0);
                    $image = Img::make($path)->crop($width_crop, $height_crop)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }
            }
            if (file_exists($path)){
                @unlink($path);
            }
            if (file_exists($path)){
                @unlink($path);
            }
            $film->cover_name = $file_rename;
            $film->ftp_id = $server->id;
        }
        // Thumb:
        if (\Request::hasFile('thumb')) {
            $uploadDir = 'data/uploads/';
            $fileName = $data['thumb']->getClientOriginalName();
            $extension = $data['thumb']->getClientOriginalExtension();
            $allowedExtensions = array('jpeg', 'jpg', 'png', 'bmp', 'gif');
            $file_rename   = $data['slug'] . '_thumb-' . uniqid() . '.' . $extension;
            if(in_array($extension, $allowedExtensions)){
                $data['thumb']->move($uploadDir, $fileName);
                @rename($uploadDir.$fileName, $uploadDir.$file_rename);
            }
            $path = $uploadDir.$file_rename;
            // Image crop cover
            $width = Img::make($path)->width();
            $height = Img::make($path)->height();
            $max_width = 300;
            $max_height = 450;
            if ($width > $max_width and $height > $max_height) {
                if ($width/$height < $max_width/$max_height) {
                    $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }
            }else{
                if ($width/$height < $max_width/$max_width) {
                    $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                    $constraint->upsize();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                    $constraint->upsize();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
                }
            }
            if (file_exists($path)){
                @unlink($path);
            }
            $film->thumb_name = $file_rename;
            $film->ftp_id = $server->id;
        }
        $film->save();
        // Stars:
        $stars = explode(',', trim($data['stars']));
        $stars = array_map('trim', $stars);
        $star_ids = [];
        if (!empty($stars)) {
            $star_exist = Star::whereIn('title', $stars)->get();
            $star_new_ids = [];
            foreach ($stars as $key => $value) {
                if ($star_exist->where('title', $value)->isEmpty()) {
                    $star = Star::firstOrCreate([
                        'title'       => $value,
                        'slug'       => str_slug($value)
                    ]);
                    $star_new_ids[] = $star->id;
                }
            }
            $star_exist_ids = $star_exist->toArray();
            $star_exist_ids = array_column($star_exist_ids, 'id');
            $star_ids = array_merge($star_exist_ids, $star_new_ids);
        }
        if (!empty($star_ids)) {
            $film->stars()->sync($star_ids);
        }
        // Directors:
        $directors = explode(',', trim($data['director']));
        $directors = array_map('trim', $directors);
        $director_ids = [];
        if (!empty($directors)) {
            $director_exist = Director::whereIn('title', $directors)->get();
            $director_new_ids = [];
            foreach ($directors as $key => $value) {
                if ($director_exist->where('title', $value)->isEmpty()) {
                    $director = Director::firstOrCreate([
                        'title'       => $value
                    ]);
                    $director_new_ids[] = $director->id;
                }
            }
            $director_exist_ids = $director_exist->toArray();
            $director_exist_ids = array_column($director_exist_ids, 'id');
            $director_ids = array_merge($director_exist_ids, $director_new_ids);
        }
        if (!empty($director_ids)) {
            $film->directors()->sync($director_ids);
        }
        // Tags
        $tags = $data['tags'];
        $tags = explode(',', trim($tags));
        $tags = array_map('trim', $tags);
        $tags = array_map('strtolower', $tags);
        $tag_ids = [];
        if (!empty($tags)) {
            $tag_exist = Tags::whereIn('title', $tags)->get();
            $tag_new_ids = [];
            foreach ($tags as $key => $value) {
                if ($tag_exist->where('title', $value)->isEmpty()) {
                    $tag = Tags::firstOrCreate([
                        'title'       => $value,
                        'slug'        => strtolower(str_slug($value)),
                    ]);
                    $tag_new_ids[] = $tag->id;
                }
            }
            $tag_exist_ids = $tag_exist->toArray();
            $tag_exist_ids = array_column($tag_exist_ids, 'id');
            $tag_ids = array_merge($tag_exist_ids, $tag_new_ids);
        }
        if (!empty($tag_ids)) {
            $film->tags()->sync($tag_ids);
        }
        // Country:
        if(isset($data['country']) and !empty($data['country'])){
            $film->countries()->sync($data['country']);
        }
        // Genre:
        if(isset($data['genre']) and !empty($data['genre'])){
            $film->genres()->sync($data['genre']);
        }

        Session::flash('message', '<p class="alert alert-success">Đã sửa!</p>');
        return Redirect::route('member_films');
    }

    function delete($id){
        $delete = Film::where(['id' => $id, 'customer_id' => Session::get('user')->id])->delete();
        if ($delete) {
            Episode::where('film_id', $id)->delete();
            Session::flash('message', '<p class="alert alert-success">Successfully to deleted !</p>');
        }else{
            Session::flash('message', '<p class="alert alert-warning">Không có quyền xóa !</p>');
        }
        return Redirect::back();
    }

    function multi_delete(Request $request){
        $data = $request->all();
        if (isset($data['id'])) {
            # code...
            $delete = Film::whereIn('id', $data['id'])->where('customer_id', Session::get('user')->id)->delete();
            if ($delete) {
                Episode::whereIn('film_id', $data['id'])->delete();
                Session::flash('message', 'Đã xóa '.count($data['id']).' mục');
            }
        }else{
            Session::flash('message', 'Chưa chọn mục để xóa hoặc không có quyền');
        }
        return Redirect::back();
    }

    function delete_ep($id){
        $episode = Episode::where(['id' => $id, 'customer_id' => Session::get('user')->id])->first();
        $film = Film::find($episode->film_id);
        $episode->delete();
        $last = Episode::where('film_id', $film->id)->get();
        if (count($last) == 0) {
            $film->exist_episodes = NULL;
        }else{

            $film->exist_episodes = $last->sortByDesc('title')->first()->title;
            if ($film->episodes < $film->exist_episodes) {
                $film->episodes = $film->exist_episodes;
            }
        }
        $film->save();
        Session::flash('message', 'Đã xóa');
        return Redirect::back();
    }

    function multi_delete_ep($id, Request $request){
        $data = $request->all();
        if (isset($data['id'])) {
            # code...
            $delete = Episode::whereIn('id', $data['id'])->where('customer_id', Session::get('user')->id)->delete();
            if ($delete) {
                $film = Film::find($id);
                $last = Episode::where('film_id', $id)->get();
                if (count($last) == 0) {
                    $film->exist_episodes = NULL;
                }else{

                    $film->exist_episodes = $last->sortByDesc('title')->first()->title;
                    if ($film->episodes < $film->exist_episodes) {
                        $film->episodes = $film->exist_episodes;
                    }
                }
                $film->save();
                Session::flash('message', 'Đã xóa '.count($data['id']).' mục');
            }
        }else{
            Session::flash('message', 'Chưa chọn mục để xóa');
        }
        return Redirect::back();
    }

    function curl($url){
        $ch = @curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $head[] = "Connection: keep-alive";
        $head[] = "Keep-Alive: 300";
        $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $head[] = "Accept-Language: vi-vn,vi;q=0.8,en-us;q=0.5,en;q=0.3";
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Expect:'
        ));
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }
}

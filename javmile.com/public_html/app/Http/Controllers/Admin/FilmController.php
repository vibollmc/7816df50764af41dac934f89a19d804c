<?php

namespace App\Http\Controllers\admin;

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
use App\Models\Image;
use App\Models\Video;
use App\Models\Star;
use App\Models\Film_star;
use App\Models\Director;
use App\Models\Film_director;
use App\Models\Tags;
use App\Models\Film_tag;
use App\Models\Film_genre;
use App\Models\Google_drive_file;
use App\Models\Episode;
use Intervention\Image\ImageManagerStatic as Img;
use Pinger, DB, Cache;

class FilmController extends AdminController
{
    function index(Request $request){
        $result = Film::with(['image_server', 'user', 'category'])->orderBy('order', 'desc')->whereNull('member');
        if (isset($_GET['k'])) {
            $keyword = Str::ascii(trim($_GET['k']));
            $result->where('title_ascii', 'like', "%$keyword%")->orWhere('title_en', 'like', "%$keyword%");
        }
        $result = $result->paginate(50);
        $result->appends($request->all());
        return view('admin.film.index', compact('result'));
    }

    function member(Request $request){
        $result = Film::with(['image_server', 'user', 'category'])->orderBy('order', 'desc')->whereNotNull('member');
        if (isset($_GET['k'])) {
            $keyword = Str::ascii(trim($_GET['k']));
            $result->where('title_ascii', 'like', "%$keyword%")->orWhere('title_en', 'like', "%$keyword%");
        }
        $result = $result->paginate(50);
        $result->appends($request->all());
        return view('admin.film.index', compact('result'));
    }

    function create(){
        $categories = Category::where('status', 1)->get();
        $qualities = Quality::all();
        $countries = Country::where('status', 1)->get();
        $servers = Server::all();
        $genres = Genre::where('status', 1)->get();
        return view('admin.film.create', compact('categories', 'qualities', 'countries', 'genres', 'servers'));
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
            'title'   => 'required|max:255, unique:films',
            'title_en' => 'required|max:255, unique:films',
            'cover' => 'required',
            'thumb' => 'required',
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
        $film_data = [
            'category_id'    => $data['category_id'],
            'title'          => trim($data['title']),
            'title_ascii'    => trim(Str::ascii($data['title'])),
            'title_en'       => trim($data['title_en']),
            'slug'           => $data['slug'],
            'date'           => trim($data['date']),
            'order'          => time(),
            'online'         => isset($data['online'])? $data['online']: NULL,
            'hot'            => isset($data['hot'])? $data['hot']: NULL,
            'slide'          => isset($data['slide'])? $data['slide']: NULL,
            'quality_id'     => $data['quality_id'],
            'episodes'       => $data['episodes'],
            'exist_episodes' => $data['exist_episodes'],
            'runtime'        => $data['runtime'],
            'imdb_url'       => $data['imdb_url'],
            'description'    => $data['description'],
            'storyline'      => strip_tags($data['storyline'], '<img>'),
            'seo'            => json_encode($data['seo']),
            'ftp_id'         => $data['server_img'],
            'extend'         => json_encode($data['extend']),
            'calendar'       => $data['calendar'],
            'user_id'        => Session::get('admin')->id,
            'trailer'        => $data['trailer']
            ];
            $film = Film::create($film_data);
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
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }
            }else{
                if ($width/$height < $max_width/$max_height) {
                    $height_crop = round($width*$max_height/$max_width, 0);
                    $width_crop = $width;
                    $image = Img::make($path)->crop($width_crop, $height_crop)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }else{
                    $height_crop = $height;
                    $width_crop = round($height*$max_width/$max_height, 0);
                    $image = Img::make($path)->crop($width_crop, $height_crop)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }
            }
            if (file_exists($path)){
                @unlink($path);
            }
            if (file_exists($path)){
                @unlink($path);
            }
            $film->cover_name = $file_rename;
            $film->ftp_id = $data['server_img'];
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
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }
            }else{
                if ($width/$height < $max_width/$max_width) {
                    $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                    $constraint->upsize();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                    $constraint->upsize();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }
            }
            if (file_exists($path)){
                @unlink($path);
            }
            $film->thumb_name = $file_rename;
            $film->ftp_id = $data['server_img'];
        }elseif (isset($data['auto_thumb'])) {
            $url = $data['auto_thumb'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            $result = curl_exec($ch);
            curl_close($ch);

            // Lưu file ảnh
            $fullpath = basename($url);
            if (file_exists($fullpath)) {
                unlink($fullpath);
            }
            $fp = fopen($fullpath, 'x');
            fwrite($fp, $result);
            fclose($fp);
            if (file_exists($fullpath)) {
                $path = $fullpath;
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
                        // $image->crop($max_width, $max_height)->save($path);
                        $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                    }else{
                        $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($path);
                        // $image->crop($max_width, $max_height)->save($path);
                        $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                    }
                }else{
                    if ($width/$height < $max_width/$max_width) {
                        $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($path);
                        // $image->crop($max_width, $max_height)->save($path);
                        $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                    }else{
                        $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($path);
                        // $image->crop($max_width, $max_height)->save($path);
                        $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                    }
                }
                if (file_exists($path)){
                    @unlink($path);
                }
                if (file_exists($path)){
                    @unlink($path);
                }
                $film->thumb_name = $fullpath;
                $film->ftp_id = $data['server_img'];
            }
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

        // return Redirect::route('auto_add');

        if(isset($data['btn_save'])){
            Session::flash('message', '<p class="alert alert-success">Added has successfull!</p>');
            return Redirect::route('edit_film', $film->id);
        }
        // Cache::flush();
        Session::flash('message', '<p class="alert alert-success">Thêm mới phim thành công!</p>');
        return Redirect::route('admin_film');
    }

    function auto_add(){
        $auto_data = Cache::get('data_auto');
        if (count($auto_data) > 0) {
            $current = current($auto_data);
            unset($auto_data[array_search($current, $auto_data)]);
            Cache::forever('data_auto', $auto_data);

            $data['url'] = $current;
            if (strpos($data['url'], 'kenhhd.tv')) {
                $respons = $this->get_kenhhd($data['url']);
            }elseif (strpos($data['url'], 'phimhai.net')) {
                $respons = $this->get_phimhai($data['url']);
            }

            $categories = Category::where('status', 1)->get();
            $qualities = Quality::all();
            $countries = Country::where('status', 1)->get();
            $servers = Server::all();
            $genres = Genre::where('status', 1)->get();
            $url = $data['url'];
            return view('admin.film.create-auto', compact('categories', 'qualities', 'countries', 'genres', 'servers', 'respons', 'url'));
        }else{
            dd('end');
        }
    }

    function add_ep($id){
        $result = Film::find($id);
        return view('admin.film.add-ep', compact('result'));
    }

    function store_ep($id, Request $request){
        $data = $request->all();
        $user = Session::get('admin');
        $film = Film::find($id);
        Session::flash('message', 'Thêm mới thành công');
        if(isset($data['add_fast'])){
            if (count($data['fast']['file_name']) > 0) {
                foreach ($data['fast']['file_name'] as $key => $item) {
                    $episodes[$key] = [
                        'film_id' => $id,
                        'user_id' => $user->id,
                        'title' => $data['fast']['title'][$key],
                        'file_name' => $data['fast']['file_name'][$key],
                        'type' => $data['fast']['type'][$key],
                    ];
                }
                $collect = collect($episodes);
                $array_ep = $collect->sortBy('title')->toArray();
                Episode::insert($array_ep);
            }
        }elseif(isset($data['add_multi'])){
            if (count($data['multi']['file_name']) > 0) {
                foreach ($data['multi']['file_name'] as $key => $item) {
                    $episodes[$key] = [
                        'film_id' => $id,
                        'user_id' => $user->id,
                        'title' => $data['multi']['title'][$key],
                        'file_name' => $data['multi']['file_name'][$key],
                        'type' => $data['multi']['type'][$key],
                    ];
                }
                $collect = collect($episodes);
                $array_ep = $collect->sortBy('title')->toArray();
                Episode::insert($array_ep);
            }
        }elseif (isset($data['add_single'])) {
            $episode = Episode::create([
                    'film_id' => $id,
                    'user_id' => $user->id,
                    'title' => $data['single']['title'],
                    'file_name' => $data['single']['file_name'],
                    'type' => $data['single']['type'],
                ]);
            if (\Request::hasFile('sub_vi')) {
                $uploadDir = 'data/uploads/';
                $fileName = $data['sub_vi']->getClientOriginalName();
                $extension = $data['sub_vi']->getClientOriginalExtension();
                $allowedExtensions = array('srt', 'vtt');

                $file_rename   = $film->slug . '-ep-'.str_slug($episode->title).'.' . $extension;
                if(in_array($extension, $allowedExtensions)){
                    $data['cover']->move($uploadDir, $fileName);
                    @rename($uploadDir.$fileName, $uploadDir.$file_rename);
                    $path = $uploadDir.$file_rename;
                    $ftp = Server::where('title', 'Subtitle')->first();
                    $sub_vi = Server::uploadFtp($path, null, $ftp->id);
                    if (file_exists($path)){
                        @unlink($path);
                    }
                    $episode->sub_vi = $sub_vi;
                    $episode->ftp_id = $ftp->id;
                    $episode->save();
                }
            }
        }else{
            Session::flash('message', 'Thêm mới thất bại');
            return Redirect::back();
        }
        $last = Episode::where('film_id', $id)->get();
        if (count($last) == 0) {
            $film->exist_episodes = NULL;
        }else{
            $film->exist_episodes = $last->sortByDesc('title')->first()->title;
            if ($film->episodes < $film->exist_episodes) {
                $film->episodes = $film->exist_episodes;
            }
        }
        $film->order = time();
        $film->online = 1;
        $film->save();
        return Redirect::route('episode', $id);
    }

    function film_ep($id){
        $film = Film::find($id);
        $result = Episode::where('film_id', $id)->orderBy('title', 'desc')->get();
        return view('admin.film.film-ep', compact('result', 'film'));
    }

    function edit_ep($id){
        $result = Episode::where('id', $id)->with('film')->first();
        return view('admin.film.edit-ep', compact('result'));
    }

    function update_ep($id, Request $request){
        $data = $request->all();
        $result = Episode::where('id', $id)->with('film')->first();
        $result->title = $data['title'];
        $result->file_name = $data['file_name'];
        $result->type = $data['type'];
        $user = Session::get('admin');
        $result->user_id = $user->id;
        if (\Request::hasFile('sub_vi') and !isset($data['remove_sub'])) {
            $uploadDir = 'data/uploads/';
            $fileName = $data['sub_vi']->getClientOriginalName();
            $extension = $data['sub_vi']->getClientOriginalExtension();
            $allowedExtensions = array('srt', 'vtt');

            $file_rename   = $result->film->slug . '-ep-'.str_slug($result->title).'.' . $extension;
            if(in_array($extension, $allowedExtensions)){
                $data['sub_vi']->move($uploadDir, $fileName);
                @rename($uploadDir.$fileName, $uploadDir.$file_rename);
                $path = $uploadDir.$file_rename;
                $ftp = Server::where('title', 'Subtitle')->first();
                $sub_vi = Server::uploadFtp($path, null, $ftp->id);
                if (file_exists($path)){
                    @unlink($path);
                }
                $result->sub_vi = $sub_vi;
                $result->ftp_id = $ftp->id;
            }
        }
        if (isset($data['remove_sub'])) {
            $result->sub_vi = Null;
        }
        $result->save();
        $film = Film::find($result->film_id);
        $last = Episode::where('film_id', $result->film_id)->get();
        if (count($last) == 0) {
            $film->exist_episodes = NULL;
        }else{

            $film->exist_episodes = $last->sortByDesc('title')->first()->title;
            if ($film->episodes < $film->exist_episodes) {
                $film->episodes = $film->exist_episodes;
            }
        }
        $film->order = time();
        $film->online = 1;
        $film->save();
        Session::flash('message', 'Đã cập nhật');
        return Redirect::route('episode', $result->film_id);
    }

    function edit($id){
        $categories = Category::where('status', 1)->get();
        $qualities = Quality::all();
        $countries = Country::where('status', 1)->get();
        $servers = Server::all();
        $genres = Genre::where('status', 1)->get();
        $result = Film::where('id', $id)->with(['image_server', 'stars', 'directors', 'tags', 'countries', 'genres'])->first();
        return view('admin.film.edit', compact('result', 'categories', 'qualities', 'countries', 'genres', 'servers'));
    }

    function update($id, Request $request){
        $data = $request->all();
        $data['description'] = str_replace("\r\n", ' ', strip_tags($data['description']));
        $film = Film::where('id', $id)->first();
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
            $data['slug'] .= '-'.time().strtolower(str_random(5));
        }
        $film->category_id = $data['category_id'];
        $film->title       = trim($data['title']);
        $film->title_ascii = Str::ascii(trim($data['title']));
        $film->title_en    = trim($data['title_en']);
        $film->slug        = $data['slug'];
        $film->date        = trim($data['date']);
        if (isset($data['save_up'])) {
            $film->order       = time();
        }
        $film->online         = isset($data['online'])? $data['online']: NULL;
        $film->hot            = isset($data['hot'])? $data['hot']: NULL;
        $film->slide          = isset($data['slide'])? $data['slide']: NULL;
        $film->quality_id     = $data['quality_id'];
        $film->episodes       = $data['episodes'];
        $film->exist_episodes = $data['exist_episodes'];
        $film->runtime        = $data['runtime'];
        $film->imdb_url       = $data['imdb_url'];
        $film->description    = $data['description'];
        $film->storyline      = strip_tags($data['storyline'], '<img>');
        $film->seo            = json_encode($data['seo']);
        $film->extend         = json_encode($data['extend']);
        $film->calendar       = $data['calendar'];
        $film->trailer        = $data['trailer'];
        $film->member         = $data['member'] == 1? 1: NUll;
        $film->updated_by     = Session::get('admin')->id;
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
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }
            }else{
                if ($width/$height < $max_width/$max_height) {
                    $height_crop = round($width*$max_height/$max_width, 0);
                    $width_crop = $width;
                    $image = Img::make($path)->crop($width_crop, $height_crop)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }else{
                    $height_crop = $height;
                    $width_crop = round($height*$max_width/$max_height, 0);
                    $image = Img::make($path)->crop($width_crop, $height_crop)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }
            }
            if (file_exists($path)){
                @unlink($path);
            }
            if (file_exists($path)){
                @unlink($path);
            }
            $film->cover_name = $file_rename;
            $film->ftp_id = $data['server_img'];
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
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }
            }else{
                if ($width/$height < $max_width/$max_width) {
                    $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                    $constraint->upsize();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                    $constraint->upsize();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $data['server_img']);
                }
            }
            if (file_exists($path)){
                @unlink($path);
            }
            $film->thumb_name = $file_rename;
            $film->ftp_id = $data['server_img'];
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
        return Redirect::route('admin_film');
    }

    function genres(){
        $result = Genre::paginate(30);
        return view('admin.genres.index', compact('result'));
    }

    function create_genres(){
        // $genres = Genre::whereNull('parent_id')->get();
        return view('admin.genres.create');
    }

    function post_create_genres(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'slug' => 'required|max:255',
        ]);
        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $genre = Genre::create([
            'title'       => $data['title'],
            'title_ascii' => Str::ascii($data['title']),
            'slug'        => $data['slug'],
            'status'      => isset($data['status'])? $data['status']: NULL,
            'menu'      => isset($data['menu'])? $data['menu']: NULL,
            'seo'         => json_encode($data['seo']),
            'description' => $data['description']
            ]);
        Session::flash('message', '<p class="alert alert-success">Added has successfull!</p>');
        return Redirect::route('admin_film_genres');
    }

    function edit_genres($id){
        $result = Genre::find($id);
        return view('admin.genres.edit', compact('result'));
    }

    function update_genres($id, Request $request){
        $genre = Genre::find($id);
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'slug' => 'required|max:255',
        ]);
        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $genre->title       = $data['title'];
        $genre->title_ascii = Str::ascii($data['title']);
        $genre->slug = $data['slug'];
        $genre->status = isset($data['status'])? $data['status']: NULL;
        $genre->seo = json_encode($data['seo']);
        $genre->description = $data['description'];
        $genre->menu = isset($data['menu'])? $data['menu']: NULL;
        $genre->save();
        Session::flash('message', '<p class="alert alert-success">Successfully updated !</p>');
        return Redirect::route('admin_film_genres');
    }

    function change_genres($id){
        $genre= Genre::find($id);
        if ($genre->menu == 1) {
            $genre->menu = NULL;
        }else{
            $genre->menu = 1;
        }
        $genre->save();
        return Redirect::back();
    }

    function delete_genres($id){
        $genres= Genre::find($id);
        $genres->delete();
        Session::flash('message', '<p class="alert alert-success">Successfully to deleted !</p>');
        return Redirect::back();
    }
    function delete($id){
        $admin = Session::get('admin');
        $film= Film::where('id', $id);
        if ($admin->group_id != 1) {
            $film->where('user_id', $admin->id);
        }
        $delete = $film->delete();
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
            $admin = Session::get('admin');
            if ($admin->group_id == 1) {
                $delete = Film::whereIn('id', $data['id'])->delete();
            }else{
                $delete = Film::whereIn('id', $data['id'])->where('user_id', $admin->id)->delete();
            }
            if ($delete) {
                Episode::whereIn('film_id', $data['id'])->delete();
                Session::flash('message', 'Đã xóa '.count($data['id']).' mục');
            }
        }else{
            Session::flash('message', 'Chưa chọn mục để xóa');
        }
        return Redirect::back();
    }

    function delete_ep($id){
        $admin = Session::get('admin');
        if ($admin->group_id == 1) {
            $episode = Episode::where('id', $id)->first();
        }else{
            $episode = Episode::where('id', $id)->where('user_id', $admin->id)->first();
        }
        if (!is_null($episode)) {
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
        }
        return Redirect::back();
    }

    function multi_delete_ep($id, Request $request){
        $data = $request->all();
        if (isset($data['id'])) {
            $admin = Session::get('admin');
            if ($admin->group_id == 1) {
                $delete = Episode::whereIn('id', $data['id'])->delete();
            }else{
                $delete = Episode::whereIn('id', $data['id'])->where('user_id', $admin->id)->delete();
            }
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

    function get_info_auto(Request $request){
        $data = $request->all();
        if (strpos($data['url'], 'kenhhd.tv')) {
            $respons = $this->get_kenhhd($data['url']);
        }elseif (strpos($data['url'], 'phimhai.net')) {
            $respons = $this->get_phimhai($data['url']);
        }

        $categories = Category::where('status', 1)->get();
        $qualities = Quality::all();
        $countries = Country::where('status', 1)->get();
        $servers = Server::all();
        $genres = Genre::where('status', 1)->get();
        $url = $data['url'];
        return view('admin.film.create-auto', compact('categories', 'qualities', 'countries', 'genres', 'servers', 'respons', 'url'));
    }

    function get_phimhai($url){
        $get = $this->curl($url);
        // title
        $cat = explode('<div class="movie-detail-info">', $get);
        $cat = explode('<div class="detail-info">', $cat[1]);
        $title = trim(strip_tags(str_replace("\r\n", '', $cat[0])));
        $title_dot = explode(':', $title);
        if (count($title_dot) > 1) {
            if (count($title_dot) == 2) {
                $respons['title'] = trim($title_dot[0]);
                $respons['title_en'] = trim($title_dot[1]);
            }elseif (count($title_dot) == 4) {
                $respons['title'] = trim($title_dot[0]).': '.trim($title_dot[1]);
                $respons['title_en'] = trim($title_dot[2]).': '.trim($title_dot[3]);
            }
        }else{
            $title = explode('-', $title);
            if (count($title) == 2) {
                $respons['title'] = trim($title[0]);
                $respons['title_en'] = trim($title[1]);
            }elseif (count($title) == 4) {
                $respons['title'] = trim($title[0]).': '.trim($title[1]);
                $respons['title_en'] = trim($title[2]).': '.trim($title[3]);
            }
            elseif (count($title) == 3) {
                $respons['title'] = trim($title[0]);
                $respons['title_en'] = trim($title[1]);
            }
        }
        if (!isset($respons['title'])) {
            $respons['title'] = $respons['title_en'] = $title[0];
        }
        // Thumb
        $cat = explode('<div class="time-release">', $cat[1]);

        $title = explode('<img src="', $cat[0]);
        $title = explode('"', $title[1]);
        $respons['thumb'] = $title[0];
        // Date
        $cat = explode('<div class="imdb">', $cat[1]);
        $respons['date'] = substr(trim(strip_tags($cat[0])), -4);
        // Episode
        if (strpos($cat[1], 'Số tập')) {
            $cat = explode('Thời lượng', $cat[1]);
            $title = explode('Số tập', $cat[0]);
            $title = trim(strip_tags($title[1]));
            $episode = explode("/", $title);
            $respons['episodes'] = $episode[1];
            $respons['exist_episodes'] = $episode[0];
        }
        // Runtime
        $cat = explode('Đạo diễn', $cat[1]);
        $title = explode('</a>', $cat[0]);
        $respons['runtime'] = trim(strip_tags($title[0]));
        // Director
        $cat = explode('Diễn viên', $cat[1]);
        $title = trim(strip_tags($cat[0]));
        $title = explode(',', $title);
        foreach ($title as $item) {
            $director[] = trim($item);
        }
        $respons['director'] = $director;
        // Actor
        $cat = explode('Quốc gia', $cat[1]);
        $title = trim(strip_tags($cat[0]));
        $title = explode(',', $title);
        foreach ($title as $item) {
            $actor[] = trim($item);
        }
        $respons['actor'] = $actor;
        // Country
        $cat = explode('Thể loại', $cat[1]);
        $title = trim(strip_tags($cat[0]));
        $title = explode(',', $title);
        foreach ($title as $item) {
            $country[] = trim($item);
        }
        $respons['country'] = $country;
        // Genre
        $cat = explode('Chất lượng', $cat[1]);
        $title = trim(strip_tags($cat[0]));
        $title = explode(',', $title);
        foreach ($title as $item) {
            $genre[] = trim($item);
        }
        $respons['genre'] = $genre;
        //Content
        $cat = explode('<div class="movie-detail-des">', $cat[1]);
        $cat = explode('<div class="movie-detail-tag">', $cat[1]);
        $respons['content'] = trim(strip_tags($cat[0]));

        return $respons;
    }

    function get_kenhhd($url){
        $get = $this->curl($url);
        // title
        $cat = explode('<h3 class="no-margin">', $get);
        $cat = explode('</h3>', $cat[1]);
        $title = trim(strip_tags(str_replace("\r\n", '', $cat[0])));
        $title_dot = explode(':', $title);
        if (count($title_dot) > 1) {
            if (count($title_dot) == 2) {
                $respons['title'] = trim($title_dot[0]);
                $respons['title_en'] = trim($title_dot[1]);
            }elseif (count($title_dot) == 4) {
                $respons['title'] = trim($title_dot[0]).': '.trim($title_dot[1]);
                $respons['title_en'] = trim($title_dot[2]).': '.trim($title_dot[3]);
            }
        }else{
            $title = explode('-', $title);
            if (count($title) == 2) {
                $respons['title'] = trim($title[0]);
                $respons['title_en'] = trim($title[1]);
            }elseif (count($title) == 4) {
                $respons['title'] = trim($title[0]).': '.trim($title[1]);
                $respons['title_en'] = trim($title[2]).': '.trim($title[3]);
            }elseif (count($title) == 3) {
                $respons['title'] = trim($title[0]);
                $respons['title_en'] = trim($title[1]);
            }
        }
        if (!isset($respons['title'])) {
            $respons['title'] = $respons['title_en'] = $title[0];
        }
        // Thumb
        $cat = explode('<div class="col-xs-12 col-sm-7 col-md-5">', $cat[1]);
        $title = explode('data-original="', $cat[0]);
        $title = explode('"', $title[1]);
        $respons['thumb'] = $title[0];
        //Content
        $cat = explode('<div class="tag_list"', $cat[1]);
        $respons['content'] = trim(strip_tags($cat[0]));
        // Date
        $cat = explode('table-movie-information">', $cat[1]);
        $cat = explode('</table>', $cat[1]);
        $cat = explode('</tr>', $cat[0]);
        foreach ($cat as $key => $value) {
            // Date
            if (strpos($value, 'Phát hành')) {
                $respons['date'] = substr(trim(strip_tags(str_replace('Phát hành', '', $value))), -4);
            }
            if (strpos($value, 'Điểm IMDb')) {
                $title = explode('href="', $value);
                $title = explode('"', $title[1]);
                $respons['imdb_url'] = $title[0];
            }
            if (strpos($value, 'Số tập')) {
                $episode = trim(strip_tags(str_replace('Số tập', '', $value)));
                $episode = explode("/", $episode);
                $respons['episodes'] = $episode[1];
                $respons['exist_episodes'] = $episode[0];
            }
            if (strpos($value, 'Thời lượng')) {
                $respons['runtime'] = trim(strip_tags(str_replace('Thời lượng', '', $value)));
            }
            if (strpos($value, 'Diễn viên')) {
                $title = trim(strip_tags(str_replace('Diễn viên', '', $value)));
                $title = explode(',&nbsp;', $title);
                foreach ($title as $item) {
                    $actor[] = trim($item);
                }
                $respons['actor'] = $actor;
            }
            if (strpos($value, 'Đạo diễn')) {
                $title = trim(strip_tags(str_replace('Đạo diễn', '', $value)));
                $title = explode(',&nbsp;', $title);
                foreach ($title as $item) {
                    $director[] = trim($item);
                }
                $respons['director'] = $director;
            }
            if (strpos($value, 'Quốc gia')) {
                $title = trim(strip_tags(str_replace('Quốc gia', '', $value)));
                $title = explode(',&nbsp;', $title);
                foreach ($title as $item) {
                    $country[] = trim($item);
                }
                $respons['country'] = $country;
            }
            if (strpos($value, 'Thể loại')) {
                $title = trim(strip_tags(str_replace('Thể loại', '', $value)));
                $title = explode(',&nbsp;', $title);
                foreach ($title as $item) {
                    $genre[] = trim($item);
                }
                $respons['genre'] = $genre;
            }
        }

        return $respons;

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

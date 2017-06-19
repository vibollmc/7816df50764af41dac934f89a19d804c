<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use App\Http\Controllers\AdminController;
use Validator, Session, Redirect;
use App\Models\Article;
use App\Models\Image;
use App\Models\Server;
use App\Models\Tags;
use Intervention\Image\ImageManagerStatic as Img;
use Pinger;

class ArticleController extends AdminController
{
    public function index()
    {
        if (isset($_GET['k']) and !is_null($_GET['k']) AND ($_GET['k'] != '')) {
            $keyword = $_GET['k'];
            if (!is_null($keyword) AND ($keyword != '')) {
                $result =  $result = Article::with(['cover'])->where('title', 'like', "%$keyword%")->orderBy('created_at', 'desc')->paginate(20);
            }
        }else{
            $result = Article::with(['cover'])->orderBy('created_at', 'desc')->paginate(20);
        }

        return view('admin.article.index', compact('result'));
    }

    function create(){
        $servers = Server::all();
        return view('admin.article.create', compact('servers'));
    }

    function store(Request $request){
        $data  = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|max:255|min:3|unique:articles',
            'slug' => 'required|max:255'
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors()->all();
            return redirect()->back()->withInput()->withErrors($validator);
        }else{
            $data['title_ascii'] = Str::ascii($data['title']);
            $slug_unique         = Validator::make($data, ['slug' => 'unique:articles,slug']);
            if ($slug_unique->fails()) {
                $data['slug'] .= '-'.time().strtolower(str_random(5));
            }
            $article = Article::create([
                'title'       => $data['title'],
                'title_ascii' => $data['title_ascii'],
                'slug'        => $data['slug'],
                'status'      => isset($data['status'])? $data['status']: NULL,
                'online'      => isset($data['online'])? $data['online']: NULL,
                'type'        => $data['type'],
                'content'     => $data['content'],
                'description' => $data['seo']['description'],
                'seo'         => json_encode($data['seo'])
                ]);
            // Cover:
            if (\Request::hasFile('cover')) {
                $uploadDir = 'data/uploads/';
                $fileName = $data['cover']->getClientOriginalName();
                $extension = $data['cover']->getClientOriginalExtension();
                $allowedExtensions = array('jpeg', 'jpg', 'png', 'bmp', 'gif');
                $file_rename   = $data['slug'] . '_cover-' . uniqid() . '.' . $extension;
                if(in_array($extension, $allowedExtensions)){
                    $data['cover']->move($uploadDir, $fileName);
                    @rename($uploadDir.$fileName, $uploadDir.$file_rename);
                }
                $thumb = $uploadDir.$file_rename;
                // Image crop cover
                $width = img::make($thumb)->width();
                $height = img::make($thumb)->height();
                $max_width = 600;
                $max_height = 337;
                if ($width > $max_width or $height > $max_height) {
                    if ($width/$height < $max_width/$max_height) {
                        $image = img::make($thumb)->resize($max_width, NULL, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($max_width, $max_height)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $data['server']);
                    }else{
                        $image = img::make($thumb)->resize(NULL, $max_height, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($max_width, $max_height)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $data['server']);
                    }
                }else{
                    if ($width/$height < 600/337) {
                        $image = img::make($thumb)->resize($max_width, NULL, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($width, $width)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $data['server']);
                    }else{
                        $image = img::make($thumb)->resize(NULL, $max_height, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($height, $height)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $data['server']);
                    }
                }
                @unlink($thumb);
                $cover = Image::create([
                 'id_article' => $article->id,
                 'type'       => 'cover',
                 'server_id'  => $data['server'],
                 'filename'   => $file_rename,
                 'link'       => $image_thumb
                ]);
            }
            // Tags
            $tags = \Request::input('tags');
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
                            'title_ascii' => Str::ascii($value),
                            'slug'        => str_slug($value)
                        ]);
                        $tag_new_ids[] = $tag->id;
                    }
                }
                $tag_exist_ids = $tag_exist->toArray();
                $tag_exist_ids = array_column($tag_exist_ids, 'id');
                $tag_ids = array_merge($tag_exist_ids, $tag_new_ids);
            }
            if (!empty($tag_ids)) {
                $article->tags()->sync($tag_ids);
            }
            Pinger::pingPingOMatic($article->title,route('show_article', $article->slug));
            Session::flash('message', '<p class="alert alert-success">Added!</p>');
            if (isset($data['btn_save'])) return Redirect::route('edit_article', $article->id);
            if (isset($data['btn_save_exit'])) return Redirect::route('admin_article');
        }
    }

    function edit($id){
        $servers = Server::all();
        $result = Article::where('id', $id)->with(['cover', 'images'])->first();
        return view('admin.article.edit', compact('servers', 'result'));
    }

    function update($id, Request $request){
        $data  = $request->all();
        $article = Article::where('id', $id)->first();
        $validator = Validator::make($data, [
            'title' => 'required|max:255|min:3',
            'slug' => 'required|max:255'
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors()->all();
            return redirect()->back()->withInput()->withErrors($validator);
        }else{
            $article_unique = Article::where('id', '<>', $id)->where('title', $data['title'])->first();
            if (!is_null($article_unique)) {
                Session::flash('title_unique', '<span style="color:red;"> Uniqid</span>');
                return redirect()->back()->withInput()->with(['title_unique' => '<span style="color:red;"> Uniqid</span>']);
            }
            $data['title_ascii'] = Str::ascii($data['title']);
            $data['status'] = $data['status'] != 1? 0: $data['status'];
            $slug_unique = Article::where('id','<>',$id)->where('slug', $data['slug'])->get();
            if (count($slug_unique) > 0) {
                $data['slug'] .= '-'.strtolower(str_random(10));
            }
            $article->title       = $data['title'];
            $article->title_ascii = $data['title_ascii'];
            $article->slug        = $data['slug'];
            $article->status      = $data['status'];
            $article->seo         = json_encode($data['seo']);
            $article->description = $data['seo']['description'];
            $article->content     = $data['content'];
            $article->status      = isset($data['status'])? $data['status']: NULL;
            $article->online      = isset($data['online'])? $data['online']: NULL;
            $article->type        = $data['type'];
            $article->save();
            // Cover:
            if (\Request::hasFile('cover')) {
                $uploadDir = 'data/uploads/';
                $fileName = $data['cover']->getClientOriginalName();
                $extension = $data['cover']->getClientOriginalExtension();
                $allowedExtensions = array('jpeg', 'jpg', 'png', 'bmp', 'gif');
                $file_rename   = $data['slug'] . '_cover-' . uniqid() . '.' . $extension;
                if(in_array($extension, $allowedExtensions)){
                    $data['cover']->move($uploadDir, $fileName);
                    @rename($uploadDir.$fileName, $uploadDir.$file_rename);
                }
                $thumb = $uploadDir.$file_rename;
                // Image crop cover
                $width = img::make($thumb)->width();
                $height = img::make($thumb)->height();
                $max_width = 600;
                $max_height = 337;
                if ($width > $max_width or $height > $max_height) {
                    if ($width/$height < $max_width/$max_height) {
                        $image = img::make($thumb)->resize($max_width, NULL, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($max_width, $max_height)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $data['server']);
                    }else{
                        $image = img::make($thumb)->resize(NULL, $max_height, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($max_width, $max_height)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $data['server']);
                    }
                }else{
                    if ($width/$height < 600/337) {
                        $image = img::make($thumb)->resize($max_width, NULL, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($width, $width)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $data['server']);
                    }else{
                        $image = img::make($thumb)->resize(NULL, $max_height, function ($constraint) {
                                                                        $constraint->aspectRatio();
                                                                    })->save($thumb);
                        $image->crop($height, $height)->save($thumb);
                        $image_thumb = Server::uploadFtp($thumb, null, $data['server']);
                    }
                }
                @unlink($thumb);
                $cover = Image::where(['id_article' => $article->id, 'type' => 'cover'])->first();
                if(is_null($cover)){
                    $cover = Image::create([
                     'id_article'   => $article->id,
                     'type'      => 'cover',
                     'server_id' => $data['server'],
                     'filename'  => $file_rename,
                     'link'      => $image_thumb
                    ]);
                }else{
                    $cover->filename = $file_rename;
                    $cover->link = $image_thumb;
                    $cover->save();
                }
            }
            // Tags
            $tags = \Request::input('tags');
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
                            'title_ascii' => strtolower(Str::ascii($value)),
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
                $article->tags()->sync($tag_ids);
            }
            Pinger::pingPingOMatic($article->title,route('show_article', $article->slug));
            Session::flash('message', '<p class="alert alert-success">Updated!</p>');
            if (isset($data['btn_save'])) return Redirect::route('admin_article');
        }
    }

    function upload(){
        try {
            $server   = \Request::input('server');
            $id_item   = \Request::input('id');
            $alias   = \Request::input('alias');
            $type   = \Request::input('type');

            //Load library for upload, image
            require_once(app_path() . '/Libraries/fileuploader.php');

            //Create & get path uploader dir
            $upload_path = 'data/uploads/';

            if (!is_dir($upload_path)) {
                @mkdir($upload_path, 0755, true);
            }

            //Check writeable
            if (!is_writable($upload_path)){
                die(htmlspecialchars(json_encode(array('success' => false, 'msg' => 'The directory cannot be writeable. Please check this !')), ENT_NOQUOTES));
            }

            // list of valid extensions, ex. array("jpeg", "xml", "bmp")
            $allowedExtensions = array('jpeg', 'jpg', 'png', 'bmp', 'gif');

            // max file size in bytes
            $sizeLimit = 5 * 1024 * 1024;

            //Create a instance uploader
            $uploader = new \qqFileUploader\qqFileUploader($allowedExtensions, $sizeLimit);

            //Upload file
            $result = $uploader->handleUpload($upload_path);
            if (isset($result['error'])) {
                die(htmlspecialchars(json_encode(array('success' => false, 'msg' => $result['error'])), ENT_NOQUOTES));
            }else{
                $result['status'] = true;
            }
            //File uploaded name
            $file_uploaded = $result['filename'];
            $file_rename   = $alias . '_' . uniqid() . '.' . $result['ext'];
            $file_cover   = $alias . '-thumb' . uniqid() . '.' . $result['ext'];
            //Rename file after upload
            if (file_exists($file_rename)){
                @fclose($file_rename);
                @unlink($file_rename);
            }
            @rename($upload_path.$file_uploaded, $upload_path.$file_rename);

            $path = $upload_path.$file_rename;
            $image_link = Server::uploadFtp($path, null, $server);
            if ($image_link !== false) {
                @unlink($path);
                // Insert to image tables
                $image = Image::create([
                    'id_article'   => intval($id_item),
                    'type'      => $type,
                    'server_id' => $server,
                    'filename'  => $file_rename,
                    'link'      => $image_link,
                ]);

                $result['status'] = true;
                $result['link']   = $image_link;
                $result['id']     = $image->id;
            } else {
                $result['status'] = false;
            }
            echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        } catch (Exception $e){
            echo json_encode(array(
                'status' => false,
                'msg'    => $e->getMessage()
            ));
        }
    }

    function delete_image(){
        $id = \Request::input('id');
        $image = Image::find($id);
        if (is_null($image)) {
            return ['status' => false, 'msg' => 'image not found'];
        } else {
            $result = $image->delete();
            return ['status' => true];
        }
    }

    function change_status(){
       try {
            $id = \Request::input('id');
            $article = Article::where('id', $id)->first();
            if($article->status == 1){
                $article->status = 0;
            }else{
                $article->status = 1;
            }
            $article->save();
            echo htmlspecialchars(json_encode(['status' => true, 'value' => $article->status]), ENT_NOQUOTES);
        } catch (Exception $e){
            echo json_encode(array(
                'status' => false,
                'msg'    => $e->getMessage()
            ));
        }
    }

    function delete($id){
        $article = Article::find($id);
        $article->delete();
        Session::flash('message', '<p class="alert alert-success">Đã xóa!</p>');
        return Redirect::back();
    }

}

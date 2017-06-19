<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AdminController;
use App\Models\Star;
use App\Models\Server;
use App\Models\Image;
use Validator, Session, Redirect, Cache, DB;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Img;

class ActorController extends AdminController {
    public function index() {
        if(isset($_GET['k'])){
            $key = $_GET['k'];
            $result = Star::where('title', 'like', "%$key%")->with('image_server')->orderBy('hot', 'desc')->paginate(20);
        }else{
            $result = Star::with('image_server')->orderBy('hot', 'desc')->paginate(20);
        }
        return view('admin.actor.index', compact('result'));
    }

    function edit($id){
        $result = Star::where('id', $id)->with('image_server')->first();
        $servers = Server::where('type', 'ftp')->get();
        return view('admin.actor.edit', compact('result', 'servers'));
    }

    function update($id, Request $request){
        $data = $request->all();
        $title = Star::where('id', '<>', $id)->where('title', $data['title'])->first();
        $slug = Star::where('id', '<>', $id)->where('slug', $data['slug'])->first();
        if (!is_null($title)) {
            Session::flash('title_err', 'This name already exists.');
        }
        if (!is_null($slug)) {
            Session::flash('slug_err', 'This slug already exists.');
        }
        if (!is_null($slug) or !is_null($title)) {
            return Redirect::back()->withInput();
        }
        if (\Request::hasFile('file')) {
            $avatar = $data['file'];
            unset($data['file']);
        }
        $server = $data['server_img'];
        unset($data['_token'], $data['server_img']);
        $result = Star::find($id);
        $data['seo'] = json_encode($data['seo']);
        $data['hot'] = isset($data['hot'])? $data['hot']: NULL;
        foreach ($data as $key => $value) {
            $result->$key = $value;
        }
        $result->slug = $data['slug'];

        if (isset($avatar)) {
            $uploadDir = 'data/uploads/';
            $fileName = $avatar->getClientOriginalName();
            $extension = $avatar->getClientOriginalExtension();
            $allowedExtensions = array('jpeg', 'jpg', 'png', 'bmp', 'gif');
            $file_rename   = $result->slug.'-avatar.'. $extension;
            if(in_array($extension, $allowedExtensions)){
                $avatar->move($uploadDir, $fileName);
                @rename($uploadDir.$fileName, $uploadDir.$file_rename);
            }else{
                Session::flash('message', '<p class="alert alert-warning">Avatar file not support that file type</p>');
                return Redirect::back()->withInput();
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
                    $image_thumb = Server::uploadFtp($path, null, $server);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server);
                }
            }else{
                if ($width/$height < $max_width/$max_width) {
                    $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                    $constraint->upsize();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server);
                }else{
                    $image = Img::make($path)->resize(NULL, $max_height, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                    $constraint->upsize();
                                                                })->save($path);
                    $image->crop($max_width, $max_height)->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server);
                }
            }
            if (file_exists($path)){
                @unlink($path);
            }
            $result->thumb_name = $file_rename;
            $result->ftp_id = $server;
        }
        $result->save();
        Session::flash('message', '<p class="alert alert-success">Update has successfull!</p>');
        return Redirect::route('admin_actor');
    }

    function status(){
       try {
            $id = \Request::input('id');
            $result = Star::where('id', $id)->first();
            if($result->hot == 1){
                $result->hot = NULL;
            }else{
                $result->hot = 1;
            }
            $result->save();
            echo htmlspecialchars(json_encode(['status' => true, 'value' => $result->hot]), ENT_NOQUOTES);
        } catch (Exception $e){
            echo json_encode(array(
                'status' => false,
                'msg'    => $e->getMessage()
            ));
        }
    }

    function delete($id){
        $result = Star::find($id);
        if(!is_null($result)){
            $result->delete();
        }
        Session::flash('message', '<p class="alert alert-success">Delete has successfull!</p>');
        return Redirect::route('admin_actor');
    }
}

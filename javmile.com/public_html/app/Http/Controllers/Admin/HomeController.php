<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Requests;
use App\Http\Controllers\AdminController;

use App\Models\Server;
use Cache, Session, Redirect;
use Intervention\Image\ImageManagerStatic as Img;

class HomeController extends AdminController {
    public function index() {
        return view('admin.home.index');
    }

    function clear_cache(){
        Cache::flush();
        Session::flash('message', 'Đã xóa cache');
        return Redirect::route('admin_home');
    }

    function upload_image(Request $request){
        $data = $request->all();
        // Allowed extentions.
        $allowedExts = array("gif", "jpeg", "jpg", "png", 'bmp');

        // Get filename.
        $temp = explode(".", $_FILES["file"]["name"]);
        // Get extension.
        $extension = end($temp);
        $uploadDir = 'data/uploads/';
        // An image check is being done in the editor but it is best to
        // check that again on the server side.
        // Do not use $_FILES["file"]["type"] as it can be easily forged.
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);

        if ((($mime == "image/gif")
        || ($mime == "image/jpeg")
        || ($mime == "image/pjpeg")
        || ($mime == "image/x-png")
        || ($mime == "image/png"))
        && in_array($extension, $allowedExts)) {
            // Generate new random name.
            $name = $data['slug']. '-' . uniqid() . "." . $extension;
            // Save file in the uploads folder.
            $data['file']->move($uploadDir, $name);
            $server = Server::where(['type' => 'ftp', 'default' => 1])->first();
            $path = $uploadDir.$name;
            // Image crop cover
            $width = Img::make($path)->width();
            $height = Img::make($path)->height();
            $max_width = 1280;
            $max_height = 720;
            if ($width > $max_width ) {
                    $image = Img::make($path)->resize($max_width, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
            }elseif ($height > $max_height ) {
                    $image = Img::make($path)->resize($max_height, NULL, function ($constraint) {
                                                                    $constraint->aspectRatio();
                                                                })->save($path);
                    $image_thumb = Server::uploadFtp($path, null, $server->id);
            }else{
                $image_thumb = Server::uploadFtp($path, null, $server->id);
            }
            if (file_exists($path)){
                @unlink($path);
            }
            if (file_exists($path)){
                @unlink($path);
            }
            echo json_encode(['link' => $image_thumb]);die;
        }
    }

}

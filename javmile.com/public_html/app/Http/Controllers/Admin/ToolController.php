<?php namespace App\Http\Controllers\Admin;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Controllers\AdminController;
use App\Models\Google_drive;
use App\Models\Film;
use App\Models\Video;
use App\Models\Episode;

use Validator, Session, Redirect;

class ToolController extends AdminController {

    function die_episode(Request $request){
        $data = $request->all();
        if (isset($_GET['sort'])) {
            $sort_arr = explode('-', $_GET['sort']);
            $sort = $sort_arr[1];
            $by = $sort_arr[0];
        }else{
            $sort = 'asc';
            $by = 'updated_at';
        }
        $result = Episode::where('status', 2)->orderBy($by , $sort)->with('user', 'film')->paginate(50);
        $result->appends($request->all());
        return view('admin.tool.die', compact('result'));
    }

    function null_episode(Request $request){
        $data = $request->all();
        if (isset($_GET['sort'])) {
            $sort_arr = explode('-', $_GET['sort']);
            $sort = $sort_arr[1];
            $by = $sort_arr[0];
        }else{
            $sort = 'asc';
            $by = 'updated_at';
        }
        $ids = Episode::groupBy('film_id')->pluck('film_id');
        $current = count($ids);
        $count = Film::whereNotIn('id', $ids)->count();
        // Film::whereNotIn('id', $ids)->update(['online' => NULL]);
        $result = Film::whereNotIn('id', $ids)->orderBy($by, $sort)->with('image_server');
        if (isset($_GET['k'])) {
            $keyword = Str::ascii(trim($_GET['k']));
            $result->where('title_search', 'like', "%$keyword%")->orWhere('title_en', 'like', "%$keyword%");
        }
        $result = $result->paginate(50);
        $result->appends($request->all());
        return view('admin.tool.film-null', compact('result', 'count', 'current'));
    }

    function drive(){
        return view('admin.tool.google-drive');
    }

    function update_drive(Request $request){
        $data = $request->all();
        $url = 'https://drive.google.com/folderview?id='.$data['folder_id'];
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
                return view('admin.tool.drive', compact('files', 'num'));
            }
        }
    }

    public function google_drive() {
        // $get = $this->curl('https://drive.google.com/file/d/0B4DJsmCrB_2FWWdYcDF5WTktNjg');
        // dd($get);
        // $cat = explode(',["fmt_stream_map","', $get);
        // if(isset($cat[1])){
        //     $cat = explode('"]', $cat[1]);
        //     $cat = explode(',', $cat[0]);
        //     foreach($cat as $link_str){
        //         $cat = explode('|', $link_str);
        //         $links = str_replace(array('\u003d', '\u0026'), array('=', '&'), $cat[1]);
        //         if($cat[0] == 37) {$link['1080'] = $links;}
        //         if($cat[0] == 22) {$link['720'] = $links;}
        //         if($cat[0] == 59) {$link['480'] = $links;}
        //         if($cat[0] == 18) { $link['360'] = $links;}
        //     }
        // }
        // dd($link[720]);
        if (isset($_GET['status'])) {
            $result = Google_drive::where('status', $_GET['status'])->paginate(30);
        }else{
            $result = Google_drive::paginate(30);
        }
        return view('admin.tool.google-drive', compact('result'));
    }

    function add_folder(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'folder_name' => 'required',
            'folder_id' => 'required|unique:google_drives,folder_id'
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $folder = Google_drive::create(['folder_name' => $data['folder_name'], 'folder_id' => $data['folder_id']]);
        // $url = 'https://drive.google.com/folderview?id='.$data['folder_id'];
        // $get = $this->curl($url);
        // $cat = explode('viewerItems: [', $get);
        // $cat = explode("\n,};", $cat[1]);
        // $cat = explode("\n,", $cat[0]);
        // /*array*/
        // foreach ($cat as $key => $value) {
        //     $file_info = explode(',', str_replace('"', '', $value));
        //     if(count($file_info) > 3){
        //         $files[] = [
        //             'able_id' => $folder->id,
        //             'file_name' => trim($file_info[2]),
        //             'file_id' => trim($file_info[7]),
        //             'status' => 'live'
        //             ];
        //     }
        // }
        // if(isset($files)){
        //     $insert = Google_drive_file::insert($files);
        // }
        Session::flash('message', 'Created!');
        return Redirect::route('google_drive_tool');
    }

    function edit_google_folder($id){
        $result = Google_drive::find($id);
        return view('admin.tool.google-edit', compact('result'));
    }

    function post_edit_folder($id, Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'folder_name' => 'required',
            'folder_id' => 'required'
        ]);
        if ($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $isset = Google_drive::where('folder_name', $data['folder_name'])->orWhere('folder_id', $data['folder_id'])->get();
        if(!is_null($isset->where('id', $id)->first())){
            Session::flash('message', 'Đã tồn tại tên hoặc id');
            return redirect()->back();
        }
        $folder = Google_drive::find($id);
        $folder->folder_id = $data['folder_id'];
        $folder->folder_name = $data['folder_name'];
        $folder->save();
        Session::flash('message', 'Updated!');
        return Redirect::route('google_drive_tool');
    }

    function update_google_all(){
        $folders = Google_drive::all();
        $count = ['update' => 0, 'create' => 0, 'die' => 0];
        foreach ($folders as $key => $folder) {
            $olds = Google_drive_file::where('able_id', $folder->id)->get();
            $old_files = $olds->pluck('file_name')->toArray();
            $url = 'https://drive.google.com/folderview?id='.$folder->folder_id;
            // $url = 'https://drive.google.com/folderview?id=0ByVAeHwSZfAgVlJ1UVNtRFE0aG8';
            $get = $this->curl($url);
            $cat = explode('viewerItems: [', $get);
            if(isset($cat[1])){
                $cat = explode("\n,};", $cat[1]);
                $cat = explode("\n,", $cat[0]);
                /*array*/
                foreach ($cat as $key => $value) {
                    $file_info = explode(',', str_replace('"', '', $value));
                    // echo $key.' - '.$file_info[2].' - '.$file_info[7].'</br>';
                    if(count($file_info) > 3){
                        if(in_array($file_info[2], $old_files)){
                            unset($old_files[array_search($file_info[2], $old_files)]);
                            $old = $olds->where('file_name', trim($file_info[2]))->first();
                            if ($old->file_id == trim($file_info[7]) and $old->status == 'live') {
                                # code...
                            }else{
                                Google_drive_file::where('id', $old->id)->update([
                                        'file_id' => trim($file_info[7]),
                                        'status' => 'live']);
                                if($old->film_video_id != NULL){
                                    Video::where('id', $old->film_video_id)->update(['free_filename' => trim($file_info[7])]);
                                }
                            }
                            $count['update']++;
                        }else{
                            $files[] = [
                                'able_id' => $folder->id,
                                'file_name' => trim($file_info[2]),
                                'file_id' => trim($file_info[7]),
                                'status' => 'live'
                                ];
                        }
                    }
                }
                if(isset($files)){
                    foreach ($files as $key_file => $value_file) {
                        $update = Google_drive_file::where('file_id', $value_file['file_id'])->update($value_file);
                        if ($update) {
                            $count['update']++;
                            unset($files[$key_file]);
                        }
                    }
                    if (count($files) > 0) {
                        $insert = Google_drive_file::insert($files);
                    }
                    $count['create'] += count($files);
                }
                if(count($old_files) > 0){
                    foreach ($old_files as $key => $value) {
                        Google_drive_file::where('file_name', $value)->update(['status' => 'die']);
                    }
                }
                $folder->status = 'live';
                $folder->save();
                $count['die'] += count($old_files);
            }else{
                $folder->status = 'die';
                $folder->save();
                Google_drive_file::where('able_id', $folder->id)->update(['status' => 'die']);
            }
        }
        Session::flash('message', '<div class="alert alert-success"><ul><li>Cập nhật '.$count['update'].' file</li><li>Thêm mới '.$count['create'].' file</li><li>Die '.$count['die'].' file</li></ul></div>');
        return Redirect::route('google_drive_file');
    }

    function update_google_folder($id){
        $folder = Google_drive::find($id);
        if (!is_null($folder)) {
            $olds = Google_drive_file::where('able_id', $id)->get();
            $old_files = $olds->pluck('file_name')->toArray();
            $url = 'https://drive.google.com/folderview?id='.$folder->folder_id;
            $get = $this->curl($url);
            $cat = explode('viewerItems: [', $get);
            if(isset($cat[1])){
                $cat = explode("\n,};", $cat[1]);
                $cat = explode("\n,", $cat[0]);
                /*array*/
                $count = ['update' => 0, 'create' => 0, 'die' => 0];
                foreach ($cat as $key => $value) {
                    $file_info = explode(',', str_replace('"', '', $value));
                    if(count($file_info) > 3){
                        if(in_array($file_info[2], $old_files)){
                            unset($old_files[array_search($file_info[2], $old_files)]);
                            $old = $olds->where('file_name', trim($file_info[2]))->first();
                            if ($old->file_id == trim($file_info[7]) and $old->status == 'live') {
                                # code...
                            }else{
                                Google_drive_file::where('id', $old->id)->update([
                                        'file_id' => trim($file_info[7]),
                                        'status' => 'live']);
                                if($old->film_video_id != NULL){
                                    Video::where('id', $old->film_video_id)->update(['free_filename' => trim($file_info[7])]);
                                }
                            }
                            $count['update']++;
                        }else{
                            $files[] = [
                                'able_id' => $folder->id,
                                'file_name' => trim($file_info[2]),
                                'file_id' => trim($file_info[7]),
                                'status' => 'live'
                                ];
                        }
                    }
                }
                if(isset($files)){
                    foreach ($files as $key_file => $value_file) {
                        $update = Google_drive_file::where('file_id', $value_file['file_id'])->update($value_file);
                        if ($update) {
                            $count['update']++;
                            unset($files[$key_file]);
                        }
                    }
                    if (count($files) > 0) {
                        $insert = Google_drive_file::insert($files);
                    }
                    $count['create'] = count($files);
                }
                if(count($old_files) > 0){
                    foreach ($old_files as $key => $value) {
                        Google_drive_file::where('file_name', $value)->update(['status' => 'die']);
                    }
                }
                $folder->status = 'live';
                $folder->save();
                $count['die'] = count($old_files);
                Session::flash('message', '<div class="alert alert-success"><ul><li>Cập nhật '.$count['update'].' file</li><li>Thêm mới '.$count['create'].' file</li><li>Die '.$count['die'].' file</li></ul></div>');
            }else{
                $folder->status = 'die';
                $folder->save();
                Google_drive_file::where('able_id', $id)->update(['status' => 'die']);
                Session::flash('message', 'folder die');
                return Redirect::route('google_drive_tool');
            }
        }
        return Redirect::route('google_drive_file');
    }

    function delete_google_folder($id){
        $delete = Google_drive::where('id', $id)->delete();
        Google_drive_file::where('able_id', $id)->delete();
        Session::flash('message', 'Deleted!');
        return Redirect::route('google_drive_tool');
    }

    function google_file(Request $request){
        $query = Google_drive_file::with('folder');
        $uri = $request->all();
        if (isset($uri['page'])) {
            unset($uri['page']);
        }
        if (isset($_GET['folder'])) {
            $query->where('able_id', $_GET['folder']);
        }
        if (isset($_GET['k'])) {
            $key = $_GET['k'];
            $query->where('file_name', 'like', "%$key%");
        }
        if(isset($_GET['film_video_id'])){
            if ($_GET['film_video_id'] == 1) {
                $query->whereNotNull('film_video_id');
            }else{
                $query->whereNull('film_video_id');
            }
        }
        if (isset($_GET['status'])) {
            $query->where('status', $_GET['status']);
        }
        $count = $query->count();
        $result = $query->paginate(50);
        if (count($uri) > 0) {
            # code...
            $result->appends($uri);
        }
        return view('admin.tool.google-file', compact('result', 'count'));
    }

    function delete_google_file($id){
        $delete = Google_drive_file::where('id', $id)->delete();
        Session::flash('message', 'Deleted!');
        return Redirect::route('google_drive_file');
    }

    function curl($url){
        $ch = @curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $head[] = "Connection: keep-alive";
        $head[] = "Keep-Alive: 300";
        $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $head[] = "Accept-Language: en-us,en;q=0.5";
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

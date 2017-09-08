<?php
namespace App\Http\Controllers\Client;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Film;
use App\Models\Server;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Bookmark;
use App\Models\Feedback;
use App\Models\Reporter;
use App\Models\Error;
use App\Models\Customer;
use App\Models\Episode;

use Validator, Session, Redirect, MetaTag, Cache, DB;

class FilmController extends Controller {

    function detail($cat_slug, $slug){
        $minutes = env('CACHE_TIME');
        $per_page = env('PERPAGE');
        if(!Cache::has('detail_'.$cat_slug.'_'.$slug)){
            $film = Film::where('slug', $slug)->with(['category', 'image_server', 'episode_list', 'directors', 'stars', 'tags', 'countries', 'quality', 'genres'])->first();
            Cache::put('detail_'.$cat_slug.'_'.$slug, $film, $minutes);
        }
        $result = Cache::get('detail_'.$cat_slug.'_'.$slug);
        // if (!Session::has(\URL::current())) {
        //     $updated = Film::where('id', $result->id)->increment('viewed');
        //     Session::set(\URL::current(), true);
        // }
        if (!is_null($result->image_server)) {
            # code...
            $image_data = json_decode($result->image_server->data);
            $image_prefix = $image_data->public_url.'/'.$image_data->dir;
            MetaTag::set('image_src', $image_prefix .$result->thumb_name);
            $img = explode('.', $result->thumb_name);
            MetaTag::set('image_type', array_pop($img));
        }
        MetaTag::set('title',$result->title.'- Free JAVHD - javmile.com');
        if(isset($result->seo)){
            $seo = json_decode($result->seo);
            // if($seo->title !=''){
            //     MetaTag::set('title',$seo->title);
            // }
            if($seo->keyword !=''){
                MetaTag::set('keyword',$seo->keyword);
            }
        }
        if($result->description !=''){
            MetaTag::set('description',$result->description);
        }
        if (!Cache::has('film_relation_'.$result->id)) {
            $relation = Film::where('id', '<>', $result->id)->where('online', 1)->with(['image_server', 'quality', 'category'])->orderByRaw("RAND()")->take(env('PERBLOCK_HOME'))->get();
            Cache::put('film_relation_'.$result->id, $relation, env('CACHE_TIME'));
        }
        $relation = Cache::get('film_relation_'.$result->id);
        $breadcrumb[] = ['link' => route('category', $cat_slug), 'title' => $result->category->title];
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => $result->title];
        if(Session::has('user')){
            $bookmark = Bookmark::where([
                'user_id' => Session::get('user')->id,
                'type' => 'film',
                'able_id' => $result->id
                ])->first();
            if (is_null($bookmark)) {
                $bookmarked = false;
            }else{
                $bookmarked = true;
            }
        }else{
            $bookmarked = false;
        }
        if (!Cache::has('report_errors')) {
            $report_errors = Error::all();
            Cache::put('report_errors', $report_errors, $minutes);
        }
        $report_errors = Cache::get('report_errors');
        return view('client.film.detail', compact('result', 'relation', 'breadcrumb', 'bookmarked', 'report_errors'));
    }

    function play($cat_slug, $slug, $id){
        $minutes = env('CACHE_TIME');
        $per_page = env('PERPAGE');
        if(!Cache::has('detail_'.$cat_slug.'_'.$slug)){
            $film = Film::where('slug', $slug)->with(['category', 'image_server', 'episode_list', 'directors', 'stars', 'tags', 'countries', 'quality', 'genres'])->first();
            Cache::put('detail_'.$cat_slug.'_'.$slug, $film, $minutes);
        }
        $result = Cache::get('detail_'.$cat_slug.'_'.$slug);

        if (!is_null($result->image_server)) {
            # code...
            $image_data = json_decode($result->image_server->data);
            $image_prefix = $image_data->public_url.'/'.$image_data->dir;
            MetaTag::set('image_src', $image_prefix .$result->thumb_name);
            $img = explode('.', $result->thumb_name);
            MetaTag::set('image_type', array_pop($img));
        }
        if (!Cache::has('film_relation_'.$result->id)) {
            $relation = Film::where('id', '<>', $result->id)->where('online', 1)->with(['image_server', 'quality', 'category'])->orderByRaw("RAND()")->take(env('PERBLOCK_HOME'))->get();
            Cache::put('film_relation_'.$result->id, $relation, env('CACHE_TIME'));
        }
        $relation = Cache::get('film_relation_'.$result->id);
        if(Session::has('user')){
            $bookmark = Bookmark::where([
                'user_id' => Session::get('user')->id,
                'type' => 'film',
                'able_id' => $result->id
                ])->first();
            if (is_null($bookmark)) {
                $bookmarked = false;
            }else{
                $bookmarked = true;
            }
        }else{
            $bookmarked = false;
        }
        if (!Cache::has('report_errors')) {
            $report_errors = Error::all();
            Cache::put('report_errors', $report_errors, $minutes);
        }
        $report_errors = Cache::get('report_errors');

        // Getlink
        $episode = Episode::find($id);
        if (!Session::has(\URL::current())) {
            $episode->viewed++;
            $updated = Film::where('id', $result->id)->increment('viewed');
            Session::set(\URL::current(), time());
        }else{
            $time = Session::get(\URL::current());
            if ((time() - $time) > 300) {
                $episode->viewed++;
                $updated = Film::where('id', $result->id)->increment('viewed');
                Session::set(\URL::current(), time());
            }
        }
        MetaTag::set('title',$result->title. '- Free JAVHD - javmile.com');
        if(isset($result->seo)){
            $seo = json_decode($result->seo);
            // if($seo->title !=''){
            //     MetaTag::set('title',$seo->title.' - '.'Tập '.$episode->title);
            // }
            if($seo->keyword !=''){
                MetaTag::set('keyword',$seo->keyword);
            }
        }
        if($result->description !=''){
            MetaTag::set('description',$result->description);
        }
        $breadcrumb[] = ['link' => route('category', $cat_slug), 'title' => $result->category->title];
        $breadcrumb[] = ['link' => route('film_detail', ['cat' => $cat_slug, 'slug' => $result->slug]), 'title' => $result->title];
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Eps '.$episode->title];
        $link = NULL;
        if (strlen($episode->sub_vi) > 0) {
            $sub = $episode->sub_vi;
        }else{
            $sub = NULL;
        }
        $next = $result->episode_list->sortBy('title')->whereLoose('type', $episode->type)->keyBy('title')->all();
        $i = 0;
        $next_ep = Null;
        foreach ($next as $key => $value) {
            if ($key > $episode->title and $i < 1) {
                $i++;
                $next_ep = $value;
            }
        }
        $auto_start = true;
        $isembed = false;
        $link_embed = null;

        if (!is_null($episode)) {
            if (strpos($episode->file_name, 'javhihi.in') or strpos($episode->file_name, 'javhihi.com') or strpos($episode->file_name, 'jav789.com') or strpos($episode->file_name, 'javbuz.com')) {
                $link = $this->javhihi($episode->file_name);
                if(!is_null($link)){
                    if ($episode->status == 2) {
                        $episode->status = NULL;
                    }
                }else{
                    if ($episode->status != 2) {
                        $episode->status = 2;
                    }
                    $link['HD'] = env('REDIRECT_VIDEO');
                }
            }
            elseif(strpos($episode->file_name, 'drive.google.com')) {
                $link = $this->google_drive($episode->file_name);
                if(!is_null($link)){
                    if ($episode->status == 2) {
                        $episode->status = NULL;
                    }
                }else{
                    if ($episode->status != 2) {
                        $episode->status = 2;
                    }
                    $link['HD'] = env('REDIRECT_VIDEO');

                }
            }elseif(strpos($episode->file_name, 'picasaweb.google.com')){
                // include(app_path('Libraries/picasa.php'));
                $picasa = new Picasa($episode->file_name);
                $link['480'] = $picasa->get_480p_mp4();
                $link['720'] = $picasa->get_720p_mp4();
                $sub = NULL;
                if (is_null($link['480']) and is_null($link['720'])) {
                    unset($link['480'], $link['720']);
                    if ($episode->status != 2) {
                        $episode->status = 2;
                    }
                    $link['HD'] = env('REDIRECT_VIDEO');
                }
            }elseif (strpos($episode->file_name, 'youtube.com')) {
                $link['HD'] = $episode->file_name;
                $auto_start = false;
            }else {
                $isembed = true;
                $link['HD'] = $episode->file_name;
                $link_embed = $episode->file_name;
            }
        }
        $episode->save();
        return view('client.film.detail', compact('result', 'episode', 'auto_start', 'next_ep', 'relation', 'breadcrumb', 'bookmarked', 'report_errors', 'link', 'sub', 'isembed', 'link_embed'));
    }

    function download($slug, $type){
        $result = Film::where('slug', $slug)->with('category')->first();
        $episodes = Episode::where(['film_id' => $result->id, 'type' => $type])->get();
        $types = ['Full' => 'Vietsub', 'Part' => 'Dự phòng', 'ThuyetMinh' => 'Thuyết minh', 'raw' => 'NoSub'];
        MetaTag::set('title','Download '.$result->title. ' HD Thuyết Minh + Vietsub - '.$result->title_en.' server '.$types[$type]);
        if(isset($result->seo)){
            $seo = json_decode($result->seo);
            // if($seo->title !=''){
            //     MetaTag::set('title', 'Download '.$seo->title.' server '.$types[$type]);
            // }
            if($seo->keyword !=''){
                MetaTag::set('keyword',$seo->keyword);
            }
        }
        if($result->description !=''){
            MetaTag::set('description',$result->description);
        }
        $breadcrumb[] = ['link' => route('category', $result->category->slug), 'title' => $result->category->title];
        $breadcrumb[] = ['link' => route('film_detail', ['cat' => $result->category->slug, 'slug' => $result->slug]), 'title' => $result->title];
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Download server '.$types[$type]];
        return view('client.film.download', compact('result', 'episodes', 'breadcrumb', 'type', 'types'));
    }

    function download_ep($id, $slug, $type){
        $result = Film::where('slug', $slug)->with('category')->first();
        $episodes = Episode::where(['film_id' => $result->id, 'type' => $type])->get();
        $types = ['Full' => 'Server 1', 'Part' => 'Server 2', 'ThuyetMinh' => 'Server 3', 'raw' => 'Server 4'];
        $episode = $episodes->whereLoose('id', $id)->first();
        $link = NULL;
        $message = Null;
        if (!is_null($episode)) {
            $episode_title = $result->title.'_eps '.$episode->title.'_'.str_slug($types[$type]);
            if (strpos($episode->file_name, 'javhihi.com') or strpos($episode->file_name, 'jav789.com')) {
                $links = $this->javhihi($episode->file_name);
                if(!is_null($links)){
                    foreach ($links as $key => $value) {
                        $link[$value['label']] = str_replace('&ip=', '&filename=video.mp4&type=video/mp4&title='.$episode_title.'_'.$value['label'].'&ip=', $value['file']);
                    }
                    if ($episode->status == 2) {
                        $episode->status = NULL;
                    }
                }else{
                    if ($episode->status != 2) {
                        $episode->status = 2;
                        $episode->save();
                    }
                    $message = 'This video is going on to fix.';
                }
            }
            elseif (strpos($episode->file_name, 'drive.google.com')) {
                // $get = $this->curl($episode->file_name);
                // $get = str_replace('drive.google.com', 'googlevideo.com', $get);
                // $cat = explode(',["fmt_stream_map","', $get);
                // if(isset($cat[1])){
                //     $cat = explode('"]', $cat[1]);
                //     $cat = explode(',', $cat[0]);
                //     foreach($cat as $link_str){
                //         $cat = explode('|', $link_str);
                //         $links = str_replace(array('\u003d', '\u0026'), array('=', '&'), $cat[1]);
                //         if($cat[0] == 37) {$link['1080'] = str_replace('&ip=', '&filename=video.mp4&type=video/mp4&title='.$episode_title.'_1080p'.'&ip=', $links);}
                //         if($cat[0] == 22) {$link['720'] = str_replace('&ip=', '&filename=video.mp4&type=video/mp4&title='.$episode_title.'_720p'.'&ip=', $links);}
                //         if($cat[0] == 59) {$link['480'] = str_replace('&ip=', '&filename=video.mp4&type=video/mp4&title='.$episode_title.'_480p'.'&ip=', $links);}
                //         if($cat[0] == 18) { $link['360'] = str_replace('&ip=', '&filename=video.mp4&type=video/mp4&title='.$episode_title.'_360p'.'&ip=', $links);}
                //     }
                //     if ($episode->status == 2) {
                //         $episode->status = NULL;
                //         $episode->save();
                //     }
                // }
                $links = $this->google_drive($episode->file_name);
                if(!is_null($links)){
                    foreach ($links as $key => $value) {
                        $link[$value['label']] = str_replace('&ip=', '&filename=video.mp4&type=video/mp4&title='.$episode_title.'_'.$value['label'].'&ip=', $value['file']);
                    }
                    if ($episode->status == 2) {
                        $episode->status = NULL;
                    }
                }else{
                    if ($episode->status != 2) {
                        $episode->status = 2;
                        $episode->save();
                    }
                    $message = 'This video is going on to fix.';
                }
            }else{
                $message = 'Cannot download it';
            }
        }else{
            $message = 'This video was deleted';
        }
        MetaTag::set('title','Download '.$result->title. 'Free JAVHD - javmile.com');
        if(isset($result->seo)){
            $seo = json_decode($result->seo);
            // if($seo->title !=''){
            //     MetaTag::set('title', 'Download '.$seo->title.' server '.$types[$type]);
            // }
            if($seo->keyword !=''){
                MetaTag::set('keyword',$seo->keyword);
            }
        }
        if($result->description !=''){
            MetaTag::set('description',$result->description);
        }
        $breadcrumb[] = ['link' => route('category', $result->category->slug), 'title' => $result->category->title];
        $breadcrumb[] = ['link' => route('film_detail', ['cat' => $result->category->slug, 'slug' => $result->slug]), 'title' => $result->title];
        $breadcrumb[] = ['link' => 'javascript:void(0)', 'title' => 'Download server '.$types[$type]];
        return view('client.film.download-ep', compact('result', 'episodes', 'breadcrumb', 'type', 'types', 'link', 'message', 'episode'));
    }

    function bookmark(Request $request){
        $data = $request->all();
        $bookmark = Bookmark::where([
            'user_id' => Session::get('user')->id,
            'able_id' => $data['key'],
            'type' => 'film'
            ])->first();
        if (is_null($bookmark)) {
            $bookmark = Bookmark::insert([
                'user_id' => Session::get('user')->id,
                'able_id' => $data['key'],
                'type' => 'film'
                ]);
            $add = true;
        }else{
            $bookmark->delete();
            $add = false;
        }
        echo json_encode(['add' => $add]);
    }

    function report(Request $request){
        $data = $request->all();
        $user = Session::get('user');
        $reported = Reporter::where(['customer_id' => $user->id, 'error_id' => $data['id'], 'able_id' => $data['able_id']])->first();
        if ($user->baned_to < time() and is_null($reported)) {
            Reporter::create([
                'customer_id' => $user->id,
                'error_id' => $data['id'],
                'able_id' => $data['able_id'],
                'content' => $data['content']
                ]);
            $film = Film::where('id', $data['able_id'])->increment('reported', 1);
        }else{
            Session::flash('message', 'Thank you.');
        }
        return Redirect::back();
    }

    function google_drive($url){
        if (!Cache::has($url)) {
            $get = $this->viewsource('https://videoapi.io/api/getlink?key=6a2bd95f0a61dd8de36611219ecaa5c6&link='.$url.'/view&cache=false');
            $links = json_decode($get, true);
            Cache::put($url, $links, 180);
        }
        return Cache::get($url);
    }

    function javhihi($url){
        if (!Cache::has($url)) {
            $get = $this->viewsource('http://crawler.javmile.com/video?link='.$url);
            $links = json_decode($get, true);
            Cache::put($url, $links, 180);
        }
        return Cache::get($url);
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

    function viewsource($url){
        $ch      = curl_init();
        $timeout = 15;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.69 Safari/537.36");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}

class Picasa {
 private $link;
 private $type;
 private $obj_array;

 /**
  *
  * @param string $link
  */
 public function __construct($link) {
  $this->link = $link;
  $this->type = $this->check_link();
  $this->obj_array = $this->get_json($this->get_xml_link());
 }

 /**
  *
  * @return number
  */
 public function check_link(){
  if (preg_match('/directlink/', $this->link)){
   return 1;
  }else {
   return 2;
  }
 }


 /**
  *
  * @return boolean|mixed
  */
function viewsource($url){
    $ch      = curl_init();
    $timeout = 15;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.69 Safari/537.36");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

 /**
  *
  * @return Ambigous <string, mixed>
  */
public function get_xml_link(){
    $source = $this->view_source($this->link);
    if ( !$source){
        $xml_link = Null;
    }else{
        $xml_link = '';
        switch ($this->type){
            case 1:
            $xml_link = explode('"application/atom+xml","href":"', $source)[1];
            $xml_link = explode('"}', $xml_link)[0];
            break;
            case 2:
            $start = strpos($source, 'https://picasaweb.google.com/data/feed/base/user/');
            $end = strpos($source, '?alt=');
            $xml_link = substr($source, $start, $end - $start);
            $photoid = trim(explode('#', $this->link)[1], ' ');
            $xml_link .= '/photoid/' . $photoid . '?alt=jsonm&authkey=';
            $xml_link .= explode('#', explode('authkey=', $this->link)[1])[0];
            $xml_link = str_replace('base', 'tiny', $xml_link);
            break;
        }
    }
    return $xml_link;
}

 /**
  *
  * @param string $xml_link
  * @return stdClass
  */

function curl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

 public function get_json($xml_link){
    $sourceJson = $this->curl($xml_link);
    $decodeJson = json_decode($sourceJson);
    if (isset($decodeJson->feed->media->content)) {
        return $decodeJson->feed->media->content;
    }else{
        return NULL;
    }

 }


 /**
  * @return string
  * It return 720p.mp4 if has, otherwise return 480p.mp4
  */
 public function get_720p_mp4(){
  for ($i = count($this->obj_array) - 1; $i >= 0; $i--){
   if ( $this->obj_array[$i]->type == 'video/mpeg4'){
    return $this->obj_array[$i]->url;
   }
  }
 }

 /**
  * @return string
  * It return 480p.mp4
  */
 public function get_480p_mp4(){
  for ($i = 0; $i < count($this->obj_array); $i++){
   if ( $this->obj_array[$i]->type == 'video/mpeg4'){
    return $this->obj_array[$i]->url;
   }
  }
 }
}
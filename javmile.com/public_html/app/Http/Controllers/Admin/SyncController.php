<?php namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Str;
use App\Models\Tb_film;
use App\Models\Film;
use App\Models\Episode;
use App\Models\Tb_episode;

use Validator, Session, Redirect, Cache, DB;

class SyncController extends AdminController {
    function index(Request $request){
        $result = Tb_film::where('is_sync', '<>', 1)->with(['episodes'])->orderBy('timeupdate', 'desc');
        if (isset($_GET['k'])) {
            $keyword = Str::ascii(trim($_GET['k']));
            $result->where('title_search', 'like', "%$keyword%")->orWhere('title_en', 'like', "%$keyword%");
        }
        $result = $result->paginate(50);
        $result->appends($request->all());
        $count = Tb_film::where('is_sync', '<>', 1)->count();
        return view('admin.sync.index', compact('result', 'count'));
    }

    function episode($id){
        $result = Tb_episode::where('filmid', $id)->orderBy('name', 'asc')->get();
        $film = Tb_film::find($id);

        if (strpos($film->title_en, 'season')) {
            $en = trim(substr($film->title_en, 0, strpos($film->title_en, 'season')));
        }elseif (strpos($film->title_en, 'Season')) {
            $en = trim(substr($film->title_en, 0, strpos($film->title_en, 'Season')));
        }elseif (strpos($film->title_en, '(')) {
            $en = trim(substr($film->title_en, 0, strpos($film->title_en, '(')));
        }else{
            $en = trim($film->title_en);
        }
        $vi = trim(Str::ascii($film->title_search));
        $current = Film::where('title_en', 'like', "%$en%")->orWhere('title_ascii', 'like', "%$vi%")->take(10)->get();
        return view('admin.sync.episode', compact('result', 'film', 'current'));
    }

    function sync($id, $old_id){
        $old = Tb_film::where('id', $old_id)->with('episodes')->first();
        $user = Session::get('admin');
        $film = Film::where('id', $id)->with('episode_list')->first();
        if ($film->episode_list->count() == 0) {
            foreach ($old->episodes as $key => $item) {
                $episodes[$key]['film_id'] = $film->id;
                $episodes[$key]['user_id'] = $user->id;
                $episodes[$key]['title'] = $item->name;
                $episodes[$key]['file_name'] = $item->url;
                switch ($item->present) {
                    case 3:
                        $episodes[$key]['type'] = 'Full';
                        break;
                    case 2:
                        $episodes[$key]['type'] = 'Part';
                        break;
                    case 1:
                        $episodes[$key]['type'] = 'ThuyetMinh';
                        break;
                    default:
                        $episodes[$key]['type'] = 'Part';
                        break;
                }
            }
            if (isset($episodes)) {
                $collect = collect($episodes);
                $array_ep = $collect->sortBy('title')->toArray();
                Episode::insert($array_ep);
                if ($film->category_id == 2) {
                    # code...
                    $film->exist_episodes = $film->episodes;
                }else{
                    $film->exist_episodes = 'Full';
                }
                $film->save();
                DB::table('tb_films')->where('id', $old->id)->update(['is_sync' => 1, 'active' => 0]);
            }
        }else{
            DB::table('tb_films')->where('id', $old->id)->update(['is_sync' => 1, 'active' => 0]);
        }
        return Redirect::route('admin_old_film');
    }

    function change($id){
        DB::table('tb_films')->where('id', $id)->update(['is_sync' => 1]);
        return Redirect::route('admin_old_film');
    }
}

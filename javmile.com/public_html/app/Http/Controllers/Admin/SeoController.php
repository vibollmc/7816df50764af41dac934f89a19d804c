<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AdminController;
use App\Models\Seo;
use Validator, Session, Redirect;
use Illuminate\Http\Request;

class SeoController extends AdminController {
    public function index() {
        $result = Seo::paginate(30);
        return view('admin.seo.index', compact('result'));
    }

    function create(){
        $slug = ['phim-chieu-rap' => 'Phim chiếu rạp'];
        return view('admin.seo.create', compact('slug'));
    }

    function store(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
            'slug' => 'required|unique:seos,slug'
        ]);
        if ($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $seo = Seo::create([
                'title' => $data['title'],
                'slug' => $data['slug'],
                'keyword' => $data['keyword'],
                'description' => $data['description']
            ]);
        Session::flash('message', 'Created');
        return Redirect::route('admin_seo_block');
    }

    function edit($id){
        $result = Seo::find($id);
        $slug = ['phim-chieu-rap' => 'Phim chiếu rạp'];
        return view('admin.seo.edit', compact('result', 'slug'));
    }

    function update($id, Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required'
        ]);
        if ($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator);
        }
        $result = Seo::find($id);
        $result->title = $data['title'];
        $result->keyword = $data['keyword'];
        $result->description = $data['description'];
        $result->save();
        Session::flash('message', '<p class="alert alert-success">Update has successfull!</p>');
        return Redirect::route('admin_seo_block');
    }

    function delete($id){
        $result = Seo::where('id', $id)->delete();
        if($result){
            Session::flash('message', '<p class="alert alert-success">Delete has successfull!</p>');
        }
        return Redirect::route('admin_seo_block');
    }
}

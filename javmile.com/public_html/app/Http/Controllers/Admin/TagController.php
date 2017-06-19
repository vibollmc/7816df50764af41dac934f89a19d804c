<?php namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AdminController;
use App\Models\Tags;
use Validator, Session, Redirect;
use Illuminate\Http\Request;

class TagController extends AdminController {
    public function index() {
        if(isset($_GET['k'])){
            $key = $_GET['k'];
            $result = Tags::where('title', 'like', "%$key%")->orderBy('status', 'asc')->paginate(20);
        }else{
            $result = Tags::orderBy('status', 'asc')->paginate(20);
        }
        return view('admin.tag.index', compact('result'));
    }

    function edit($id){
        $result = Tags::find($id);
        return view('admin.tag.edit', compact('result'));
    }

    function update($id, Request $request){
        $data = $request->all();
        $title = Tags::where('id', '<>', $id)->where('title', $data['title'])->first();
        $slug = Tags::where('id', '<>', $id)->where('slug', $data['slug'])->first();
        if (!is_null($title)) {
            Session::flash('title_err', 'This name already exists.');
        }
        if (!is_null($slug)) {
            Session::flash('slug_err', 'This slug already exists.');
        }
        if (!is_null($slug) or !is_null($title)) {
            return Redirect::back()->withInput();
        }
        unset($data['_token']);
        $result = Tags::find($id);
        $data['seo'] = json_encode($data['seo']);
        foreach ($data as $key => $value) {
            $result->$key = $value;
        }
        $result->status = 1;
        $result->save();
        Session::flash('message', '<p class="alert alert-success">Update has successfull!</p>');
        return Redirect::route('admin_tag');
    }

    function delete($id){
        $result = Tags::find($id);
        if(!is_null($result)){
            $result->delete();
        }
        Session::flash('message', '<p class="alert alert-success">Delete has successfull!</p>');
        return Redirect::back();
    }
}

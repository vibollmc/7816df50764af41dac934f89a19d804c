<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use Validator, Session, Redirect;
use App\Models\Category;

class CategoryController extends AdminController
{
    function index(){
        $result = Category::paginate(10);
        return view('admin.category.index', compact('result'));
    }

    function create(){
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.category.create', compact('categories'));
    }

    function store(Request $request){
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
        $category = Category::create([
            'title'       => $data['title'],
            'title_ascii' => Str::ascii($data['title']),
            'slug'        => $data['slug'],
            'parent_id'   => $data['parent_id'],
            'status'      => isset($data['status'])? $data['status']: NULL,
            'seo'         => json_encode($data['seo']),
            'description' => $data['description']
            ]);
        Session::flash('message', '<p class="alert alert-success">Added has successfull!</p>');
        return Redirect::route('admin_category');
    }

    function edit($id){
        $result = Category::find($id);
        $categories = Category::whereNull('parent_id')->where('id', '<>', $id)->get();
        return view('admin.category.edit', compact('result', 'categories'));
    }

    function update($id, Request $request){
        $category = Category::find($id);
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
        $category->title       = $data['title'];
        $category->title_ascii = Str::ascii($data['title']);
        $category->slug = $data['slug'];
        $category->parent_id = $data['parent_id'];
        $category->status = !isset($data['status'])? 0: $data['status'];
        $category->seo = json_encode($data['seo']);
        $category->description = $data['description'];
        $category->save();
        Session::flash('message', '<p class="alert alert-success">Successfully updated !</p>');
        return Redirect::route('admin_category');
    }

    function delete($id){
        $category= Category::find($id);
        $category->delete();
        Session::flash('message', '<p class="alert alert-success">Successfully to deleted !</p>');
        return Redirect::back();
    }
}

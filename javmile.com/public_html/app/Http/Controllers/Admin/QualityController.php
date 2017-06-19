<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use Validator, Session, Redirect;
use App\Models\Quality;

class QualityController extends AdminController
{
    function index(){
        $result = Quality::paginate(10);
        return view('admin.quality.index', compact('result'));
    }

    function create(){
        return view('admin.quality.create', compact('categories'));
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
        $quality = Quality::create([
            'title'       => $data['title'],
            'title_ascii' => Str::ascii($data['title']),
            'slug'        => $data['slug'],
            'status'      => isset($data['status'])? $data['status']: NULL,
            'seo'         => json_encode($data['seo']),
            'description' => $data['description']
            ]);
        Session::flash('message', '<p class="alert alert-success">Added has successfull!</p>');
        return Redirect::route('admin_quality');
    }

    function edit($id){
        $result = Quality::find($id);
        return view('admin.quality.edit', compact('result'));
    }

    function update($id, Request $request){
        $quality = Quality::find($id);
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
        $quality->title       = $data['title'];
        $quality->title_ascii = Str::ascii($data['title']);
        $quality->slug = $data['slug'];
        $quality->status = !isset($data['status'])? 0: $data['status'];
        $quality->seo = json_encode($data['seo']);
        $quality->description = $data['description'];
        $quality->save();
        Session::flash('message', '<p class="alert alert-success">Successfully updated !</p>');
        return Redirect::route('admin_quality');
    }

    function delete($id){
        $quality= Quality::find($id);
        $quality->delete();
        Session::flash('message', '<p class="alert alert-success">Successfully to deleted !</p>');
        return Redirect::back();
    }
}

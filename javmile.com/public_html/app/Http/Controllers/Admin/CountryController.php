<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use Validator, Session, Redirect;
use App\Models\Country;

class CountryController extends AdminController
{
    function index(){
        $result = Country::paginate(30);
        return view('admin.country.index', compact('result'));
    }

    function change_menu($id){
        $result= Country::find($id);
        if ($result->menu == 1) {
            $result->menu = NULL;
        }else{
            $result->menu = 1;
        }
        $result->save();
        return Redirect::back();
    }

    function create(){
        return view('admin.country.create');
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
        $country = Country::create([
            'title'       => $data['title'],
            'title_ascii' => Str::ascii($data['title']),
            'slug'        => $data['slug'],
            'code'        => $data['code'],
            'status'      => isset($data['status'])? $data['status']: NULL,
            'seo'         => json_encode($data['seo']),
            'description' => $data['description']
            ]);
        Session::flash('message', '<p class="alert alert-success">Added has successfull!</p>');
        return Redirect::route('admin_country');
    }

    function edit($id){
        $result = Country::find($id);
        return view('admin.country.edit', compact('result'));
    }

    function update($id, Request $request){
        $country = Country::find($id);
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
        $country->title       = $data['title'];
        $country->title_ascii = Str::ascii($data['title']);
        $country->slug = $data['slug'];
        $country->code = $data['code'];
        $country->status = !isset($data['status'])? 0: $data['status'];
        $country->seo = json_encode($data['seo']);
        $country->description = $data['description'];
        $country->save();
        Session::flash('message', '<p class="alert alert-success">Successfully updated !</p>');
        return Redirect::route('admin_country');
    }

    function delete($id){
        $quality= Quality::find($id);
        $quality->delete();
        Session::flash('message', '<p class="alert alert-success">Successfully to deleted !</p>');
        return Redirect::back();
    }
}

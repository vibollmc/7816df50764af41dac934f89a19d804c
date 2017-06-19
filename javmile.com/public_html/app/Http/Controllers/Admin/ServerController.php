<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use Validator, Session, Redirect;
use App\Models\Server;

class ServerController extends AdminController
{
    function index(){
        $result = Server::paginate(10);
        return view('admin.server.index', compact('result'));
    }

    function create(){
        return view('admin.server.create');
    }

    function store(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|max:255|unique:servers',
        ]);
        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $server = Server::create([
            'title'       => $data['title'],
            'title_ascii' => Str::ascii($data['title']),
            'type'        => $data['type'],
            'status'      => isset($data['status'])? $data['status']: NULL,
            'default'     => isset($data['default'])? $data['default']: NULL,
            'data'        => json_encode($data['data']),
            'description' => $data['description']
            ]);
        Session::flash('message', '<p class="alert alert-success">Added has successfull!</p>');
        return Redirect::route('admin_server');
    }

    function edit($id){
        $result = Server::find($id);
        return view('admin.server.edit', compact('result'));
    }

    function update($id, Request $request){
        $server = Server::find($id);
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required|max:255',
        ]);
        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
            return Redirect::back()->withInput()->withErrors($validator);
        }
        $isset = Server::where('id','<>', $id)->where('title', $data['title'])->get();
        if ($isset->count()>0) {
            Session::flash('message', '<p class="alert alert-warning">Title is exists !</p>');
            return Redirect::back()->withInput();
        }
        $server->title       = $data['title'];
        $server->title_ascii = Str::ascii($data['title']);
        $server->type        = $data['type'];
        $server->status      = !isset($data['status'])? NULL: $data['status'];
        $server->default     = !isset($data['default'])? NULL: $data['default'];
        $server->data        = json_encode($data['data']);
        $server->description = $data['description'];
        $server->save();
        Session::flash('message', '<p class="alert alert-success">Successfully updated !</p>');
        return Redirect::route('admin_server');
    }

    function delete($id){
        $server = Server::find($id);
        $server->delete();
        Session::flash('message', '<p class="alert alert-success">Successfully to deleted !</p>');
        return Redirect::back();
    }
}

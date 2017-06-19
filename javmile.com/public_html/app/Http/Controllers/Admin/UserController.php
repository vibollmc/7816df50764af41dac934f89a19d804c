<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use App\Models\User;
use App\Models\User_group;
use App\Http\Requests;
use App\Http\Controllers\AdminController;
use Validator, Session, Redirect;
use App\Models\Customer;

class UserController extends AdminController
{
    function index(){
        if (isset($_GET['k']) and !is_null($_GET['k']) AND ($_GET['k'] != '')) {
            $keyword = $_GET['k'];
            if (!is_null($keyword) AND ($keyword != '')) {
                $result =  User::with(['group'])->where('username', 'like', "%$keyword%")->orderBy('created_at', 'desc')->paginate(20);
            }
        }else{
            $result = User::with(['group'])->orderBy('created_at', 'desc')->paginate(20);
        }
        return view('admin.user.index', compact('result'));
    }

    function type($type_id){
        $result = User::where('type_id', $type_id)->with(['type'])->paginate(20);
        return view('admin.user.index', compact('result'));
    }

    function create(){
        $types = User_group::all();
        return view('admin.user.create', compact('types'));
    }

    function store(Request $request){
        $data      = $request->all();
        $validator = Validator::make($data, [
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|max:255|min:6',
            'repassword' => 'required|same:password',
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors()->all();
            return redirect()->back()->withInput()->withErrors($validator);
        }else{
            $user = User::create([
                'username'     => $data['username'],
                'email'    => $data['email'],
                'password' => md5($data['password']),
                'group_id' => $data['group_id'],
                'status'   => isset($data['status'])? $data['status']: NULL]);
            Session::flash('message', 'Success to add an usser!');
            return Redirect::route('edit_user', $user->id);
        }
    }

    function edit($id){
        $result = User::find($id);
        $types = User_group::all();
        return view('admin.user.edit', compact('result', 'types'));
    }

    function update($id, Request $request){
        $data      = $request->all();
        $user = User::find($id);
        $rule = array('username' => 'required',
            'email' => 'required',
            'password' => 'max:255|min:6',
            );
        $name_err = $email_err = '';
        if (count(User::where('id','<>',$id)->where('username',$data['username'])->get())>0) {
            $name_err = '<span style="color:red;"> Uniqid</span>';
            $has_err = true;
        }
        if (count(User::where('id','<>',$id)->where('email',$data['email'])->get())>0) {
            $email_err = '<span style="color:red;"> Uniqid</span>';
            $has_err = true;
        }
        $validator = Validator::make($data, $rule);
        if ($validator->fails() or isset($has_err))
        {
            return redirect()->back()->withInput()->withErrors($validator)
            ->with(['name_err' => $name_err, 'email_err' => $email_err]);
        }else{
                $user->username     = $data['username'];
                $user->email    = $data['email'];
                $user->password = $data['password'] == ''?$user->password: md5($data['password']);
                $user->group_id = $data['group_id'];
                $user->status   = isset($data['status'])? $data['status']: NULL;
                $user->save();
            Session::flash('message', 'Success to update');
            return Redirect::route('admin_user');
        }
    }

    function show($id){
        $result = User::where('id',$id)->first();
        return view('admin.user.show', compact('result'));
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        Session::flash('message', 'Deleted!');
        return Redirect::back();
    }
}

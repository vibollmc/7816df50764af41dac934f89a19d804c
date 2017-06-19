<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\MessageBag;
use App\Models\Customer;
use App\Models\Customer_type;
use App\Http\Requests;
use App\Http\Controllers\AdminController;
use Validator, Session, Redirect, Hash;

class CustomerController extends AdminController
{
    function index(){
        if (isset($_GET['k']) and !is_null($_GET['k']) AND ($_GET['k'] != '')) {
            $keyword = $_GET['k'];
            if (!is_null($keyword) AND ($keyword != '')) {
                $result = Customer::where('username', 'like', "%$keyword%")->with('type')->paginate(20);
            }
        }else{
            $result = Customer::with('type')->paginate(20);
        }
        return view('admin.customer.index', compact('result'));
    }

    function create(){
        $types = Customer_type::all();
        return view('admin.customer.create', compact('types'));
    }

    function store(Request $request){
        $data      = $request->all();
        $validator = Validator::make($data, [
            'username' => 'required|unique:Customer',
            'email' => 'required|unique:Customer',
            'password' => 'required|max:255|min:6',
            'repassword' => 'required|same:password',
        ]);

        if ($validator->fails())
        {
            $errors = $validator->errors()->all();
            return redirect()->back()->withInput()->withErrors($validator);
        }else{
            $user = Customer::create([
                'username'     => $data['username'],
                'email'    => $data['email'],
                'password' => md5($data['password']),
                'type_id'  => $data['type'],
                'status'   => $data['status'] == 1? 1: 0,
                'data'     => json_encode([
                    'birth'   => $data['birth'],
                    'address' => $data['address'],
                    'phone'   => $data['phone'],
                    'sex'     => $data['sex'],
                    'job'     => $data['job']
                    ])
                ]);
            Session::flash('message', 'Success to add an usser!');
            if (isset($data['btn_save'])) return Redirect::route('edit_user', $user->id);
            if (isset($data['btn_save_exit'])) return Redirect::route('admin_user');
        }
    }

    function edit($id){
        $result = Customer::find($id);
        $types = Customer_type::all();
        return view('admin.customer.edit', compact('result', 'types'));
    }

    function update($id, Request $request){
        $data      = $request->all();
        $user = Customer::find($id);
        $rule = array('username' => 'required',
            'email' => 'required',
            'password' => 'max:255|min:6',
            );
        $name_err = $email_err = '';
        if (count(Customer::where('id','<>',$id)->where('username',$data['username'])->get())>0) {
            $name_err = '<span style="color:red;"> Đã tồn tại</span>';
            $has_err = true;
        }
        if (count(Customer::where('id','<>',$id)->where('email',$data['email'])->get())>0) {
            $email_err = '<span style="color:red;"> Đã tồn tại</span>';
            $has_err = true;
        }
        $validator = Validator::make($data, $rule);
        if ($validator->fails() or isset($has_err))
        {
            return redirect()->back()->withInput()->withErrors($validator)
            ->with(['name_err' => $name_err, 'email_err' => $email_err]);
        }else{
                $user->username = $data['username'];
                $user->email    = $data['email'];
                $user->password = $data['password'] == ''?$user->password: md5($data['password']);
                $user->type_id  = $data['type'];
                $user->status   = $data['status'] == 1? 1: NULL;
                if(isset($data['baned_to'])){
                    $dt = \Carbon\Carbon::now();
                    $dt->addDays($data['baned_to']);
                    $user->baned_to = $dt->timestamp;
                }
                if (isset($data['remove_ban']) and $data['remove_ban'] == 1) {
                    $user->baned_to = NULL;
                }
                $user->save();
            Session::flash('message', 'Success to update');
            if (isset($data['btn_save'])) return Redirect::route('edit_customer', $user->id);
            if (isset($data['btn_save_exit'])) return Redirect::route('admin_customer');
        }
    }

    function show($id){
        $result = Customer::where('id',$id)->first();
        return view('admin.customer.show', compact('result'));
    }

    function message($id){
        $result = Message::where('from_id',$id)->orWhere('to_id', $id)->with(['from', 'to'])->paginate(20);
        $box = ['title' => 'Hộp thư đến', 'type' => 'Người gửi'];
        return view('admin.customer.message', compact('result', 'box'));
    }

    function send_message($id){
        $result = Message::where('from_id',$id)->orWhere('to_id', $id)->with(['from', 'to'])->paginate(20);
        $box = ['title' => 'Tin đã gửi', 'type' => 'Người nhận'];
        return view('admin.customer.message', compact('result', 'box'));
    }

    function create_message($username){
        return view('admin.customer.create_message', compact('$username'));
    }

    public function destroy($id)
    {
        $user = Customer::find($id);
        $user->delete();

        Session::flash('message', 'Deleted!');
        return Redirect::back();
    }
}

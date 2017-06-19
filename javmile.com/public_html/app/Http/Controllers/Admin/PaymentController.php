<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\AdminController;
use App\Models\Payment;
use Cache, Redirect;

class PaymentController extends AdminController
{
    function index(Request $request){
        $result = Payment::orderBy('time_pending', 'desc')->with('customer');
        if (isset($_GET['customer_id'])) {
            $result->where('customer_id', $_GET['customer_id']);
        }
        if (isset($_GET['status'])) {
            $result->where('status', $_GET['status']);
        }
        $result = $result->paginate(30);
        $result->appends($request->all());
        return view('admin.payment.index', compact('result'));
    }

    function edit($id){
        $result = Payment::where('id', $id)->with('customer')->first();
        return view('admin.payment.edit', compact('result'));
    }

    function update_payment(Request $request, $id){
        $data = $request->all();
        $result = Payment::find($id);
        $result->status = $data['status'];
        $result->save();
        return Redirect::route('admin_payment');
    }
}

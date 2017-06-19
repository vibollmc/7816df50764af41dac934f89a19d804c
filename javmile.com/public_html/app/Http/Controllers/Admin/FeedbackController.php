<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Controllers\AdminController;
use App\Models\Customer;
use App\Models\Feedback;
use App\Models\Reporter;
use App\Models\Error;
use App\Models\Film;

use Redirect, Session;

class FeedbackController extends AdminController
{

    function reporter(){
        if (isset($_GET['k']) and !is_null($_GET['k']) AND ($_GET['k'] != '')) {
            $keyword = $_GET['k'];
            $result = Film::where('reported', '>', 0)->whereNull('fixing')->where('title', 'like', "%$keyword%")->orderBy('reported', 'desc')->paginate(20);
        }else{
            $result = Film::where('reported', '>', 0)->whereNull('fixing')->orderBy('reported', 'desc')->paginate(20);
        }
        return view('admin.feedback.reporter', compact('result'));
    }

    function pending(){
        if (isset($_GET['k']) and !is_null($_GET['k']) AND ($_GET['k'] != '')) {
            $keyword = $_GET['k'];
            $result = Film::whereNotNull('fixing')->where('title', 'like', "%$keyword%")->orderBy('reported', 'desc')->paginate(20);
        }else{
            $result = Film::whereNotNull('fixing')->orderBy('reported', 'desc')->paginate(20);
        }
        return view('admin.feedback.reporter', compact('result'));
    }

    function error(){
        $result = Error::all();
        return view('admin.feedback.error', compact('result'));
    }

    function update_error(Request $request){
        $data = $request->all();
        if(strlen($data['title']) > 0){
            if(isset($data['id'])){
                $result = Error::where('id', $data['id'])->update(['title' => $data['title']]);
            }else{
                $result = Error::insert(['title' => $data['title']]);
            }
            Session::flash('message', 'Success');
        }
        return Redirect::back();
    }

    public function show_report($id) {
        $result = Film::where('id', $id)->with('reports')->first();
        if(count($result->reports) == 0){
            $result->reported = 0;
            $result->fixing = NULL;
            $result->save();
            return Redirect::route('admin_reporter');
        }
        $errors = Error::all();
        return view('admin.feedback.show', compact('result', 'errors'));
    }

    function report_spam($able_id, $error_id){
        $result = Reporter::where(['able_id' => $able_id, 'error_id' => $error_id])->get();
        $ids = $result->pluck('customer_id')->toArray();
        $users = Customer::whereIn('id', $ids)->get();
        foreach ($users as $key => $user) {
            $user->spamed = $user->spamed+1;
            $user->save();
        }
        foreach ($result as $key => $value) {
            $value->delete();
        }
        return Redirect::back();
    }

    function change_report(){
        $result = Film::find($_GET['id']);
        if (is_null($result->fixing)) {
            $result->fixing = 1;
        }else{
            $result->fixing = NULL;
            $result->reported = 0;
        }
        $result->save();
        echo json_encode(['status' => true]);
    }
}
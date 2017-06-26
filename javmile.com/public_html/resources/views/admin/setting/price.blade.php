@extends('admin.master')

@section('content')
<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Settings</li>
    <li><a href="javascript:void(0)">Price</a></li>
</ul>
<!-- END Table Styles Header -->
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<form action="{{route('update_price')}}" method="post" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="row">
    <div class="col-md-6">
        <div class="block">
            <div class="block-title">
                <h2><strong>Cài đặt thành viên kiếm tiền</strong></h2>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">Số tiền cho 1000 view</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="data" value="{{$result->data}}">
                    <span class="input-group-addon">VND</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group form-actions">
            <div class="text-center">
                <input type="submit" class="btn btn-info" value="Save">
                <input type="reset" class="btn btn-danger" value="Reset">
            </div>
        </div>
    </div>
</div>
</form>
@stop
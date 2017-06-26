@extends('admin.master')

@section('content')
<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Users</li>
    <li><a href="javascript:void(0)">Update</a></li>
</ul>
<!-- END Table Styles Header -->
@if (Session::has('message'))
<p class="alert alert-success">{{ Session::get('message') }}</p>
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Edit user</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <form action="{{ route('update_customer',$result->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                <div class="block">
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">User name</label>
                        <div class="col-md-9">
                            <input type="text" id="example-text-input" name="username" class="form-control" placeholder="" value="<?php echo old('username') != NULL? old('username'): $result->username; ?>">
                            <span class="help-block">{!! $errors->first('username') !!}<?php echo (session('name_err'))? session('name_err'): ''; ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Email</label>
                        <div class="col-md-9">
                            <input type="email" id="example-email-input" name="email" value="<?php echo old('email') != NULL? old('email'): $result->email; ?>" class="form-control" placeholder="">
                            <span class="help-block">{!! $errors->first('email') !!}<?php echo (session('email_err'))? session('email_err'):''; ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-password-input">Password</label>
                        <div class="col-md-9">
                            <input type="password" id="example-password-input" name="password" class="form-control" placeholder="">
                            <span class="help-block">{!! $errors->first('password') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-password-input">Password confirm</label>
                        <div class="col-md-9">
                            <input type="password" id="example-password-input" name="repassword" class="form-control" placeholder="">
                            <span class="help-block">{!! $errors->first('repassword') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-select">Type</label>
                        <div class="col-md-9">
                            <select id="example-select" name="type" class="form-control" size="1">
                                @foreach($types as $type)
                                <option value="{{ $type->id }}" <?php echo ($result->type_id == $type->id or old('type')== $type->id)? 'selected':''; ?>>{!! $type->title !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-multiple-select">Status</label>
                        <div class="col-md-9">
                            <label class="switch switch-primary" for="val_terms">
                                <input type="checkbox" <?php echo ($result->status == 1 or old('status')==1)? 'checked':''; ?> id="val_terms" name="status" value="1">
                                <span data-toggle="tooltip" title="" data-original-title="ON/OFF"></span>
                            </label>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                </div>
                <!-- END Basic Form Elements Block -->
            </div>
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                <div class="block">
                    <!-- Basic Form Elements Content -->
                    @if($result->baned_to > time())
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Nick bị ban đến:</label>
                        <div class="col-md-9">
                            <span class="help-block">{{date('d-m-Y', $result->baned_to)}}</span>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Ban nick</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" name="baned_to" class="form-control" placeholder="">
                                <span class="input-group-addon">ngày</span>
                            </div>
                            <span class="help-block">Số ngày ban tính từ thời điểm hiện tại, không cộng dồn.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Bỏ ban nick</label>
                        <div class="col-md-9">
                            <label class="radio-inline" for="example-inline-radio2">
                                <input type="radio" id="example-inline-radio2" name="remove_ban" value="1"> Đồng ý
                            </label>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                </div>
                <!-- END Basic Form Elements Block -->
            </div>
            <div class="form-group form-actions">
                <div class="col-md-9 col-md-offset-4">
                    <input type="submit" name="btn_save_exit" class="btn btn-primary" value="Save & Exit">
                    <input type="submit" name="btn_save" class="btn btn-info" value="Save">
                    <input type="reset" class="btn btn-danger" value="Reset">
                </div>
            </div>
        </div>
    </form>
</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/formsValidation.js"></script>
<script>$(function(){ FormsValidation.init(); });</script>
@stop
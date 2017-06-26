@extends('admin.master')

@section('content')
<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Users</li>
    <li><a href="javascript:void(0)">Add</a></li>
</ul>
<!-- END Table Styles Header -->

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Add new</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <form action="{{ route('store_user') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                <div class="block">
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">User name</label>
                        <div class="col-md-9">
                            <input type="text" id="example-text-input" name="username" class="form-control" placeholder="" value="{{ old('name') }}">
                            <span class="help-block">{!! $errors->first('username') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Email</label>
                        <div class="col-md-9">
                            <input type="email" id="example-email-input" name="email" value="{{ old('email') }}" class="form-control" placeholder="">
                            <span class="help-block">{!! $errors->first('email') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-password-input">Password</label>
                        <div class="col-md-9">
                            <input type="password" id="example-password-input" name="password" class="form-control" placeholder="more than 6 char">
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
                            <select id="example-select" name="group_id" class="form-control" size="1">
                                @foreach($types as $key => $type)
                                <option value="{{ $type->id}}">{!! $type->title !!}</option>
                                @endforeach
                            </select>
                            <span class="help-block">{!! $errors->first('group_id') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-multiple-select">Status</label>
                        <div class="col-md-9">
                            <label class="switch switch-primary" for="val_terms">
                                <input type="checkbox" <?php echo (old('status')==null)? '':'checked'; ?> id="val_terms" name="status" value="1">
                                <span data-toggle="tooltip" title="" data-original-title="ON/OFF"></span>
                            </label>
                            <span class="help-block">{!! $errors->first('status') !!}</span>
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
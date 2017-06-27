@extends('admin.master')

@section('content')
<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Customer</li>
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
    <form action="{{ route('store_customer') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                <div class="block">
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">User name</label>
                        <div class="col-md-9">
                            <input type="text" id="example-text-input" name="username" class="form-control" placeholder="" value="{{ old('username') }}">
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
                            <select id="example-select" name="type_id" class="form-control" size="1">
                                @foreach($types as $key => $type)
                                <option value="{{ $type->id}}">{!! $type->title !!}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-multiple-select">Status</label>
                        <div class="col-md-9">
                            <label class="switch switch-primary" for="val_terms">
                                <input type="checkbox" <?php echo (old('status')==null)? '':'checked'; ?> id="val_terms" name="status" value="1">
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
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Full name</label>
                        <div class="col-md-9">
                            <input type="text" id="example-datepicker3" name="full_name" class="form-control" value="{{old('full_name')}}">
                            <span class="help-block">{!! $errors->first('full_name') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Birth</label>
                        <div class="col-md-9">
                            <input type="text" id="example-datepicker3" name="data[birth]" value="{{old('data')['birth']}}" class="form-control input-datepicker" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy">
                            <span class="help-block">{!! $errors->first('data[birth]') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Address</label>
                        <div class="col-md-9">
                            <input type="text" name="data[address]" class="form-control" placeholder="" value="{{old('data')['address']}}">
                            <span class="help-block">{!! $errors->first('address') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-select">Phone</label>
                        <div class="col-md-9">
                            <input type="text" name="phone" class="form-control" value="{{old('phone')}}">
                            <span class="help-block">{!! $errors->first('phone') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-select">Sex</label>
                        <div class="col-md-9">
                            <select id="example-select" name="data[sex]" class="form-control" size="1">
                                <option value="0">Select</option>
                                <option value="male" <?php echo old('data')['sex']=='male'? 'selected': ''; ?>>Male</option>
                                <option value="female" <?php echo old('data')['sex']=='female'? 'selected': ''; ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Job</label>
                        <div class="col-md-9">
                            <input type="text" name="data[job]" class="form-control" value="{{old('data')['job']}}">
                            <span class="help-block">{!! $errors->first('job') !!}</span>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                </div>
                <!-- END Basic Form Elements Block -->
            </div>
            <div class="form-group form-actions">
                <div class="col-md-9 col-md-offset-4">
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
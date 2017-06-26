@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Setting</li>
    <li><a href="javascript:void(0)">Interface</a></li>
</ul>
<!-- END Table Styles Header -->

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Edit</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <form action="{{ route('update_interface') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-6">
                <div class="block">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label" for="example-text-input">Header Logo</label>
                        </div>
                        <div class="col-md-6">
                            <input type="file" accept=".png" class="form-control" name="logo" >
                            <span class="help-block"></span>
                        </div>
                        <div class="col-md-6">
                            <img style="background: #EAEDF1;" class="img-responsive" src="{{asset('themes/client/img/logo.png')}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label class="control-label" for="example-text-input">Footer Logo</label>
                        </div>
                        <div class="col-md-6">
                            <input type="file" accept=".png" class="form-control" name="footer_logo">
                            <span class="help-block"></span>
                        </div>
                        <div class="col-md-6">
                            <img style="background: #EAEDF1;" class="img-responsive" src="{{asset('themes/client/img/footer-logo.png')}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group form-actions text-center">
                    <input type="submit" class="btn btn-info" value="Save">
                    <input type="reset" class="btn btn-danger" value="Reset">
                </div>
            </div>
        </div>
    </form>
</div>
<!-- END Table Styles Block -->
@stop
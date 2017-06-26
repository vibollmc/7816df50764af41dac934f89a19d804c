@extends('admin.master')

@section('content')
<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Settings</li>
    <li><a href="javascript:void(0)">Social</a></li>
</ul>
<!-- END Table Styles Header -->
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<form action="{{route('update_social')}}" method="post" enctype="multipart/form-data">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="row">
    <div class="col-md-6">
        <div class="block">
            <div class="block-title">
                <h2><strong>Facebook</strong></h2>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">Title</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="facebook[title]" value="{{isset(json_decode($facebook->data)->title)? json_decode($facebook->data)->title: ''}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">Url</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="facebook[link]" value="{{isset(json_decode($facebook->data)->link)? json_decode($facebook->data)->link: ''}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">App ID</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="facebook[app_id]" value="{{isset(json_decode($facebook->data)->app_id)? json_decode($facebook->data)->app_id: ''}}">
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">App Secret</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="facebook[app_secret]" value="{{isset(json_decode($facebook->data)->app_secret)? json_decode($facebook->data)->app_secret: ''}}">
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">Site URL</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="facebook[site_url]" value="{{isset(json_decode($facebook->data)->site_url)? json_decode($facebook->data)->site_url: ''}}">
                    <span class="help-block"><i>Your_domaint</i>/social/facebook</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="block">
            <div class="block-title">
                <h2><strong>Google</strong></h2>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">Title</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="google[title]" value="{{isset(json_decode($google->data)->title)? json_decode($google->data)->title: ''}}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">Url</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="google[link]" value="{{isset(json_decode($google->data)->link)? json_decode($google->data)->link: ''}}" placeholder="your Google+ url">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">Client ID</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="google[app_id]" value="{{isset(json_decode($google->data)->app_id)? json_decode($google->data)->app_id: ''}}">
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">Client Secret</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="google[app_secret]" value="{{isset(json_decode($google->data)->app_secret)? json_decode($google->data)->app_secret: ''}}">
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3" for="example-text-input">Site URL</label>
                <div class="input-group col-md-9">
                    <input class="form-control" name="google[site_url]" value="{{isset(json_decode($google->data)->site_url)? json_decode($google->data)->site_url: ''}}">
                    <span class="help-block"><i>Your_domaint</i>/social/google</span>
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
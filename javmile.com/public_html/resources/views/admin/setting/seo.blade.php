@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Setting</li>
    <li><a href="javascript:void(0)">Home page Seo</a></li>
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
    <form action="{{ route('update_seo') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-6">
                <div class="block">
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">Title</label>
                        <input class="form-control" name="title" value="{{$result->title}}">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">Keyword</label>
                        <input class="form-control" name="keyword" value="{{$result->keyword}}">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">author</label>
                        <input class="form-control" name="author" value="{{$result->author}}">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">Description</label>
                        <div>
                            <textarea name="description" style="height: 100px; width: 100%;">{{$result->description}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="block">
                    <div class="form-group">
                        <div class="col-md-9">
                            <label class="control-label" for="example-text-input">image_src</label>
                            <input class="form-control" name="image_src" value=" {{$result->image_src}} ">
                        </div>
                        <div class="col-md-3">
                            <label class="control-label" for="example-text-input">Image type</label>
                            <select name="image_type" class="form-control select-chosen">
                                <option value="image/jpeg" <?php echo $result->image_type == 'image/jpeg'? 'selected': ''; ?>>jpeg</option>
                                <option value="image/png" <?php echo $result->image_type == 'image/png'? 'selected': ''; ?>>png</option>
                                <option value="image/bmp" <?php echo $result->image_type == 'image/bmp'? 'selected': ''; ?>>bmp</option>
                                <option value="image/gif" <?php echo $result->image_type == 'image/gif'? 'selected': ''; ?>>gif</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">shortcut icon</label>
                        <img width="50" class="img-responsive" src="{{asset('favicon.ico')}}">
                        <input type="file" accept=".ico" class="form-control" name="icon">
                        <span class="help-block">file type must be icon type (.ico)</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">og:type</label>
                        <input class="form-control" name="og_type" value=" {{$result->og_type}} ">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">facebook:app_id</label>
                        <input class="form-control" name="fb_app_id" value=" {{$result->fb_app_id}} ">
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
@extends('client.master')
@section('content')

@include('client.layouts.froala_editor_style')
@include('client.layouts.froala_editor_js')
<div class="content-header main-content">
    <div class="header-section">
        <div id="auth-content">
            @include('client.auth.sidebar')
            <div id="auth-container">
                <div class="auth-title">Sửa tập {{$result->title}}</div>
                <div class="thumbnail-list auth-container">
                    <div class="col-md-8">
                        <form action="{{route('member_update_film_ep', $result->id)}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="help-block">
                                <ul>
                                    <li>Tên tập phim bộ dạng số 1, 2, 3 ... hoặc 1.1, 1.2, 1.3</li>
                                    <li>chỉ hỗ trợ link video google drive</li>
                                </ul>
                            </div>
                            <div class="text-danger">{{\Session::has('message')? \Session::get('message'): ''}}</div>
                            <div class="form-group main-list">
                                <label class="col-md-3 control-label" for="example-text-input">Tên tập</label>
                                <div class="col-md-9">
                                    <input type="text" name="title" class="form-control" value="{{$result->title}}">
                                    <span class="help-block alert-warning text-danger">{{ $errors->first('title[]') }}</span>
                                </div>
                                <label class="col-md-3 control-label" for="example-select">Loại</label>
                                <div class="col-md-9">
                                    <select name="type" class="form-control" data-placeholder="Choose a category..">
                                        <option {{$result->type == 'Full'? 'selected': ''}} value="Full">Vietsub</option>
                                        <option {{$result->type == 'ThuyetMinh'? 'selected': ''}} value="ThuyetMinh">Thuyết Minh</option>
                                        <option {{$result->type == 'raw'? 'selected': ''}} value="raw">NoSub</option>
                                    </select>
                                    <span class="help-block alert-warning text-danger">{{ $errors->first('type[]') }}</span>
                                </div>
                                <label class="col-md-3 control-label" for="example-text-input">Liên kết (link)</label>
                                <div class="col-md-9">
                                    <input type="text" name="link" class="form-control" value="{{$result->file_name}}">
                                    <span class="help-block alert-warning text-danger">{{ $errors->first('ep_link') }}</span>
                                </div>
                                <div class="clearfix"></div>
                                <p><hr/></p>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group form-actions text-center">
                                <input type="submit" name="btn_save" class="btn btn-primary" value="Hoàn tất">
                                <input type="reset" class="btn btn-danger" value="Làm lại">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop

@extends('admin.master')

@section('content')

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Sửa tập phim <b>{{$result->film->title.' - '.$result->film->title_en}}</b></h2>
    </div>
    <!-- END Table Styles Title -->
    @if(\Session::has('message'))
    <div class="alert alert-danger">{{\Session::get('message')}}</div>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                @if(Session::has('error_server'))
                @foreach (Session::get('error_server') as $error)
                    <li>{{ $error }}</li>
                @endforeach
                @endif
            </ul>
        </div>
    @endif
    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="clearfix"></div>
    <p>
        Lưu ý: tập bình thường ghi số chẵn 1 2 3 ...
        <br>
        tập chia nhỏ dạng: 5.1 5.2 5.3 ....
    </p>
    <form action="{{ route('update_film_ep', $result->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="block">
            <div>
                <!-- Search Info - Pagination -->
                <div class="block-section clearfix">
                </div>
                <!-- END Search Info - Pagination -->

                <!-- Projects Results -->
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Tên tập</label>
                        <div class="col-md-9">
                            <input type="text" name="title" class="form-control" placeholder="" value="{{$result->title}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Liên kết</label>
                        <div class="col-md-9">
                            <input type="url" name="file_name" value="{{$result->file_name}}" class="form-control" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Phụ đề</label>
                        <div class="col-md-9">
                            <span class="help-block remove-sub">{{$result->sub_vi}}</span>
                            <input type="checkbox" name="remove_sub" value="1"> không dùng phụ đề
                            <input type="file" name="sub_vi" class="form-control" placeholder="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Chọn loại</label>
                        <div class="col-md-9">
                            <div class="radio">
                                <label for="example-radio1">
                                    <input type="radio" id="example-radio1" name="type" {{$result->type == 'Full'? 'checked': ''}} value="Full"> Việt sub full
                                </label>
                            </div>
                            <div class="radio">
                                <label for="example-radio2">
                                    <input type="radio" id="example-radio2" name="type" {{$result->type == 'Part'? 'checked': ''}} value="Part"> Chia nhỏ
                                </label>
                            </div>
                            <div class="radio">
                                <label for="example-radio3">
                                    <input type="radio" id="example-radio3" name="type" {{$result->type == 'ThuyetMinh'? 'checked': ''}} value="ThuyetMinh"> Thuyết minh
                                </label>
                            </div>
                            <div class="radio">
                                <label for="example-radio4">
                                    <input type="radio" id="example-radio4" name="type" {{$result->type == 'trailer'? 'checked': ''}} value="trailer"> Trailer
                                </label>
                            </div>
                            <div class="radio">
                                <label for="example-radio5">
                                    <input type="radio" id="example-radio5" name="type" {{$result->type == 'raw'? 'checked': ''}} value="raw"> Nosub
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Projects Results -->
                <div class="clearfix"></div>
                <!-- Bottom Navigation -->
                <div class="block-section">
                    <div class="form-group form-actions text-center">
                        <input type="submit" name="submit" class="btn btn-primary" value="Hoàn tất">
                        <input type="reset" class="btn btn-danger" value="Làm lại">
                    </div>
                </div>
                <!-- END Bottom Navigation -->
            </div>
        </div>
    </form>
</div>
<!-- END Table Styles Block -->
@stop
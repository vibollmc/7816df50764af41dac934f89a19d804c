@extends('client.master')
@section('content')

@include('client.layouts.froala_editor_style')
@include('client.layouts.froala_editor_js')
<div class="content-header main-content">
    <div class="header-section">
        <div id="auth-content">
            @include('client.auth.sidebar')
            <div id="auth-container">
                <div class="auth-title">Get list file trong folder google drive</div>
                <div class="thumbnail-list auth-container">
                    <div>
                        <div class="text-danger">{{\Session::has('message')? \Session::get('message'): ''}}</div>
                        <form action="{{route('post_drive_tool')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <th>
                                <span class="help-block">Nhập link folder Google Drive: ví dụ: <b>https://drive.google.com/drive/u/0/folders/0B-ZQhg2W2-oxMVdwRy1peF9abcd</b> Và folder <b>Tối đa 50 files</b></span>
                                <input type="text" name="folder" class="form-control " placeholder="{{ $errors->has('folder') ? $errors->first('folder') : 'Enter folder url' }}">
                            </th>
                            <th >
                                <span class="help-block">Số bắt đầu trước danh sách file (hỗ trợ cho thêm tập phim nhanh)</span>
                                <input type="text" name="num" value="1" />
                            </th>
                            <th class="text-center">
                                <div class="btn-group btn-group-xs">
                                    <button type="submit" class="btn btn-primary">ADD</button>
                                </div>
                            </th>
                        </form>
                        @if(isset($files))
                        <div class="block">
                            <div class="block-title">
                                Danh sách từng link
                            </div>
                            <div class="table-responsive">
                                <table id="general-table" class="table table-striped table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>tên file</th>
                                            <th>link</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($files)>0)
                                            @foreach($files as $key => $item)
                                                <tr>
                                                    <td><input class="form-control" value="{{$item['name']}}"></td>
                                                    <td><input class="form-control" value="{{$item['link']}}"></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop

@extends('client.master')
@section('content')

@include('client.layouts.froala_editor_style')
@include('client.layouts.froala_editor_js')
<div class="content-header main-content">
    <div class="header-section">
        <div id="auth-content">
            @include('client.auth.sidebar')
            <div id="auth-container">
                <div class="auth-title">Danh sách tập phim lỗi</div>
                <div class="thumbnail-list auth-container">
                    <div class="text-success">{{\Session::has('message')? \Session::get('message'): ''}}</div>
                    <div class="table-responsive">
                        <table class="table table-vcenter table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 80px;" class="text-center"><input type="checkbox"></th>
                                    <th>Tập</th>
                                    <th>Link</th>
                                    <th>Loại</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Chức năng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($result->count() > 0)
                                    @foreach($result->sortByDesc('title') as $key => $item)
                                    <tr>
                                        <td class="text-center"><input type="checkbox" id="id[{{$key}}]" name="id[{{$key}}]" value="{{$item->id}}"></td>
                                        <td>{{$item->title}}</td>
                                        <td>{{$item->file_name}}</td>
                                        <td>{{$item->type}}</td>
                                        <td>{!!$item->status == 2? '<span class="label label-danger">Die</span>': '<span class="label label-success">Live</span>'!!}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{route('member_edit_film_ep', $item->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-success">Sửa tập</a>
                                            </div>
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{route('member_delete_ep', $item->id)}}" onclick="return confirm('Press Ok to confirm!');" data-toggle="tooltip" title="Delete" class="btn btn-danger">Xóa tập</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="6"><h3>Chưa có tập phim</h3></td>
                                </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6">
                                        <div class="btn-group btn-group-sm">
                                            <button type="submit" class="btn btn-warning">Xóa Tập đã chọn</button>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop

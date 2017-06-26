@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Danh sách tập phim <strong>{{$film->title.' - '.$film->title_en}}</strong></h2>
        <div class="block-options pull-right">
            <a href="{{route('add_film_ep', $film->id)}}" class="btn btn-success">Thêm tập</a>
        </div>
    </div>
    <!-- END Table Styles Title -->
    <!-- Table Styles Content -->
    <form action="{{ route('multi_delete_ep', $film->id) }}" method="post" enctype="multipart/form-data">
        {!!csrf_field()!!}
        <div class="btn-group btn-group-sm pull-left">
            <button type="submit" class="btn btn-warning">Xóa tập đã chọn</button>
        </div>
        <div class="clearfix"></div>
        <!-- Responsive Full Content -->
        <p>
        @if(\Session::has('message'))
        {{\Session::get('message')}}
        @endif
        </p>

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
                                    <a href="{{route('edit_film_ep', $item->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-success">Sửa tập</a>
                                </div>
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('delete_ep', $item->id)}}" onclick="return confirm('Press Ok to confirm!');" data-toggle="tooltip" title="Delete" class="btn btn-danger">Xóa tập</a>
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
        <!-- END Responsive Full Content -->
    </form>
</div>
<!-- Load and execute javascript code used only in this page -->
<script src="{{asset('themes/admin/js/pages/tablesGeneral.js')}}"></script>
<script>
$(function(){
    TablesGeneral.init();
});
$('#searchSubmit').on('click', function(){
    $('#search-film').submit();
});
</script>
@stop
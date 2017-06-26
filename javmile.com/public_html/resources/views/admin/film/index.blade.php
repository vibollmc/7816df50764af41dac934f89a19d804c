@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>{{isset($_GET['filter'])? 'Phim chưa chuyển link Google drive': 'Danh sách phim'}}</h2>
        <div class="block-options pull-right">
            <a href="{{route('create_film')}}" class="btn btn-primary">Thêm mới</a>
        </div>
    </div>
    <!-- END Table Styles Title -->
    <div class="no-padding">
        <form name="search" action="{{\URL::current()}}" method="get" enctype="multipart/form-data" class="form-horizontal">
            <div id="example-datatable_filter" class="dataTables_filter">
                <div class="input-group">
                    <input type="search" name="k" value="<?php echo isset($_GET['k'])? $_GET['k']:''; ?>" class="form-control" placeholder="Search" aria-controls="example-datatable">
                    <span class="input-group-addon btn" id="searchSubmit"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </form>
    </div>
    <!-- Table Styles Content -->
    <form action="{{ route('multi_delete_film') }}" method="post" enctype="multipart/form-data">
        {!!csrf_field()!!}
        <div class="btn-group btn-group-sm pull-left">
            <button type="submit" class="btn btn-warning">Xóa phim đã chọn</button>
        </div>
        <div class="dataTables_paginate paging_bootstrap pull-right"> {!! $result->render() !!} </div>
        <div class="clearfix"></div>
        <!-- Responsive Full Content -->

        <div class="table-responsive">
            <table class="table table-vcenter table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="text-center"><input type="checkbox"></th>
                        <th style="width: 120px;">Ảnh</th>
                        <th>Thông tin</th>
                        <th class="text-center">Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @if($result->count() > 0)
                        @foreach($result as $key => $item)
                        <?php
                            if (isset($_GET['filter'])) {
                                $value = $item->film;
                            }else{
                                $value = $item;
                            }
                            $image_data = json_decode($value->image_server->data);
                            $image_prefix = $image_data->public_url.'/'.$image_data->dir;
                        ?>
                        <tr>
                            <td class="text-center"><input type="checkbox" id="id[{{$key}}]" name="id[{{$key}}]" value="{{$value->id}}"></td>
                            <td><img src="{{ $image_prefix.$value->thumb_name }}" class="img-responsive"></td>
                            <td>
                                <a href="{{route('film_detail', ['category' => $value->category->slug, 'slug' => $value->slug])}}">{{$value->title.' - '. $value->title_en}}</a><br/>
                                Loại phim: {{is_null($value->customer)? 'admin post': 'member post'}}<br/>
                                Tập mới nhất: {{$value->category_id == 2? $value->exist_episodes.'/'.$value->episodes: $value->exist_episodes}}<br/>
                                Trạng thái: {!!$value->online == 1? '<span class="label label-success">Online</span>': '<span class="label label-warning">Ẩn</span>'!!}<br/>
                                Người đăng: {{is_null($value->user)? '': $value->user->username}}{{is_null($value->customer)? '': $value->customer->username}}{{(is_null($value->user) and is_null($value->customer))? 'get auto': ''}}<br/>
                                Lượt xem: {{$value->viewed}}
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('edit_film', $value->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-default">Sửa phim</a>
                                </div>
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('episode', $value->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-primary">Tập phim</a>
                                </div>
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('add_film_ep', $value->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-success">Thêm tập</a>
                                </div>
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('delete_film', $value->id)}}" onclick="return confirm('Press Ok to confirm!');" data-toggle="tooltip" title="Delete" class="btn btn-danger">Xóa phim</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="6"><h3>Empty data</h3></td>
                    </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6">
                            <div class="btn-group btn-group-sm">
                                <button type="submit" class="btn btn-warning">Xóa phim đã chọn</button>
                            </div>
                            <div class="dataTables_paginate paging_bootstrap pull-right"> {!! $result->render() !!} </div>
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
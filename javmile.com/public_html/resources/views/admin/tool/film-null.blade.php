@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Film is null episode</h2>
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
    <form action="" method="post" enctype="multipart/form-data">
        {!!csrf_field()!!}
        <span class="label label-success">{{count($result)}}</span>/<span class="label label-default">{{$count}}</span> / <span class="label label-info">{{$current}}</span>
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
                            $image_data = json_decode($item->image_server->data);
                            $image_prefix = $image_data->public_url.'/'.$image_data->dir;
                        ?>
                        <tr>
                            <td class="text-center"><input type="checkbox" id="id[{{$key}}]" name="id[{{$key}}]" value="{{$item->id}}"></td>
                            <td><img src="{{ $image_prefix.$item->thumb_name }}" class="img-responsive"></td>
                            <td>
                                <b>{{strlen($item->title_search) > 0? $item->title_search: $item->title}}{{' - '.$item->title_en}}</b><br/>
                                Năm: <b>{{$item->date}}</b><br/>
                                Ngày update: <b>{{date('d-m-Y', strtotime($item->updated_at))}}</b>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('edit_film', $item->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-default">Sửa phim</a>
                                </div>
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('episode', $item->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-primary">Tập phim</a>
                                </div>
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('add_film_ep', $item->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-success">Thêm tập</a>
                                </div>
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('delete_film', $item->id)}}" onclick="return confirm('Press Ok to confirm!');" data-toggle="tooltip" title="Delete" class="btn btn-danger">Xóa phim</a>
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
    $('[name="search"]').submit();
});
</script>
@stop
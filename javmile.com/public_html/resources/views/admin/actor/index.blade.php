@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Actors</h2>
    </div>
    <div class="table-options clearfix">
    <div class="row">
        <form action="{{ route('admin_actor') }}" method="get" enctype="multipart/form-data" class="form-horizontal">
        <div class="col-sm-6 col-xs-7">
            <div id="example-datatable_filter" class="dataTables_filter">
                <label>
                    <div class="input-group">
                        <input type="search" name="k" value="<?php echo isset($_GET['k'])? $_GET['k']:''; ?>" class="form-control" placeholder="Enter Search">
                        <span class="input-group-addon btn" id="searchSubmit"><i class="fa fa-search"></i></span>
                    </div>
                </label>
            </div>
        </div>
        </form>
    </div>
    </div>
    <!-- END Table Styles Title -->
    <div class="table-responsive">
        <table id="general-table" class="table table-striped table-vcenter">
            <thead>
                <tr>
                    {{-- <th style="width: 80px;" class="text-center"><input type="checkbox"></th> --}}
                    <th style="width: 80px;" class="text-center">ID</th>
                    <th style="width: 80px;">Avatar</th>
                    <th>Name</th>
                    <th style="width: 150px;" >Is hot</th>
                    <th style="width: 150px;" >Status</th>
                    <th style="width: 150px;" class="text-center">Action</th>
                </tr>
            </thead>
            @if(isset($result))
            <tbody>
                    @foreach($result as $key => $item)
                    <?php
                        if (!is_null($item->ftp_id)) {
                            $image_data = json_decode($item->image_server->data);
                            $image_prefix = $image_data->public_url.'/'.$image_data->dir;
                        }

                     ?>
                    <tr>
                        <td class="text-center"> {{ $item->id }} </td>
                        <td class="text-center"><img src="{{is_null($item->thumb_name)? '':$image_prefix.$item->thumb_name}}" class="img-responsive"></td>
                        <td><a href="{{ route('edit_actor',$item->id) }}">{{ $item->title }}</a></td>
                        <td><a href="javascript:void(0)" data-key="{{$item->id}}" class="change-status label label-<?php echo is_null($item->hot)? 'default': 'primary'; ?>"><?php echo is_null($item->hot)? 'OFF': 'ON'; ?></a></td>
                        <td><a href="javascript:void(0)" class="label label-default"><?php echo is_null($item->thumb_name)? 'null profile': 'updated'; ?></a></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">
                                <a href="{{ route('edit_actor',$item->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
                                <a onclick="return confirm('Click OK to confirm delete.');" href="{{ route('delete_actor', $item->id) }}" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-times"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="pull-right">
                            <div class="dataTables_paginate paging_bootstrap"> {!! $result->render() !!} </div>
                        </div>
                        <div class="btn-group btn-group-sm">
                            {{-- <a href="javascript:void(0)" class="btn btn-primary" data-toggle="tooltip" title="Edit Selected"><i class="fa fa-pencil"></i></a> --}}
                            {{-- <a href="javascript:void(0)" class="btn btn-primary" data-toggle="tooltip" title="Xóa lựa chọn"><i class="fa fa-times"></i></a> --}}
                        </div>
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    <!-- END Table Styles Content -->
</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script>
$(document).ready(function(){
    $('body').on('click', '#searchSubmit', function(){
        $('form').submit();
    });
    $('body').on('click', '.change-status', function(e){
        e.preventDefault();
        var i = $(this);
        var id = $(this).attr('data-key');
        $.ajax({
            url: '{{ route('update_actor_status') }}',
            type: 'POST',
            data: {id: id, _token: '{{ csrf_token() }}' },
            dataType: 'json',
            success: function(responseJSON){
                if (responseJSON.status) {
                    var html = '<a href="javascript:void(0)" data-key="'+id+'"';
                    if (responseJSON.value == 1) {
                        html += 'class="change-status label label-primary">ON</a>';
                    }else{
                        html += 'class="change-status label label-default">OFF</a>';
                    }
                    i.parent().html(html);
                }
            },
            error: function(err){},
        });
    });
});
</script>
@stop
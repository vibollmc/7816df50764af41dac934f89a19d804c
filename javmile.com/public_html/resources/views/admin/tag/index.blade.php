@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Tags</h2>
    </div>
    <div class="table-options clearfix">
    <div class="row">
        <form name="tag-search" action="{{ route('admin_tag') }}" method="get" enctype="multipart/form-data" class="form-horizontal">
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
                    <th class="text-center">ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            @if(isset($result))
            <tbody>
                    @foreach($result as $key => $item)
                    <tr>
                        <td class="text-center"> {{ $item->id }} </td>
                        <td><a href="{{ route('edit_tag',$item->id) }}">{{ $item->title }}</a></td>
                        <td><a href="javascript:void(0)" class="label label-default"><?php echo $item->status == 1? 'updated': ''; ?></a></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">
                                <a href="{{ route('edit_tag',$item->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
                                <a onclick="return confirm('Click OK to confirm delete.');" href="{{ route('delete_tag', $item->id) }}" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-times"></i></a>
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
        $('[name="tag-search"]').submit();
    });
});
</script>
@stop
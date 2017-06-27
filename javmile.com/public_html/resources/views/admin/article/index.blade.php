@extends('admin.master')

@section('content')
<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Articles</li>
    <li><a href="javascript:void(0)">List</a></li>
</ul>
<!-- END Table Styles Header -->
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Table</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix">
    <div class="row">
        <div class="col-sm-6 col-xs-5">
            <div class="dataTables_length" id="example-datatable_length">
                <div class="btn-group btn-group-sm pull-left">
                    <a href="{{ route('create_article') }}" class="btn btn-primary">New article</a>
                </div>
            </div>
        </div>
        <form action="{{ route('admin_article') }}" method="get" enctype="multipart/form-data" class="form-horizontal">
        <div class="col-sm-6 col-xs-7">
            <div id="example-datatable_filter" class="dataTables_filter">
                <label>
                    <div class="input-group">
                        <input type="search" name="k" value="<?php echo isset($_GET['k'])? $_GET['k']:''; ?>" class="form-control" placeholder="Search" aria-controls="example-datatable">
                        <span class="input-group-addon btn" id="searchSubmit"><i class="fa fa-search"></i></span>
                    </div>
                </label>
            </div>
        </div>
        </form>
    </div>
    </div>
    <div class="table-responsive">
        <table id="general-table" class="table table-striped table-vcenter">
            <thead>
                <tr>
                    {{-- <th style="width: 80px;" class="text-center"><input type="checkbox"></th> --}}
                    <th style="width: 80px;" class="text-center">ID</th>
                    <th style="width: 120px;" class="text-center">Thumb</th>
                    <th>Title</th>
                    <th style="width: 150px;">Status</th>
                    <th style="width: 120px;" class="text-center">Action</th>
                </tr>
            </thead>
            @if(isset($result))
            <tbody>
                    @foreach($result as $key => $item)
                    <tr>
                        <td class="text-center"> {{ $item->id }} </td>
                        <td class="text-center"><img src="{{ $item->cover['link'] }}" class="img-responsive"></td>
                        <td><a href="javascript:void(0)">{{ $item->title }}</a></td>
                        <td><a href="javascript:void(0)" data-key="{{ $item->id }}" <?php echo $item->status == 1? 'class="change-status label label-success">ON': 'class="change-status label label-warning">OFF' ?></a></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">
                                <a href="{{ route('edit_article',$item->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
                                <a href="{{ route('delete_article',$item->id) }}" onclick="return confirm('Chắc chắn xóa?');" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-times"></i></a>
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
<script src="{{asset('themes/admin/js/pages/tablesGeneral.js')}}"></script>
<script>
$(function(){
    TablesGeneral.init();
});
$(document).ready(function(){
    $('#searchSubmit').on('click', function(){
        $('form').submit();
    });

    $('body').on('click', '.change-status', function(e){
        e.preventDefault();
        var i = $(this);
        var id = $(this).attr('data-key');
        $.ajax({
            url: '{{ route('article_change_status') }}',
            type: 'POST',
            data: {id: id, _token: '{{ csrf_token() }}' },
            dataType: 'json',
            success: function(responseJSON){
                if (responseJSON.status) {
                    var html = '<a href="javascript:void(0)" data-key="'+id+'"';
                    if (responseJSON.value == 1) {
                        html += 'class="change-status label label-success">ON</a>';
                    }else{
                        html += 'class="change-status label label-warning">OFF</a>';
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
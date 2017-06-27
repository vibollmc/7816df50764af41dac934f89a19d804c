@extends('admin.master')

@section('content')
<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Feedback</li>
    <li><a href="javascript:void(0)">Reported</a></li>
</ul>
<!-- END Table Styles Header -->
@if (Session::has('message'))
<p class="alert alert-success">{{ Session::get('message') }}</p>
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Danh sách lỗi</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix">
    <div class="row">
        <form id="reporter-search" action="{{ route('admin_reporter') }}" method="get" enctype="multipart/form-data" class="form-horizontal">
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
                    <th style="width: 80px;" class="text-center">#</th>
                    <th>Title</th>
                    <th>Reported</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            @if(count($result) > 0)
                    @foreach($result as $key => $item)
                    <tr>
                        <td class="text-center">{{ $key+1 }}</td>
                        <td><a href="{{route('show_report', $item->id)}}">{{ $item->title }}</a></td>
                        <td>{{ $item->reported }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">
                                <a href="{{route('show_report', $item->id)}}" class="btn btn-info">Chi tiết</a>
                                @if(is_null($item->fixing))
                                    <a id="{{$item->id}}" href="javascript:void(0)" class="btn btn-success btn-edit">Xác nhận</a>
                                @else
                                    <a id="{{$item->id}}" href="javascript:void(0)" class="btn btn-primary btn-edit">Xong</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <div class="pull-right">
                            <div class="dataTables_paginate paging_bootstrap"> {!! $result->render() !!} </div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- END Table Styles Content -->
</div>
<script>
    $("#searchSubmit").click(function(){
        $("#reporter-search").submit();
    });
    $(document).ready(function(){
        $('body').on('click', '.btn-edit', function(){
            var i = $(this);
            $.ajax({
                type: 'GET',
                url: '{{route('change_report')}}',
                data: {_token: '{{ csrf_token() }}', id: i.attr('id')},
                success: function(res){
                    i.parents('tr').remove();
                },
                error: function(err){
                    console.log(err);
                },
            });
        });
    });
</script>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
@stop
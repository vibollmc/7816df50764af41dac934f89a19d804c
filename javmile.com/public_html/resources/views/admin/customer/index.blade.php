@extends('admin.master')

@section('content')
<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Customers</li>
</ul>
<!-- END Table Styles Header -->
@if (Session::has('message'))
<p class="alert alert-success">{{ Session::get('message') }}</p>
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2><?php echo isset($_GET['k'])? 'Search result for "'.$_GET['k'].'"': 'List users'; ?></h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix">
    <div class="row">
        <form id="searchUser" action="{{ route('admin_customer') }}" method="get" enctype="multipart/form-data" class="form-horizontal">
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
                    <th class="text-center">ID</th>
                    <th style="width: 80px;" class="text-center">Avatar</th>
                    <th>User name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Baned</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            @if($result->count()>0)
            <tbody>
                    @foreach($result as $key => $item)
                    <tr>
                        <td class="text-center"> {{ $item->id }} </td>
                        <td class="text-center"><img src="{{ $item->avatar['link'] }}" class="img-responsive"></td>
                        <td><a href="javascript:void(0)">{{ $item->username }}</a></td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->type['title'] }}</td>
                        <td><a href="javascript:void(0)" class="label <?php echo $item->status == 1? 'label-success': 'label-default'; ?>"><?php echo $item->status == 1? 'ON': 'OFF'; ?> </a></td>
                        <td>{{$item->baned_to > time()? date('d-m-Y', $item->baned_to): ''}}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">
                                <a href="{{ route('edit_customer',$item->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
                                <a href="{{ route('delete_customer', $item->id) }}" data-toggle="tooltip" title="Xóa" class="btn btn-danger"><i class="fa fa-times"></i></a>
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
<script>
    $("#searchSubmit").click(function(){
        $("#searchUser").submit();
    });
</script>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script src="{{asset('themes/admin/js/pages/tablesGeneral.js')}}"></script>
<script>
$(function(){
    TablesGeneral.init();

});
</script>
@stop
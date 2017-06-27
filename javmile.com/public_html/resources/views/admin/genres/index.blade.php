@extends('admin.master')

@section('content')
<!-- END Table Styles Header -->
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Genres</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix">
    <div class="row">
        <div class="col-sm-6 col-xs-5">
            <div class="dataTables_length" id="example-datatable_length">
                <div class="btn-group btn-group-sm pull-left">
                    <a href="{{ route('create_genres') }}" class="btn btn-primary">Create genre</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="table-responsive">
        <table id="general-table" class="table table-striped table-vcenter">
            <thead>
                <tr>
                    {{-- <th style="width: 80px;" class="text-center"><input type="checkbox"></th> --}}
                    <th style="width: 80px;" class="text-center">ID</th>
                    <th>Title</th>
                    <th style="width: 150px;" >Status</th>
                    <th style="width: 150px;" >Menu</th>
                    <th style="width: 150px;" class="text-center">Action</th>
                </tr>
            </thead>
            @if(isset($result))
            <tbody>
                    @foreach($result as $key => $item)
                    <tr>
                        <td class="text-center"> {{ $item->id }} </td>
                        <td><a href="{{ route('edit_genres',$item->id) }}">{{ $item->title }}</a></td>
                        <td><a href="javascript:void(0)" class="label <?php echo $item->status == 1? 'label-success">ON': 'label-warning">OFF'; ?></a></td>
                        <td><a href="{{route('change_genres', $item->id)}}" class="label <?php echo $item->menu == 1? 'label-success">ON': 'label-warning">OFF'; ?></a></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">
                                <a href="{{ route('edit_genres',$item->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>
                                <a onclick="return confirm('Click OK to confirm delete.');" href="{{ route('delete_genres', $item->id) }}" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-times"></i></a>
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
</script>
@stop
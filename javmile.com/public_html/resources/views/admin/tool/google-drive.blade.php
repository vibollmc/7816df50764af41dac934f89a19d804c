@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Google drive</h2>
    </div>
    <div class="table-options clearfix">
    <div class="row">
        <form action="{{\URL::current()}}" method="get" enctype="multipart/form-data" class="form-horizontal">
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
                <form action="{{route('update_drive')}}" method="POST">
                    {!!csrf_field()!!}
                    <th style="width: 80px;" class="text-center"></th>
                    <th>
                        <span class="help-block">Nhập ID folder Google Drive: ví dụ: <b>https://drive.google.com/drive/u/0/folders/0B-ZQhg2W2-oxMVdwRy1peF9abcd</b>  thì <b>0B-ZQhg2W2-oxMVdwRy1peF9abcd</b> là ID</span>
                        <input type="text" name="folder_id" class="form-control " placeholder="{{ $errors->has('folder_id') ? $errors->first('folder_id') : 'Enter folder ID' }}">
                    </th>
                    <th >
                        <span class="help-block">Số bắt đầu trước danh sách file (hỗ trợ cho thêm tập phim nhanh)</span>
                        <input type="text" name="num" value="1" />
                    </th>
                    <th class="text-center">
                        <div class="btn-group btn-group-xs">
                            <button type="submit" class="btn btn-primary">ADD</button>
                        </div>
                    </th>
                </form>
                </tr>
            </thead>
            <tbody>
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
});
</script>
@stop
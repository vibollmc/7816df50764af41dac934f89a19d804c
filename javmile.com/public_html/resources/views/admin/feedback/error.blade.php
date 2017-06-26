@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Error</h2>
    </div>
    <!-- END Table Styles Title -->
    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <div class="table-responsive">
        <table id="general-table" class="table table-striped table-vcenter table-hover">
            <thead>
                <tr>
                    {{-- <th style="width: 80px;" class="text-center"><input type="checkbox"></th> --}}
                    <th>ID</th>
                    <th>Title</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
            @if(count($result) > 0)
                    @foreach($result as $key => $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{ $item->title }}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">
                                <a id="{{$item->id}}" val="{{$item->title}}" href="javascript:void(0)" class="btn btn-success btn-edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a key="{{route('delete_error', $item->id)}}" href="javascript:void(0)" class="btn btn-danger feedback-change" onclick="return confirm('Click OK to confirm delete.');" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
            @endif
            </tbody>
        </table>
    </div>
    <div class="table-options clearfix"></div>
    <form action="{{ route('update_error') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-md-3 control-label input-title">Title</label>
                    <div class="col-md-9">
                        <input type="text" id="title" name="title" class="form-control" placeholder="" value="{{ old('title') }}">
                        <span class="help-block text-danger">{!! $errors->first('title') !!}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary btn-submit">ADD</button>
            </div>
    </form>
</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script type="text/javascript">
$(document).ready(function(){
    $('body').on('click', '.btn-edit', function(){
        $('.input-title').text('ID '+$(this).attr('id'));
        $('.btn-submit').text('Save');
        $('#title').val($(this).attr('val'));
        $('#title').before('<input type="hidden" name="id" value="'+$(this).attr('id')+'" />');
        $(this).remove();
        $('#title').trigger('focus');
    });
})
</script>
@stop
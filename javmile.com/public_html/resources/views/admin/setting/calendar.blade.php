@extends('admin.master')

@section('content')
<script src="{{asset('themes/admin/js/fileuploader.js')}}"></script>
<!-- Table Styles Header -->
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- END Table Styles Header -->

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Calendar</h2>
    </div>
    <!-- END Table Styles Title -->
    <form action="{{ route('update_calendar') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <table id="general-table" class="table table-striped table-vcenter table-hover">
            <thead>
                <tr>
                    {{-- <th style="width: 80px;" class="text-center"><input type="checkbox"></th> --}}
                    <th class="text-center">#</th>
                    <th class="text-center">Film</th>
                    <th class="text-center">Link</th>
                    <th class="text-center">Time</th>
                    <th class="text-center">tool</th>
                </tr>
            </thead>
            <tbody id="link-group">
            @if(!is_null($result->data))
                @foreach(json_decode($result->data) as $key => $item)
                <tr>
                    <td class="text-center"> {{ $key+1 }} </td>
                    <td>
                        <input name="data[{{$key}}][title]" value="{{$item->title}}" class="form-control">
                    </td>
                    <td class="text-center">
                        <input name="data[{{$key}}][url]" value="{{$item->url or ''}}" class="form-control">
                    </td>
                    <td class="text-center">
                        <input name="data[{{$key}}][time]" value="{{$item->time}}" class="form-control">
                    </td>
                    <td class="text-center">
                        <a href="javascript:void(0);" class="remove-link">
                            <i class="fa fa-times text-danger"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            @endif
                <tr class="hide html-adding">
                    <td class="text-center"> new </td>
                    <td>
                        <input type="text" name="data[{{isset($key)? $key +1: 0 }}][title]" class="form-control">
                    </td>
                    <td class="text-center">
                        <input type="text" name="data[{{isset($key)? $key +1: 0 }}][url]" class="form-control">
                    </td>
                    <td class="text-center">
                        <input type="text" name="data[{{isset($key)? $key +1: 0 }}][time]" class="form-control">
                    </td>
                    <td class="text-center">
                        <a href="javascript:void(0);" class="remove-link">
                            <i class="fa fa-times text-danger"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="text-right" colspan="5">
                        <button type="button" class="btn btn-primary" id="add-link">Add film</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    <div class="clearfix"></div>
    <div class="form-actions text-center">
        <input type="submit" class="btn btn-info" value="Save">
        <input type="reset" class="btn btn-danger" value="Reset">
    </div>
    </form>
</div>
<script>
$(document).ready(function(){
    $('body').on('click', '#add-link', function(){
        $('.html-adding').removeClass('hide');
    });
    $('body').on('click', '.remove-link', function(){
        $(this).parent().parent().remove();
    });
});
</script>
@stop
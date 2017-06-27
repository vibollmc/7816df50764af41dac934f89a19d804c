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
    </div>
    <!-- END Table Styles Title -->
    <div class="table-responsive">
        <table id="general-table" class="table table-striped table-vcenter">
            <thead>
                <tr>
                    <th>name</th>
                    <th>link</th>
                </tr>
            </thead>
            <tbody>
                @if(count($files)>0)
                    @foreach($files as $key => $item)
                        <tr>
                            <td><input class="form-control" value="{{$item['name']}}"></td>
                            <td><input class="form-control" value="{{$item['link']}}"></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <!-- END Table Styles Content -->
    <div class="clearfix">
        @if(count($files)>0)
            @foreach($files as $key => $item)
                {{$num++}}|{{$item['link']}}<br/>
            @endforeach
        @endif
    </div>
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
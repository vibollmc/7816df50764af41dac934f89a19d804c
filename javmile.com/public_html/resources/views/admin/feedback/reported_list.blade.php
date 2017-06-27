@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Feed back list</h2>
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
                    <th><i class="fa fa-clock-o"></i> started</th>
                    <th>Problem</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th class="text-center">Reported</th>
                    <th>status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            @if(isset($result))
            <tbody>
                    @foreach($result as $key => $item)
                    <tr>
                        <td><?php $dt = \Carbon\Carbon::parse($item->created_at); echo $dt->diffForHumans(); ?></td>
                        <td><a href="{{ route('show_feedback',$item->id) }}">{{ $item->title }}</a></td>
                        <td>{{ $item->type }}</td>
                        <td title="{{ $item->film->title}}" class="text-info">
                            <?php
                                if(strlen($item->film->title) > 50 and strpos($item->film->title, ' ', 50) > 0){
                                    echo substr($item->film->title, 0, strpos($item->film->title, ' ', 50)) . '...';
                                }else{
                                    echo $item->film->title;
                                }
                            ?>
                        </td>
                        <td class="text-center">{{$item->reported}}</td>
                        <td>{{$item->status}}</td>
                        <td class="text-center">
                            <div class="btn-group btn-group-xs">
                                @if($item->status == 'pending')
                                <a key="{{route('change_feedback_fixed', $item->id)}}" href="javascript:void(0)" class="btn btn-default feedback-change" data-toggle="tooltip" title="Fixed"><i class="fa fa-check text-success"></i></a>
                                @endif
                                @if($item->status == 'fixed')
                                <a key="{{route('change_feedback_pending', $item->id)}}" href="javascript:void(0)" class="btn btn-default feedback-change" data-toggle="tooltip" title="Pending"><i class="gi gi-refresh text-success"></i></a>
                                @endif
                                @if($item->status != 'spam')
                                <a key="{{route('spam_feedback_status', $item->id)}}" href="javascript:void(0)" class="btn btn-default feedback-change" data-toggle="tooltip" title="Spam"><i class="gi gi-warning_sign text-warning"></i></a>
                                @else
                                <a key="{{route('change_feedback_fixed', $item->id)}}" href="javascript:void(0)" class="btn btn-default feedback-change" data-toggle="tooltip" title="Fixed"><i class="fa fa-check text-success"></i></a>
                                <a key="{{route('change_feedback_pending', $item->id)}}" href="javascript:void(0)" class="btn btn-default feedback-change" data-toggle="tooltip" title="Pending"><i class="gi gi-refresh text-success"></i></a>
                                @endif
                                <a key="{{route('delete_feedback', $item->id)}}" href="javascript:void(0)" class="btn btn-danger feedback-change" onclick="return confirm('Click OK to confirm delete.');" data-toggle="tooltip" title="Delete">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
            @endif
        </table>
        @if(isset($result))
        <div class="pull-right">
            <div class="dataTables_paginate paging_bootstrap"> {!! $result->render() !!} </div>
        </div>
        @endif
    </div>
    <!-- END Table Styles Content -->
</div>
<div>
    <form id="sort-form" action="{{route('admin_feedback')}}" method="get" accept-charset="utf-8">
        <input type="hidden" name="sort" value="">
    </form>
</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script src="{{asset('themes/admin/js/pages/tablesGeneral.js')}}"></script>
<script>
$(function(){
    TablesGeneral.init();
});
$(document).ready(function(){
    $('body').on('click', '.feedback-change', function(){
        var i = $(this);
        var url = $(this).attr('key');
        $.ajax({
            url: url,
            type: 'GET',
            success: function(){
                window.location.reload(true);
            },
            error: function(err){
            },
        });
    });
});
</script>
@stop
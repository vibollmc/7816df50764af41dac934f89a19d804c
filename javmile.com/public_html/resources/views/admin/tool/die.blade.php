@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Died episodes</h2>
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
                    <th>Film</th>
                    <th>User</th>
                    <th>Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if(count($result)>0)
                    @foreach($result as $key => $item)
                        <tr>
                            <td>{{$item->title}} | {{$item->type}}</td>
                            <td>{{$item->file_name}}</td>
                            <td>{{$item->film->title or 'Phim đã xóa'}}<br>{{$item->film->title_en or ''}}</td>
                            <td>{{$item->user->username or 'admin'}}</td>
                            <td>{{$item->created_at}}<br><span class="text-danger">{{$item->updated_at}}</span></td>
                            <td>
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('edit_film_ep', $item->id)}}" data-toggle="tooltip" title="Edit" class="btn btn-success">Sửa tập</a>
                                </div>
                                <div class="btn-group btn-group-xs">
                                    <a href="{{route('delete_ep', $item->id)}}" onclick="return confirm('Press Ok to confirm!');" data-toggle="tooltip" title="Delete" class="btn btn-danger">Xóa tập</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <!-- END Table Styles Content -->
    <div class="clearfix">
        <div class="dataTables_paginate paging_bootstrap pull-right"> {!! $result->render() !!} </div>
    </div>
</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
@stop
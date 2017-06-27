@extends('client.master')
@section('content')
<?php
    if (isset($_GET['active'])) {
        $fill = $_GET['active'] == 1? 'Đã đọc': 'Chưa đọc';
    }else{
        $fill = 'Tất cả';
    }
 ?>
<div class="content-header main-content">
    <div class="header-section">
        <div id="auth-content">
            @include('client.auth.sidebar')
            <div id="auth-container">
                <div class="auth-title">
                {{MetaTag::get('title')}}
                <div class="btn-group pull-right">
                    <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle btn-option" aria-expanded="false">{{$fill}}<span class="caret"></span></a>
                    <ul class="dropdown-menu text-right">
                        <li><a href="{{route('user_notifi')}}">Tất cả</a></li>
                        <li><a href="{{route('user_notifi', ['active' => 0])}}">Chưa đọc</a></li>
                        <li><a href="{{route('user_notifi', ['active' => 1])}}">Đã đọc</a></li>
                    </ul>
                </div>
                </div>
                <div class="thumbnail-list auth-container">
                    @if(count($result)> 0)
                        @if(isset($_GET['id']))
                        <strong>{{date('d/m/Y H:i:s', strtotime($result->created_at))}} : </strong>
                        <div>
                            {!!$result->content!!}
                        </div>
                        <a href="{{route('user_notifi')}}">Quay lại danh sách..</a>
                        @else
                        <ul class="message-list">
                            @foreach($result as $key => $item)
                            <li class="message-item {{$item->status == 1? 'active': ''}}">
                                <a href="{{route('user_notifi', ['id' => $item->id])}}"><strong>{{date('d/m/Y H:i:s', strtotime($item->created_at))}} </strong> {{$item->content}}</a>
                            </li>
                            @endforeach
                        </ul>
                        <div class="pull-right">
                            <div class="dataTables_paginate paging_bootstrap"> {!! $result->render() !!} </div>
                        </div>
                        @endif
                    @else
                        <h3>Không có thông báo nào</h3>
                    @endif
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop
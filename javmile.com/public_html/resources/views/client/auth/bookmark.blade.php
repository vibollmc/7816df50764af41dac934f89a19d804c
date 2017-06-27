@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div id="auth-content">
            @include('client.auth.sidebar')
            <div id="auth-container">
                <div class="auth-title">
                {{isset($title)? $title: 'Thông tin cá nhân'}}
                </div>
                <div class="thumbnail-list auth-container">
                    @if(count($result) > 0)
                        @foreach($result as $key => $value)
                            <?php $item = $value->film; ?>
                            @include('client.block.film-item')
                        @endforeach
                        <div class="clearfix"></div>
                        <div class="pull-right">
                            <div class="dataTables_paginate paging_bootstrap"> {!! $result->render() !!} </div>
                        </div>
                    @else
                        <h3 class="text-center help-block">Chưa có phim nào trong bộ sưu tập</h3>
                    @endif
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop

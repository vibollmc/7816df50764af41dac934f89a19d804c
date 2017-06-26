@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div class="col-md-9">
            <h4 class="sub-header">
                <strong>Bộ sưu tập</strong>
            </h4>
            <div class="thumbnail-list">
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
        <div class="col-md-3 hidden-sm">
            @include('client.block.trailer')
            @include('client.block.popular')
            @include('client.block.action')
            @include('client.block.thiller')
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop
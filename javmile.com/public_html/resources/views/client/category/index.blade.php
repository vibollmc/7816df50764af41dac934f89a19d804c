@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div class="col-md-9">
            <h4 class="sub-header">
                <strong>{{$result['title']}}</strong>
            </h4>
            <div class="thumbnail-list">
                @foreach($result['result'] as $key => $item)
                    @include('client.block.film-item')
                @endforeach
                <div class="clearfix"></div>
                <div class="pull-right">
                    <div class="dataTables_paginate paging_bootstrap"> {!! $result['pagination'] !!} </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-3 hidden-sm hidden-sm">
            @include('client.block.popular')
            @include('client.block.action')
            @include('client.block.actor')
            @include('client.block.thiller')
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop
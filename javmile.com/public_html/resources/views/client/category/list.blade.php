@extends('client.master')
@section('content')
<div class="main-content">
    <div class="">
        <div class="col-md-9">
            <h4 class="sub-header">
                <strong>{{$breadcrumb[0]['title']}}</strong>
            </h4>
            <div class="thumbnail-list">
                @if($result->count() > 0)
                    @foreach($result as $key => $item)
                        <div class="film-item-responsive thumbnail-item">
                            <a href="{{route($route, $item->slug)}}" class="label label-success">{{$item->title}}</a>
                        </div>
                    @endforeach
                @else
                    <p><h3>Chưa có dữ liệu.</h3></p>
                @endif
            </div>
        </div>
        <div class="col-md-3 hidden-sm">
            @include('client.block.trailer')
            @include('client.block.popular')
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop
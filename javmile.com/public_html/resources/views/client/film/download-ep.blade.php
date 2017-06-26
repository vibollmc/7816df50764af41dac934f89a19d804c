@extends('client.master')
@section('content')
<div class="main-content">
    <div class="">
        <h4 class="sub-header text-center">
            <strong>Download {{$result->title}} tập {{$episode->title}} server {{$types[$type]}}</strong>
        </h4>
        <div class="thumbnail-list col-sm-6 col-md-offset-5 col-sm-offset-5 col-xs-offset-4">
            <div class=""></div>
            @if(!is_null($episode))
                @foreach($link as $key => $item)
                    <div class="episode-download">
                        <a href="{{$item}}" class="btn btn-warning">Download {{$key}}p</a>
                    </div>
                @endforeach
            @else
                <p><h3>{{$message}}</h3></p>
            @endif
        </div>
        <div class="clearfix"></div>
        <h4 class="sub-header text-center">
            <strong>Download các tập server {{$types[$type]}}</strong>
        </h4>
        @if($episodes->count() > 0)
        <div class="thumbnail-list col-sm-6 col-md-offset-5 col-sm-offset-5 col-xs-offset-4">
            @foreach($episodes as $key => $item)
                <div class="episode-download">
                    <a href="{{route('download_ep', ['id' => $item->id, 'slug' => $result->slug, 'type' => $type])}}" class="btn btn-success {{$item->id == $episode->id? 'active': ''}}">Download tập {{$item->title}}</a>
                </div>
            @endforeach
        </div>
        @else
            <p><h3>Chưa có dữ liệu.</h3></p>
        @endif
        <div class="clearfix"></div>
    </div>
</div>
@stop
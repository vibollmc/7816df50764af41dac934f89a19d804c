@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div class="col-md-9">
            <h4 class="sub-header">
                Porn star <strong>{{$result->title}}</strong>
            </h4>
			<div class="clearfix"></div>
			<center>
<!-- BEGIN EroAdvertising ADSPACE CODE -->
<script type="text/javascript" language="javascript" charset="utf-8" src="//adspaces.ero-advertising.com/adspace/3558995.js"></script>
<!-- END EroAdvertising ADSPACE CODE -->
			</center>
			<div class="clearfix"></div>
            <div class="movie-detail-info">
                <div class="detail-info">
                    <div class="col-md-3">
                        <div class="detail-movie-thumbail">
                            <img src="{{$image_prefix.$result->thumb_name}}" alt="{{$result->slug}}" class="img-responsive">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="title-line">
                            <label>{{$result->fullname}}</label>
                        </div>
                        <div class="film-info-line">
                            <span class="sub">DOB:</span><label>{{$result->birth}}</label>
                        </div>
                        <div class="film-info-line">
                            <span class="sub">Height:</span><label>{{is_null($result->height)? 'NA': $result->height}}</label>
                        </div>
                        <div class="film-info-line">
                            <span class="sub">Hometown:</span>
                            <label>{{$result->home_town}}</label>
                        </div>
                        <div class="film-info-line">
                            {!!$result->story!!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <h4 class="sub-header">
                <strong>In movies</strong>
            </h4>
            <div class="thumbnail-list">
                @foreach($result->films as $key => $item)
                    @include('client.block.film-item')
                @endforeach
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-3 hidden-sm">
            @include('client.block.actor')
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop
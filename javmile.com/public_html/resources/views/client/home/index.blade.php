@extends('client.master')
@section('content')

<div class="main-content">
    <div class="">
        <div class="col-md-9">
            <h4 class="sub-header">
            <a href="{{route('category', $recent_series->first()->category->slug)}}">
                <strong>DVD</strong>
                <i class="gi gi-play"></i>
                <a href="{{route('category', $recent_series->first()->category->slug)}}" class="pull-right more"> view more ></a>
            </a>
            </h4>
            <div class="thumbnail-list">
                @foreach($recent_series as $key => $item)
                    @include('client.block.film-item')
                @endforeach
            </div>
            <div class="clearfix"></div>
			<center class="hidden-md hidden-lg">
				<!--adxxx 300x100-->
				<div id="SC_TBlock_672355" class="SC_TBlock">loading...</div>
				<!--end adxxx 300x100-->
			</center>
			<center class="hidden-xs hidden-sm">
				<!--adxxx 728x90-->
				<div id="SC_TBlock_672696" class="SC_TBlock">loading...</div>
				<!--end adxxx 728x90-->
			</center>
			<div class="clearfix"></div>
            <h4 class="sub-header">
                 <a href="{{route('category', $recent_movie->first()->category->slug)}}">
                    <strong>Video</strong>
                    <i class="gi gi-play"></i>
                </a>
                <a href="{{route('category', $recent_movie->first()->category->slug)}}" class="pull-right more"> view more ></a>
            </h4>
            <div class="thumbnail-list">
                <div class="hidden-md hidden-lg">
                @foreach($recent_movie as $key => $item)
                    @if($key < 12)
                    @include('client.block.film-item')
                    @endif
                @endforeach
                </div>
                <div class="hidden-sm hidden-xs">
                    @include('client.block.home-movie-block')
                </div>
            </div>
            <div class="clearfix"></div>
			<center class="hidden-md hidden-lg">
				<!--adxxx 300x100-->
				<div id="SC_TBlock_672702" class="SC_TBlock">loading...</div>
				<!--end adxxx 300x100-->
			</center>
			<center class="hidden-xs hidden-sm">
				<!--adxxx 728x90-->
				<div id="SC_TBlock_672701" class="SC_TBlock">loading...</div>
				<!--end adxxx 728x90-->
			</center>
			<div class="clearfix"></div>
            <h4 class="sub-header">
                <a href="{{route('genre', $gameshow_block['genre']->slug)}}">
                    <strong>Random</strong>
                    <i class="gi gi-play text-success"></i>
                </a>
                <a href="{{route('genre', $gameshow_block['genre']->slug)}}" class="pull-right more"> view more ></a>
            </h4>
            <div class="thumbnail-list">
                @foreach($gameshow_block['films'] as $key => $item)
                    @include('client.block.film-item')
                @endforeach
            </div>
            <div class="clearfix"></div>
            <h4 class="sub-header">
                <a href="{{route('advance_fill', ['member' => 1])}}">
                    <strong>Suggestions</strong>
                    <i class="gi gi-play text-success"></i>
                </a>
                <a href="{{route('advance_fill', ['member' => 1])}}" class="pull-right more"> view more ></a>
            </h4>
            <div class="thumbnail-list">
                @foreach($member_films as $key => $item)
                    @include('client.block.film-item')
                @endforeach
            </div>
        </div>
        <div class="col-md-3 hidden-sm hidden-xs">
            @include('client.block.popular')
            @include('client.block.action')
            @include('client.block.actor')
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!--adxxx 300x100-->
<script type="text/javascript">var SC_CId="672355",SC_Domain="n.adxxx.info";SC_Start_672355=(new Date).getTime();</script>
<script type="text/javascript" src="//st.adxxx.info/js/adv_out.js"></script>

<script type="text/javascript">var SC_CId = "672702",SC_Domain="n.adxxx.info";SC_Start_672702=(new Date).getTime();</script>
<script type="text/javascript" src="//st.adxxx.info/js/adv_out.js"></script>
<!--end adxxx 300x100-->
<!--adxxx 728x90-->
<script type="text/javascript">var SC_CId="672696",SC_Domain="n.adxxx.info";SC_Start_672696=(new Date).getTime();</script>
<script type="text/javascript" src="//st.adxxx.info/js/adv_out.js"></script>

<script type="text/javascript">var SC_CId = "672701",SC_Domain="n.adxxx.info";SC_Start_672701=(new Date).getTime();</script>
<script type="text/javascript" src="//st.adxxx.info/js/adv_out.js"></script>
<!--end adxxx 728x90-->
@stop

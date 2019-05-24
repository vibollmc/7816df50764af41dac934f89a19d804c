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
			<center>
				<!--
				The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
				In case you use the code multiple times then be sure that every id is unique!
				-->
				<div id="ea_5112237_node">&nbsp;</div>

				<script type="text/javascript" language="javascript" charset="utf-8">
				/*
				code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
				if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
				*/ 

				if (typeof eaCtrl =="undefined"){ 
					var eaCtrlRecs=[];
					var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
					var js = document.createElement('script');
					js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=5112237");
					document.head.appendChild(js);
				}
				/*
				End load eactrl 

				Command eaCtrl to load ads
				*/
				eaCtrl.add({"display":"ea_5112237_node","sid":5112237,"plugin":"banner","traffic_type":"all","subid":""});
				</script>
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
			<center>
				<!--
				The div tag below is used to insert the advertisement, the id value match with param "display" in the Javascript code
				In case you use the code multiple times then be sure that every id is unique!
				-->
				<div id="ea_5112239_node">&nbsp;</div>

				<script type="text/javascript" language="javascript" charset="utf-8">
				/*
				code below check and load eaCtrl, its recommended to load this with a normal javascript tag in the top of your page to speedup
				if you have Eactrl installed then you dont need this part, also if you use multiple codes than only the first one need this
				*/ 

				if (typeof eaCtrl =="undefined"){ 
					var eaCtrlRecs=[];
					var eaCtrl = {add:function(ag){eaCtrlRecs.push(ag)}};
					var js = document.createElement('script');
					js.setAttribute("src","//go.ero-advertising.com/loadeactrl.go?pid=103546&siteid=936737&spaceid=5112239");
					document.head.appendChild(js);
				}
				/*
				End load eactrl 

				Command eaCtrl to load ads
				*/
				eaCtrl.add({"display":"ea_5112239_node","sid":5112239,"plugin":"banner","traffic_type":"all","subid":""});
				</script>
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
@stop

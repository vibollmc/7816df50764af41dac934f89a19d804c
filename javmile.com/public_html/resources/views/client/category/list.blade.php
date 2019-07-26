@extends('client.master')
@section('content')
<div class="main-content">
    <div class="">
        <div class="col-md-9">
            <h4 class="sub-header">
                <strong>{{$breadcrumb[0]['title']}}</strong>
            </h4>
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
            <div class="thumbnail-list">
                @if($result->count() > 0)
                    @foreach($result as $key => $item)
                        <div class="film-item-responsive thumbnail-item">
                            <a href="{{route($route, $item->slug)}}" class="label label-success">{{$item->title}}</a>
                        </div>
                    @endforeach
                @else
                    <p><h3>No data.</h3></p>
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
<!--adxxx 300x100-->
<script type="text/javascript">var SC_CId="672355",SC_Domain="n.adxxx.info";SC_Start_672355=(new Date).getTime();</script>
<script type="text/javascript" src="//st.adxxx.info/js/adv_out.js"></script>
<!--end adxxx 300x100-->
<!--adxxx 728x90-->
<script type="text/javascript">var SC_CId="672696",SC_Domain="n.adxxx.info";SC_Start_672696=(new Date).getTime();</script>
<script type="text/javascript" src="//st.adxxx.info/js/adv_out.js"></script>
<!--end adxxx 728x90-->
@stop
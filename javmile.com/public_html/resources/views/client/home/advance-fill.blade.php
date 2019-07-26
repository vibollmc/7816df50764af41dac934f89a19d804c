@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div class="col-md-9">
            <h4 class="sub-header">
                <strong>Filter</strong>
            </h4>
            <div class="thumbnail-list">
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
                @foreach($result as $key => $item)
                    @include('client.block.film-item')
                @endforeach
				<div class="clearfix"></div>
                <div class="pull-right">
                    <div class="dataTables_paginate paging_bootstrap"> {!! $result->render() !!} </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="col-md-3 hidden-sm hidden-xs">
            @include('client.block.popular')
            @include('client.block.actor')
            <?php $adnow = 1; ?>
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
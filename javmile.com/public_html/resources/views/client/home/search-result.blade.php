@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div class="col-md-9">
            <h4 class="sub-header">
                <strong>Search results</strong>
                <div class="pull-right sort">
                    <small>
                        <ul class="nav nav-tabs">
                            <li class="">
                                <?php
                                    $name = 'sort';
                                    $sort = isset($_GET['sort'])? $_GET['sort']: NULL;
                                 ?>
                                <form id="form-sort" action="{{\URL::current()}}" method="get" class="form-inline" accept-charset="utf-8">
                                    <span>Order by: </span>
                                    <select name="{{$name}}" class="" size="1">
                                        @if($name=='sort')
                                        <option class="enter-sort" value="order-desc" <?php echo $sort == 'order-desc'? 'selected': ''; ?>>Public date</option>
                                        <option class="enter-sort" value="viewed-desc" <?php echo $sort == 'viewed-desc'? 'selected': ''; ?>>Most view</option>
                                        <option class="enter-sort" value="title_ascii-asc" <?php echo $sort == 'title-asc'? 'selected': ''; ?>>Title - A-Z</option>
                                        <option class="enter-sort" value="title_ascii-desc" <?php echo $sort == 'title-desc'? 'selected': ''; ?>>Title - Z-A</option>
                                        @endif
                                    </select>
                                    <?php unset($uri['page']); ?>
                                    @foreach($uri as $k => $array)
                                        @if(is_array($array))
                                            @foreach($array as $key => $item)
                                                <input type="hidden" name="{{$k}}[{{$key}}]" value="{{$item}}">
                                            @endforeach
                                        @else
                                            <input type="hidden" name="{{$k}}" value="{{$array}}">
                                        @endif
                                    @endforeach
                                </form>
                            </li>
                        </ul>
                    </small>
                </div>
            </h4>
            <div class="thumbnail-list">
				<div>
                    <center>
<!-- BEGIN EroAdvertising ADSPACE CODE -->
<script type="text/javascript" language="javascript" charset="utf-8" src="//adspaces.ero-advertising.com/adspace/3558982.js"></script>
<!-- END EroAdvertising ADSPACE CODE -->
                    </center>
                </div>
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
        <div class="col-md-3 hidden-sm">
            @include('client.block.popular')
            @include('client.block.actor')
            <?php $adnow = 1; ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
@stop
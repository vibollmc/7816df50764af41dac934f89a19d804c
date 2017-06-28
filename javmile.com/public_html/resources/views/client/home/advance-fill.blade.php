@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div class="col-md-9">
            <h4 class="sub-header">
                <strong>Filter</strong>
            </h4>
            <div class="thumbnail-list">
                @foreach($result as $key => $item)
                    @include('client.block.film-item')
                @endforeach
                <div class="clearfix"></div>

                <div>
                    <center>
                <script type="text/javascript">
                var ad_idzone = "2673528",
                    ad_width = "468",
                    ad_height = "60";
                </script>
                <script type="text/javascript" src="https://ads.exoclick.com/ads.js"></script>
                <noscript><a href="https://main.exoclick.com/img-click.php?idzone=2673528" target="_blank"><img src="https://syndication.exoclick.com/ads-iframe-display.php?idzone=2673528&output=img&type=468x60"></a></noscript>
                    </center>
                </div>
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
@stop
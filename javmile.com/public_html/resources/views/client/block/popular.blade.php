<h4 class="sub-header">
    <a href="{{route('advance_fill', ['sort'=>'viewed-desc'])}}">
        <strong>Popular</strong>
        <i class="gi gi-play"></i>
    </a>
</h4>
<div class="thumbnail-list">
    <div class="clearfix"></div>
    <center>
    <script type="text/javascript">
    var ad_idzone = "2727038",
        ad_width = "250",
        ad_height = "250";
    </script>
    <script type="text/javascript" src="https://ads.exosrv.com/ads.js"></script>
    <noscript><a href="https://main.exosrv.com/img-click.php?idzone=2727038" target="_blank"><img src="https://syndication.exosrv.com/ads-iframe-display.php?idzone=2727038&output=img&type=250x250"></a></noscript>
    </center>
    <hr/>
    <div class="clearfix"></div>    
    @foreach($popular as $key => $item)
        @include('client.block.film-block-item')
    @endforeach
</div>
<div class="clearfix"></div>
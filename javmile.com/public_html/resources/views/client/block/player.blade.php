<div class="container-overlay hide">
    <div class="pull-right light-on">
        <a href="javascript:void(0)"><i class="gi gi-lightbulb"></i> Light On(Esc)</a>
    </div>
    
</div>
<script src="{{ asset('themes/client/js/jwplayer-7.2.2/jwplayer.js') }}" type="text/javascript"></script>
<script type="text/javascript">jwplayer.key = "dWwDdbLI0ul1clbtlw+4/UHPxlYmLoE9Ii9QEw==";</script>
<div class="cover_wraper {{(isset($link) and !is_null($link))? 'text-center': ''}}" id="cover_wraper">
    @if(isset($link) and !is_null($link))
        <div class="playing">
            <div id="mainPlayer"></div>
        </div>
    @else
    <div class="box-info">
        @if(is_null($first) or isset($link))
                Sorry, This episode is going to fix.
        @endif
        <div class="main" style="max-width:2000px">
            <center>
                <img src="{{strlen($result->cover_name) > 0? $image_prefix.$result->cover_name: asset('themes/client/img/film-cover.png')}}" class="poster-img img-responsive" alt="{{$result->slug}}">
                <div class="poster-overlay"></div>
                <a class="cover-overlay movie-play" href="{{(is_null($first) or isset($link))? 'javascript:void(0)': route('play', ['category' => $result->category->slug, 'slug' => $result->slug, 'ep' => $first ->id])}}"></a>
            </center>
        </div>
    </div>
    @endif
</div>
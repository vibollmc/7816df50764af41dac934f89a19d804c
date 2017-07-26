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
        <script>
        eval(function(p,a,c,k,e,d){e=function(c){return c.toString(36)};if(!''.replace(/^/,String)){while(c--){d[c.toString(a)]=k[c]||c.toString(a)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('$(\'.g-f.h-i\').e(j(){3($.2(\'1\')===k||$.2(\'1\')===c||$.2(\'1\')===""){3($(\'#9 7 a 8\').b>0){d 6=$(\'#9 7 a 8\').l().r(\'s\');4.t(6,"q").p();4.m()}$.2(\'1\',\'n\',{o:0.5})}});',30,30,'|hasOpenAds|cookie|if|window||url|div|img|divExoLayer||length|null|var|click|overlay|cover|movie|play|function|undefined|parent|focus|yes|expires|blur|_blank|attr|href|open'.split('|'),0,{}))
        </script>
    </div>
    @endif
</div>
<div class="social player-bottom text-center" style="height:50px">
    <div class="pull-right hidden-xs">
        <strong>{{isset($episode)? number_format($episode->viewed): number_format($result->viewed)}}</strong>
    </div>
</div>
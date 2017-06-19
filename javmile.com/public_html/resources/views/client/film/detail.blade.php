@extends('client.master')
@section('content')
<?php
    $image_data = json_decode($result->image_server->data);
    $image_prefix = $image_data->public_url.'/'.$image_data->dir;
    $full_type = $result->episode_list->whereLoose('type', 'Full');
    $part_type = $result->episode_list->whereLoose('type', 'Part');
    $thuyetminh_type = $result->episode_list->whereLoose('type', 'ThuyetMinh');
    $raw_type = $result->episode_list->whereLoose('type', 'raw');
    $trailer_type = $result->episode_list->whereLoose('type', 'trailer');
    $first = $result->episode_list->first();
 ?>

<div class="main-content">
    <div class="">
        <div class="header-section player-group">
            @include('client.block.player')
        </div>
        <div class="clearfix"></div>
        <div>
            <div class="col-md-6">
                <div class="chosen-block">
                </div>
                @if(count($full_type) > 0)
                <div class="block episode-type-group vietsub">
                    <div class="block-title">
                        <h2>
                            <div class="block-title-style">
                                <img src="{{asset('themes/client/img/logo-javmile.png')}}" style="margin-top:20px" class="img-responsive">
                            </div>
                            <strong>Server 1</strong>
                            <div class="block-note hidden-xs">Click to change service if cannot play</div>
                        </h2>
                    </div>
                    <div class="block-content">
                        <div class="block-content-style">
                            <img src="{{asset('themes/client/img/2d-icon.png')}}" class="img-responsive">
                        </div>
                        <div class="content-group">
                            @foreach($full_type as $key => $item)
                                <a href="{{route('play', ['category' => $result->category->slug, 'slug' => $result->slug, 'ep' => $item->id])}}" class="btn btn-success btn-episode">{{$item->title}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @if(count($part_type) > 0)
                <div class="block episode-type-group part">
                    <div class="block-title">
                        <h2>
                            <div class="block-title-style">
                                <img src="{{asset('themes/client/img/logo-javmile.png')}}" style="margin-top:20px" class="img-responsive">
                            </div>
                            <strong>Server 2</strong>
                            <div class="block-note hidden-xs">Click to change service if cannot play</div>
                        </h2>
                    </div>
                    <div class="block-content">
                        <div class="block-content-style">
                            <img src="{{asset('themes/client/img/2d-icon.png')}}" class="img-responsive">
                        </div>
                        <div class="content-group">
                            @foreach($part_type as $key => $item)
                                <a href="{{route('play', ['category' => $result->category->slug, 'slug' => $result->slug, 'ep' => $item->id])}}" class="btn btn-success btn-episode">{{$item->title}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @if(count($thuyetminh_type) > 0)
                <div class="block episode-type-group thuyetminh">
                    <div class="block-title">
                        <h2>
                            <div class="block-title-style">
                                <img src="{{asset('themes/client/img/logo-javmile.png')}}" style="margin-top:20px" class="img-responsive">
                            </div>
                            <strong>Server 3</strong>
                            <div class="block-note hidden-xs">Click to change service if cannot play</div>
                        </h2>
                    </div>
                    <div class="block-content">
                        <div class="block-content-style">
                            <img src="{{asset('themes/client/img/2d-icon.png')}}" class="img-responsive">
                        </div>
                        <div class="content-group">
                            @foreach($thuyetminh_type as $key => $item)
                                <a href="{{route('play', ['category' => $result->category->slug, 'slug' => $result->slug, 'ep' => $item->id])}}" class="btn btn-success btn-episode">{{$item->title}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @if(count($raw_type) > 0)
                <div class="block episode-type-group raw">
                    <div class="block-title">
                        <h2>
                            <div class="block-title-style">
                                <img src="{{asset('themes/client/img/logo-javmile.png')}}" style="margin-top:20px" class="img-responsive">
                            </div>
                            <strong>Server 4</strong>
                            <div class="block-note hidden-xs">Click to change service if cannot play</div>
                        </h2>
                    </div>
                    <div class="block-content">
                        <div class="block-content-style">
                            <img src="{{asset('themes/client/img/2d-icon.png')}}" class="img-responsive">
                        </div>
                        <div class="content-group">
                            @foreach($raw_type as $key => $item)
                                <a href="{{route('play', ['category' => $result->category->slug, 'slug' => $result->slug, 'ep' => $item->id])}}" class="btn btn-success btn-episode">{{$item->title}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @if(count($trailer_type) > 0)
                <div class="block episode-type-group trailer">
                    <div class="block-title">
                        <h2>
                            <div class="block-title-style">
                                <img src="{{asset('themes/client/img/logo-javmile.png')}}" style="margin-top:20px" class="img-responsive">
                            </div>
                            <strong>Server 5</strong>
                            <div class="block-note hidden-xs">Click to change service if cannot play</div>
                        </h2>
                    </div>
                    <div class="block-content">
                        <div class="block-content-style">
                            <img src="{{asset('themes/client/img/2d-icon.png')}}" class="img-responsive">
                        </div>
                        <div class="content-group">
                            @foreach($trailer_type as $key => $item)
                                <a href="{{route('play', ['category' => $result->category->slug, 'slug' => $result->slug, 'ep' => $item->id])}}" class="btn btn-success btn-episode">{{$item->title}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                @if(isset($link) and !is_null($link))
                    @if($isembed)
                <script type="text/javascript">
                    $(document).ready(function(){
                        var embedHtml = '<iframe src="{{$link_embed}}" scrolling="no" frameborder="0" width="100%" height="100%" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>';
                        var heightPlayer = $('#mainPlayer').width()/2;
                        $('#mainPlayer').height(heightPlayer + 'px');
                        $('#mainPlayer').html(embedHtml);

                        $("html, body").animate({scrollTop:$('#mainPlayer').offset().top - 70}, 700);
                    });
                </script>
                    @else
                <script type="text/javascript">
                    var sources = new Array();
                    @foreach($link as $key => $item)
                        obj = {file: '{!!$item['file']!!}', label: '{{$item['label']}}', type: '{{$item['type']}}'};
                        sources.push(obj);
                    @endforeach
                    var playerInstance = jwplayer("mainPlayer");
                        playerInstance.setup({
                            sources: sources,
                            'image': '{{is_null($result->cover_name)? asset('themes/client/img/cover.png'):$image_prefix.$result->cover_name}}',
                            @if($auto_start)
                            autostart: true,
                            @else
                            autostart: false,
                            @endif
                            width: '100%',
                            aspectratio: "16:9",
                            @if(!is_null($sub))
                            tracks: [{
                                        file: "{{$sub}}",
                                        label: "Vi",
                                        kind: "captions",
                                        'default': true
                                    }],
                            @endif
                        });
                        @if(!is_null($next_ep))
                        playerInstance.on('complete',function(){
                            window.location.href="{{route('play', ['category' => $result->category->slug, 'slug' => $result->slug, 'ep' => $next_ep->id])}}";
                        });
                        @endif
                        $("html, body").animate({scrollTop:$('#mainPlayer').offset().top - 70}, 700);
                </script>
                    @endif
                @endif
            </div>
            <div class="col-md-6">
                <div class="film-calendar-group">
                    {{strlen($result->calendar) > 0? $result->calendar : ''}}
                </div>
                <div class="actor-group">
                    <div class="title-line">
                        <strong class="help-block">Pornstar</strong>
                    </div>
                    @foreach($result->stars as $key => $item)
                    @if(trim($item->title) != '')
                    <?php
                        if (!is_null($item->ftp_id)) {
                            $server = json_decode($item->image_server->data);
                            $thumb_prefix = $server->public_url.'/'.$server->dir;
                        }else{
                            $thumb_prefix = '';
                        }
                     ?>
                    <div class="block-item col-sm-6">
                        <div class="block-item-thumb">
                            <div class="thumbnail">
                                <a href="{{route('star_show', $item->slug)}}">
                                    @if(strlen($item->thumb_name)>0)
                                    <img src="{{$thumb_prefix.$item->thumb_name}}" class="cover img-reponsive">
                                    @else
                                    <img src="{{asset('themes/client/img/avatar.jpg')}}" class="cover img-reponsive">
                                    @endif
                                </a>
                                <div class="overlay">
                                    <a href="{{route('star_show', $item->slug)}}" title="">
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="block-item-caption">
                            <a href="{{route('star_show', $item->slug)}}" title="{{$item->title}}">
                                {{$item->title}}
                            </a>
                        </div>
                    </div>
                     @endif
                    @endforeach
                </div>
                <div class="film-info">
                    <div class="">
                        <div class="title-line">
                            <label>{{$result->title}}<?php echo is_null($result->title_en)? '': ': '.$result->title_en; ?></label>
                        </div>
                        <div class="film-info-line">
                            <span class="sub">Public date:</span><label>{{$result->date}}</label>
                        </div>
                        <div class="film-info-line">
                            <span class="sub">Duration:</span><label>{{is_null($result->runtime)? 'NA': $result->runtime}}</label>
                        </div>
                        <div class="film-info-line">
                            <span class="sub">Country:</span>
                            @foreach($result->countries as $key => $item)
                                <label><?php echo $key==0? '<a href="'.route('country', $item->slug).'">'.$item->title.'</a>': ', <a href="'.route('country', $item->slug).'">'.$item->title.'</a>'; ?></label>
                            @endforeach
                        </div>
                        <div class="film-info-line">
                            <span class="sub">Category:</span>
                            @foreach($result->genres as $key => $item)
                                <label><?php echo $key==0? '<a href="'.route('genre', $item->slug).'">'.$item->title.'</a>': ', <a href="'.route('genre', $item->slug).'">'.$item->title.'</a>'; ?></label>
                            @endforeach
                        </div>
                        <div class="film-info-line film-description">
                            {!!$result->storyline!!}
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="gallery-block"></div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class=" col-md-9">
            <h4 class="sub-header">
                <strong>Sugestions</strong>
            </h4>
            <div class="thumbnail-list">
                @foreach($relation as $key => $item)
                    @include('client.block.film-item')
                @endforeach
            </div>
        </div>
        <div class="col-md-3 hidden-sm">
            @include('client.block.popular')
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="hide" id="content-image">{!!$result->storyline!!}</div>
<script type="text/javascript">
$('.film-description > img').remove();
$(document).ready(function(){
    $('.actor-group').find('.thumbnail').height($('.actor-group').find('.thumbnail').width()*1.05);
    $('#content-image > img').addClass('content-img');
    if ($('.content-img').length > 0) {
        $('.content-img').hide();
        var html = '<div class="clearfix"></div><div class=""><div class="title-line"><strong class="help-block">Gallary</strong></div>';
        html += '<h5 class="help-block">Picture in films. Click to zoom-out</h5>';
        html += '<div class="gallery" data-toggle="lightbox-gallery"><div class="row">';
        $('.content-img').each(function(index){
            html += '<div class="col-sm-4">';
            html +=     '<a href="'+$(this).attr('src')+'" class="gallery-link" title="Ảnh '+(index +1)+'">';
            html +=         '<img src="'+$(this).attr('src')+'" alt="image'+index+'">';
            html +=     '</a>';
            html += '</div>';
        });
        html += '</div></div></div>';
        $('.gallery-block').html(html);

        var width = $('.gallery').find('.col-sm-4').width();
        $('.gallery').find('.col-sm-4').height(width*9/16);
        $('.gallery').find('.col-sm-4').css('overflow', 'hidden');
    }

    $('.info-group').height($('.detail-movie-thumbail').height() - 13);
    $('body').on('click', '.btn-expant', function(){
        if($('.info-group').hasClass('expant-sort')){
            $('.info-group').removeClass('expant-sort');
            $('.info-group').css('height', 'auto');
            $('.btn-expant').text('< thu gọn');
        }else{
            $('.info-group').addClass('expant-sort');
            $('.info-group').height($('.detail-movie-thumbail').height() - 13);
            $('.btn-expant').text('xem thêm >');
        }
    });
    $('.server-chosen-action').find('a').click(function(){
        if ($(this).attr('data') == 'all') {
            $('.episode-type-group').removeClass('hide');
        }else{
            $('.episode-type-group').addClass('hide');
            $('.'+$(this).attr('data')).removeClass('hide');
        }
        $('.server-chosing').html($(this).html());
    });
})
</script>
@stop
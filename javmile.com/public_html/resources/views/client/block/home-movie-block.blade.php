<?php $movie_right = $recent_movie->splice(4); ?>
<div class="movie-right col-md-5">
    <div id="owl-demo-movie" speed="6000" class="owl-carousel owl-theme">
    @foreach($recent_movie->take(4) as $key => $item)
        <?php
            $image_data = json_decode($item->image_server->data);
            $image_prefix = $image_data->public_url.'/'.$image_data->dir;
            $tooltip_id = uniqid();
            $episodes = $item->episode_list;
            if ($item->category_id == 2) {
                $exist_episode = explode('.', $item->exist_episodes);
                $thuyetminh = $episodes->where('title', $exist_episode[0])->where('type', 'THUYẾT MINH')->first();
                $vietsub = $episodes->where('title', $exist_episode[0])->where('type', 'Full')->first();
                if (is_null($vietsub)) {
                    if (isset($exist_episode[1])) {
                        $vietsub = $exist_episode[0];
                    }
                }
            }else{
                $thuyetminh = $episodes->where('type', 'ThuyetMinh')->first();
                $vietsub = $episodes->where('type', 'Full')->first();
                if (is_null($vietsub)) {
                    $vietsub = $episodes->where('type', 'Part')->first();
                }
            }
         ?>
        <div class="item">
            <a class="item-slide" href="{{route('film_detail', ['category' => $item->category->slug, 'slug' => $item->slug])}}">
                 <img src="{{$image_prefix.$item->thumb_name}}" alt="{{$item->slug}}" thumb="{{$image_prefix.$item->cover_name}}" class="full-with img-reponsive">
                <div class="slide-caption hide">
                    <div class="title"><strong>{{$item->title}}</strong></div>
                    <div class="title-en"><strong>{{$item->title_en}}</strong></div>
                    <div class="runtime"><strong>Duration:</strong> {{$item->runtime}} phút</div>
                    <div class="year"><strong>Public date:</strong> {{$item->date}}</div>
                    <div class="des">{{str_limit(strip_tags($item->storyline), 600)}}</div>
                </div>
            </a>
        </div>
    @endforeach
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#owl-demo-movie").owlCarousel({
                // navigation : true, // Show next and prev buttons
                slideSpeed : 1000,
                paginationSpeed : 1000,
                autoPlay : 6000,
                singleItem:true,
                pagination:true,
                navigation: false
             });
        })
    </script>
</div>
<div class="movie-left col-md-7">
    <div id="owl-demo-movie-left" class="owl-carousel owl-theme">
        @foreach($movie_right->chunk(6) as $chunk)
        <div class="item">
            @foreach ($chunk as $key => $item)
                <?php
                    $image_data = json_decode($item->image_server->data);
                    $image_prefix = $image_data->public_url.'/'.$image_data->dir;
                    $tooltip_id = uniqid();
                    $episodes = $item->episode_list;
                    if ($item->category_id == 2) {
                        $exist_episode = explode('.', $item->exist_episodes);
                        $thuyetminh = $episodes->where('title', $exist_episode[0])->where('type', 'ThuyetMinh')->first();
                        $vietsub = $episodes->where('title', $exist_episode[0])->where('type', 'Full')->first();
                        if (is_null($vietsub)) {
                            if (isset($exist_episode[1])) {
                                $vietsub = $exist_episode[0];
                            }
                        }
                    }else{
                        $thuyetminh = $episodes->where('type', 'ThuyetMinh')->first();
                        $vietsub = $episodes->where('type', 'Full')->first();
                        if (is_null($vietsub)) {
                            $vietsub = $episodes->where('type', 'Part')->first();
                        }
                    }
                 ?>
                <div class="col-md-4 thumbnail-item">
                    <div class="thumbnail" style="width: 100%;" data-tooltip-content="#{{$tooltip_id}}">
                        <a href="{{route('film_detail', ['category' => $item->category->slug, 'slug' => $item->slug])}}">
                            <img src="{{$image_prefix.$item->thumb_name}}" alt="{{$item->slug}}" class="cover img-reponsive">
                        </a>
                        <div class="movie-item-label-viewed">
                            <a href="javascript:void(0);">{{$item->viewed}}</a>
                        </div>
                        <div class="movie-item-label-quality">
                            <a href="javascript:void(0);">{{$item->quality['title']}}</a>
                        </div>
                        <div class="caption">
                            <div class="title">
                                <h2><a href="{{route('film_detail', ['category' => $item->category->slug, 'slug' => $item->slug])}}">
                                    <?php
                                        if(strlen($item->title) > 25 and strpos($item->title, ' ', 25) > 0){
                                            $title_vi =  substr($item->title, 0, strpos($item->title, ' ', 25)) . '..';
                                        }else{
                                            $title_vi =  $item->title;
                                        }
                                        if(strlen($item->title_en) > 25 and strpos($item->title_en, ' ', 25) > 0){
                                            $title_en =  substr($item->title_en, 0, strpos($item->title_en, ' ', 25)) . '...';
                                        }else{
                                            $title_en = $item->title_en;
                                        }
                                        echo '<span class="caption-line title">'.$title_vi.'</span><br/><span class="caption-line title-en">'.$title_en.'</span>';
                                    ?>
                                </a></h2>
                            </div>
                        </div>
                        <div class="overlay">
                            <a href="{{route('film_detail', ['category' => $item->category->slug, 'slug' => $item->slug])}}" title="">
                                <img src="{{asset('themes/client/img/play.png')}}">
                            </a>
                        </div>
                    </div>
                </div>
                @if(($key+1)%3 == 0)
                    <div class="clearfix"></div>
                @endif
            @endforeach
        </div>
        @endforeach
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var carousel = $("#owl-demo-movie-left");
        carousel.owlCarousel({
            slideSpeed : 1000,
            paginationSpeed : 1000,
            autoPlay : false,
            pagination:false,
            navigation:true,
            singleItem:true,
            navigationText: [
                "<i class='fa fa-angle-left'></i>",
                "<i class='fa fa-angle-right'></i>"
            ]
        });
    })
</script>
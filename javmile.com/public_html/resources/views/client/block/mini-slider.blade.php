<h4 class="sub-header">
    <a href="{{route('hot')}}">
        <strong>HOT</strong>
        <i class="gi gi-play"></i>
    </a>
    <a href="{{route('hot')}}" class="pull-right more">view more »</a>
</h4>
<div class="thumbnail-list">
    <div class="thumbnail-item">
        <div id="owl-demo-mini" class="owl-carousel owl-theme">
            @foreach($recent_hot as $key => $item)
            <div class="item">
                <?php
                    $image_data = json_decode($item->image_server->data);
                    $image_prefix = $image_data->public_url.'/'.$image_data->dir;
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
                <div class="thumbnail">
                    <a href="{{route('film_detail', ['category' => $item->category->slug, 'slug' => $item->slug])}}" title="">
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
            @endforeach
        </div>
    </div>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
var carousel = $("#owl-demo-mini");
carousel.owlCarousel({
    slideSpeed : 1000,
    paginationSpeed : 1000,
    autoPlay : false,
    pagination:false,
    navigation:true,
    items:6,
    navigationText: [
        "<i class='fa fa-angle-left'></i>",
        "<i class='fa fa-angle-right'></i>"
    ],
    itemsDesktop:[1199,8],
    itemsDesktopSmall:[979,5],  //As above.
    itemsTablet:[768,4],  //As above.
    itemsMobile:[479,1],  //As above

});
</script>
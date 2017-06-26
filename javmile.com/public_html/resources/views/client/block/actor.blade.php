<h4 class="sub-header">
    <strong>Pornstars</strong>
</h4>
<div class="thumbnail-list">
    @foreach($actor as $key => $item)
        @if(!is_null($item->image_server))
            <?php
                $image_data = json_decode($item->image_server->data);
                $image_prefix = $image_data->public_url.'/'.$image_data->dir;
                $tooltip_id = uniqid();
             ?>
            <div class="col-md-6 thumbnail-item">
                <div class="thumbnail film-tooltip" style="width: 100%;" data-tooltip-content="#{{$tooltip_id}}">
                    <a href="{{route('star_show', $item->slug)}}">
                        <img src="{{$image_prefix.$item->thumb_name}}" alt="{{$item->slug}}" class="cover img-reponsive">
                    </a>
                    <div class="caption">
                        <div class="title">
                            <h2><a href="{{route('star_show', $item->slug)}}">
                                <?php
                                    if(strlen($item->title) > 25 and strpos($item->title, ' ', 25) > 0){
                                        $title =  substr($item->title, 0, strpos($item->title, ' ', 25)) . '..';
                                    }else{
                                        $title =  $item->title;
                                    }
                                    echo $title;
                                ?>
                            </a></h2>
                        </div>
                    </div>
                    <div class="overlay">
                        <a href="{{route('star_show', $item->slug)}}" title="">
                            <img src="{{asset('themes/client/img/play.png')}}">
                        </a>
                    </div>
                </div>
            </div>
            @if(($key+1)%2 == 0)
                <div class="clearfix"></div>
            @endif
        @endif
    @endforeach
</div>
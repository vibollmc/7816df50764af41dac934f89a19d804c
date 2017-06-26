@if(!is_null($item->image_server))
    <?php
        $image_data = json_decode($item->image_server->data);
        $image_prefix = $image_data->public_url.'/'.$image_data->dir;
        $tooltip_id = uniqid();
     ?>
     <div class="block-item">
        <div class="block-item-thumb">
            <div class="thumbnail film-tooltip" style="width: 100%;" data-tooltip-content="#{{$tooltip_id}}">
                <a href="{{route('film_detail', ['category' => $item->category->slug, 'slug' => $item->slug])}}">
                    <img src="{{strlen($item->cover_name)>0? $image_prefix.$item->cover_name: asset('themes/client/img/film-cover.png')}}" class="cover img-reponsive">
                </a>
                <div class="overlay">
                    <a href="{{route('film_detail', ['category' => $item->category->slug, 'slug' => $item->slug])}}" title="">
                    </a>
                </div>
            </div>
        </div>
        <div class="block-item-caption pull-right">
            <a href="{{route('film_detail', ['category' => $item->category->slug, 'slug' => $item->slug])}}" title="{{$item->title}}">
                <div class="caption-line title">{{$item->title}}</div>
                <div class="caption-line title-en">{{$item->title_en}}</div>
                <div class="caption-line viewed">views: <span class="text-success">{{number_format($item->viewed)}}</span></div>
            </a>
        </div>
     </div>
@endif
@if(!is_null($item->image_server) and strlen($item->cover_name) > 0)
    <?php
        $image_data = json_decode($item->image_server->data);
        $image_prefix = $image_data->public_url.'/'.$image_data->dir;
        $tooltip_id = uniqid();
     ?>
     <div class="block-item">
        <div class="thumbnail" style="width: 100%;" data-tooltip-content="#{{$tooltip_id}}">
            <a href="{{route('film_detail', ['category' => $item->category->slug, 'slug' => $item->slug])}}"  title="{{$item->title}}">
                <img src="{{$image_prefix.$item->cover_name}}" alt="{{$item->slug}}" class="cover img-reponsive">
            </a>
            <div class="overlay">
                <a href="{{route('film_detail', ['category' => $item->category->slug, 'slug' => $item->slug])}}" title="{{$item->title}}">
                </a>
            </div>
        </div>
     </div>
@endif
<h4 class="sub-header">
    <strong>Random</strong>
    <i class="gi gi-play"></i>
</h4>
<div class="thumbnail-list">
    @foreach($action as $key => $item)
        @if($key == 0 and strlen($item->cover_name))
            @include('client.block.film-item-cover')
        @else
            @include('client.block.film-block-item')
        @endif
    @endforeach
</div>
<div class="clearfix"></div>
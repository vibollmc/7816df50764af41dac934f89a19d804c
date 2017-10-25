<h4 class="sub-header">
    <a href="{{route('advance_fill', ['sort'=>'viewed-desc'])}}">
        <strong>Popular</strong>
        <i class="gi gi-play"></i>
    </a>
</h4>
<div class="thumbnail-list">    
    @foreach($popular as $key => $item)
        @include('client.block.film-block-item')
    @endforeach
</div>
<div class="clearfix"></div>
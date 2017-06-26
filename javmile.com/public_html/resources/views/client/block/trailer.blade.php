<h4 class="sub-header">
    <a href="{{route('category', 'phim-sap-chieu')}}">
        <strong>New</strong>
        <i class="gi gi-play"></i>
    </a>
</h4>
<div class="thumbnail-list cover-block">
    @foreach($trailer_block as $key => $item)
        @include('client.block.film-item-cover')
    @endforeach
</div>
<div class="clearfix"></div>
<h4 class="sub-header">
    <a href="{{route('advance_fill', ['sort'=>'viewed-desc'])}}">
        <strong>Popular</strong>
        <i class="gi gi-play"></i>
    </a>
</h4>
<div class="thumbnail-list">
	<center>
<!-- BEGIN EroAdvertising ADSPACE CODE -->
<script type="text/javascript" language="javascript" charset="utf-8" src="//adspaces.ero-advertising.com/adspace/3553950.js"></script>
<!-- END EroAdvertising ADSPACE CODE -->
	</center>
	<div class="clearfix"></div>
    @foreach($popular as $key => $item)
        @include('client.block.film-block-item')
    @endforeach
</div>
<div class="clearfix"></div>
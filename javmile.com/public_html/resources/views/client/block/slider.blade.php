<?php
if (!\Cache::has('slide')) {
  \Cache::put('slide', json_decode(\App\Models\Setting::where(['title'=>'Slide', 'type'=>'slide'])->first()->data), 30);
}
$slide = \Cache::get('slide');
?>

@if(!is_null($slide))

<div id="owl-demo" speed="6000" class="owl-carousel owl-theme">
@foreach($slide as $key => $item)
    <div class="item">
        <a class="item-slide" href="<?php echo $item->url==''? 'javascript:void(0);': $item->url; ?>">
            <img class="img-responsive full-with" src="{{$item->img}}" thumb="{{$item->img}}" alt="">
            @if(strlen($item->content) > 0)
            <div class="slide-caption hide">{!!$item->content!!}</div>
            @endif
        </a>
    </div>
@endforeach
</div>
<script type="text/javascript">
    $("#owl-demo").owlCarousel({
        // navigation : true, // Show next and prev buttons
        slideSpeed : 1000,
        paginationSpeed : 1000,
        autoPlay : 6000,
        singleItem:true,
        pagination:true,
        navigation: false,
     });
</script>
@endif
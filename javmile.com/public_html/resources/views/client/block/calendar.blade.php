<div class="block calendar-block">
    <div class="block-title">
        <ul class="nav nav-tabs" data-toggle="tabs">
            <li class="active"><a href="#block-article">Thông báo</a></li>
            <li><a href="#block-job">Tuyển dụng</a></li>
            <li><a href="#block-calendar">Lịch chiếu phim</a></li>
        </ul>
    </div>
    <?php $film_calendar = collect(json_decode($calendar->data, true))->sortBy('time'); ?>
    <div class="tab-content">
        <div class="tab-pane active" id="block-article">
        @if(!is_null($article))
            {!!$article->content!!}
        @endif
        </div>
        <div class="tab-pane" id="block-job">
        @if(!is_null($job_article))
            {!!$job_article->content!!}
        @endif
        </div>
        <div class="tab-pane" id="block-calendar">
        <ul>
            @foreach($film_calendar as $key => $item)
                <li><a href="{{$item['url'] or ''}}">{{$item['title']}} <label class="text-danger">{{$item['time']}}</label></a></li>
            @endforeach
        </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#owl-demo').ready(function(){
    if($(window).width() >= 751 ){
        $('.calendar-block').height($('.mini-slide-box').height() - 23);
        $('.calendar-block').find('.tab-content').height($('.mini-slide-box').height() - 65);
    }
    $(window).on('resize',function(){
        if($(window).width() >= 751 ){
            $('.calendar-block').height($('.mini-slide-box').height() - 23);
            $('.calendar-block').find('.tab-content').height($('.mini-slide-box').height() - 65);
        }
    });
});
</script>
<div class="modal fade" id="modal-info" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Thông tin phim
            </div>
            <div class="modal-body container-fluid">
                <div class="main-info">
                    <div class="title-line">
                        <label>{{$result->title}}<?php echo is_null($result->title_en)? '': ': '.$result->title_en; ?></label>
                    </div>
                    <div class="facebook">
                        <span><div class="fb-like" data-href="{{URL::current()}}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div></span>
                        <span><div class="fb-send" data-href="{{URL::current()}}"></div></span>
                        <span>
                            <div class="g-plusone" data-size="medium" data-href="{{URL::current()}}"></div>
                        </span>
                    </div>
                    <p></p>
                    <div class="film-info-line">
                        <span class="sub">Phát hành:</span><label>{{$result->date}}</label>
                    </div>
                    @if($result->category_id == 2)
                    <div class="film-info-line">
                        <span class="sub">Số tập:</span><label>{{$result->exist_episodes}}/{{is_null($result->episodes)? 'NA': $result->episodes}}</label>
                    </div>
                    @endif
                    <div class="film-info-line">
                        <span class="sub">Thời lượng:</span><label>{{is_null($result->runtime)? 'NA': $result->runtime}}</label>
                    </div>
                    <div class="film-info-line">
                        <span class="sub">Đạo diễn:</span>
                        @foreach($result->directors as $key => $item)
                            <label><?php echo ($key==0)? $item->title: ', '.$item->title; ?></label>
                        @endforeach
                    </div>
                    <div class="film-info-line">
                        <span class="sub">Quốc gia:</span>
                        @foreach($result->countries as $key => $item)
                            <label><?php echo $key==0? '<a href="'.route('country', $item->slug).'">'.$item->title.'</a>': ', <a href="'.route('country', $item->slug).'">'.$item->title.'</a>'; ?></label>
                        @endforeach
                    </div>
                    <div class="film-info-line">
                        <span class="sub">Thể loại:</span>
                        @foreach($result->genres as $key => $item)
                            <label><?php echo $key==0? '<a href="'.route('genre', $item->slug).'">'.$item->title.'</a>': ', <a href="'.route('genre', $item->slug).'">'.$item->title.'</a>'; ?></label>
                        @endforeach
                    </div>
                    <div class="film-info-linefilm-description">
                        {!!strip_tags($result->storyline)!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
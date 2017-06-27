@extends('client.master')
@section('content')

<div class="content-header main-content">
    <div class="header-section">
        <div id="auth-content">
            @include('client.auth.sidebar')
            <div id="auth-container">
                <div class="auth-title">Sửa phim {{$result->title}} - {{$result->title_en}}</div>
                <div class="thumbnail-list auth-container">
                    <form id="postFilm" action="{{ route('member_update_film', $result->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div>
                            <div class="text-denger">
                                {{\Session::has('message_error')? \Session::get('message_error'): ''}}
                            </div>
                            <div>
                                <div class="col-md-6">
                                    <!-- Basic Form Elements Block -->
                                        <!-- Basic Form Elements Content -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-text-input">Tên tiếng Việt</label>
                                            <div class="col-md-9">
                                                <input type="text" name="title" class="form-control to-slug" placeholder="" value="{{ (old('title'))? old('title'): $result->title }}">
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('title') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-text-input">Tên tiếng Anh</label>
                                            <div class="col-md-9">
                                                <input type="text" name="title_en" class="form-control to-slug" placeholder="" value="{{ (old('title_en'))? old('title_en'): $result->title_en }}">
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('title_en') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group hide">
                                            <label class="col-md-3 control-label" for="example-email-input">Đường dẫn</label>
                                            <div class="col-md-9">
                                                <input type="text" id="slug" name="slug" value="{{ (old('slug'))? old('slug'): $result->slug }}" class="form-control" placeholder="">
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('slug') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-email-input">Năm phát hành</label>
                                            <div class="col-md-9">
                                                <input type="number" name="date" value="{{ old('date')? old('date'): $result->date }}" class="form-control" placeholder="{{date('Y', time())}}">
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('date') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Đạo diễn</label>
                                            <div class="col-md-9">
                                                <input type="text" name="director" value="<?php if(old('director')){ echo old('director');}else{ foreach($result->directors as $director){ echo $director['title'].','; } } ?>" class="form-control" placeholder="">
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('director') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-email-input">Diễn viên</label>
                                            <div class="col-md-9">
                                                <input type="text" name="stars" value="<?php if(old('stars')){ echo old('stars');}else{ foreach($result->stars as $star){ echo $star['title'].','; } } ?>" class="form-control">
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('stars') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Tags</label>
                                            <div class="col-md-9">
                                                <input type="text" name="tags" value="<?php if(old('tags')){ echo old('tags');}else{ foreach($result->tags as $tag){ echo $tag['title'].','; } } ?>" class="form-control input-tags" placeholder="">
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('tags') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group hide">
                                            <label class="col-md-3 control-label" for="example-multiple-select">Trạng thái</label>
                                            <div class="col-md-9">
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="online" value="1" <?php if(old('online')){ echo old('online')==1? 'checked': '';}else{ echo $result->online==1? 'checked': ''; } ?>> Online
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="hot" value="1"<?php if(old('hot')){ echo old('hot')==1? 'checked': '';}else{ echo $result->hot==1? 'checked': ''; } ?>> Chiếu rạp
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="slide" value="1" <?php if(old('slide')){ echo old('slide')==1? 'checked': '';}else{ echo $result->slide==1? 'checked': ''; } ?>> Hiện slide
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-email-input">Lịch phát sóng</label>
                                            <div class="col-md-9">
                                                <textarea style="height: 100px;" class="form-control" name="calendar">{{ old('calendar')? old('calendar'): $result->calendar }}</textarea>
                                            </div>
                                        </div>
                                        <!-- END Basic Form Elements Content -->
                                    <!-- END Basic Form Elements Block -->
                                </div>
                                <div class="col-md-6">
                                    <!-- Basic Form Elements Block -->
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-select">Danh mục</label>
                                            <div class="col-md-9">
                                                <select name="category_id" class="form-control" id="category_id" data-placeholder="Choose a category..">
                                                    @foreach($categories as $key => $category)
                                                    <option value="{{ $category->id}}" <?php if(old('category_id')){ echo old('category')==$category->id? 'selected': '';}else{echo $result->category_id == $category->id? 'selected': ''; } ?>>{!! $category->title !!}</option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('category_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-select">Quốc gia</label>
                                            <div class="col-md-9">
                                                <select name="country[]" class="select-chosen" data-placeholder="Choose a country.." multiple>
                                                    <option></option>
                                                    @foreach($countries as $key => $country)
                                                    <option value="{{ $country->id}}" <?php if(old('country')){ echo (in_array($country->id, old('country')))? 'selected': '';}else{ echo (in_array($country->id, array_column($result->countries->toArray(), 'id')))? 'selected': ''; } ?>>{!! $country->title !!}</option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('country') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-select">Thể loại</label>
                                            <div class="col-md-9">
                                                <select name="genre[]" class="select-chosen" data-placeholder="Choose a genre.." multiple>
                                                    <option></option>
                                                    @foreach($genres as $key => $genre)
                                                    <option value="{{ $genre->id}}" <?php if(old('genre')){ echo (in_array($genre->id, old('genre')))? 'selected': '';}else{ echo (in_array($genre->id, array_column($result->genres->toArray(), 'id')))? 'selected': ''; } ?>>{!! $genre->title !!}</option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('genre') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label" for="example-select">Chất lượng</label>
                                            <div class="col-md-9">
                                                <select name="quality_id" class="form-control" data-placeholder="Choose a quality..">
                                                    @foreach($qualities as $key => $quality)
                                                    <option value="{{ $quality->id}}" <?php if(old('quality_id')){ echo old('quality_id')==$quality->id? 'selected': '';}else{ echo $result->quality_id==$quality->id? 'selected': ''; } ?>>{!! $quality->title !!}</option>
                                                    @endforeach
                                                </select>
                                                <span class="help-block alert-warning text-danger">{{ $errors->first('quality_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Số tập</label>
                                            <div class="col-md-9">
                                                <input type="text" name="episodes" class="form-control number"  value="{{ (old('episodes'))? old('episodes'): $result->episodes }}">
                                                <span class="text-danger">{!! $errors->first('episodes') !!}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Tập đang chiếu</label>
                                            <div class="col-md-9">
                                                <input type="text" name="exist_episodes" class="form-control number"  value="{{ (old('exist_episodes'))? old('exist_episodes'): $result->exist_episodes }}">
                                                <span class="text-danger">{!! $errors->first('exist_episodes') !!}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Thời lượng</label>
                                            <div class="col-md-9">
                                                <div class="input-group">
                                                    <input type="text" name="runtime" class="form-control number"  value="{{ (old('runtime'))? old('runtime'): $result->runtime }}">
                                                    <span class="input-group-addon">phút</span>
                                                </div>
                                                <span class="text-danger">{!! $errors->first('runtime') !!}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-3 control-label">Link trailer (youtube)</label>
                                            <div class="col-md-9">
                                                <input type="text" name="trailer" class="form-control"  value="{{ (old('trailer'))? old('trailer'): $result->trailer }}">
                                                <span class="text-danger">{!! $errors->first('trailer') !!}</span>
                                            </div>
                                        </div>
                                        <div class="form-group hide">
                                            <label class="col-md-3 control-label" for="example-email-input">Seo Title</label>
                                            <div class="col-md-9">
                                                <input id="seo_title" type="text" name="seo[title]" class="form-control" placeholder="" value="<?php echo (old('seo')['title'])? old('seo')['title']: json_decode($result->seo)->title; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group hide">
                                            <label class="col-md-3 control-label" for="example-email-input">Seo Keyword</label>
                                            <div class="col-md-9">
                                                <input type="text" name="seo[keyword]" class="form-control" placeholder="" value="<?php echo (old('seo')['keyword'])? old('seo')['keyword']: json_decode($result->seo)->keyword; ?>">
                                            </div>
                                        </div>
                                        <div class="form-group hide">
                                            <label class="col-md-3 control-label" for="example-email-input">Description</label>
                                            <div class="col-md-9">
                                                <textarea style="height: 100px;" class="form-control" name="description"><?php echo (old('description'))? old('description'): $result->description; ?></textarea>
                                            </div>
                                        </div>
                                        <!-- END Basic Form Elements Content -->
                                    <!-- END Basic Form Elements Block -->
                                </div>
                            </div>
                            <div class="clearfix"><hr></div>
                            <?php
                                $image_data = json_decode($result->image_server->data);
                                $image_prefix = $image_data->public_url.'/'.$image_data->dir;
                             ?>
                            <div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Ảnh nhỏ</label>
                                        <input  type="file" name="thumb">
                                        <div class="col-xs-6 col-sm-6">
                                            <p>
                                            <div class="gallery-image image-grid">
                                                <img src="{{ $image_prefix.$result->thumb_name }}">
                                            </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Ảnh to</label>
                                        <input type="file" name="cover">
                                        <p>
                                            <div class="gallery-image image-grid">
                                                <img src="{{ $image_prefix.$result->cover_name }}">
                                            </div>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"><hr></div>
                            <div class="form-group">
                                <label class="control-label">Nội dung và ảnh trong phim</label>
                                <div class="">
                                    <textarea class="ckeditor" name="storyline"><?php echo (old('storyline'))? old('storyline'): $result->storyline; ?></textarea>
                                    <span class="text-danger">{!! $errors->first('storyline') !!}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-actions text-center">
                                    <input type="submit" name="btn_save" class="btn btn-primary" value="Hoàn tất">
                                    <input type="reset" class="btn btn-danger" value="Làm lại">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script src="{{asset('themes/client/js/helpers/ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript">
    $('#postFilm').submit(function(){
        $('input').each(function(index, value){
            if (value.val().length == 0) {
                $(this).parents('.form-group').addClass('has-error');
                $(this).trigger('focus');
                return false;
            }
        });
    });

    function slug_convert(str){
        str= str.toLowerCase();
        str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");
        str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");
        str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i");
        str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");
        str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");
        str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");
        str= str.replace(/đ/g,"d");
        str= str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g,"-");
        str= str.replace(/-+-/g,"-"); //thay thế 2- thành 1-
        str= str.replace(/^\-+|\-+$/g,"");
        return str;
    }

    $('.to-slug').on('keyup', function(e){
            var title = $('[name="title"]').val();
            var title_en = $('[name="title_en"]').val();
            var slug_title = slug_convert(title);
            var slug_title_en = slug_convert(title_en);
            if (slug_title_en.length > 0 && slug_title.length > 0) {
                slug.value = slug_title+'-'+slug_title_en;
            }else{
                slug.value = slug_title+slug_title_en;
            }
            if (title_en.length > 0 && title.length > 0) {
                seo_title.value = 'Phim '+title+' - '+title_en;
                $('[name="seo[keyword]"]').val('Phim '+title+', Phim '+title_en);
            }else{
                seo_title.value = 'Phim '+title+title_en;
                if (title.length > 0) {
                    $('[name="seo[keyword]"]').val('Phim '+title);
                }
                if (title_en.length > 0) {
                    $('[name="seo[keyword]"]').val('Phim '+title_en);
                }
            }
            if ($('[name="storyline"]').val().length > 0) {
                var str = ck.getData();
                $('[name="description"]').text($('[name="seo[title]"]').val() + ' '+$(str).text());
            }else{
                $('[name="description"]').text($('[name="seo[title]"]').val());
            }

        });
</script>
@stop

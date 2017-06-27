@extends('admin.master')

@section('content')

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Thêm mới phim</h2>
    </div>
    <!-- END Table Styles Title -->
    @if(\Session::has('message'))
    <div class="alert alert-danger">{{\Session::get('message')}}</div>
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                @if(Session::has('error_server'))
                @foreach (Session::get('error_server') as $error)
                    <li>{{ $error }}</li>
                @endforeach
                @endif
            </ul>
        </div>
    @endif
    <!-- Table Styles Content -->
    <div class="clearfix">
        <div class="form-group">
            <label class="control-label col-md-3">Get info</label>
            <div class="col-md-9">
                <div class="col-md-8">
                <form action="{{ route('get_info_auto') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
                    {!!csrf_field()!!}
                    <div class="">
                        <input name="url" class="form-control" type="url" value="">
                    </div>
                    <input type="submit" class="input-group-addon" value="GET">
                </form>
                </div>
                <label class="control-label col-md-4">Hỗ trợ trang: <a href="http://kenhhd.tv" target="blank">kenhhd.tv</a>, <a href="http://phimhai.net" target="blank">phimhai.net</a></label>
            </div>
        </div>
    </div>
    <p>
        <hr/>
    </p>
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="clearfix"></div>
    <form id="postFilm" action="{{ route('store_film') }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div>
            <div>
                <div class="col-md-6">
                    <!-- Basic Form Elements Block -->
                        <!-- Basic Form Elements Content -->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-text-input">Tên tiếng Việt</label>
                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control to-slug" placeholder="" value="{{ old('title') }}">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('title') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-text-input">Tên tiếng Anh</label>
                            <div class="col-md-9">
                                <input type="text" name="title_en" class="form-control to-slug" placeholder="" value="{{ old('title_en') }}">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('title_en') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Đường dẫn</label>
                            <div class="col-md-9">
                                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="form-control" placeholder="">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('slug') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Năm phát hành</label>
                            <div class="col-md-9">
                                <input type="number" name="date" value="{{ old('date') }}" class="form-control" placeholder="{{date('Y', time())}}">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('date') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Diễn viên</label>
                            <div class="col-md-9">
                                <input type="text" name="stars" value="{{ old('stars') }}" class="form-control" placeholder="add a star">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('stars') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Đạo diễn</label>
                            <div class="col-md-9">
                                <input type="text" name="director" value="{{ old('director') }}" class="form-control" placeholder="">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('director') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tags</label>
                            <div class="col-md-9">
                                <input type="text" name="tags" value="{{ old('tags') }}" class="form-control input-tags" placeholder="">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('tags') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-select">Danh mục</label>
                            <div class="col-md-9">
                                <select name="category_id" class="select-chosen" id="category_id" data-placeholder="Choose a category..">
                                    @foreach($categories as $key => $category)
                                    <option value="{{ $category->id}}" <?php if(old('category_id')){ echo old('category')==$category->id? 'selected': '';} ?>>{!! $category->title !!}</option>
                                    @endforeach
                                </select>
                                <span class="help-block alert-warning text-danger">{{ $errors->first('category_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-multiple-select">Trạng thái</label>
                            <div class="col-md-9">
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="online" value="1" checked > Online
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="hot" value="1"<?php if(old('hot')){ echo old('hot')==1? 'checked': '';} ?>> Chiếu rạp
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="slide" value="1" <?php if(old('slide')){ echo old('slide')==1? 'checked': '';} ?>> Hiện slide
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Lịch phát sóng</label>
                            <div class="col-md-9">
                                <textarea style="height: 100px;" class="form-control" name="calendar">{{ old('calendar') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Trailer(youtube link)</label>
                            <div class="col-md-9">
                                <input type="text" name="trailer" value="{{ old('trailer') }}" class="form-control">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('trailer') }}</span>
                            </div>
                        </div>
                        <!-- END Basic Form Elements Content -->
                    <!-- END Basic Form Elements Block -->
                </div>
                <div class="col-md-6">
                    <!-- Basic Form Elements Block -->
                        <!-- Basic Form Elements Content -->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-select">Quốc gia</label>
                            <div class="col-md-9">
                                <select name="country[]" class="select-chosen" data-placeholder="Choose a country.." multiple>
                                    <option></option>
                                    @foreach($countries as $key => $country)
                                    <option value="{{ $country->id}}" <?php if(old('country')){ echo (in_array($country->id, old('country')))? 'selected': '';} ?>>{!! $country->title !!}</option>
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
                                    <option value="{{ $genre->id}}" <?php if(old('genre')){ echo (in_array($genre->id, old('genre')))? 'selected': '';} ?>>{!! $genre->title !!}</option>
                                    @endforeach
                                </select>
                                <span class="help-block alert-warning text-danger">{{ $errors->first('genre') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-select">Chất lượng</label>
                            <div class="col-md-9">
                                <select name="quality_id" class="select-chosen" data-placeholder="Choose a quality..">
                                    @foreach($qualities as $key => $quality)
                                    <option value="{{ $quality->id}}" <?php if(old('quality_id')){ echo old('quality_id')==$quality->id? 'selected': '';} ?>>{!! $quality->title !!}</option>
                                    @endforeach
                                </select>
                                <span class="help-block alert-warning text-danger">{{ $errors->first('quality_id') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Số tập</label>
                            <div class="col-md-9">
                                <input type="text" name="episodes" class="form-control number"  value="{{ old('episodes') }}">
                                <span class="text-danger">{!! $errors->first('episodes') !!}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tập đang chiếu</label>
                            <div class="col-md-9">
                                <input type="text" name="exist_episodes" class="form-control number"  value="{{ old('exist_episodes') }}">
                                <span class="text-danger">{!! $errors->first('exist_episodes') !!}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Thời lượng</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" name="runtime" class="form-control number"  value="{{ old('runtime') }}">
                                    <span class="input-group-addon">phút</span>
                                </div>
                                <span class="text-danger">{!! $errors->first('runtime') !!}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">link IMdb</label>
                            <div class="col-md-9">
                                <input type="text" name="imdb_url" class="form-control" placeholder="Url" value="{{ old('imdb_url') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Seo Title</label>
                            <div class="col-md-9">
                                <input id="seo_title" type="text" name="seo[title]" class="form-control" placeholder="" value="{{ old('seo')['title'] }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Seo Keyword</label>
                            <div class="col-md-9">
                                <input type="text" name="seo[keyword]" class="form-control" placeholder="" value="{{ old('seo')['keyword'] }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Description</label>
                            <div class="col-md-9">
                                <textarea style="height: 100px;" class="form-control" name="description">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Ghi chú</label>
                            <div class="col-md-9">
                                <textarea style="height: 70px;" class="form-control" name="extend[alert]">{{ old('extend')['alert'] }}</textarea>
                            </div>
                        </div>
                        <!-- END Basic Form Elements Content -->
                    <!-- END Basic Form Elements Block -->
                </div>
            </div>
            <div class="clearfix"><hr></div>
            <div class="col-md-6">
                    <div class="form-group">
                        <label>Chọn server upload</label>
                        <select name="server_img" class="select-chosen" data-placeholder="Choose a server..">
                            @foreach($servers->where('type','ftp') as $key => $server)
                            <option value="{{ $server->id }}" <?php if(old('server_img')){ echo old('server_img')==$server->id? 'selected': ''; } ?>>{{ $server->title }}</option>
                            @endforeach
                        </select>
                        <span class="help-block alert-warning text-danger">{{ $errors->first('server_img') }}</span>
                    </div>
                </div>
            <div class="clearfix"><hr></div>
            <div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-5 control-label">Ảnh nhỏ<br><small>(300x450 pixel recommend)</small></label>
                        <div class="col-md-7">
                            <input type="file" name="thumb">
                            <span class="text-danger">{!! $errors->first('thumb') !!}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-5 control-label">Ảnh to<br><small>(1170x644 pixel recommend)</small></label>
                        <div class="col-md-7">
                            <input type="file" name="cover">
                            <span class="text-danger">{!! $errors->first('cover') !!}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clearfix"><hr></div>
            <div class="form-group">
                <label class="control-label">Nội dung</label>
                <div class="">
                    <textarea class="textarea" name="storyline">{{old('storyline')}}</textarea>
                    <span class="help-block">{!! $errors->first('storyline') !!}</span>
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
<div id="storylineVal" class="hide">

</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script src="{{asset('themes/admin/js/pages/formsValidation.js')}}"></script>
<script>$(function(){ FormsValidation.init(); });</script>
<script type="text/javascript">
    $('#postFilm').submit(function(){
        if ($('[name="thumb"]').val().length == 0) {
            $('[name="thumb"]').parents('.form-group').addClass('has-error');
            $('[name="thumb"]').trigger('focus');
            return false;
        }
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

    $('body').on('click', '.get-info-target', function(){
        if ($('#getInfo').val().length > 0) {
            $.ajax({
                url: '{{route('get_info_auto')}}',
                type: 'POST',
                data: {url: $('#getInfo').val(), _token: '{{csrf_token()}}' },
                dataType: 'json',
                success: function(res){
                    console.log(res);
                    $('[name="title"]').val(res.title);
                    $('[name="title_en"]').val(res.title_en);
                    $('[name="date"]').val(res.date);
                    $('[name="stars"]').val(res.actor);
                    $('[name="director"]').val(res.director);
                    // $('[name="category_id"]').val(res.);
                    // $('[name="country[]"]').val(res.);
                    // $('[name="genre[]"]').val(res.);
                    $('[name="runtime"]').val(res.runtime);
                    $('[name="imdb_url"]').val(res.imdb_url);
                    if(res.episode){
                        $('[name="episodes"]').val(res.episode);
                    }else{
                        document.getElementById('category_id').value=1;
                    }


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
                },
                error: function(err){
                    console.log('dsdsd');
                },
            });
        }
    });
</script>
@stop
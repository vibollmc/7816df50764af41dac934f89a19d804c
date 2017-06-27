@extends('admin.master')

@section('content')

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Edit Film</h2>
        <div class="block-options pull-right">
            <a href="{{route('create_film')}}" class="btn btn-primary">Thêm mới</a>
        </div>
    </div>
    <!-- END Table Styles Title -->
    @if(\Session::has('message'))
        {!!\Session::get('message')!!}
    @endif
    @if(\Session::has('message_error'))
    <div class="alert alert-danger">{{\Session::get('message_error')}}</div>
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
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="clearfix"></div>
    <form action="{{ route('update_film', $result->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div>
            <div>
                <div class="col-md-6">
                    <!-- Basic Form Elements Block -->
                        <!-- Basic Form Elements Content -->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-text-input">Tên tiếng Việt</label>
                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control to-slug" placeholder="" value="<?php echo (old('title'))? old('title'): $result->title; ?>">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('title') }}<?php echo (Session::get('error_title'))? Session::get('error_title'): ''; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-text-input">Tên tiếng Anh</label>
                            <div class="col-md-9">
                                <input type="text" name="title_en" class="form-control to-slug" placeholder="" value="<?php echo (old('title_en'))? old('title_en'): $result->title_en; ?>">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('title_en') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Slug</label>
                            <div class="col-md-9">
                                <input type="text" id="slug" name="slug" value="<?php echo (old('slug'))? old('slug'): $result->slug; ?>" class="form-control">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('slug') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Năm phát hành</label>
                            <div class="col-md-9">
                                <input type="number" name="date" value="<?php echo (old('date'))? old('date'): $result->date; ?>" class="form-control">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('date') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Diễn viên</label>
                            <div class="col-md-9">
                                <input type="text" name="stars" value="<?php if(old('stars')){ echo old('stars');}else{ foreach($result->stars as $star){ echo $star['title'].','; } } ?>" class="form-control" placeholder="add a star">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('stars') }}</span>
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
                            <label class="col-md-3 control-label">Tags</label>
                            <div class="col-md-9">
                                <input type="text" name="tags" value="<?php if(old('tags')){ echo old('tags');}else{ foreach($result->tags as $tag){ echo $tag['title'].','; } } ?>" class="form-control input-tags">
                                <span class="help-block alert-warning text-danger">{{ $errors->first('tags') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-select">Danh mục</label>
                            <div class="col-md-9">
                                <select name="category_id" class="select-chosen" data-placeholder="Choose a category..">
                                    @foreach($categories as $key => $category)
                                    <option value="{{ $category->id}}" <?php if(old('category_id')){ echo old('category')==$category->id? 'selected': '';}else{echo $result->category_id == $category->id? 'selected': ''; } ?>>{!! $category->title !!}</option>
                                    @endforeach
                                </select>
                                <span class="help-block alert-warning text-danger">{{ $errors->first('category') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
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
                        <div class="form-group">
                            <label class="col-md-3 control-label">Link trailer (youtube)</label>
                            <div class="col-md-9">
                                <input type="text" name="trailer" class="form-control"  value="{{ (old('trailer'))? old('trailer'): $result->trailer }}">
                                <span class="text-danger">{!! $errors->first('trailer') !!}</span>
                            </div>
                        </div>
                        <!-- END Basic Form Elements Content -->
                    <!-- END Basic Form Elements Block -->
                </div>
                <div class="col-md-6">
                    <!-- Basic Form Elements Block -->
                        <!-- Basic Form Elements Content -->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-select">Loại phim</label>
                            <div class="col-md-9">
                                <select name="member" class="select-chosen" data-placeholder="">
                                    <option value="1" {{$result->member == 1? 'selected': ''}}>Member's film</option>
                                    <option value="" {{$result->member != 1? 'selected': ''}}>Admin's film</option>
                                </select>
                                <span class="help-block alert-warning text-danger">{{ $errors->first('category') }}</span>
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
                                <span class="help-block alert-warning text-danger">{{ $errors->first('genres') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-select">CHất lượng</label>
                            <div class="col-md-9">
                                <select name="quality_id" class="select-chosen" data-placeholder="Choose a quality..">
                                    <option></option>
                                    @foreach($qualities as $key => $quality)
                                    <option value="{{ $quality->id}}" <?php if(old('quality_id')){ echo old('quality_id')==$quality->id? 'selected': '';}else{ echo $result->quality_id==$quality->id? 'selected': ''; } ?>>{!! $quality->title !!}</option>
                                    @endforeach
                                </select>
                                <span class="help-block alert-warning text-danger">{{ $errors->first('quality') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Số tập</label>
                            <div class="col-md-9">
                                <input type="text" name="episodes" class="form-control number"  value="<?php echo (old('episodes'))? old('episodes'): $result->episodes; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Tập đang chiếu</label>
                            <div class="col-md-9">
                                <input type="text" name="exist_episodes" class="form-control number"  value="<?php echo (old('exist_episodes'))? old('exist_episodes'): $result->exist_episodes; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Thời lượng</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="text" name="runtime" class="form-control number"  value="<?php echo (old('runtime'))? old('runtime'): $result->runtime; ?>">
                                    <span class="input-group-addon">min</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">IMdb link</label>
                            <div class="col-md-7">
                                <input type="text" name="imdb_url" class="form-control" placeholder="Url" value="<?php echo (old('imdb_url'))? old('imdb_url'): $result->imdb_url; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Seo Title</label>
                            <div class="col-md-9">
                                <input id="seo_title" type="text" name="seo[title]" class="form-control" placeholder="" value="<?php echo (old('seo')['title'])? old('seo')['title']: json_decode($result->seo)->title; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Seo Keyword</label>
                            <div class="col-md-9">
                                <input type="text" name="seo[keyword]" class="form-control" placeholder="" value="<?php echo (old('seo')['keyword'])? old('seo')['keyword']: json_decode($result->seo)->keyword; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Description</label>
                            <div class="col-md-9">
                                <textarea style="height: 100px;" class="form-control" name="description"><?php echo (old('description'))? old('description'): $result->description; ?></textarea>
                            </div>
                        </div>
                        <?php
                        $extend = json_decode($result->extend);
                        $alert = isset($extend->alert)? $extend->alert: '';
                         ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Ghi chú</label>
                            <div class="col-md-9">
                                <textarea style="height: 70px;" class="form-control" name="extend[alert]"><?php echo (old('extend')['alert'])? old('extend')['alert']: $alert; ?></textarea>
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Server ảnh</label>
                        <div class="row col-md-12">
                            <select name="server_img" class="select-chosen" data-placeholder="Choose a server..">
                                @foreach($servers->whereLoose('type','ftp') as $key => $server)
                                <option value="{{ $server->id }}" <?php if(old('server_img')){ echo old('server_img')==$server->id? 'selected': ''; }else{ echo $result->thumb['server_id']==$server->id? 'selected': ''; } ?>>{{ $server->title }}</option>
                                @endforeach
                            </select>
                            <span class="help-block alert-warning text-danger">{{ $errors->first('server_img') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
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
                <div class="col-md-4">
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
                <label class="control-label">Nội dung</label>
                <div class="">
                    <textarea class="textarea" name="storyline"><?php echo (old('storyline'))? old('storyline'): $result->storyline; ?></textarea>
                    <span class="help-block">{!! $errors->first('storyline') !!}</span>
                </div>
            </div>
            <div class="clearfix"><hr></div>
            <div class="form-horizontal">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-actions text-center">
                            <input type="submit" name="btn_save" class="btn btn-primary" value="Save">
                            <input type="reset" class="btn btn-danger" value="Reset">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script src="{{asset('themes/admin/js/pages/formsValidation.js')}}"></script>
<script>$(function(){ FormsValidation.init(); });</script>
<script type="text/javascript">
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
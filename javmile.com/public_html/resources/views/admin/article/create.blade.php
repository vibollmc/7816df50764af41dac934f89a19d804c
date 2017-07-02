@extends('admin.master')

@section('content')

<!-- Table Styles Header -->
<ul class="breadcrumb breadcrumb-top">
    <li>Article</li>
    <li><a href="javascript:void(0)">Create</a></li>
</ul>
<!-- END Table Styles Header -->

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>New Article</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <form id="form-validation" action="{{ route('store_article') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row">
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                <div class="block">
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">Title</label>
                        <div>
                            <input type="text" id="title" name="title" class="form-control" placeholder="" value="{{ old('title') }}">
                            <span class="help-block">{!! $errors->first('title') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">Slug</label>
                        <div>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="form-control" placeholder="">
                            <span class="help-block">{!! $errors->first('slug') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-6">
                            <label class="control-label" for="example-multiple-select">Status</label>
                            <div>
                                <label class="switch switch-primary" for="val_terms">
                                    <input type="checkbox" <?php echo (old('status')==null)? '':'checked'; ?> id="val_terms" name="status" value="1">
                                    <span data-toggle="tooltip" title="" data-original-title="ON/OFF"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <label class="control-label" for="example-multiple-select">Online</label>
                            <div>
                                <label class="switch switch-primary" for="online">
                                    <input type="checkbox" <?php echo (old('online')==null)? '':'checked'; ?> id="online" name="online" value="1">
                                    <span data-toggle="tooltip" title="" data-original-title="ON/OFF"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Type</label>
                        <div>
                            <select name="type" class="form-control">
                                <option value="term">Điều khoản</option>
                                <option value="help">Trợ giúp</option>
                                <option value="new">Tin tức, thông báo</option>
                            </select>
                            <span class="help-block">{!! $errors->first('type') !!}</span>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                </div>
                <!-- END Basic Form Elements Block -->
            </div>
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                <div class="block">
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">Seo title</label>
                        <div>
                            <input id="seo_title" type="text" name="seo[title]" class="form-control" value="{{ old('seo')['title'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">Seo keyword</label>
                        <div>
                            <input type="text" name="seo[keyword]" class="form-control" value="{{ old('seo')['keyword'] }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">Description</label>
                        <div>
                            <textarea name="seo[description]" class="form-control">{{ old('seo')['description'] }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Tags</label>
                        <div class="">
                            <input type="text" name="tags" value="{{ old('tags') }}" class="form-control input-tags" placeholder="">
                            <span class="help-block alert-warning text-danger">{{ $errors->first('tags') }}</span>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                </div>
                <!-- END Basic Form Elements Block -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label" for="example-text-input">Chose server</label>
                    <div>
                        <select name="server" class="form-control select-chosen">
                            @foreach($servers->where('type', 'ftp') as $server)
                            <option value="{{ $server->id }}" <?php echo (old('type_id')==$server->id)? 'selected':''; ?>>{{ $server->title }}</option>
                            @endforeach
                        </select>
                        <span class="help-block">{!! $errors->first('server') !!}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label" for="example-text-input">Thumb</label>
            <div>
                <input type="file" name="cover">
                <span class="help-block">{!! $errors->first('cover') !!}</span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label" for="example-text-input">Content</label>
            <div>
                <textarea name="content" class="textarea" style="height:300px;">{{ old('content') }}</textarea>
                <span class="help-block">{!! $errors->first('content') !!}</span>
            </div>
        </div>
        <div class="form-group form-actions">
            <div class="col-md-9 col-md-offset-4">
                <input type="submit" name="btn_save" class="btn btn-info" value="Save">
                <input type="reset" class="btn btn-danger" value="Reset">
            </div>
        </div>
    </form>
</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script src="{{asset('themes/admin/js/pages/formsValidation.js')}}"></script>
<script>$(function(){ FormsValidation.init(); });</script>
  <!-- Initialize the editor. -->
<script >
  $(document).ready(function(){
        $('#title').on('keyup', function(e){
            var str = title.value;
            str= str.toLowerCase();
            str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");
            str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");
            str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i");
            str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");
            str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");
            str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");
            str= str.replace(/đ/g,"d");
            str= str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g,"-");
            /* tìm và thay thế các kí tự đặc biệt trong chuỗi sang kí tự - */
            str= str.replace(/-+-/g,"-"); //thay thế 2- thành 1-
            str= str.replace(/^\-+|\-+$/g,"");
            //cắt bỏ ký tự - ở đầu và cuối chuỗi
            slug.value = str;
            seo_title.value = title.value;
        });
    });
</script>
@stop
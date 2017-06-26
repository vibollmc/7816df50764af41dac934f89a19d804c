@extends('admin.master')

@section('content')

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Edit Quality</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <form action="{{ route('update_quality', $result->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Title</label>
                        <div class="col-md-9">
                            <input type="text" id="title" name="title" class="form-control" placeholder="" value="<?php echo old('title')? old('title'): $result->title; ?>">
                            <span class="help-block">{!! $errors->first('title') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Slug</label>
                        <div class="col-md-9">
                            <input type="text" id="slug" name="slug" value="<?php echo old('slug')? old('slug'): $result->slug; ?>" class="form-control" placeholder="">
                            <span class="help-block">{!! $errors->first('slug') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-multiple-select">Status</label>
                        <div class="col-md-9">
                            <label class="switch switch-primary" for="val_terms">
                                <input type="checkbox" <?php if(old('status')==1){ echo 'checked';}else{if($result->status==1){echo 'checked';}} ?> id="val_terms" name="status" value="1">
                                <span data-toggle="tooltip" title="" data-original-title="ON/OFF"></span>
                            </label>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Seo Title</label>
                        <div class="col-md-9">
                            <input id="seo_title" type="text" name="seo[title]" class="form-control" placeholder="" value="<?php echo old('seo')['title']? old('seo')['title']: json_decode($result->seo)->title; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Seo Keyword</label>
                        <div class="col-md-9">
                            <input type="text" name="seo[keyword]" class="form-control" placeholder="" value="<?php echo old('seo')['keyword']? old('seo')['keyword']: json_decode($result->seo)->keyword; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Description</label>
                        <div class="col-md-9">
                            <textarea style="height: 100px;" class="form-control" name="description"><?php echo old('description')? old('description'): $result->description; ?></textarea>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <div class="form-group form-actions">
                <div class="col-md-9 col-md-offset-4">
                    <input type="submit" name="btn_save_exit" class="btn btn-primary" value="Save & Exit">
                    <input type="reset" class="btn btn-danger" value="Reset">
                </div>
            </div>
    </form>
</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/formsValidation.js"></script>
<script>$(function(){ FormsValidation.init(); });</script>
<script type="text/javascript">
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
        str= str.replace(/-+-/g,"-"); //thay thế 2- thành 1-
        str= str.replace(/^\-+|\-+$/g,"");
        slug.value = str;
        seo_title.value = title.value;
    });
</script>
@stop
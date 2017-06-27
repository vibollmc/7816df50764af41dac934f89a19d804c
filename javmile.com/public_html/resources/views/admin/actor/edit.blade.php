@extends('admin.master')

@section('content')

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Edit Actor profile</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <form action="{{ route('update_actor', $result->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Artist name</label>
                        <div class="col-md-9">
                            <input type="text" name="title" class="form-control" placeholder="" value="<?php echo old('title')? old('title'): $result->title; ?>">
                            <span class="text-danger">{!! $errors->first('title') !!}<?php echo Session::has('title_err')? Session::get('title_err'): ''; ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Full name</label>
                        <div class="col-md-9">
                            <input type="text" name="fullname" value="<?php echo old('fullname')? old('fullname'): $result->fullname; ?>" class="form-control">
                            <span class="help-block">{!! $errors->first('fullname') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Birth day</label>
                        <div class="col-md-9">
                            <input type="text" name="birth" value="<?php echo old('birth')? old('birth'): $result->birth; ?>" class="form-control" placeholder="">
                            <span class="help-block text-danger">{!! $errors->first('birth') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Height</label>
                        <div class="col-md-9">
                            <input type="text" name="height" value="<?php echo old('height')? old('height'): $result->height; ?>" class="form-control" placeholder="">
                            <span class="help-block text-danger">{!! $errors->first('height') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Home town</label>
                        <div class="col-md-9">
                            <input type="text" name="home_town" value="<?php echo old('home_town')? old('home_town'): $result->home_town; ?>" class="form-control" placeholder="">
                            <span class="help-block text-danger">{!! $errors->first('home_town') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-multiple-select">Is hot</label>
                        <div class="col-md-9">
                            <label class="switch switch-primary" for="val_terms">
                                <input type="checkbox" <?php if(old('hot')==1){ echo 'checked';}else{if($result->hot==1){echo 'checked';}} ?> id="val_terms" name="hot" value="1">
                                <span data-toggle="tooltip" title="" data-original-title="ON/OFF"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Slug</label>
                        <div class="col-md-9">
                            <input type="text" name="slug" value="<?php echo old('slug')? old('slug'): $result->slug; ?>" class="form-control" placeholder="">
                            <span class="text-danger">{!! $errors->first('slug') !!}<?php echo Session::has('slug_err')? Session::get('slug_err'): ''; ?></span>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <div class="col-md-6">
                <?php
                    $seo = json_decode($result->seo);
                    $seo_title = isset($seo->title)? $seo->title: '';
                    $seo_keyword = isset($seo->keyword)? $seo->keyword: '';
                    $seo_description = isset($seo->description)? $seo->description: '';
                 ?>
                <div class="form-group">
                    <label class="control-label" for="example-email-input">Seo title</label>
                    <div class="">
                        <input type="text" name="seo[title]" value="<?php echo old('seo')['title']? old('seo')['title']: $seo_title; ?>" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="example-email-input">Seo keyword</label>
                    <div class="">
                        <input type="text" name="seo[keyword]" value="<?php echo old('seo')['keyword']? old('seo')['keyword']: $seo_keyword; ?>" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="example-email-input">Seo description</label>
                    <div class="">
                        <textarea class="form-control" name="seo[description]"><?php echo old('seo')['description']? old('seo')['description']: $seo_description; ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Image Server</label>
                    <div class="row col-md-12">
                        <select name="server_img" class="select-chosen" data-placeholder="Choose a server..">
                            @foreach($servers->where('type','ftp') as $key => $server)
                            <option value="{{ $server->id }}" <?php if(old('server_img')){ echo old('server_img')==$server->id? 'selected': ''; }else{ echo $result->thumb['server_id']==$server->id? 'selected': ''; } ?>>{{ $server->title }}</option>
                            @endforeach
                        </select>
                        <span class="help-block alert-warning text-danger">{{ $errors->first('server_img') }}</span>
                    </div>
                </div>
                <?php
                    if (!is_null($result->ftp_id)) {
                        $image_data = json_decode($result->image_server->data);
                        $image_prefix = $image_data->public_url.'/'.$image_data->dir;
                    }

                 ?>
                <div class="form-group">
                    <label class="control-label" for="example-email-input">Avatar <small>(300x450 pixel image size recommend)</small></label>
                    <div>
                        <input type="file" name="file">
                    </div>
                </div>
                <div class="col-xs-3">
                    <img src="{{ is_null($result->thumb_name)? '':$image_prefix.$result->thumb_name }}" class="img-responsive">
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group">
                <label>Story</label>
                <textarea name="story" class="form-control ckeditor"><?php echo (old('story'))? old('story'): $result->story; ?></textarea>
                <span class="help-block alert-warning text-danger"></span>
            </div>
            <div class="form-group form-actions">
                <div class="col-md-9 col-md-offset-4">
                    <input type="submit" class="btn btn-primary" value="Save & Exit">
                    <input type="reset" class="btn btn-danger" value="Reset">
                </div>
            </div>
    </form>
</div>
<!-- END Table Styles Block -->
@stop
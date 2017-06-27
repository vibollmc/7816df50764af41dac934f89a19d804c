@extends('admin.master')

@section('content')

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Edit Tag</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <form action="{{ route('update_tag', $result->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="control-label" for="example-text-input">Title</label>
                        <div class="">
                            <input type="text" name="title" class="form-control" placeholder="" value="<?php echo old('title')? old('title'): $result->title; ?>">
                            <span class="text-danger">{!! $errors->first('title') !!}<?php echo Session::has('title_err')? Session::get('title_err'): ''; ?></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Slug</label>
                        <div class="">
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
                    <label class="control-label col-md-3" for="example-email-input">Seo title</label>
                    <div class="col-md-9">
                        <input type="text" name="seo[title]" value="<?php echo old('seo')['title']? old('seo')['title']: $seo_title; ?>" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3" for="example-email-input">Seo keyword</label>
                    <div class="col-md-9">
                        <input type="text" name="seo[keyword]" value="<?php echo old('seo')['keyword']? old('seo')['keyword']: $seo_keyword; ?>" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3" for="example-email-input">Seo description</label>
                    <div class="col-md-9">
                        <textarea class="form-control" name="seo[description]"><?php echo old('seo')['description']? old('seo')['description']: $seo_description; ?></textarea>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group form-actions">
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" value="Save & Exit">
                    <input type="reset" class="btn btn-danger" value="Reset">
                </div>
            </div>
    </form>
</div>
<!-- END Table Styles Block -->
@stop
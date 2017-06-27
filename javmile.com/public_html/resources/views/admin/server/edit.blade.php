@extends('admin.master')

@section('content')
@if (Session::has('message'))
{!! Session::get('message') !!}
@endif
<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Edit Server</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <form action="{{ route('post_edit_server', $result->id) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
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
                        <label class="col-md-3 control-label" for="example-email-input">Type</label>
                        <div class="col-md-9">
                            <select id="type" name="type" class="form-control">
                                <option value="ftp" <?php if(old('type')){echo (old('type')=='ftp')? 'selected':'';}else{ echo $result->type=='ftp'? 'selected': '';} ?>>Ftp</option>
                                <option value="lighttpd" <?php if(old('type')){echo old('type')=='lighttpd'? 'selected':'';}else{ echo $result->type=='lighttpd'? 'selected': '';} ?>>Lighttpd</option>
                                <option value="wowza" <?php if(old('type')){echo old('type')=='wowza'? 'selected':'';}else{ echo $result->type=='wowza'? 'selected': '';} ?>>Wowza</option>
                                <option value="iframe" <?php if(old('type')){echo old('type')=='iframe'? 'selected':'';}else{ echo $result->type=='iframe'? 'selected': '';} ?>>IFrame</option>
                                <option value="g_drive" <?php if(old('type')){echo old('type')=='g_drive'? 'selected':'';}else{ echo $result->type=='g_drive'? 'selected': '';} ?>>G.Drive</option>
                            </select>
                            <span class="help-block">{!! $errors->first('type') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-multiple-select">Status</label>
                        <div class="col-md-9">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?php if(old('status')){echo (old('status')==null)? '':'checked';}else{ echo $result->status==1? 'checked': ''; } ?> name="status" value="1">
                                <span data-toggle="tooltip" title="" data-original-title="ON/OFF"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-multiple-select">Is Default</label>
                        <div class="col-md-9">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?php if(old('default')){echo (old('default')==null)? '':'checked';}else{ echo $result->default==1? 'checked': ''; } ?> name="default" value="1">
                                <span data-toggle="tooltip" title="" data-original-title="ON/OFF"></span>
                            </label>
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
            <!-- Ftp modal -->
            <?php
            $data['host'] = $data['port'] = $data['username'] = $data['password'] = $data['dir'] = $data['public_url'] = $data['domain'] = $data['prefix'] = $data['secret'] = $data['suffix'] = $data['hash_type'] = $data['hash_param'] = '';
            $data = json_decode(json_encode($data));
            $data_result = json_decode($result->data);
            foreach ($data_result as $key => $value) {
                $data->$key = $value;
            }
            if(!isset($data->frame)){
                $frame = '';
            }else{
                $frame = $data->frame;
            }
             ?>
            <div id="main_wrap" class="col-md-6"></div>
            <div id="wrap_detail_ftp" class="hide wrap_detail col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">IP/Domaint</label>
                        <div class="col-md-9">
                            <input type="text" name="data[host]" class="form-control" value="<?php if(isset( old('data')['host'])){echo  old('data')['host'];}else{ echo $data->host;} ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Port</label>
                        <div class="col-md-9">
                            <input type="text" name="data[port]" class="form-control" value="<?php if(isset( old('data')['port'])){echo  old('data')['port'];}else{ echo $data->port;} ?>">
                            <span class="help-block">{!! $errors->first('port') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Username</label>
                        <div class="col-md-9">
                            <input type="text" name="data[username]" class="form-control" value="<?php if(isset( old('data')['username'])){echo  old('data')['username'];}else{ echo $data->username;} ?>">
                            <span class="help-block">{!! $errors->first('username') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Password</label>
                        <div class="col-md-9">
                            <input type="text" name="data[password]" class="form-control" value="<?php if(isset( old('data')['password'])){echo  old('data')['password'];}else{ echo $data->password;} ?>">
                            <span class="help-block">{!! $errors->first('password') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Folder</label>
                        <div class="col-md-9">
                            <input type="text" name="data[dir]" class="form-control" value="<?php if(isset( old('data')['dir'])){echo  old('data')['dir'];}else{ echo $data->dir;} ?>">
                            <span class="help-block">{!! $errors->first('dir') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Public Url</label>
                        <div class="col-md-9">
                            <input type="text" name="data[public_url]" class="form-control" value="<?php if(isset( old('data')['public_url'])){echo  old('data')['public_url'];}else{ echo $data->public_url;} ?>">
                            <span class="help-block">{!! $errors->first('public_url') !!}</span>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <!-- Lighttpd modal -->
            <div id="wrap_detail_lighttpd" class="hide wrap_detail col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Public url</label>
                        <div class="col-md-9">
                            <input type="text" name="data[domain]" class="form-control" value="<?php if(isset( old('data')['domain'])){echo  old('data')['domain'];}else{ echo $data->domain;} ?>">
                            <span class="help-block">{!! $errors->first('domain') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Prefix</label>
                        <div class="col-md-9">
                            <input type="text" name="data[prefix]" class="form-control" value="<?php if(isset( old('data')['prefix'])){echo  old('data')['prefix'];}else{ echo $data->prefix;} ?>">
                            <span class="help-block">{!! $errors->first('prefix') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Secret</label>
                        <div class="col-md-9">
                            <input type="text" name="data[secret]" class="form-control" value="<?php if(isset( old('data')['secret'])){echo  old('data')['secret'];}else{ echo $data->secret;} ?>">
                            <span class="help-block">{!! $errors->first('secret') !!}</span>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <!-- Wowza modal -->
            <div id="wrap_detail_wowza" class="hide wrap_detail col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Prefix</label>
                        <div class="col-md-9">
                            <input type="text" name="data[prefix]" class="form-control" value="<?php if(isset( old('data')['prefix'])){echo  old('data')['prefix'];}else{ echo $data->prefix;} ?>">
                            <span class="help-block">{!! $errors->first('prefix') !!}</span>
                            <span class="help-block">Example: https://555945a30.streamlock.net/film/_definst_/video/</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Suffix</label>
                        <div class="col-md-9">
                            <input type="text" name="data[suffix]" class="form-control" value="<?php if(isset( old('data')['suffix'])){echo  old('data')['suffix'];}else{ echo $data->suffix;} ?>">
                            <span class="help-block">{!! $errors->first('suffix') !!}</span>
                            <span class="help-block">Example: /playlist.m3u8</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Shared Secret</label>
                        <div class="col-md-9">
                            <input type="text" name="data[secret]" class="form-control" value="<?php if(isset( old('data')['secret'])){echo  old('data')['secret'];}else{ echo $data->secret;} ?>">
                            <span class="help-block">{!! $errors->first('secret') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Hash Algorithm</label>
                        <div class="col-md-9">
                            <select name="data[hash_type]" class="form-control">
                                <option value="">None</option>
                                <option value="SHA-256" <?php if(isset(old('data')['hash_type'])){echo old('data')['hash_type']=='SHA-256'? 'selected': '';}else{ echo $data->hash_type=='SHA-256'? 'selected': ''; } ?>>SHA-256</option>
                                <option value="SHA-384" <?php if(isset(old('data')['hash_type'])){echo old('data')['hash_type']=='SHA-384'? 'selected': '';}else{ echo $data->hash_type=='SHA-384'? 'selected': ''; } ?>>SHA-384</option>
                                <option value="SHA-512" <?php if(isset(old('data')['hash_type'])){echo old('data')['hash_type']=='SHA-512'? 'selected': '';}else{ echo $data->hash_type=='SHA-512'? 'selected': ''; } ?>>SHA-512</option>
                            </select>
                            <span class="help-block">{!! $errors->first('hash_type') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Hash Query Parameter Prefix</label>
                        <div class="col-md-9">
                            <input type="text" name="data[hash_param]" class="form-control" value="<?php if(isset( old('data')['hash_param'])){echo  old('data')['hash_param'];}else{ echo $data->hash_param;} ?>">
                            <span class="help-block">{!! $errors->first('hash_param') !!}</span>
                            <span class="help-block">Example: wowzaParameterToken</span>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <div id="wrap_detail_iframe" class="hide wrap_detail col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Frame Html</label>
                        <div class="col-md-9">
                            <textarea style="height: 150px;" class="form-control" name="data[frame]"><?php echo old('data')['frame']? old('data')['frame']: $frame; ?></textarea>
                            <span class="help-block">Example:<p>{{'<iframe width="100%" height="380" src="" frameborder="0" allowfullscreen></iframe>'}}</p><p>Note: don't change <b><i>src=""</i></b></p></span>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <!-- Google drive modal -->
            <div id="wrap_detail_g_drive" class="hide wrap_detail col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Public url</label>
                        <div class="col-md-9">
                            <input type="text" name="data[domain]" class="form-control" value="<?php if(isset( old('data')['domain'])){echo  old('data')['domain'];}else{ echo $data->domain;} ?>">
                            <span class="help-block">{!! $errors->first('domain') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Prefix</label>
                        <div class="col-md-9">
                            <input type="text" name="data[prefix]" class="form-control" value="<?php if(isset( old('data')['prefix'])){echo  old('data')['prefix'];}else{ echo $data->prefix;} ?>">
                            <span class="help-block">{!! $errors->first('prefix') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Sub link</label>
                        <div class="col-md-9">
                            <input type="text" name="data[sub_link]" class="form-control" value="<?php if(isset( old('data')['sub_link'])){echo  old('data')['sub_link'];}else{ echo isset($data->sub_link)? $data->sub_link: '';} ?>">
                            <span class="help-block">{!! $errors->first('sub_link') !!}</span>
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
<script src="{{asset('themes/admin/js/pages/formsValidation.js')}}"></script>
<script>$(function(){ FormsValidation.init(); });</script>
<script type="text/javascript">
    $(function(){
        setTimeout(function(){$('[name="type"]').change();}, 100);
        $('body').on('change', '[name="type"]', function(e){
            e.preventDefault();
            var i = $(this);
            var type = i.val();
            $('.wrap_detail').addClass('hide');
            var html = $('#wrap_detail_' + type).html();
            $('#main_wrap').html(html);
            // $('#wrap_detail_' + type).removeClass('hide');
        });
    });
    $( "form" ).submit(function() {
        var modal = $('.wrap_detail');
        if (modal.hasClass('hide')) {
            $('.hide').remove();
        }
        return;
    });
</script>
@stop
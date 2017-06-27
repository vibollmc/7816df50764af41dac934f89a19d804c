@extends('admin.master')

@section('content')

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>Create Server</h2>
    </div>
    <!-- END Table Styles Title -->

    <!-- Table Styles Content -->
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="table-options clearfix"></div>
    <form action="{{ route('store_server') }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-text-input">Title</label>
                        <div class="col-md-9">
                            <input type="text" id="title" name="title" class="form-control" placeholder="" value="{{ old('title') }}">
                            <span class="help-block">{!! $errors->first('title') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Type</label>
                        <div class="col-md-9">
                            <select id="type" name="type" class="form-control">
                                <option value="ftp" <?php echo old('type')=='ftp'? 'selected':''; ?>>Ftp</option>
                                <option value="lighttpd" <?php echo old('type')=='lighttpd'? 'selected':''; ?>>Lighttpd</option>
                                <option value="wowza" <?php echo old('type')=='wowza'? 'selected':''; ?>>Wowza</option>
                                <option value="iframe" <?php echo old('type')=='iframe'? 'selected':''; ?>>IFrame</option>
                                <option value="g_drive" <?php echo old('type')=='g_drive'? 'selected':''; ?>>G.Drive</option>
                            </select>
                            <span class="help-block">{!! $errors->first('type') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-multiple-select">Status</label>
                        <div class="col-md-9">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?php echo (old('status')==null)? '':'checked'; ?> name="status" value="1">
                                <span data-toggle="tooltip" title="" data-original-title="ON/OFF"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-multiple-select">Is Default</label>
                        <div class="col-md-9">
                            <label class="switch switch-primary">
                                <input type="checkbox" <?php echo (old('default')==null)? '':'checked'; ?> name="default" value="1">
                                <span data-toggle="tooltip" title="" data-original-title="ON/OFF"></span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Description</label>
                        <div class="col-md-9">
                            <textarea style="height: 100px;" class="form-control" name="description">{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <!-- Ftp modal -->
            <div id="main_wrap" class="col-md-6"></div>
            <div id="wrap_detail_ftp" class="hide wrap_detail col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">IP/Domaint</label>
                        <div class="col-md-9">
                            <input type="text" name="data[host]" class="form-control" value="<?php if(isset( old('data')['host'])){echo  old('data')['host'];} ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Port</label>
                        <div class="col-md-9">
                            <input type="text" name="data[port]" class="form-control" value="<?php echo isset(old('data')['port'])? old('data')['port']: ''; ?>">
                            <span class="help-block">{!! $errors->first('port') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Username</label>
                        <div class="col-md-9">
                            <input type="text" name="data[username]" class="form-control" value="<?php echo isset(old('data')['username'])? old('data')['username']: ''; ?>">
                            <span class="help-block">{!! $errors->first('username') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Password</label>
                        <div class="col-md-9">
                            <input type="text" name="data[password]" class="form-control" value="<?php echo isset(old('data')['password'])? old('data')['password']: ''; ?>">
                            <span class="help-block">{!! $errors->first('password') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Folder</label>
                        <div class="col-md-9">
                            <input type="text" name="data[dir]" class="form-control" value="<?php echo isset(old('data')['dir'])? old('data')['dir']: ''; ?>">
                            <span class="help-block">{!! $errors->first('dir') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Public Url</label>
                        <div class="col-md-9">
                            <input type="text" name="data[public_url]" class="form-control" value="<?php echo isset(old('data')['public_url'])? old('data')['public_url']: ''; ?>">
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
                            <input type="text" name="data[domain]" class="form-control" value="<?php echo isset(old('data')['domain'])? old('data')['domain']: ''; ?>">
                            <span class="help-block">{!! $errors->first('domain') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Prefix</label>
                        <div class="col-md-9">
                            <input type="text" name="data[prefix]" class="form-control" value="<?php echo isset(old('data')['prefix'])? old('data')['prefix']: ''; ?>">
                            <span class="help-block">{!! $errors->first('prefix') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Secret</label>
                        <div class="col-md-9">
                            <input type="text" name="data[secret]" class="form-control" value="<?php echo isset(old('data')['secret'])? old('data')['secret']: ''; ?>">
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
                            <input type="text" name="data[prefix]" class="form-control" value="<?php echo isset(old('data')['prefix'])? old('data')['prefix']: ''; ?>">
                            <span class="help-block">{!! $errors->first('prefix') !!}</span>
                            <span class="help-block">Example: https://555945a30.streamlock.net/film/_definst_/video/</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Suffix</label>
                        <div class="col-md-9">
                            <input type="text" name="data[suffix]" class="form-control" value="<?php echo isset(old('data')['suffix'])? old('data')['suffix']: ''; ?>">
                            <span class="help-block">{!! $errors->first('suffix') !!}</span>
                            <span class="help-block">Example: /playlist.m3u8</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Shared Secret</label>
                        <div class="col-md-9">
                            <input type="text" name="data[secret]" class="form-control" value="<?php (isset(old('data')['secret']))? old('data')['secret']: ''; ?>">
                            <span class="help-block">{!! $errors->first('secret') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Hash Algorithm</label>
                        <div class="col-md-9">
                            <select name="data[hash_type]" class="form-control">
                                <option value="">None</option>
                                <option value="SHA-256" <?php echo (isset(old('data')['hash_type']) and old('data')['hash_type']=='SHA-256')? 'selected': ''; ?>>SHA-256</option>
                                <option value="SHA-384" <?php echo (isset(old('data')['hash_type']) and old('data')['hash_type']=='SHA-384')? 'selected': ''; ?>>SHA-384</option>
                                <option value="SHA-512" <?php echo (isset(old('data')['hash_type']) and old('data')['hash_type']=='SHA-512')? 'selected': ''; ?>>SHA-512</option>
                            </select>
                            <span class="help-block">{!! $errors->first('hash_type') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Hash Query Parameter Prefix</label>
                        <div class="col-md-9">
                            <input type="text" name="data[hash_param]" class="form-control" value="<?php echo isset(old('data')['hash_param'])? old('data')['hash_param']: ''; ?>">
                            <span class="help-block">{!! $errors->first('hash_param') !!}</span>
                            <span class="help-block">Example: wowzaParameterToken</span>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <!-- I Frame -->
            <div id="wrap_detail_iframe" class="hide wrap_detail col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Frame Html</label>
                        <div class="col-md-9">
                            <textarea style="height: 150px;" class="form-control" name="data[frame]"><?php echo old('data')['frame']? old('data')['frame']: ''; ?></textarea>
                            <span class="help-block">Example:<p>{{'<iframe width="100%" height="380" src="" frameborder="0" allowfullscreen></iframe>'}}</p><p>Note: don't change <b><i>src=""</i></b></p></span>
                        </div>
                    </div>
                    <!-- END Basic Form Elements Content -->
                <!-- END Basic Form Elements Block -->
            </div>
            <!-- Google drive -->
            <div id="wrap_detail_g_drive" class="hide wrap_detail col-md-6">
                <!-- Basic Form Elements Block -->
                    <!-- Basic Form Elements Content -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Public url</label>
                        <div class="col-md-9">
                            <input type="text" name="data[domain]" class="form-control" value="<?php echo isset(old('data')['domain'])? old('data')['domain']: ''; ?>">
                            <span class="help-block">{!! $errors->first('domain') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Prefix</label>
                        <div class="col-md-9">
                            <input type="text" name="data[prefix]" class="form-control" value="<?php echo isset(old('data')['prefix'])? old('data')['prefix']: ''; ?>">
                            <span class="help-block">{!! $errors->first('prefix') !!}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="example-email-input">Sub link</label>
                        <div class="col-md-9">
                            <input type="text" name="data[sub_link]" class="form-control" value="<?php echo isset(old('data')['sub_link'])? old('data')['sub_link']: ''; ?>">
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
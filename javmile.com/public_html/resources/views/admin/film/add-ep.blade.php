@extends('admin.master')

@section('content')

<!-- Table Styles Block -->
<div class="block">
    <!-- Table Styles Title -->
    <div class="block-title">
        <h2>thêm mới tập phim <b>{{$result->title.' - '.$result->title_en}}</b></h2>
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
    <!-- Changing classes functionality initialized in js/pages/tablesGeneral.js -->
    <div class="clearfix"></div>
    <p>
        Lưu ý: tập bình thường ghi số chẵn 1 2 3 ...
        <br>
        tập chia nhỏ dạng: 5.1 5.2 5.3 ....
    </p>
    <form action="{{ route('store_ep', $result->id) }}" method="post" class="form-horizontal" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="block">
            <!-- Search Styles Title -->
            <div class="block-title">
                <ul class="nav nav-tabs" data-toggle="tabs">
                    <li class="active"><a href="#search-tab-single">Thêm 1 tập</a></li>
                    <li><a href="#search-tab-multi">Thêm nhiều tập</a></li>
                    <li><a href="#search-tab-fast">Thêm nhiều tập nhanh</a></li>
                </ul>
            </div>
            <!-- END Search Styles Title -->

            <!-- Search Styles Content -->
            <div class="tab-content">
                <!-- Projects Search -->
                <div class="tab-pane active" id="search-tab-single">
                    <!-- Search Info - Pagination -->
                    <div class="block-section clearfix">
                    </div>
                    <!-- END Search Info - Pagination -->

                    <!-- Projects Results -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-text-input">Tên tập</label>
                            <div class="col-md-9">
                                <input type="text" name="single[title]" class="form-control" placeholder="" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Liên kết</label>
                            <div class="col-md-9">
                                <input type="url" name="single[file_name]" value="" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Phụ đề</label>
                            <div class="col-md-9">
                                <input type="file" name="sub_vi" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Chọn loại</label>
                            <div class="col-md-9">
                                <div class="radio">
                                    <label for="example-radio1">
                                        <input type="radio" id="example-radio1" name="single[type]" value="Full" checked > Việt sub full
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="example-radio2">
                                        <input type="radio" id="example-radio2" name="single[type]" value="Part"> Chia nhỏ
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="example-radio3">
                                        <input type="radio" id="example-radio3" name="single[type]" value="ThuyetMinh"> Thuyết minh
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="example-radio4">
                                        <input type="radio" id="example-radio4" name="single[type]" {{$result->type == 'trailer'? 'checked': ''}} value="trailer"> Trailer
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="example-radio5">
                                        <input type="radio" id="example-radio5" name="single[type]" {{$result->type == 'raw'? 'checked': ''}} value="raw"> Nosub
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Projects Results -->
                    <div class="clearfix"></div>
                    <!-- Bottom Navigation -->
                    <div class="block-section">
                        <div class="form-group form-actions text-center">
                            <input type="submit" name="add_single" class="btn btn-primary" value="Hoàn tất">
                            <input type="reset" class="btn btn-danger" value="Làm lại">
                        </div>
                    </div>
                    <!-- END Bottom Navigation -->
                </div>
                <!-- END Projects Search -->

                <!-- Images Search -->
                <div class="tab-pane" id="search-tab-multi">
                    <!-- Search Info - Pagination -->
                    <div class="block-section clearfix">
                    </div>
                    <!-- END Search Info - Pagination -->
                    <!-- Projects Results -->
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-text-input">Tập bắt đầu</label>
                            <div class="col-md-9">
                                <input type="text" name="start" class="form-control" value="1">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-text-input">Tập kết thúc</label>
                            <div class="col-md-9">
                                <input type="text" name="end" class="form-control" value="10">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-text-input">Tên Tập kết thúc</label>
                            <div class="col-md-9">
                                <input type="text" name="end_name" class="form-control" value="" placeholder="ví dụ: 10End">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Chọn loại</label>
                            <div class="col-md-9">
                                <div class="radio">
                                    <label for="get_type1">
                                        <input type="radio" id="get_type1" name="get_type" value="Full" checked > Việt sub full
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="get_type2">
                                        <input type="radio" id="get_type2" name="get_type" value="Part"> Chia nhỏ
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="get_type3">
                                        <input type="radio" id="get_type3" name="get_type" value="ThuyetMinh"> Thuyết minh
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="get_type4">
                                        <input type="radio" id="get_type4" name="get_type" {{$result->type == 'trailer'? 'checked': ''}} value="trailer"> Trailer
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="get_type5">
                                        <input type="radio" id="get_type5" name="get_type" {{$result->type == 'raw'? 'checked': ''}} value="raw"> Nosub
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Projects Results -->
                    <div class="clearfix"></div>
                    <!-- Bottom Navigation -->
                    <div class="block-section">
                        <div class="form-group form-actions text-center">
                            <a href="javascript:void(0)" class="btn btn-primary multi-next">Tiếp theo</a>
                            <input type="reset" class="btn btn-danger" value="Làm lại">
                        </div>
                    </div>
                    <!-- END Bottom Navigation -->
                </div>
                <!-- END Images Search -->

                <!-- Users Search -->
                <div class="tab-pane" id="search-tab-fast">
                    <!-- Search Info - Pagination -->
                    <div class="block-section clearfix">
                    </div>
                    <!-- END Search Info - Pagination -->

                    <!-- Projects Results -->
                    <div class="">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-text-input">Thêm nhiều link</label>
                            <div class="col-md-9">
                                <span>Lưu ý không có dấu khoảng trắng(dấu cách). Mỗi link 1 dòng.</span>
                                <textarea name="string" class="form-control" placeholder="Tên tập|Liên kết" style="min-height: 300px;"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="example-email-input">Chọn loại</label>
                            <div class="col-md-9">
                                <div class="radio">
                                    <label for="fast_type1">
                                        <input type="radio" id="fast_type1" name="fast_type" value="Full" checked > Việt sub full
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="fast_type2">
                                        <input type="radio" id="fast_type2" name="fast_type" value="Part"> Chia nhỏ
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="fast_type3">
                                        <input type="radio" id="fast_type3" name="fast_type" value="ThuyetMinh"> Thuyết minh
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="fast_type4">
                                        <input type="radio" id="fast_type4" name="fast_type" {{$result->type == 'trailer'? 'checked': ''}} value="trailer"> Trailer
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="fast_type5">
                                        <input type="radio" id="fast_type5" name="fast_type" {{$result->type == 'raw'? 'checked': ''}} value="raw"> Nosub
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Projects Results -->
                    <div class="clearfix"></div>
                    <!-- Bottom Navigation -->
                    <div class="block-section">
                        <div class="form-group form-actions text-center">
                            <a href="javascript:void(0)" class="btn btn-primary add-fast">Tiếp theo</a>
                            <input type="reset" class="btn btn-danger" value="Làm lại">
                        </div>
                    </div>
                    <!-- END Bottom Navigation -->
                </div>
                <!-- END Users Search -->
            </div>
            <!-- END Search Styles Content -->
        </div>
    </form>
</div>
<!-- END Table Styles Block -->
<!-- Load and execute javascript code used only in this page -->
<script type="text/javascript">
    $('body').on('click', '.multi-next', function(){
        var start = $('[name="start"]').val();
        var end = $('[name="end"]').val();
        var type = $('[name="get_type"]:checked').val();
        var full = part = thuyetminh = '';
        if (type == 'Full') {
            full = 'checked';
        }
        if (type == 'Part') {
            part = 'checked';
        }
        if (type == 'ThuyetMinh') {
            thuyetminh = 'checked';
        }
        var end_name = $('[name="end_name"]').val();
        if (start.indexOf('.') >= 0 && end.indexOf('.') >= 0) {
            var i = start.split(".");
            var j = end.split(".");
            if(i[0] == j[0]){
                $('#search-tab-multi').html('');
                for(p= parseInt(i[1]); p<= parseInt(j[1]); p++){
                    var title = i[0]+'.'+p;
                    if (p == j[1] && end_name != '') {
                        title = end_name;
                    }
                    var html = '<div class="form-group">';
                        html +=      '<label class="col-md-3 control-label" for="example-text-input">Tên tập</label>';
                        html +=      '<div class="col-md-9">';
                        html +=          '<input type="text" name="multi[title]['+p+']" class="form-control" placeholder="" value="'+title+'">';
                        html +=      '</div>';
                        html +=  '</div>';
                        html +=  '<div class="form-group">';
                        html +=      '<label class="col-md-3 control-label" for="example-email-input">Liên kết</label>';
                        html +=      '<div class="col-md-9">';
                        html +=          '<input type="url" name="multi[file_name]['+p+']" value="" class="form-control" placeholder="">';
                        html +=      '</div>';
                        html +=  '</div>';
                        html +=  '<div class="form-group">';
                        html +=      '<label class="col-md-3 control-label" for="example-email-input">Chọn loại</label>';
                        html +=      '<div class="col-md-9">';
                        html +=          '<div class="radio">';
                        html +=              '<label for="example-radio1">';
                        html +=                  '<input type="radio" id="example-radio1" name="multi[type]['+p+']" value="Full" '+full+' > Việt sub full';
                        html +=              '</label>';
                        html +=          '</div>';
                        html +=          '<div class="radio">';
                        html +=              '<label for="example-radio2">';
                        html +=                  '<input type="radio" id="example-radio2" name="multi[type]['+p+']" value="Part" '+part+'> Chia nhỏ';
                        html +=              '</label>';
                        html +=          '</div>';
                        html +=          '<div class="radio">';
                        html +=              '<label for="example-radio3">';
                        html +=                  '<input type="radio" id="example-radio3" name="multi[type]['+p+']" value="ThuyetMinh" '+thuyetminh+'> Thuyết minh';
                        html +=             ' </label>';
                        html +=          '</div>';
                        html +=      '</div>';
                        html +=  '</div>';
                        html +=  '<hr/>';

                    $('#search-tab-multi').append(html);
                }
                var end_html = '<div class="clearfix"></div>';
                    end_html += '<div class="block-section">';
                    end_html +=     '<div class="form-group form-actions text-center">';
                    end_html +=         '<input type="submit" name="add_multi" class="btn btn-primary" value="Hoàn tất">';
                    end_html +=         '<input type="reset" class="btn btn-danger" value="Làm lại">';
                    end_html +=      '</div>';
                    end_html +=  '</div>';
                $('#search-tab-multi').append(end_html);
            }else{
                alert('Chỉ được thêm tập chia nhỏ cùng một tập lớn. VD: 5.1 đến 5.8');
            }
        }else{
            if (start.indexOf('.') < 0 && end.indexOf('.') < 0) {
                var i = parseInt(start);
                var j = parseInt(end);
                if(i[0] == j[0]){
                    $('#search-tab-multi').html('');
                    for(p=i; p<= j; p++){
                        var title = p;
                        if (p == j && end_name != '') {
                            title = end_name;
                        }
                        var html = '<div class="form-group">';
                            html +=      '<label class="col-md-3 control-label" for="example-text-input">Tên tập</label>';
                            html +=      '<div class="col-md-9">';
                            html +=          '<input type="text" name="multi[title]['+p+']" class="form-control" placeholder="" value="'+title+'">';
                            html +=      '</div>';
                            html +=  '</div>';
                            html +=  '<div class="form-group">';
                            html +=      '<label class="col-md-3 control-label" for="example-email-input">Liên kết</label>';
                            html +=      '<div class="col-md-9">';
                            html +=          '<input type="url" name="multi[file_name]['+p+']" value="" class="form-control" placeholder="">';
                            html +=      '</div>';
                            html +=  '</div>';
                            html +=  '<div class="form-group">';
                            html +=      '<label class="col-md-3 control-label" for="example-email-input">Chọn loại</label>';
                            html +=      '<div class="col-md-9">';
                            html +=          '<div class="radio">';
                            html +=              '<label for="example-radio1">';
                            html +=                  '<input type="radio" id="example-radio1" name="multi[type]['+p+']" value="Full" '+full+' > Việt sub full';
                            html +=              '</label>';
                            html +=          '</div>';
                            html +=          '<div class="radio">';
                            html +=              '<label for="example-radio2">';
                            html +=                  '<input type="radio" id="example-radio2" name="multi[type]['+p+']" value="Part" '+part+'> Chia nhỏ';
                            html +=              '</label>';
                            html +=          '</div>';
                            html +=          '<div class="radio">';
                            html +=              '<label for="example-radio3">';
                            html +=                  '<input type="radio" id="example-radio3" name="multi[type]['+p+']" value="ThuyetMinh" '+thuyetminh+'> Thuyết minh';
                            html +=             ' </label>';
                            html +=          '</div>';
                            html +=      '</div>';
                            html +=  '</div>';

                        $('#search-tab-multi').append(html);
                    }
                    var end_html = '<div class="clearfix"></div>';
                        end_html += '<div class="block-section">';
                        end_html +=     '<div class="form-group form-actions text-center">';
                        end_html +=         '<input type="submit" name="add_multi" class="btn btn-primary" value="Hoàn tất">';
                        end_html +=         '<input type="reset" class="btn btn-danger" value="Làm lại">';
                        end_html +=      '</div>';
                        end_html +=  '</div>';
                    $('#search-tab-multi').append(end_html);
                }else{
                    alert('Chỉ được thêm tập chia nhỏ cùng một tập lớn. VD: 5.1 đến 5.8');
                }
            }else{
                alert('Nhập không đúng định dạng');
            }
        }
    });

    $('body').on('click', '.add-fast', function(){
        var content = $('[name="string"]').val();
        var array = content.split("\n");
        var type = $('[name="fast_type"]:checked').val();
        var full = part = thuyetminh = '';
        if (type == 'Full') {
            full = 'checked';
        }
        if (type == 'Part') {
            part = 'checked';
        }
        if (type == 'ThuyetMinh') {
            thuyetminh = 'checked';
        }
        $('#search-tab-fast').html('');
        var error = 0;
        var old_html = '<div class="form-group">';
            old_html +=      '<label class="col-md-3 control-label" for="example-text-input">Thêm nhiều link</label>';
            old_html +=      '<div class="col-md-9">';
            old_html +=          '<span>Lưu ý không có dấu khoảng trắng(dấu cách). Mỗi link 1 dòng.</span>';
            old_html +=          '<textarea name="string" class="form-control" placeholder="Tên tập|Liên kết" style="min-height: 300px;">'+content+'</textarea>';
            old_html +=     '</div>';
            old_html +=  '</div>';
        $('#search-tab-fast').prepend(old_html);
        $.each(array, function(index, item){
            if(item.indexOf('|') >= 0){
                var ep = item.split("|");
                var html = '<div class="form-group">';
                    html +=      '<label class="col-md-3 control-label" for="example-text-input">Tên tập</label>';
                    html +=      '<div class="col-md-9">';
                    html +=          '<input type="text" name="fast[title]['+index+']" class="form-control" placeholder="" value="'+ep[0]+'">';
                    html +=      '</div>';
                    html +=  '</div>';
                    html +=  '<div class="form-group">';
                    html +=      '<label class="col-md-3 control-label" for="example-email-input">Liên kết</label>';
                    html +=      '<div class="col-md-9">';
                    html +=          '<input type="url" name="fast[file_name]['+index+']" value="'+ep[1]+'" class="form-control" placeholder="">';
                    html +=      '</div>';
                    html +=  '</div>';
                    html +=  '<div class="form-group">';
                    html +=      '<label class="col-md-3 control-label" for="example-email-input">Chọn loại</label>';
                    html +=      '<div class="col-md-9">';
                    html +=          '<div class="radio">';
                    html +=              '<label for="example-radio1">';
                    html +=                  '<input type="radio" id="example-radio1" name="fast[type]['+index+']" value="Full" '+full+' > Việt sub full';
                    html +=              '</label>';
                    html +=          '</div>';
                    html +=          '<div class="radio">';
                    html +=              '<label for="example-radio2">';
                    html +=                  '<input type="radio" id="example-radio2" name="fast[type]['+index+']" value="Part" '+part+'> Chia nhỏ';
                    html +=              '</label>';
                    html +=          '</div>';
                    html +=          '<div class="radio">';
                    html +=              '<label for="example-radio3">';
                    html +=                  '<input type="radio" id="example-radio3" name="fast[type]['+index+']" value="ThuyetMinh" '+thuyetminh+'> Thuyết minh';
                    html +=             ' </label>';
                    html +=          '</div>';
                    html +=      '</div>';
                    html +=  '</div>';
                    html +=  '<hr/>';

                $('#search-tab-fast').append(html);
            }else{
                error = 1;
                alert('Lỗi dòng '+(index+1)+' "'+item+'"');
            }
        });
        var end_html = '<div class="clearfix"></div>';
            end_html += '<div class="block-section">';
            end_html +=     '<div class="form-group form-actions text-center">';
            end_html +=         '<input type="submit" name="add_fast" class="btn btn-primary" value="Hoàn tất">';
            end_html +=         '<input type="reset" class="btn btn-danger" value="Làm lại">';
            end_html +=      '</div>';
            end_html +=  '</div>';
        $('#search-tab-fast').append(end_html);

    });
</script>
@stop
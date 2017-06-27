@extends('admin.master')

@section('content')
<ul class="breadcrumb breadcrumb-top">
</ul>
<div class="row">
    <!-- Table Styles Block -->
    <div class="block">
        <!-- Table Styles Title -->
        <div class="block-title">
            <h2>Slide</h2>
        </div>
        <!-- END Table Styles Title -->
        <p>
            @if (Session::has('message'))
            {!! Session::get('message') !!}
            @endif
        </p>
        <div class="table-options clearfix">
        <input type="hidden" name="slug" value="slide">
        <?php $data = json_decode($result->data); ?>
        <form action="{{route('update_slide')}}" method="post" enctype="multipart/form-data">
            {!!csrf_field()!!}
            <div class="row draggable-blocks">
                <div class="col-md-6 column col-md-offset-3 list-slide">
                @if(count($data) > 0)
                    @foreach($data as $key => $item)
                    <!-- Block -->
                    <div class="block">
                        <div class="block-title">
                            <div class="block-options pull-right">
                                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-danger"><i class="fa fa-times"></i></a>
                            </div>
                            <h3 class="help-block">Kéo thả để thay đổi vị trí</h3>
                        </div>
                        <label class="label-control">Link (đầy đủ http://)</label>
                        <input type="text" name="data[{{$key}}][url]" value="{{$item->url or ''}}" class="form-control">
                        <label class="label-control">Text</label>
                        <textarea name="data[{{$key}}][content]" class="textarea">{{$item->content or ''}}</textarea>
                        <label class="label-control">Ảnh</label>
                        <p>
                            <input type="hidden" name="data[{{$key}}][img]" value="{{$item->img or ''}}" />
                            <img src="{{$item->img or ''}}" class="img-responsive">
                        </p>
                    </div>
                    <!-- END Block -->
                    @endforeach
                @endif
                    <div class="block extend-block hide">
                        <div class="block-title">
                            <div class="block-options pull-right">
                                <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-danger"><i class="fa fa-times"></i></a>
                            </div>
                            <h3 class="help-block">Click ở đây và giữ chuột kéo thả để thay đổi vị trí</h3>
                        </div>
                        <label class="label-control">Link (đầy đủ http://)</label>
                        <input type="text" name="url" value="" class="form-control">
                        <label class="label-control">Text</label>
                        <textarea name="content" class="form-control"></textarea>
                        <label class="label-control">Ảnh</label>
                        <p>
                            <input type="file" name="file" id="filePreview" />
                        </p>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group form-actions">
                <div class="text-center">
                    <button type="button" class="btn btn-success btn-add">Thêm slide</button>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group form-actions">
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
<!-- Load and execute javascript code used only in this page -->
<script src="{{asset('themes/admin/js/pages/uiDraggable.js')}}"></script>
<script>
    $(function(){ UiDraggable.init(); });

    $(document).ready(function(e){
        $('body').on('click', '.btn-danger', function(){
            $(this).parent().parent().parent().remove();
        });
        $('body').on('click', '.btn-add', function(){
            $('.extend-block').removeClass('hide');
            $(this).remove();
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $("#filePreview").after('<img src="'+e.target.result+'" class="img-responsive">');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#filePreview").change(function(){
            readURL(this);
        });
    });
</script>
@stop
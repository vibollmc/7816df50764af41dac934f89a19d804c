<!-- Auto get slug and seo tittle -->
<script >
  $(document).ready(function(){
        $(function(){
            if($('[name="slug"]').length > 0){
                if ($('[name="slug"]').val().length == 0 && $('.textarea').length > 0) {
                    var button_show = '<div class="text-center"><div class="alert">Trước khi tiếp tục hãy chắc chắn bạn Slug không bỏ trống</div><a href="javascript:void(0)" class="btn btn-success expant-btn">Tiếp tục >></a></div>';

                    $(".textarea:first").parents('.form-group').before(button_show);
                    $('.textarea').addClass('hide');
                    $('.form-actions').addClass('hide');
                }else{
                    $(function() {
                        $('.textarea').froalaEditor(
                            {
                                heightMin: 300,
                                imageUploadParam: 'file',
                                imageUploadMethod: 'POST',
                                imageUploadURL: '{{route('upload_image')}}',

                                imageUploadParams: {
                                    froala: 'true',
                                    _token: '{{csrf_token()}}',
                                    slug: $('[name="slug"]').val()
                                }
                            }
                        );
                    });
                }
            }
        });

        $('body').on('click', '.expant-btn', function(){
            if ($('[name="slug"]').val().length > 0) {
                $(this).parent().remove();
                $('.textarea').removeClass('hide');
                $('.form-actions').removeClass('hide');
                $(function() {
                    $('.textarea').froalaEditor(
                        {
                            heightMin: 300,
                            imageUploadParam: 'file',
                            imageUploadMethod: 'POST',
                            imageUploadURL: '{{route('upload_image')}}',

                            imageUploadParams: {
                                froala: 'true',
                                _token: '{{csrf_token()}}',
                                slug: $('[name="slug"]').val()
                            }
                        }
                    );
                });
            }else{
                alert('Slug còn bỏ trống');
            }
        });
    });
</script>
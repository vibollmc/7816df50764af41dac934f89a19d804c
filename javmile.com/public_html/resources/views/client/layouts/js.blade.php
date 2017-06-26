<div id="fb-root"></div>
{!!!is_null($analytic)? $analytic->data: ''!!}

<script>
    var winWidth =  $(window).width();
    if(winWidth < 751 ){
        $('.container').width(winWidth-30);
      $('.clearfix-md').removeClass('clearfix');
      $('.clearfix-sm').removeClass('clearfix');
      $('.clearfix-xs').addClass('clearfix');
    }
    if( winWidth <= 974){
        $('.container').width(winWidth-30);
      $('.clearfix-md').removeClass('clearfix');
      $('.clearfix-xs').removeClass('clearfix');
      $('.clearfix-sm').addClass('clearfix');
    }
    if(winWidth > 974){
        $('.container').width(winWidth-30);
      $('.clearfix-xs').removeClass('clearfix');
      $('.clearfix-sm').removeClass('clearfix');
      $('.clearfix-md').addClass('clearfix');
    }

    $(window).on('resize',function(){
       var winWidth =  $(window).width();
       if(winWidth < 751 ){
        $('.container').width(winWidth-30);
          $('.clearfix-md').removeClass('clearfix');
          $('.clearfix-sm').removeClass('clearfix');
          $('.clearfix-xs').addClass('clearfix');
       }
       if( winWidth <= 974){
            $('.container').width(winWidth-30);
              $('.clearfix-md').removeClass('clearfix');
              $('.clearfix-xs').removeClass('clearfix');
              $('.clearfix-sm').addClass('clearfix');
       }
       if(winWidth > 974){
            $('.container').width(winWidth-30);
          $('.clearfix-xs').removeClass('clearfix');
          $('.clearfix-sm').removeClass('clearfix');
          $('.clearfix-md').addClass('clearfix');
       }

    });
$(document).ready(function(){
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = '//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.7&appId='+$('[property="fb:app_id"]').attr('content');
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));


    if ($(window).width() > 751) {
            $('.item-slide > img').addClass('content-img');
            $('.content-img').each(function(i){
                $('.owl-controls .owl-page').eq(i)
                    .css({
                        'background': 'url(' + $(this).attr('thumb') + ')',
                        'background-size': 'cover'
                    })
            });
        }else{
            $('#owl-demo').find('.owl-pagination').addClass('hide');
        }
        if ($('.slide-caption').length > 0) {
            $('.item-slide').parent().mouseenter( function(){ $('.slide-caption').removeClass('hide'); } ).mouseleave( function(){ $('.slide-caption').addClass('hide'); } );
        }
})
</script>
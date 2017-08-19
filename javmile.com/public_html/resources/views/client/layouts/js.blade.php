<div id="fb-root"></div>
{!!!is_null($analytic)? $analytic->data: ''!!}

<script>
    function resizeMain() {
        var winWidth =  $(window).width();
        if(winWidth < 751 ){
            $('.container').width(winWidth-30);
            $('.clearfix-md').removeClass('clearfix');
            $('.clearfix-sm').removeClass('clearfix');
            $('.clearfix-xs').addClass('clearfix');
        }
        else if( winWidth <= 974){
            $('.container').width(winWidth-30);
            $('.clearfix-md').removeClass('clearfix');
            $('.clearfix-xs').removeClass('clearfix');
            $('.clearfix-sm').addClass('clearfix');
        }
        else {
            $('.container').width(winWidth-30);
            $('.clearfix-xs').removeClass('clearfix');
            $('.clearfix-sm').removeClass('clearfix');
            $('.clearfix-md').addClass('clearfix');
        }
    }

    resizeMain();

    $(window).on('resize',function(){
       resizeMain();
    });
    
    $(document).ready(function(){
    
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
    });
</script>
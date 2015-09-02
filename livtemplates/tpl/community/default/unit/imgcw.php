<style type="text/css">
 .vImageBox{position:relative;zoom:1;text-indent:0;}
 .vImageBox .vImageOption{position:absolute;left:0;top:0;display:none;}
 .vImageBox .vImageLeft, .vImageBox .vImageRight{padding:3px 5px;background:green;color:#fff;margin-right:5px;cursor:pointer;}
 </style>
{js:qingao/jquery.rotate}
<script type="text/javascript">
jQuery(function($){
    $('body').on('click', '.vImageBox', function(){
        if($(this).hasClass('on')){
            $(this).removeClass('on').find('.vimage').attr('src', function(){
                return $(this).attr('_now');
            }).removeAttr('step style');
            $(this).find('.vImageOption').hide();
            $(this).find('.imgCanvas').remove();
            if($(this).find('.wrapImg').length){
                $(this).find('.vimage').unwrap();
            }
        }else{
            $(this).addClass('on').find('.vimage').attr('src', function(){
                return $(this).attr('_big');
            });
            var option = $(this).find('.vImageOption').show();
            option.find('.vImageLeft, .vImageRight').off('click').on('click', function(event){
                event.stopPropagation();
                $(this).closest('.vImageBox').find('.vimage').rotate($(this).hasClass('vImageLeft') ? 'ccw' : 'cw');
            });
        }
    });
});
</script>
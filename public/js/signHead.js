/**
 * Created by mac on 15/7/16.
 */
$(function(){
    $(".sign_head_url li:not([mark='true'])").hover(function(){
        $(this).css('background-color','grey');
    },function(){
        $(this).css('background-color','#fff');
    })

    $('#sign_login_li').hover(function(){
        $('#sign_mark_div').stop(1).slideToggle(50);
    })

    $('#sign_mark_div div').hover(function(){
        $(this).css('color','orange');
    },function(){
        $(this).css('color','lightskyblue');
    })

    $('.span_sex1').click(function(){
        if($(this).children('i').css('visibility') == "visible") {
            $(this).children('i').css('visibility', 'hidden');
            $('#sexIput').val(2);
        }else{
            $(this).children('i').css('visibility', 'visible');
            $('.span_sex2').children('i').css('visibility', 'hidden');
            $('#sexIput').val(1);
        }
    })

    $('.span_sex2').click(function(){
        if($(this).children('i').css('visibility') == "visible") {
            $(this).children('i').css('visibility', 'hidden');
            $('#sexIput').val(2);
        }else{
            $(this).children('i').css('visibility', 'visible');
            $('.span_sex1').children('i').css('visibility', 'hidden');
            $('#sexIput').val(0);
        }
    })
})



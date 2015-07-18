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

    $('#sign_regist_form button').click(function(){
        var name = $('#sign_regist_form input:eq(0)').val();
        var password = $('#sign_regist_form input:eq(1)').val();
        var sex = $('#sign_regist_form input:eq(2)').val();
        var birthday = $('#sign_regist_form input:eq(3)').val();
        var tel = $('#sign_regist_form input:eq(4)').val();
        var address = $('#sign_regist_form input:eq(5)').val();
        $.post("http://sign.com/index.php/User/regist",
            {
                name:name,
                password:password,
                sex:sex,
                birthday:birthday,
                tel:tel,
                address:address
            },
            function(data){
                if(data){
                    console.log(data);
                    $('#succ_name').html(name);
                    $('#succ_address').html(address);
                    $(".welcome").slideDown(2000,function(){
                        $(".welcome").fadeOut(10000);
                    });
                    $('#sign_regist_form input').val('');
                }
            }
        );

    })
})



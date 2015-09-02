<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>入场</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/base.css" rel="stylesheet" >
<link href="css/form.css" rel="stylesheet">
<?php
    $id = intval($_REQUEST['id']) ?  intval($_REQUEST['id']) : 1; 
    $_id = "LED_".$id; 
    $num = array('零','一','二','三','四');
?>
</head>

<body>
<div class="wrap clearfix">
    <div class="rc-left">
        <h1 class="rc-title">华东互联网高峰论坛</h1>
        <div class="rc-code"><img src="img/entrance_<?php echo $id  ?>.jpg" /></div>
        <ul class="rc-left-list">
            <li class="openapp"><a href="#">1.打开app签到模块</a></li>
            <li class="scanningcode"><a href="#">2.扫描二维码</a></li>
        </ul>
    </div>
    <div class="rc-right">
        <div class="rc-right-qd">
            <div class="rc-right-title clearfix">
                <div class="rc-right-title-l"></div>
                <div class="rc-right-title-c">
                    <div class="rc-right-stratification">
                        <div class="welcome-admission">欢迎<span>华东互联网高峰论坛嘉宾</span>入场</div>
                        <div class="which-entrance" id="entrance" data-id="<?php echo $_id;  ?>" data-signtime=""><?php echo $num["$id"];  ?>号入口</div>
                    </div>
                </div>
                <div class="rc-right-title-r"></div>
            </div>
            <div class="rc-guests-item">
                <h1 id="rcinfo">请扫描左边的二维码<br/>进行签到</h1>
                <ul class="rc-who" id="who"></ul>
                <div class="rc-error">未能识别嘉宾身份,<br /> 请联系现场工作人员</div>
            </div>
        </div>
        <div class="rc-guest">
            <ul class="rc-right-list" id="guest"></ul>
        </div>
    </div>
    <div class="avatar"></div>
</div>
<script src="js/jquery.js"></script>
<script>
function Guest(options){
    this.timer = null;
    this.itemHeight = 0;
    this.colspan = 0;
    this.screenID  = $('#entrance').data('id');
    this.userArray = [];
    this.itemHeight  = options.height;
}
Guest.prototype.init =  function(){
    var that = this;
    that.updateData();
}
Guest.prototype.updateData =  function(){
    var that= this,
        _user = {},
        _signtime = $("#entrance").data('signtime'),
        _avatar = null;

    $.ajax({
        url : 'http://hdbbs.liv.cn/sign_in_interact.php',
        type : 'GET',
        data : {screen_id : this.screenID, sign_time : _signtime },
        dataType : 'json',
        success : function(data){
           if(data.ErrorCode){
                console.log(data.ErrorCode);
           }else if(data[0].return =="refresh"){
                //超时发起下次请求
                that.updateData();
           }else if(data !== null && data.length > 0){

                $.each(data, function(index, item){
                    _user = {};
                    if(typeof item.avatar === 'undefined' || typeof item.avatar.host === 'undefined'){
                        _user.avatar = _avatar =  'http://hdbbs.liv.cn/app/img/avatar.png';
                    }else{
                       _user.avatar = _avatar = item.avatar.host + item.avatar.dir + '240x240/' +  item.avatar.filepath + item.avatar.filename; 
                    }
                    _user.gtype = item.guest_type;

                    _user.html  = '<li><div class="rc-right-picture" style="background-image:url(' + _avatar + ')"></div><div class="rc-right-post"><p><span>' + item.name + '</span>' + item.job + '</p><p>' + item.company + '</p><p class="type-inside type-inside' + item.guest_type +'">' + item.guest_type_text + '</p></div></li>';

                    that.userArray.push(_user);
                    $("#entrance").data('signtime', item.sign_time);
                });

                //分为是否为第一次请求
                if(_signtime){
                  that.updateDom();
                }else{
                    that.updateDom(true);
                }

                //成功了发起下次请求
                that.updateData();

            }
        }
    });

}


Guest.prototype.updateDom  = function(init){
    var that = this,
        _item = null;


    if(init){
        /*初次加载直接写入页面*/
        for( var i = that.userArray.length; i > 0  ; ){
            i--;
            if(that.userArray[i].gtype != "1"){
                $('#guest').append(that.userArray[i].html);
            } 
        }
        that.userArray = [];
    }else{
        _item =  that.userArray.shift();
       
       
        if(_item.gtype != "1"){

            $('body').addClass('guest-pass');

            if($("#guest li").size() < 1){
                 $('#guest').html(_item.html);  
            }else{
                $("#guest li:first").before(_item.html); 
                
            }
            $("#guest").css("top", -that.itemHeight + "px"); 
            $('#who').html(_item.html);
            $('.avatar').css('background-image', _item.avatar).addClass('animate');
            
            //3.5s 显示正确的位置里
            this.timerhy = setTimeout(function(){
                $('#who li').remove(); 
                $('#guest').animate({
                    top : 0
                }, 500);
                $('.avatar').removeClass('animate');
                $('body').removeClass('guest-pass'); 
            }, 3400);

        }else{
            $('body').addClass('guest-error');
            clearTimeout(that.timer);
            $(".rc-error").show(0).delay(4000).hide(0, function(){
                $('body').removeClass('guest-error'); 
            });
        }

        if(that.userArray.length > 0){
            that.timer =  setTimeout(function(){
                that.updateDom();
            }, 4000)
        }  
    }
}



var guest =  new Guest({
                height : '241'
            });
    guest.init();

</script>
</body>
</html>
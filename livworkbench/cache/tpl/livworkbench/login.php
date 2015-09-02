<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $this->mTemplatesTitle;?></title>
<script>
if(top != self){
    top.location.href = self.location.href;
}
</script><link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livworkbench/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php echo CSS_FILE_URL; ?>livworkbench/newlogin/login.css" /><script type="text/javascript" src="<?php echo SCRIPT_URL . $__script_dir; ?>jquery.min.js"></script></head><style>
.auth-code-area{border:none!important;height:30px;line-height:28px;color:#999;font-size:14px;}
.auth-code{display:inline-block;padding:0 5px;color:white;height::16px;font-size:12px;line-height:16px;}
.auth-code-i{float:right;width:100px;height:22px;text-indent:5px;color:#999;line-height:22px;}
</style>
<div class="login-wrap">
      <div class="sys-intro"></div>
      <div class="login-form">
           <form id="loginform" name="loginform" target="_top" method="POST" action="login.php">
                <ul>
                      <li>
                          <em class="info-icon user"></em>
                          <input type="text" placeholder="用户名" id="username" name="username" class="info-item" />
                      </li>
                      <li>
                           <em class="info-icon password"></em>
                           <input type="password" placeholder="密码" id="password" name="password" class="info-item" />
                      </li>
                      <!--
                      <li>
                           <em class="info-icon code"></em>
                           <input type="text" placeholder="验证码" class="info-item" id="identify－code" name="identify－code" />
                      </li>
                       -->
                      <?php if($isopencard){ ?>
                      <li class="auth-code-area">
						<lable class="auth-code-l">密保卡：</lable>
						<span class="auth-code" style="background:#e65d5d;" id="zuobiao_1">A1</span>
						<span class="auth-code" style="background:#6fc1d2;" id="zuobiao_2">E6</span>
						<span class="auth-code" style="background:#7dc692;" id="zuobiao_3">C3</span>
						<input class="auth-code-i" type="text" id="zuo_val" name="secret_value" value="" />
                      </li>
					 <?php } ?>	
                      <li class="no-border">
                           <input type="submit" name="submit" value="" class="login-btn" />
                      </li>
                      <div class="warning"></div>
                      <?php if($message){ ?>
                      <div class="warning"><?php echo $message;?></div>
                      <?php } ?>
                </ul>
                       <input type="hidden" value="dologin" name="a" />
                       <input type="hidden" value="<?php echo $_INPUT['iscj'];?>" name="iscj" />
                       	<!--密保卡坐标隐藏域 -->
                        <input type="hidden" value="" name="security_zuo[]" id="sec_1" />
                        <input type="hidden" value="" name="security_zuo[]" id="sec_2" />
                        <input type="hidden" value="" name="security_zuo[]" id="sec_3" />
                        <input type="hidden" value="<?php echo $_INPUT['referto'];?>" name="referto" />
                        <input type="hidden" value="<?php echo $channel_info['code'];?>" name="code" />
                        <input type="hidden" value="<?php echo $channel_info['id'];?>" name="channel_id" />
            </form>
      </div>
 </div> <div class="login-footer">
       <span class="m2o-logo"></span>新媒体综合运营平台 <?php echo $_settings['version'];?>
       <!--<span class="hoge-logo"></span>出品-->
       <span class="license-logo"></span>授权使用：<?php echo $_settings['license'];?>
  </div><script>
var iscj = parseInt('<?php echo $_INPUT["iscj"];?>');
var queryString = "<?php echo $_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] : '';  ?>";
jQuery(function($){
    if(iscj){
        $('form').submit(function(){
            var param = $(this).serializeArray();
            var postData = {};
            $.each(param, function(i, n){
                postData[n['name']] = n['value'];
            });
            postData['ajax'] = 1;
            $.post(
                'login.php',
                postData,
                function(json){
                    if(json['msg']){
                        location.replace('run.php?' + queryString);
                    }else{
                        alert('帐号或者密码错误');
                    }
                },
                'json'
            );
            return false;
        });
    }
	var App=window.App||$({});
    /*产生密保卡随机数字段*/
    function create_random()
    {
        var z = new Array('A','B','C','D','E','F','G','H');
        var a1 = 0;
        var a2 = 0;
        var ret = new Array();
        for(var i = 0,j = 1;i<3;i++,j++)
        {
            a1 = parseInt(Math.random()*8+1);
            a2 = parseInt(Math.random()*8);
            ret[i] = z[a2]+a1;
            $('#zuobiao_'+j).text(ret[i]);
            $('#sec_'+j).val(ret[i]);
        }
    }
    $(function(){
        create_random();/*已进入登录页面就产生随机数*/
    });    /*判断是否有验证码*/
    function isCode()
    {
       var isHas=false;
       if(($('#loginform').find('#identify－code')).length){
           isHas=true;
       }
       return isHas;
    }    (function($){
    	 /*登录前本地验证*/
        $('#loginform').submit(function(){
            var name=$.trim($('#username').val()),
                password=$.trim($('#password').val()),
                errorArea=$('.warning');  
	        if(!(name&&password)){
	              if(!name){
	                 errorArea.text('请输入用户名!');
	              }
	              else{
	            	  errorArea.text('请输入密码!');
	             }
	            return false;
	        }
	        else{
	            var isTrue=isCode();
	            if(isTrue){
	               var code=$.trim($('#identify－code').val());
	               if(!code){
	            	   errorArea.text('验证码输入有误!');
	            	   return false;
	               }
	            }
	        }
       });
        /*高亮显示*/
        var els=$('.info-item'),
            btn=$('.login-btn');
        function checkView(){
               var sum=0;
                els.each(function(){
                    if(this.value) {
                          sum+=1;
                          $(this).parent().addClass('active');
                    }else{
                    	$(this).parent().removeClass('active');
                    }
                });
                if(sum==els.length){
                	btn.addClass('login-active');
                }else{
                	btn.removeClass('login-active');
                }  
            }
       	setInterval(checkView, 500);
    })($);
    $('.login-form').on('click','.info-item',function(e){
            var self=$(e.currentTarget),
                parent=self.parent();
            parent.addClass('focus').siblings().removeClass('focus');
    });
    $('.login-form').on('blur','.info-item',function(e){
        var self=$(e.currentTarget),
            parent=self.parent();
        parent.removeClass('focus');
});
});
</script>
</body>
</html>
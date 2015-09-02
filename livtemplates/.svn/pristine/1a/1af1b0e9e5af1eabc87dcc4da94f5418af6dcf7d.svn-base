{template:head}
{css:ad_style}
{css:column_node}
{js:2013/ajaxload_new}
{code}
	//print_r($list);
	//echo $user_id;
{/code}
<style type="text/css">
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
</style>
<script type="text/javascript">
	function checkPassword()
	{
		//var oldPassword = document.getElementById("old_password").value;
		//var url = 'infocenter.php?a=checkPassword&password='+oldPassword;
		//hg_request_to(url,'', 'get', 'pass', 1);
	}
	var pass = function (data)
	{	
		var str = '';
		if (data == 1)
		{
			str = '正确';
			document.getElementById("old_password_hint").style.color="green";
			document.getElementById('old_password_hint').innerHTML=str;
		}else
		{
			str = '密码错误';
			document.getElementById("old_password_hint").style.color="red";
			document.getElementById('old_password_hint').innerHTML=str;
		}		
	}
	function checkNewPassword(obj)
	{
		var str = $(obj).val();
		var res = str.match(/^@(.*)?/);
		if (str)
		{
			if (res!=null)
			{
				msg = '密码不能以@符号打头';
				$('#new_password_hint').css('color','red').html(msg);
				$(obj).val('');
			}else{
				msg = '正确';
				$('#new_password_hint').css('color','green').html(msg);
			}
		}
	}
	function checkNewPasswordAgain()
	{
		var str = document.getElementById("password_again").value;
		var str0 = document.getElementById("password").value;

				if (str == str0)
				{
					msg = '正确';
					document.getElementById("new_password_again_hint").style.color="green";
					document.getElementById('new_password_again_hint').innerHTML=msg;
				}else{
					msg = '两次输入密码不同';
					document.getElementById("new_password_again_hint").style.color="red";
					document.getElementById('new_password_again_hint').innerHTML=msg;
				}
	}

	$(function(){
		$('.person-area').on('click','.editor',function(event){
			var self=$(this),
			    item=self.closest('li');
		    self.toggleClass('up');
		    item.toggleClass('active');
		    item.siblings().hasClass('active') && item.siblings().removeClass('active');
		    item.siblings().find('.editor').hasClass('up') && item.siblings().find('.editor').removeClass('up')
		});
		$('.person-save').on('click' , function( event ){
			var self = $( event.currentTarget );
			var param = {};
			param.old_password = $('input[name="old_password"]').val();
			param.password = $('input[name="password"]').val();
			param.password_again = $('input[name="password_again"]').val();
			param.admin_id = $('input[name="admin_id"]').val();
			param.username = $('input[name="user_name"]').val();
			url = 'login.php?a=change_pwd';
			$.globalAjax( self , function(){
	    			return $.getJSON( url , param , function( data ){
					if(data.error){
						myTip( self , data.msg );
					}else{
						myTip( self , '修改成功,请用新密码登录');
						setTimeout(function(){
							location.reload();
						},'2000');
					}
	    		    });
	    		});
		});

		function myTip( item , msg ){
			item.myTip({
				string : msg,
				width : 200,
				delay: 2000,
				dtop : 0,
				dleft : 10,
			});
		}
   });
</script>
<style>
.wrap{width:800px;margin:20px auto;}
.person-title{text-indent:30px;padding-bottom:6px;margin:16px 0 0 10px;background:url({$RESOURCE_URL}dottedLine.png) repeat-x bottom;}
.person-area{width:800px;min-height:500px;margin:40px auto 50px;}
.person-area .login-item{height:48px;background:url({$RESOURCE_URL}dottedLine.png) repeat-x bottom #eaf3fc;padding-bottom:1px;clear:left;}
.person-area .top{height:48px;line-height:48px;padding:0 35px 0 0;}
.person-area .title{float:left;font-size:16px;text-indent:28px;width:100px;text-align:right;padding-right:10px;}
.person-area .info{float:left;}
.person-area .editor{float:right;color:#808080;padding-right:18px;cursor:pointer;font-size:14px;background:url({$RESOURCE_URL}person/down_b.png) no-repeat right center;}
.person-area .up{background-image:url({$RESOURCE_URL}person/up_w.png)}
.person-area .main-con{padding:30px 0 20px 170px;display:none;}
.person-area .main-con .item{margin-bottom:15px;}
.person-area .item input{height: 27px;border-radius: 3px;}
.person-area .main-con .name{display:inline-block;width:100px;color:#8e8e90;margin-right:15px;text-align:right;}
.person-area .photo-img{width:36px;height:36px;border-radius:50%;vertical-align:middle;}
.person-area .big-img{width:100px;height:100px;margin-right:15px;}
.person-area .login-item.active{height:auto;}
.person-area .active .top{background:#6ba2d8;}
.person-area .active .title{color:#fff;}
.person-area .active .editor{color:#fff;background-image:url({$RESOURCE_URL}person/up_w.png)}
.person-area .active .info{display:none;}
.person-area .active .main-con{display:block;}
.person-area .person-save{margin: 0px 0 30px 290px;}
.important{float:none;margin-left: 10px;}
#mibao_info_box{width:100%;position:relative;}
.pass-card{position:absolute;height:300px;right:220px;top:170px;width:103px;}
.pass-card a{margin-bottom:22px;}
@media only screen and (-webkit-min-device-pixel-ratio: 2),
only screen and (-moz-min-device-pixel-ratio: 2),
only screen and (-o-min-device-pixel-ratio: 2/1),
only screen and (min-device-pixel-ratio: 2) {
        .person-area .editor{background-image:url({$RESOURCE_URL}person/down_b-2x.png);background-size:8px 8px;}
        .person-area .up{background-image:url({$RESOURCE_URL}person/up_w-2x.png);background-size:8px 8px;}
}
</style>
<div class="wrap clear">
	<form action="" method="post" enctype="multipart/form-data">
	    <div class="person-area">
              <div class="login-item active">
                   <div class="top">
                       <span class="title">密码修改</span><span style="font-size:14px;color:white;">(第一次登陆需要修改密码)</span>
                       <div class="info"><span style="font-size:14px;vertical-align:middle;">******</span></div>
                   </div>
                   <div class="main-con">
                        <div class="item">
                            <span  class="name">请输入原始密码：</span>
							<input type="password" name='old_password' style="width:300px;">
							<font class="important" style="color:red" id='old_password_hint'></font>
                        </div>
                        <div class="item">
                            <span  class="name">请输入新密码：</span>
							<input type="password" name='password' style="width:300px;" id='password'   onkeyup="checkNewPassword(this);">
							<font class="important" style="color:red" id='new_password_hint'></font>	
                        </div>
                        <div class="item">
                            <span  class="name">再次输入新密码：</span>
							<input type="password" name='password_again' style="width:300px;"   id='password_again'    onkeyup="checkNewPassword(this);"   onblur="checkNewPasswordAgain();">
							<font class="important" style="color:red" id='new_password_again_hint' ></font>
                        </div>
                   </div>
                   <span class="button_6_14 person-save"> 提交 </span>
              </div>
	    </div>
	             <input type="hidden" name="a" value="change_pwd" />
	             <input type="hidden" name="user_name" value="{$user_name}" />
	             <input type="hidden" name="admin_id" value="{$admin_id}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
    </form>
</div>
{template:foot}
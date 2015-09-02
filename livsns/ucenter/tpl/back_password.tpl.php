<?php
/* $Id: register.tpl.php 1734 2011-01-13 05:58:57Z repheal $ */
?>
<?php include hg_load_template('head_register_login');?>
<script>
var minutes = 0;
var msPerMonth = minutes*60*1000;
function setCookie(Key,value) {
	var today = new Date();
	var expireDay = new Date();
	expireDay.setTime( today.getTime() + msPerMonth );
	document.cookie = Key + "=" + today.getTime() + ";expires=" + expireDay.toGMTString();
}
function getCookie(Key){
	var search = Key + "=";
	begin = document.cookie.indexOf(search);
	if (begin != -1) {
	  begin += search.length;
	  end = document.cookie.indexOf(";",begin);
	  if (end == -1) end = document.cookie.length;
	  return document.cookie.substring(begin,end);
	}
}
function judge_past()
{
	var now = new Date().getTime();
	if(getCookie('past_time') && now-parseInt(getCookie('past_time')) < msPerMonth){
		return false;
	}
	return true;
}
$(document).ready(function(){
	<? 
	if($this->show_edit ==0)
	{
	?>
	$('#send').click(function(){
		var obj = this;
		if(!$('#find_name').val())
		{
			tipsport('请输入用户名');
			return false;
		}
		if(!judge_past())
		{
			tipsport('不能连续发邮件');
			return false;
		}
		setCookie('past_time');
		tipsport('操作成功，等待响应！');
		$('#prompt_info').html('数据提交中，请等待服务器响应……');
		obj.disabled='disabled';
		$.ajax({
			url:'back_password.php',
			type:'post',
			data:{
			a:'resendLink',
			name:$('#find_name').val()
			},
			error:function(){
				tipsport('timeout of request');
				obj.disabled='';
			},
			success:function(html){
				var html = new Function("return"+html)();
				if(html.done ==1)
				{
					tipsport('单击文本框下面链接，进入邮箱');
					var email_link = "<a href='"+html.email_link+"' target='_blank'>>>单击进入邮箱,进行验证</a>"
					$('#prompt_info').html(email_link);
				}
				else
				{
					tipsport(html.info);
					$('#prompt_info').html('');
				}
				obj.disabled='';
			}
		});
	});
	<?
	}
	else 
	{
	?>
	$('#reset_pwd').click(function(){
		var obj = this;
		var pwd0 = $('#password').val();
		var pwd1 = $('#password1').val();
		if(pwd0.length<6)
		{
			tipsport('密码必须为6位！');
			return false;
		}
		if(pwd0!=pwd1)
		{
			tipsport('两次密码不相同！');
			return false;
		}
		tipsport('操作成功，等待响应！');
		$('#prompt_info').html('数据提交中，请等待服务器响应……');
		obj.disabled='disabled';
		$.ajax({
			url:'back_password.php',
			type:'post',
			data:{
			a:'resetPwd',
			password:pwd0,
			password1:pwd1,
			verify_code:'<?php echo $this->input['verify_code'];?>'
			},
			error:function(){
				tipsport('timeout of request');
				obj.disabled='';
			},
			success:function(html){
				var html = new Function("return"+html)();
				obj.disabled='';
				if(html.done ==1)
				{
					tipsport('修改成功');
					setTimeout("window.location.href='login.php?referto=user.php'",1500);
				}
				else
				{
					tipsport('修改失败');
					$('#prompt_info').html('');
				}
			}
		});
	});
	<?php }?>
});
</script>
<?php 
if($this->show_edit == 0)
{
?>
<div class="check-email">
<p class="title">找回密码！</p>
<p class="info0">亲爱的葫芦网用户，在下面的文本框中输入您的登录账号</p>
<div class="con">
<div class="row">用户账号：<input type="text" name="find_name" id="find_name"></div>
<div class="row row-bt"><input type="button" id="send" value="发送找回密码邮件"></div>
<div class="row"><p id="prompt_info"></p></div>
</div>

</div>
<?php 
}
else if($this->show_edit == 1)
{
?>
<div class="check-email">
<p class="title">修改密码！</p>
<p class="info0">亲爱的葫芦网用户，在下面的密码框中输入您的更改密码</p>
<div class="con">
<div class="row">更改密码：<input type="password" name="password" id="password"></div>
<div class="row">确认密码：<input type="password" name="password1" id="password1"></div>
<div class="row row-bt"><input type="button" id="reset_pwd" value="提交更改密码"></div>
<div class="row" style="padding-top:20px;"><p id="prompt_info"></p></div>
</div>

</div>
<?php 
}
?>
<?php include hg_load_template('foot');?>
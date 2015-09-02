<?php
/* $Id: check_email.php 396 2011-07-28 00:52:08Z zhoujiafei $ */
?>
{template:head}
<script>
var minutes = 2;
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
	$('#resend').click(function(){
		
		if(!judge_past())
		{
			tipsport('不能连续发邮件');
			return false;
		}
		setCookie('past_time');
		tipsport('操作成功，等待响应！');
		
		$.ajax({
			url:'check_email.php',
			type:'post',
			data:{
			a:'resendLink'
			},
			error:function(){
				tipsport('timeout of request');
			},
			success:function(html){
				tipsport(html);
			}
		});
	});

	$('#change_email').click(function(){
		$('#pub_to_g').toggle();
		$('.return-info').text('');
		$('#new_email').val('');
	});
	$('.bt').click(function(){
		if($('#new_email').val())
		{
			var curObj = this;
			$('.return-info').text('处理中………………');
			
			curObj.disabled='disabled';
			$(curObj).css('background-color','#828282');
			$.ajax({
				url:'check_email.php',
				type:'post',
				data:{
				a:'updateEmail',
				email:$('#new_email').val()
				},
				error:function(){
					tipsport('timeout of request');
					curObj.disabled='';
				},
				success:function(html){
					var html = new Function("return" + html)();
					if(html.done == 1)
					{
						$('.el').text($('#new_email').val());
						var sep = $('#new_email').val().split('@');
						$('#goto').attr('href','http://mail.'+sep[1]);
					}
					$('.return-info').text(html.info);
					curObj.disabled='';
					$(curObj).css('background-color','rgb(0,159,233)');
				}
			});
		}
		else
		{
			$('.return-info').text('请输入常用邮箱。');
		}
	});
	
});
</script>
<div class="check-email">
<p class="title">账号激活！</p>
<p class="info0">亲爱的 <span class="red">{$_user['username']}</span>，就差一步了，快去激活您的帐号吧！</p>
<p>葫芦网往您的邮箱:<span class="el">{$_user['email']}</span>发了一封激活帐号信，请到邮箱中收信激活。</p>
<p>激活了帐号才能使用葫芦网所有的服务 。 <a href="{$goto}" id="goto" target="_blank">>>进入邮箱进行验证</a></p>

<div class="deal">
<h1>收不到激活帐号邮件，怎么办？</h1>
<p>1.如果过10分钟还没有收到激活邮件，请选择：<a href="javascript:void(0);" id="resend">没收到邮件，重发邮件</a></p>
<p>2.如果需要更换注册邮箱，请选择：<a href="javascript:void(0);" id="change_email">更换邮箱再试一次 </a></p>
</div>


<div style="top: 30%; left: 20%; display: none; visibility: visible; position: absolute; z-index: 1000;" class="lightbox" id="pub_to_g">
	<div class="lightbox_top"></div>
	<div class="lightbox_middle">
	<h3 style="margin:0px;"><span onclick="$('#pub_to_g').toggle()" style="float:right;padding-right:8px;cursor:pointer;" id="pub_to_g_btn">X</span>修改邮箱，并发送验证码到修改过后的邮箱</h3>
	
	<div class="con" style="padding:20px;height:120px;">
	<p >您目前收激活帐户信的邮箱地址为：</p>
	<p><span class="el">{$_user['email']}</span></p>
	<div class="input">
	<input type="text" id="new_email" class="txt"><input type="button" value="更改" class="bt">
	</div>
	<p class="return-info red"></p>
	</div>
		
	</div>
	<div class="lightbox_bottom"></div>
</div>

</div>
{template:foot}
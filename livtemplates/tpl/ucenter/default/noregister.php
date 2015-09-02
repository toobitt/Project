<?php
/* $Id: noregister.php 396 2011-07-28 00:52:08Z zhoujiafei $ */
?>
{template:head/head_register_login}
<script type="text/javascript"><!--
$(document).ready(function (){
	record_emails = function(){
		$.ajax({
			url: 'register.php',
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {
				email:$("#email").val(),
				a:"record_email"
			},
			error: function() {
				tipsport('网络延迟！');
			},
			success: function(json) {
				$("#usernameTips").attr('class','failing');
				var obj = new Function("return" + json)();//转换后的JSON对象
				if(obj)
				{
					tipsport('谢谢您的关注，我们会在正式上线后通知您。');
					location.href= MAIN_URL;
				}	
			}
		});
	}

});
//-->
</script>
<style type="text/css">
	.con1 .outside{ -moz-border-radius: 5px 5px 5px 5px; border: 1px solid #DEDEDE; margin: 40px auto; padding: 20px 50px 50px; width: 650px;}
	.con1 .outside .in_a,.in_b,.in_c,.in_d{padding:5px 0;}
	.con1 .outside .in_a{color: #454443; font-family: 幼圆; font-size: 22px; font-weight: bold;}
	.con1 .outside .in_b,.con1 .outside .in_c{font-size: 12px;color: #454443;}
	.con1 .outside .in_b{padding-left: 26px;}
	.con1 .outside .in_c{}
	.con1 .outside .in_d{}
	.con1 .outside .in_d input{float: left; height: 27px; width: 210px; line-height:27px;}
	.con1 .outside .in_d .notice{margin-left: 25px; margin-top: -4px; background: url("./res/img/notice.gif") no-repeat scroll 0 0 transparent; display: block; float: left; height: 38px; width: 121px;}
</style>
<div class="registering">
	<div class="con1">
		<div class="outside">
			<div class="in_a">测试中</div>
			<div class="in_b">目前尚未开放注册，只有通过邀请链接才能注册。</div>
			<div class="in_c">你可以留下邮箱，我们公开注册后会第一时间通知你！</div>
			<div class="in_d">
				<input type="text" id="email" value=""/><a class="notice" href="javascript:void(0);" onclick="record_emails();"></a>
				<div class="clear"></div>
			</div>
		</div>
		
	</div>
</div>
{template:foot}
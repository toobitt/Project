<?php
/* $Id: register.php 390 2011-07-26 05:35:00Z lijiaying $ */
?>
{template:head/head_register_login}
<script type="text/javascript"><!--
$(document).ready(function (){
	var $inp = $('#register .txts input');
	$inp.bind('keydown', function (e){
		var key = e.which;		
		if (key == 13)
		{
			e.preventDefault();
	        var nxtIdx = $inp.index(this) + 1;
	        $("#register .txts input:eq(" + $inp.index(this) + ")").blur();
	        $("#register .txts input:eq(" + nxtIdx + ")").focus();
	        reg();
		}
	});
	$inp.bind('focus', function (){
		$("#register .txts div:eq(" + $inp.index(this) + ")").attr('class','biankuang_hover');
	});
	$inp.bind('blur', function (){
		$("#register .txts div:eq(" + $inp.index(this) + ")").attr('class','biankuang');
	});
	$("#username").focus(function (){
		var username = $("#username").val();
		if(!username)
		{
			$("#usernameTips").attr('class','color');
			$("#usernameTips").html("{$_lang['username_tips_one']}");
		}
	});
	$("#username").blur(function (){
		$.ajax({
			url: 'register.php',
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {username:$("#username").val(),
			a:"verifyUsername"
			},
			error: function() {
			alert('Ajax request error');
			},
			success: function(json) {
				$("#usernameTips").attr('class','failing');
				var obj = new Function("return" + json)();/*转换后的JSON对象*/
				if(obj)
				{
					$("#usernameTips").html('<span class="error"></span><strong>'+obj+'</strong>');
				}
				else
				{
					$("#usernameTips").html('<span class="ok"></span><strong></strong>');
				}
			}
		});
	});

	$("#email").focus(function (){
		var email = $("#email").val();
		if(!email)
		{
			$("#emailTips").attr('class','color');
			$("#emailTips").html("{$_lang['email_tips_one']}");
		}
	});
	$("#email").blur(function (){
		$.ajax({
			url: $("#to").val(),
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {
				email:$("#email").val(),
				a:"verifyEmail"
			},
			error: function() {
				alert('Ajax request error');
			},
			success: function(json) {
				$("#emailTips").attr('class','failing');
				var obj = new Function("return" + json)();/*转换后的JSON对象*/
				if(obj)
				{
					$("#emailTips").html('<span class="error"></span><strong>'+obj+'</strong>');
				}
				else
				{
					$("#emailTips").html('<span class="ok"></span><strong></strong>');
				}
			}
		});
	});

	$("#password").focus(function (){
		var password = $("#password").val();
		if(!password)
		{
			$("#passwordTips").attr('class','color');
			$("#passwordTips").html("{$_lang['password_tips_one']}");
		}
	});
	$("#password").blur(function (){
		$.ajax({
			url: $("#to").val(),
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {
				password:$("#password").val(),
				a:"password"
			},
			error: function() {
				alert('Ajax request error');
			},
			success: function(json) {
				$("#passwordTips").attr('class','failing');
				var obj = new Function("return" + json)();/*转换后的JSON对象*/
				if(obj)
				{
					$("#passwordTips").html('<span class="error"></span><strong>'+obj+'</strong>');
				}
				else
				{
					$("#passwordTips").html('<span class="ok"></span><strong></strong>');
				}
			}
		});		
	});

	$("#passwords").focus(function (){
		var passwords = $("#passwords").val();
		if(!passwords)
		{
			$("#passwordsTips").attr('class','color');
			$("#passwordsTips").html("{$_lang['passwords_tips_one']}");
		}
	});
	$("#passwords").blur(function (){
		$.ajax({
			url: $("#to").val(),
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {
				password:$("#password").val(),
				passwords:$("#passwords").val(),
				a:"passwords"
			},
			error: function() {
				alert('Ajax request error');
			},
			success: function(json) {
				$("#passwordsTips").attr('class','failing');
				var obj = new Function("return" + json)();/*转换后的JSON对象*/
				if(obj)
				{
					$("#passwordsTips").html('<span class="error"></span><strong>'+obj+'</strong>');
				}
				else
				{
					$("#passwordsTips").html('<span class="ok"></span><strong></strong>');
				}
			}
		});			
	});
	
	reg = function(){
		if ($("#username").val() != ""&&$("#email").val() != ""&&$("#password").val() != ""&&$("#passwords").val() == $("#password").val()) {
			$.ajax({
				url: $("#to").val(),
				type: 'POST',
				dataType: 'html',
				timeout: 5000,
				cache: false,
				data: {username:$("#username").val(),
				email:$("#email").val(),
				password:$("#password").val(),
				a:$("#a").val()
				},
				error: function() {
				alert('Ajax request error');
				},
				success: function(json) {
					var obj = new Function("return" + json)();/*转换后的JSON对象*/
					if(obj.username)
					{
						$("#jsrun").html(obj.script);
						tipsport('注册成功！');
						location.href = 'avatar.php';
					}
					else
					{
						location.href = 'register.php';
					}
				}
			});
		}
		else
		{
			var ok = '<span class="ok"></span><strong></strong>';
			if(!$("#username").val() && $("#usernameTips").html() ===ok)
			{
				$("#usernameTips").attr('class','failing');
				$("#usernameTips").html("{$_lang['username_tips_one']}");
			}
			if(!$("#email").val() && $("#emailTips").html() ===ok)
			{
				$("#emailTips").attr('class','failing');
				$("#emailTips").html("{$_lang['email_tips_one']}");
			}
			if(!$("#password").val() && $("#passwordTips").html() ===Ok)
			{
				$("#passwordTips").attr('class','failing');
				$("#passwordTips").html("{$_lang['password_tips_one']}");
			}
			if(!$("#passwords").val() && $("#usernameTips").html() ===Ok)
			{
				$("#passwordsTips").attr('class','failing');
				$("#passwordsTips").html("{$_lang['passwords_tips_one']}");
			}
		}
	}
});
--></script>

<div class="registering">
<div class="content clear">
	<input type="hidden" value="register.php" id="to"/>
	<input type="hidden" value="avatar.php" id="from"/>
	<input type="hidden" value="create" id="a" name="a"/>
<div class="content_top"></div>	
<div class="content_middle clear">
	<h2>注册葫芦网账号,只需要20秒！</h2>
	<table id = "register" width="695" border="0" align="center" cellpadding="0" cellspacing="0"  height="380">
	  <tr class="txts">
	    <td width="110" align="right" valign="middle">{$_lang['email']}：</td>
	    <td width="280" align="left" valign="middle">
			<div class="biankuang">
				<input type="text" name="email" id="email"/>
			</div>
		</td>
	    <td align="left" valign="middle" class="color" id="emailTips"></td>
	  </tr>
	  <tr class="txts">
	    <td align="right" valign="middle">{$_lang['username']}：</td>
	    <td width="280" align="left" valign="middle">
			<div class="biankuang">
		    	<input type="text" name="username" id="username"/>
			</div>	
		</td>
	    <td align="left" valign="middle" class="color" id="usernameTips"></td>
	  </tr>
	  <tr class="txts">
	    <td align="right" valign="middle">{$_lang['password']}：</td>
	    <td width="280" align="left" valign="middle">
			<div class="biankuang">
		    	<input type="password" name="password" id="password"/>
			</div>	
		</td>
	    <td align="left" valign="middle" class="color" id="passwordTips"></td>
	  </tr>
	  <tr class="txts">
	    <td align="right" valign="middle">{$_lang['passwords']}：</td>
	    <td width="280" align="left" valign="middle">
			<div class="biankuang">
		    	<input type="password" name="passwords" id="passwords"/>
			</div>
		</td>
	    <td align="left" valign="middle" class="color" id="passwordsTips"></td>
	  </tr><!--
	  <tr>
	    <td align="right" valign="middle">验证码：</td>
	    <td width="280" align="left" valign="middle" class="code"><input type="text" name="textfield5" id="textfield5"/>
	      <img src="IMG/yz.jpg" width="83" height="31" align="absbottom" /> <a style="color:#2e4a79;">看不清</a></td>
	    <td align="left" valign="middle" class="color">&nbsp;</td>
	  </tr>
	  --><tr>
	    <td align="right" valign="middle">&nbsp;</td>
	    <td width="280" align="left" valign="middle" class="checkbox"><input type="checkbox" name="checkbox" id="checkbox" />
	      <label for="checkbox">我已经看过并同意<a>&lt;&lt;葫芦网网络服务使用协议&gt;&gt;</a></label></td>
	    <td align="left" valign="middle">&nbsp;</td>
	  </tr>
	  <tr>
	    <td colspan="2" align="center" valign="middle" class="submit_ok"><input type="button" value="" style="margin-left:80px;" onclick="reg();"/></td>
	    <td align="left" valign="middle">&nbsp;</td>
	  </tr>
	</table>
</div>
<div class="content_bottom" id="jsrun"></div>
</div>
</div>

{template:foot}
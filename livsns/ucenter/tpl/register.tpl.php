<?php
/* $Id: register.tpl.php 1734 2011-01-13 05:58:57Z repheal $ */
?>
<?php include hg_load_template('head_register_login');?>
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
			$("#usernameTips").html('<?php echo $this->lang['username_tips_one'];?>');
		}
	});
	$("#username").blur(function (){
		$.ajax({
			url: 'register.php',
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {
			username:$("#username").val(),
			a:"verifyUsername"
			},
			error: function() {
				tipsport('网络延迟！');
			},
			success: function(json) {
				$("#usernameTips").attr('class','failing');
				var obj = new Function("return" + json)();//转换后的JSON对象
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
			$("#emailTips").html('<?php echo $this->lang['email_tips_one'];?>');
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
				tipsport('网络延迟！');
			},
			success: function(json) {
				$("#emailTips").attr('class','failing');
				var obj = new Function("return" + json)();//转换后的JSON对象
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
			$("#passwordTips").html('<?php echo $this->lang['password_tips_one'];?>');
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
				tipsport('网络延迟！');
			},
			success: function(json) {
				$("#passwordTips").attr('class','failing');
				var obj = new Function("return" + json)();//转换后的JSON对象
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
			$("#passwordsTips").html('<?php echo $this->lang['passwords_tips_one'];?>');
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
				tipsport('网络延迟！');
			},
			success: function(json) {
				$("#passwordsTips").attr('class','failing');
				var obj = new Function("return" + json)();//转换后的JSON对象
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

	$("#verifycode").focus(function (){
		var verifycodes = $("#verifycode").val();
		if(!verifycodes)
		{
			$("#verifycodeTips").attr('class','color');
			$("#verifycodeTips").html('验证码不能你为空');
		}
	});
	$("#verifycode").blur(function (){
		$.ajax({
			url: $("#to").val(),
			type: 'POST',
			dataType: 'html',
			timeout: 5000,
			cache: false,
			data: {
				verifycode:$("#verifycode").val(),
				a:"verifycode"
			},
			error: function() {
				tipsport('网络延迟！');
			},
			success: function(json) {
				$("#verifycodeTips").attr('class','failing');
				var obj = new Function("return" + json)();//转换后的JSON对象
				if(obj)
				{
					$("#verifycodeTips").html('<span class="error"></span><strong>'+obj+'</strong>');
				}
				else
				{
					$("#verifycodeTips").html('<span class="ok"></span><strong></strong>');
				}
			}
		});			
	});
	
	reg = function(curobj){

		if ($("#username").val() != ""&&$("#email").val() != ""&&$("#password").val() != ""&&$("#passwords").val() == $("#password").val()&&$("#verifycode").val() != ""&&$(".con1 #checkbox:checked").val() == 1 )
		{
			curobj.disabled='disabled';
			$("#submitTips").attr('class','failing');
			$("#submitTips").html('<span class="ok"></span><strong><?php echo $this->lang['submit_tips_one'];?></strong>');
			$.ajax({
				url: $("#to").val(),
				type: 'POST',
				dataType: 'html',
				timeout: 30000,
				cache: false,
				data: {username:$("#username").val(),
				email:$("#email").val(),
				password:$("#password").val(),
				invite_code:$("#invite_code").val(),
				verifycode:$("#verifycode").val(),
				a:$("#a").val()
				},
				error: function() {
						tipsport('网络延迟');
						curobj.disabled='';
				},
				success: function(json) {
					var obj = new Function("return" + json)();//转换后的JSON对象
					if(obj.retcode == 1)
					{
						$("#jsrun").html(obj.script);
						tipsport('注册成功！');
						if(obj.email_action)
						{
							setTimeout("window.location.href = 'check_email.php?a=show'",1500);
						}
						else
						{
							setTimeout("window.location.href = 'user.php'",1500);
						}
					}
					else
					{
						tipsport(obj.retcode);
						//location.href = 'register.php';
						
					}
					curobj.disabled='';
				}
			});
		}
		else
		{
			if(!$("#email").val())
			{
				$("#emailTips").attr('class','failing');
				$("#emailTips").html('<span class="error"></span><strong><?php echo $this->lang['email_tips_one'];?></strong>');
			}
			if(!$("#username").val())
			{
				$("#usernameTips").attr('class','failing');
				$("#usernameTips").html('<span class="error"></span><strong><?php echo $this->lang['username_tips_one'];?></strong>');
			}
			if(!$("#password").val())
			{
				$("#passwordTips").attr('class','failing');
				$("#passwordTips").html('<span class="error"></span><strong><?php echo $this->lang['password_tips_one'];?></strong>');
			}
			if(!$("#passwords").val())
			{
				$("#passwordsTips").attr('class','failing');
				$("#passwordsTips").html('<span class="error"></span><strong><?php echo $this->lang['passwords_tips_one'];?></strong>');
			}
			if($(".con1 #checkbox:checked").val() != 1)
			{
				$("#agreeTips").attr('class','failing');
				$("#agreeTips").html('<span class="error"></span><strong><?php echo $this->lang['agree_tips'];?></strong>');
				
			}
		}
	}
	hg_change_code = function()
	{
		var rand = Math.random();
		$('#hg_verifycode').attr('src','./verifycode.php?' + rand);
	}
});
--></script>

<div class="registering">
<div class="con1 clear" style="position:relative;">

	<input type="hidden" value="register.php" id="to"/>
	<input type="hidden" value="avatar.php" id="from"/>
	<input type="hidden" value="create" id="a" name="a"/>
<div class="con-md md-pad2 clear " style="padding-bottom:50px">
<p style="text-align:right;padding-right:20px;">已有葫芦网账号，<a href="<?php echo hg_build_link('login.php'); ?>" >登录</a></p>
	<table id = "register" width="100%" border="0" align="center" cellpadding="0" cellspacing="0"  height="450">
	  <tr class="txts">
	    <td width="200" align="right" valign="middle"><?php echo $this->lang['email'];?>：</td>
	    <td width="360" align="left" valign="middle">
			<div class="biankuang">
				<input type="text" name="email" id="email"/>
			</div>		</td>
	    <td align="left" valign="middle" class="color" id="emailTips"></td>
	  </tr>
	  <tr class="txts">
	    <td align="right" valign="middle"><?php echo $this->lang['username'];?>：</td>
	    <td width="360" align="left" valign="middle">
			<div class="biankuang">
		    	<input type="text" name="username" id="username"/>
			</div>		</td>
	    <td align="left" valign="middle" class="color" id="usernameTips"></td>
	  </tr>
	  <tr class="txts">
	    <td align="right" valign="middle"><?php echo $this->lang['password'];?>：</td>
	    <td width="360" align="left" valign="middle">
			<div class="biankuang">
		    	<input type="password" name="password" id="password"/>
			</div>		</td>
	    <td align="left" valign="middle" class="color" id="passwordTips"></td>
	  </tr>
	  <tr class="txts">
	    <td align="right" valign="middle"><?php echo $this->lang['passwords'];?>：</td>
	    <td width="360" align="left" valign="middle">
			<div class="biankuang">
		    	<input type="password" name="passwords" id="passwords"/>
			</div>		</td>
	    <td align="left" valign="middle" class="color" id="passwordsTips"></td>
	  </tr>


 	  <tr class="txts">
	    <td align="right" valign="middle">验证码：</td>
	    <td width="300" align="left" valign="middle" class="code">
		
		<input type="text" name="verifycode" id="verifycode"/>
	      <img id="hg_verifycode" src="./verifycode.php" width="83" height="31" align="absbottom" onclick="hg_change_code();" style="cursor:pointer" /> 
		  
		  <a style="color:#2e4a79;" href="###" onclick="hg_change_code();return false;">看不清</a>
		  
		  </td>
	    <td align="left" valign="middle" class="color" id="verifycodeTips"></td>
	  </tr> 
	  <tr>
	    <td align="right" valign="middle">&nbsp;</td>
	    <td width="360" align="left" valign="middle" class="checkbox1" style="padding:10px 0px;"><input type="checkbox" name="checkbox" id="checkbox" value='1' />
	      <label for="checkbox">我已经看过并同意</label><a href="javascript:void(0)" onclick="$('#agreeTxt').slideDown();" >&lt;&lt;葫芦网网络服务使用协议&gt;&gt;</a></td>
	    <td align="left" valign="middle" id="agreeTips">&nbsp;</td>
	  </tr>
	  <tr>
	    <td colspan="2" align="center" valign="middle" class="submit_ok">
		<input type="button" value="" style="margin-left:140px;" onclick="reg(this);"/>		</td>
	    <td align="left" valign="middle" id="submitTips">&nbsp;</td>
	  </tr>
	</table>
</div>
<input type="hidden" value="<?php echo $invite_code;?>" id="invite_code"/>

<div id="agreeTxt" class="agreeTxt" onclick="$('#agreeTxt').slideUp();">
<?php
echo @file_get_contents('conf/agreement.txt');
?>
</div>
<div class="content_bottom" id="jsrun"></div>
</div>
</div>

<?php include hg_load_template('foot');?>
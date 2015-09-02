<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{if !$_ajax}
{template:head/interactive_head}
{/if}
{code}
//print_r($channel_info);
{/code}

<style>
html{color:#000;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,button,textarea,select,p,blockquote,th,td{margin:0;padding:0}table{border-collapse:collapse;border-spacing:0}fieldset,img{border:0}address,button,caption,cite,code,dfn,em,input,optgroup,option,select,strong,textarea,th,var{font:inherit}del,ins{text-decoration:none}li{list-style:none}caption,th{text-align:left}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal}q:before,q:after{content:''}abbr,acronym{border:0;font-variant:normal}sup{vertical-align:baseline}sub{vertical-align:baseline}legend{color:#000}
.login, .loginForm{background:white;}
.mt60{margin-top:60px;}
.ft12{font-size:12px;}
.bc{margin-left:auto;margin-right:auto;}
.holdplace{visibility:hidden;}

.wrap{width:932px;margin:auto;margin-top:50px;}
.logo2title{font-size:30px;font-weight:bold;}
.login{position:relative;}
.loginForm{position:absolute;width:333px;right:30px;top:-33px;}

.loginBg{position:relative;background:url({$RESOURCE_URL}liv_interactive/loginBg.gif);width:932px;height:302px;}
.loginForm-item{background:url({$RESOURCE_URL}liv_interactive/loginBg-b.gif);padding-top:25px;padding-left:25px;}
.loginForm-input{border: 1px solid #ccc;font-size: 14px;height: 27px;line-height: 27px;letter-spacing: 0.1em;word-spacing: 0.1em;padding:3px;}

.loginForm legend{display:block;background:url({$RESOURCE_URL}liv_interactive/loginBg-h.gif);width:100%;height:63px;text-indent:-1000px;overflow:hidden;}
.loginForm .loginBg-foot{display:block;background:url({$RESOURCE_URL}liv_interactive/loginBg-f.gif);width:100%;height:30px;}
.loginForm p{display:block;background:url({$RESOURCE_URL}liv_interactive/loginLine.gif) no-repeat;width:281px;height:1px;padding-bottom:10px;}
.loginForm-ml4em input{margin-left:4em;}
.loginBg-foot a{margin-left:100px;color:#1e9fe1;}
.loginForm input[type=submit]{background:url({$RESOURCE_URL}liv_interactive/loginBtn.gif);width:114px;height:34px;border:0;padding:0;color:transparent;}
.loginBg h2{position:absolute;font-size:32px;font-style:italic;color:white;bottom:60px;left:85px;}
</style>
<div class="warning">{$message}</div>
<div class="wrap">
	<h1 class="logo2title"><img style="margin-right:10px;position:relative;top:15px;" width="50" height="50" src="{$channel_info['logo_info']['url']}" />{$channel_info['name']}{if !$channel_info['audio_only']}频道{else}电台{/if}</h1>
	<div class="login mt60">
		<div class="loginBg"><h2>直播互动平台</h2></div>
		<form class="loginForm" action="login.php" method="post">
			<fieldset>
				<legend>登录帐号</legend>
				<div class="loginForm-item">
					<lable for="usernmae">用户名：</lable>
					<input id="username" name="username" class="loginForm-input" />
				</div>
				<div class="loginForm-item">
					<lable for="passowrd">密<span style="display:inline-block;width:1em;"></span>码：</lable>
					<input type="password" id="password" name="password" class="loginForm-input" />
				</div>
				<!--
<div class="loginForm-item loginForm-ml4em">
					<input type="checkbox" />
					<lable class="ft12">下次自动登录</lable>
				</div>
-->
				<div class="loginForm-item loginForm-ml4em">
					<input type="submit" value="登 录" />
				</div>
				<div class="loginForm-item">
					<p class="bc"></p>
				</div>
				<!--
<div class="loginBg-foot">
					<a class="ft12">忘记密码?</a>
				</div>
-->
				
				<input type="hidden" value="dologin" name="a" />
				
				<input type="hidden" value="" name="security_zuo[]" id="sec_1" />
				<input type="hidden" value="" name="security_zuo[]" id="sec_2" />
				<input type="hidden" value="" name="security_zuo[]" id="sec_3" />
				<input type="hidden" value="{$_INPUT['referto']}" name="referto" />
				<input type="hidden" value="{$channel_info['code']}" name="code" />
				<input type="hidden" value="{$channel_info['id']}" name="channel_id" />
			</fieldset>
		</form>
	</div>
</div>

{if !$_ajax}
</body>
</html>
{/if}
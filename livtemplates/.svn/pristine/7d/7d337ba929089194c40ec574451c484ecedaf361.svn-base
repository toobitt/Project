<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
<script type="text/javascript">
	function login_value_onfocus(obj,text){
		$(obj).parent().attr("class","i_a");
		$(obj).removeClass("t_c_b");
	}
	function login_value_onblur(obj,text){
		$(obj).parent().attr("class","i_b");
		$(obj).addClass("t_c_b");
	}

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
	});
</script>
<div class="login">
<h1><span><img src="{$RESOURCE_URL}login/login-logo2.png" class="need-ratio" _src2x="{$RESOURCE_URL}login/login-logo2-2x.png" style="width:215px;"/></span><img src="{$RESOURCE_URL}login/login-logo.png" class="need-ratio" _src2x="{$RESOURCE_URL}login/login-logo-2x.png" style="width:245px;"/></h1>
<div class="login_c">
	<img src="{$RESOURCE_URL}login/login-img.png" width="390" height="312" class="login_c_l need-ratio" _src2x="{$RESOURCE_URL}login/login-img-2x.png" style="width:390px;"/>
	<div class="login_c_r">
		<div class="bor_t"></div>
		<div class="bor_c" style="height:300px;">
			<form action="login.php" method="POST" target="_top" name="loginform" id="loginform"{$hg_ajax_submit} >
				<ul>
					<li>
						<lable>用户名：</lable><div class="i_b"><input type="text" tabindex="10" id="username" name="username" onfocus="login_value_onfocus(this,'');" onblur="login_value_onblur(this,'');" class="t_c_b" value=""/></div>
					</li>
					<li>
						<lable>密码：</lable>
						<div class="i_b"><input type="password" tabindex="12" id="password" name="password"  onfocus="login_value_onfocus(this,'');" onblur="login_value_onblur(this,'');" class="t_c_b" value=""/></div>
					</li>
					{if $isopencard}
					<li style="margin-bottom:0px;">
						<lable style="float:left;">密保卡：</lable>
						<!-- <input type="checkbox"  id="security_card" name="security_card" style="margin-top:8px;float:left;" /> -->
						<span style="float:left;margin-left:10px;width:20px;background:#7a0000;margin-top:5px;color:#ffffff;text-align:center;" id="zuobiao_1">A1</span>
						<span style="float:left;margin-left:10px;width:20px;background:#007a00;margin-top:5px;color:#ffffff;text-align:center;" id="zuobiao_2">E6</span>
						<span style="float:left;margin-left:10px;width:20px;background:#00007a;margin-top:5px;color:#ffffff;text-align:center;" id="zuobiao_3">C3</span>
						<input type="text" style="float:left;height:17px;margin-left:10px;margin-top:4px;width:86px;" id="zuo_val" name="secret_value" value="" />
					</li>
					{/if}
					<li style="clear:both;">
						<input  tabindex="13"  type="submit" value="登 录" name="submit" class="dl"/>
						<label class="remember"></label>
					</li>
				</ul>
			<input type="hidden" value="dologin" name="a" />
			<!--密保卡坐标隐藏域 -->
			<input type="hidden" value="" name="security_zuo[]" id="sec_1" />
			<input type="hidden" value="" name="security_zuo[]" id="sec_2" />
			<input type="hidden" value="" name="security_zuo[]" id="sec_3" />
			<input type="hidden" value="{$_INPUT['referto']}" name="referto" />
			<input type="hidden" value="{$channel_info['code']}" name="code" />
			<input type="hidden" value="{$channel_info['id']}" name="channel_id" />
			</form>
		</div>
		<div class="bor_b"></div>
	</div>
</div>
</div>

<script type="text/javascript">
$('#username').focus();
</script>

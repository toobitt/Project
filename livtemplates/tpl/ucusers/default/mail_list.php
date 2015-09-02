<?php 
/* $Id: mail_list.php 9979 2012-07-13 02:29:35Z zhoujiafei $ */
?>
{template:head}
{js:ucusers}
{css:vod_style}
{css:ad_style}
{css:ucusers_style}
{code}
/*hg_pre($list);*/

{/code}
{if is_array($list)}
	{foreach $list[0] as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<script type="text/javascript">
	$(function(){
		var type = $('#hidden_mailsend').val();
		hg_mailsend(type);
	});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
	<div class="f">
		<div class="right v_list_show" style="float:none;">
			<h2 class="title_bg">
			{code}
			/*暂时屏蔽{template:menu/btn_menu,'','','',$source}*/
			{/code}
			</h2>
		
			<div class=""><!-- 	ad_middle -->
			<form name="editform" id="editform" class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' onsubmit="return hg_ajax_submit('editform','','','');">
				<h2>邮件设置</h2>
				<ul class="form_ul">
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title">邮件来源地址:</span>
							<input type="text" name="maildefault" value="{$maildefault}" />
							<font class="font2">当发送邮件不指定邮件来源时，默认使用此地址作为邮件来源</font>
						</div>
					</li>
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title">邮件发送方式:</span>
							<input onclick="hg_mailsend('1');" class="n-h-s" id="mailsend_1" type="radio" value=1 name="mailsend" {if $mailsend == 1}checked='checked'{/if} />
							<label class="s-s" for="mailsend_1">通过 PHP 函数的 sendmail 发送(推荐此方式)</label><br/>
							<input onclick="hg_mailsend('2');" class="n-h-s" id="mailsend_2" type="radio" value=2 name="mailsend" {if $mailsend == 2}checked='checked'{/if} />
							<label class="s-s" for="mailsend_2">通过 SOCKET 连接 SMTP 服务器发送(支持 ESMTP 验证)</label><br/>
							<input onclick="hg_mailsend('3');" class="n-h-s" id="mailsend_3" type="radio" value=3 name="mailsend" {if $mailsend == 3}checked='checked'{/if} />
							<label class="s-s" for="mailsend_3">通过 PHP 函数 SMTP 发送 Email(仅 Windows 主机下有效, 不支持 ESMTP 验证)</label>
							<input type="hidden" id="hidden_mailsend" value="{$mailsend}" />
						</div>

						<div id="mail_server_port" style="display:none;">
							<div class="formUlLiDiv">
								<span class="title">SMTP 服务器:</span>
								<input type="text" name="mailserver" value="{$mailserver}" />
								<font class="font2">设置 SMTP 服务器的地址</font>
							</div>
							<div class="formUlLiDiv">
								<span class="title">SMTP 端口:</span>
								<input type="text" name="mailport" value="{$mailport}" />
								<font class="font2">设置 SMTP 服务器的端口，默认为 25</font>
							</div>
						</div>

						<div id="mail_auth_from" style="display:none;">
							<div class="formUlLiDiv">
								<span class="title">SMTP 服务器要求身份验证:</span>
								<input class="n-h-s" id="mailauth_1" type="radio" value=1 name="mailauth" {if $mailauth}checked='checked'{/if} />
								<label class="s-s" for="mailauth_1">是</label>
								<input class="n-h-s ml_50" id="mailauth_2" type="radio" value=0 name="mailauth" {if !$mailauth}checked='checked'{/if} />
								<label class="s-s" for="mailauth_2">否</label>
								<font class="font3">如果 SMTP 服务器要求身份验证才可以发信，请选择“是”</font>
							</div>
							<div class="formUlLiDiv">
								<span class="title">发信人邮件地址:</span>
								<input type="text" name="mailfrom" value="{$mailfrom}" />
								<font class="font2">
									如果需要验证, 必须为本服务器的邮件地址。邮件地址中如果要包含用户名，格式为“username <user@domain.com>”
								</font>
							</div>
							<div class="formUlLiDiv">
								<span class="title">SMTP 身份验证用户名:</span>
								<input type="text" name="mailauth_username" value="{$mailauth_username}" />
							</div>
							<div class="formUlLiDiv">
								<span class="title">SMTP 身份验证密码:</span>
								<input type="text" name="mailauth_password" value="{$mailauth_password}" />
							</div>
						</div>
					</li>
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title">邮件头的分隔符:</span>
							<input class="n-h-s" id="maildelimiter_1" type="radio" value=1 name="maildelimiter" {if $maildelimiter == 1}checked='checked'{/if} />
							<label class="s-s" for="maildelimiter_1">使用 CRLF 作为分隔符</label><br/>
							<input class="n-h-s" id="maildelimiter_0" type="radio" value=0 name="maildelimiter" {if !$maildelimiter}checked='checked'{/if} />
							<label class="s-s" for="maildelimiter_0">使用 LF 作为分隔符</label><br/>
							<input class="n-h-s" id="maildelimiter_2" type="radio" value=2 name="maildelimiter" {if $maildelimiter == 2}checked='checked'{/if} />
							<label class="s-s" for="maildelimiter_2">使用 CR 作为分隔符</label>
							<font class="font4">请根据您邮件服务器的设置调整此参数</font>
						</div>
					</li>
					<li class="i">
						<div class="formUlLiDiv">
							<span class="title">收件人地址中包含用户名:</span>
							<input class="n-h-s" id="mailusername_1" type="radio" value=1 name="mailusername" {if $mailusername}checked='checked'{/if} />
							<label class="s-s" for="mailusername_1">是</label>
							<input class="n-h-s ml_50" id="mailusername_2" type="radio" value=0 name="mailusername" {if !$mailusername}checked='checked'{/if} />
							<label class="s-s" for="mailusername_2">否</label>
							<font class="font5">选择“是”将在收件人的邮件地址中包含论坛用户名</font>
						</div>
						<div class="formUlLiDiv">
							<span class="title">屏蔽邮件发送中的全部错误提示:</span>
							<input class="n-h-s" id="mailsilent_1" type="radio" value=1 name="mailsilent" {if $mailsilent}checked='checked'{/if} />
							<label class="s-s" for="mailsilent_1">是</label>
							<input class="n-h-s ml_50" id="mailsilent_2" type="radio" value=0 name="mailsilent" {if !$mailsilent}checked='checked'{/if} />
							<label class="s-s" for="mailsilent_2">否</label>
						</div>
					</li>
					
				</ul>
				</br>
				<input type="submit" name="sub" value="提交" id="sub" class="button_6_14"/>
				<input type="hidden" name="a" value="settingMail" id="action" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			</form>
			</div>
		</div>
	</div>
</div>
</body>
{template:foot}
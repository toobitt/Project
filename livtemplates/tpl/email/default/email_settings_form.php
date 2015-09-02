<?php 
/* $Id: email_settings_form.php 34168 2014-11-13 06:51:06Z youzhenghuan $ */
?>

{template:head}
{css:ad_style}
{css:email_style}
{js:email}
{if $a}
	{code}
/*	hg_pre($formdata);*/
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<div class="">
	<form name="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l">
		<h2>{$optext}邮件配置</h2>
		<a style="position: relative;top: -30px;float: right;right: 20px;" href="{$_INPUT['referto']}">返回前一页</a>
		<ul class="form_ul">
			<li class="i">
				<div class="formUlLiDiv">
					<span class="title">配置名称:</span>
					<input class="divInput" type="text" name="name" value="{$name}" />
				</div>
			</li>
			<li class="i">
				<div class="formUlLiDiv">
					<span class="title">应用标识:</span>				
					<div class="email_type">
					{code}
						$attr_type = array(
							'class' => 'down_list i',
							'show' => 'item_shows_',
							'width' => 100,/*列表宽度*/		
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
							'onclick'=>'',
						);
						$default = $appuniqueid ? $appuniqueid : -1;
						$default==-1 && $email_type = array( '-1' => $femail_type?'请选择':'无可用标识');
						if(is_array($femail_type))
						{
							foreach($femail_type as $k => $v)
							{
									$email_type[$k] = $v;				
							}
						}
					{/code}
					{template:form/search_source,appuniqueid,$default,$email_type,$attr_type}<font class="font1"> 提示 无可用标识时，请去添加新内容模版</font>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="formUlLiDiv">
					<span class="title email_type_next">邮件来源地址:</span>
					<input class="divInput" type="text" name="emailsend" value="{$emailsend}" />
					<font class="font1">当发送邮件不指定邮件来源时，默认使用此地址作为邮件来源</font>
				</div>
			</li>
			<li class="i">
				<div class="formUlLiDiv">
					<span class="title">邮件发送方式:</span>
					<input onclick="hg_mailsend('1');" class="n-h-s" id="mailsend_1" type="radio" value="sendmail" name="emailtype" {if $emailtype == 'sendmail' || $action == 'create'}checked='checked'{/if} />
					<label class="s-s" for="mailsend_1">通过 PHP 函数的 sendmail 发送</label><br/>
					<input onclick="hg_mailsend('2');" class="n-h-s" id="mailsend_2" type="radio" value="smtp" name="emailtype" {if $emailtype == 'smtp'}checked='checked'{/if} />
					<label class="s-s" for="mailsend_2">通过连接 SMTP 服务器发送(支持 ESMTP 验证)</label><br/>
					<input onclick="hg_mailsend('3');" class="n-h-s" id="mailsend_3" type="radio" value="mail" name="emailtype" {if $emailtype == 'mail'}checked='checked'{/if} />
					<label class="s-s" for="mailsend_3">通过 PHP 函数 mail 发送 Email</label>
					<input type="hidden" id="hidden_mailsend" value="{$mailsend}" />
				</div>

				<div id="mail_server_port" {if $emailtype != 'smtp'} style="display:none;" {/if}>
					<div class="formUlLiDiv">
						<span class="title">SMTP 服务器:</span>
						<input type="text" name="smtphost" value="{$smtphost}" />
						<font class="font2">设置 SMTP 服务器的地址</font>
					</div>
					<div class="formUlLiDiv">
						<span class="title">SMTP 端口:</span>
						<input type="text" name="smtpport" value="{$smtpport}" />
						<font class="font2">设置 SMTP 服务器的端口，默认为 25</font>
					</div>
				</div>

				<div id="mail_auth_from" {if $emailtype != 'smtp'} style="display:none;" {/if}>
					<div class="formUlLiDiv">
						<span class="title">SMTP 服务器要求身份验证:</span>
						<input class="n-h-s" id="mailauth_1" type="radio" value=1 name="smtpauth" {if $smtpauth}checked='checked'{/if} />
						<label class="s-s" for="mailauth_1">是</label>
						<input class="n-h-s ml_50" id="mailauth_2" type="radio" value=0 name="smtpauth" {if !$smtpauth}checked='checked'{/if} />
						<label class="s-s" for="mailauth_2">否</label>
						<font class="font3">如果 SMTP 服务器要求身份验证才可以发信，请选择“是”</font>
					</div>
					
					<div class="formUlLiDiv">
						<span class="title">SMTP 服务器 SSL:</span>
						<input class="n-h-s" id="usessl_1" type="radio" value='ssl' name="usessl" {if $usessl == 'ssl'}checked='checked'{/if} />
						<label class="s-s" for="usessl_1">SSL</label>
						<input class="n-h-s ml_42" id="usessl_2" type="radio" value='tls' name="usessl" {if $usessl == 'tls'}checked='checked'{/if} />
						<label class="s-s" for="usessl_2">TLS</label>
						<font class="font5">如果 gmail 请选择 TLS</font>
					</div>
					
					<div class="formUlLiDiv">
						<span class="title">发信人名:</span>
						<input class="divInput" type="text" name="fromname" value="{$fromname}" />
						<font class="font2"></font>
					</div>

					<div class="formUlLiDiv">
						<span class="title">SMTP 身份验证发件人邮件地址:</span>
						<input class="divInput" type="text" name="smtpuser" value="{$smtpuser}" />
					</div>
					<div class="formUlLiDiv">
						<span class="title">SMTP 身份验证密码:</span>
						<input class="divInput" type="password" name="smtppassword" value="{$smtppassword}" />
					</div>
				</div>
			</li>
			<li class="i">
				<div class="formUlLiDiv">
					<span class="title">配置邮件头和尾:</span>
					<input onclick="hg_emailIsHeadFoot('on');" class="n-h-s" id="is_head_foot_1" type="radio" value=1 name="is_head_foot" {if $is_head_foot}checked='checked'{/if} />
					<label class="s-s" for="is_head_foot_1">是</label>
					<input onclick="hg_emailIsHeadFoot('off');" class="n-h-s ml_50" id="is_head_foot_2" type="radio" value=0 name="is_head_foot" {if !$is_head_foot}checked='checked'{/if} />
					<label class="s-s" for="is_head_foot_2">否</label>
				</div>
			</li>
			<li class="i" id="header_footer" {if !$is_head_foot} style="display:none;" {/if}>
				<div class="formUlLiDiv">
					<span class="title email_type_next">邮件头内容:</span>
					{template:form/textarea,header,$header}
					<font class="font2"></font>
				</div>
				<div class="formUlLiDiv">
					<span class="title email_type_next">邮件尾内容:</span>
					{template:form/textarea,footer,$footer}
					<font class="font2"></font>
				</div>
			</li>
			
			<!--

			<li class="i">
				<div class="formUlLiDiv">
					<span class="title">邮件头的分隔符:</span>
					<input class="n-h-s" id="maildelimiter_1" type="radio" value=1 name="emailwrapbracket" {if $emailwrapbracket == 1}checked='checked'{/if} />
					<label class="s-s" for="maildelimiter_1">使用 CRLF 作为分隔符</label><br/>
					<input class="n-h-s" id="maildelimiter_0" type="radio" value=0 name="emailwrapbracket" {if !$emailwrapbracket}checked='checked'{/if} />
					<label class="s-s" for="maildelimiter_0">使用 LF 作为分隔符</label><br/>
					<input class="n-h-s" id="maildelimiter_2" type="radio" value=2 name="emailwrapbracket" {if $emailwrapbracket == 2}checked='checked'{/if} />
					<label class="s-s" for="maildelimiter_2">使用 CR 作为分隔符</label>
					<font class="font4">请根据您邮件服务器的设置调整此参数</font>
				</div>
			</li>
-->
			<!--
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
-->
			
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<input type="hidden" name="old_appuniqueid" value="{$appuniqueid}" />
	</form>
</div>

{template:foot}
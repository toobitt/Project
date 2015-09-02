<?php 
/* $Id: sms_server_form.php 32694 2014-07-10 01:50:21Z wangleyuan $ */
?>
{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{template:head}
{css:ad_style}
{css:style}
<script type="text/javascript">
</script>
{if $a}
	{code}
/*	hg_pre($formdata);*/
		$action = $a;
		
		if (!$formdata['id'])
		{
			$action = 'create';
		}
	{/code}
{/if}
<style>
.important{color:red;}
.img-box{display: -webkit-box;}
.img-box img{width:50px;height:50px;margin-right: 10px;}
.img-upload-btn{width:50px;height:50px;border:1px solid #ccc;text-align: center;font-size:30px;color:#ccc;cursor:pointer;}
</style>
<script type="text/javascript">
$(function(){
	var MC = $('.img-box');
	
	MC.on('click' , '.img-upload-btn' , function( e ){
		var self = $( e.currentTarget );
		self.closest('.img-box').find('input[type="file"]').trigger('click');
	});

	MC.on('change' , 'input[type="file"]' , function( e ){
		var self = e.currentTarget,
	   		file = self.files[0],
	   		type = file.type;
		var reader=new FileReader();
		reader.onload=function(event){
			imgData=event.target.result;
			var box = MC.find('.img'),
			img = box.find('img');
			!img[0] && (img = $('<img />').appendTo( box ));
			img.attr('src', imgData);
		}
    	reader.readAsDataURL( file );
	});
})
</script>
<div class="ad_middle">
	<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l">
		<h2>{$optext}短信服务器配置</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">名称：</span>
					<input type="text" name="name" value="{$name}" required style="width:192px"/>
					<font class="important">必填</font>
				</div>
				<div class="form_ul_div">	
					<span class="title">描述：</span>
					{template:form/textarea,brief,$brief}
				</div>
				<div class="form_ul_div clear">
					<span class="title">LOGO：</span>
					<!--<span class="file_input s" style="float:left;">选择文件</span>
					<span style="float:right;">
						{if $logo}<img width="80" height="30" src="{$logo['host']}/{$logo['dir']}/{$logo['filepath']}/{$logo['filename']}" />{/if}
					</span>-->
					<div class="img-box">
						<div class="img">
							{if $logo}<img src="{$logo['host']}/{$logo['dir']}/{$logo['filepath']}/{$logo['filename']}" />{/if}
						</div>
						<input name="logo" type="file" value="" style="display: none;" />
						<div class="img-upload-btn">+</div>
					</div>
					
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">验证码：</span>
					<span>长度</span>
					<input type="text" name="verifycode_length" value="{if $verifycode_length}{$verifycode_length}{else}{$_configs['mobile_verifycode_length']}{/if}" style="width:60px;text-align: center;"/>
					<span>内容</span>
					<input type="text" name="verifycode_content" value="{if $verifycode_content}{$verifycode_content}{else}{$_configs['mobile_verifycode_content']}{/if}" style="width:315px"/>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">公司名：</span>
					<input type="text" name="company_name" value="{$company_name}" style="width:200px" />
				</div>
				<div class="form_ul_div">
					<span class="title">账号：</span>
					<input type="text" name="account" value="{$account}" />
				</div>
				<div class="form_ul_div">
					<span class="title">密码：</span>
					<input type="password" name="password" value="{$password}" />
				</div>
				<div class="form_ul_div">
					<span class="title">手机：</span>
					<input type="text" name="admin_mobile" value="{$admin_mobile}"/>
					<font class="important">管理员手机号</font>
				</div>
				<div class="form_ul_div">
					<span class="title">余额：</span>
					<input type="text" name="over" value="{$over}"/>
					<font class="important">余额数目</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">用户KEY：</span>
					<input type="text" name="accesskey" value="{$accesskey}"/>
					<font class="important">用户接入KEY</font>
				</div>
				<div class="form_ul_div">
					<span class="title">用户密钥：</span>
					<input type="text" name="secretkey" value="{$secretkey}" style="width:300px" />
					<font class="important">用户接入密钥</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">发送URL：</span>
					<input type="text" name="send_url" value="{$send_url}" style="width:440px" />
				</div>
				<div class="form_ul_div">
					<span class="title">发送内容：</span>
					{template:form/textarea,content,$content}
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">余额提醒：</span>
					<input type="text" name="over_remind" value="{$over_remind}"/>
					<font class="important">余额提醒数目</font>
				</div>
				<div class="form_ul_div">
					<span class="title">手机：</span>
					<input type="text" name="over_mobile" value="{$over_mobile}"/>
					<font class="important">接收余额手机号</font>
				</div>
				<div class="form_ul_div">
					<span class="title">余额URL：</span>
					<input type="text" name="over_url" value="{$over_url}" style="width:440px" />
				</div>
				<div class="form_ul_div">
					<span class="title">余额内容：</span>
					{template:form/textarea,over_content,$over_content}
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">绑定IP：</span>
					<input type="text" name="bind_ip" value="{$bind_ip}"/>
					<font class="important">注意：多个IP，请使用半角逗号 , 隔开；如果动态IP，请填写*号，即不绑定您的Ip</font>
				</div>
				<div class="form_ul_div">
					<span class="title">上行转发：</span>
					<input type="text" name="http_address" value="{$http_address}"/>
					<font class="important">地址必须以http://或https://开头</font>
				</div>
			</li>
		</ul>
		</br>
		<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14"/>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="id" value="{$formdata['id']}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}
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
		//hg_pre($formdata);
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
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">验证码：</span>
					<span>长度</span>
					<input type="text" name="verifycode_length" value="{if $length}{$length}{else}{$_configs['mobile_verifycode_length']}{/if}" style="width:60px;text-align: center;"/>
					<!-- 
					<span>内容</span>
					<input type="text" name="verifycode_content" value="{if $verifycode_content}{$verifycode_content}{else}{$_configs['mobile_verifycode_content']}{/if}" style="width:315px"/>
					 -->
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">发送URL：</span>
					<input type="text" name="send_url" value="{$send_url}" style="width:440px" />
				</div>
				<div class="form_ul_div">
					<span class="title">发送内容：</span>
					{code}
						//$content = send_content;
						//hg_pre($content);
					{/code}
					{template:form/textarea,content,$send_content}
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
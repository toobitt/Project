<?php 
/* $Id: member_platform_form.php 33760 2014-10-14 06:16:50Z youzhenghuan $ */
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
			var box = $(self).closest('.img-box').find('.img'),
			img = box.find('img');
			!img[0] && (img = $('<img />').appendTo( box ));
			img.attr('src', imgData);
		}
    	reader.readAsDataURL( file );
	});
})
</script>

<div class="ad_middle" style="min-height:740px;">
	<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l">
		<h2>{$optext}站外平台</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">名称：</span>
					<input type="text" name="name" value="{$name}" style="width:192px"/>
					<font class="important">必填</font>
				</div>
				<div class="form_ul_div">
					<span class="title">标识：</span>
					<input type="text" name="mark" value="{$mark}" />
					<font class="important">必填 唯一</font>
				</div>
				<div class="form_ul_div">	
					<span class="title">描述：</span>
					{template:form/textarea,brief,$brief}
				</div>
				<div class="form_ul_div clear">
					<span class="title">LOGO：</span>
					<!-- <span class="file_input s" style="float:left;">选择文件</span>
					<span style="float:right;">
						{if $logo_display}<img width="30" height="30" src="{$logo_display['host']}/{$logo_display['dir']}/{$logo_display['filepath']}/{$logo_display['filename']}" />{/if}
					</span>
					<input name="logo_display" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
					-->
					<div class="img-box">
						<div class="img">
							{if $logo_display}<img width="30" height="30" src="{$logo_display['host']}/{$logo_display['dir']}/{$logo_display['filepath']}/{$logo_display['filename']}" />{/if}
						</div>
						<input name="logo_display" type="file" value="" style="display: none;" />
						<div class="img-upload-btn">+</div>
					</div>
				
				</div>
				<div class="form_ul_div clear">
					<span class="title">登陆：</span>
					<!--  <span class="file_input s" style="float:left;">选择文件</span>
					<span style="float:right;">
						{if $logo_login}<img width="80" height="30" src="{$logo_login['host']}/{$logo_login['dir']}/{$logo_login['filepath']}/{$logo_login['filename']}" />{/if}
					</span>
					<input name="logo_login" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;" />
					-->
					<div class="img-box">
						<div class="img">
							{if $logo_login}<img width="80" height="30" src="{$logo_login['host']}/{$logo_login['dir']}/{$logo_login['filepath']}/{$logo_login['filename']}" />{/if}
						</div>
						<input name="logo_login" type="file" value="" style="display: none;" />
						<div class="img-upload-btn">+</div>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">官方账号：</span>
					<input type="text" name="official_account" value="{$official_account}" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">APIKEY：</span>
					<input type="text" name="apikey" value="{$apikey}" style="width:300px" />
					<font class="important">接入KEY</font>
				</div>
				<div class="form_ul_div">
					<span class="title">密钥：</span>
					<input type="text" name="secretkey" value="{$secretkey}" style="width:300px" />
					<font class="important">接入密钥</font>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">回调函数：</span>
					<input type="text" name="callback" value="{$callback}" style="width:440px" />
				</div>
			</li>
			{code}
			$limit_appids =array();
			$limit_appids = explode(',',$limit_appid);
			{/code}
			<li class="i">
					<div class="form_ul_div">
						<span class="title">限制低版本: </span>
						<input type="text" name="limit_version[min]" value="{$limit_version['min']}" style="width:100px;" />
					<font class="important">*留空则不限制最低版本</font>
					</div>
					<div class="form_ul_div">
						<span class="title">限制高版本: </span>
						<input type="text" name="limit_version[max]" value="{$limit_version['max']}" style="width:100px;" />
					<font class="important">*留空则不限制最高版本</font>
					</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<span class="title">限制appid：</span>
					<select multiple="multiple" name="limit_appid[]" size="10" style="border: 1px solid #dedede">
						<option value ="0" {if !$limit_appid} selected="selected"{/if} >无限制</option>
						{foreach $appinfo as $v}
						<option value ="{$v['appid']}" {if in_array($v['appid'],$limit_appids)}selected="selected"{/if} >{$v['custom_name']}</option>
						{code}$app_info[$v['appid']] = $v['custom_name'];{/code}
						{/foreach}
					</select>
				</div>
			</li>
		</ul>
		</br></br>
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
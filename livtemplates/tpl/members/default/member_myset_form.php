<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z ayou $ */
?>
{template:head}
{css:ad_style}
{css:bigcolorpicker}
{css:member_form}
{js:jqueryfn/jquery.tmpl.min}
{js:bigcolorpicker}
{js:area}

{if $a}
	{code}
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
{code}//print_r($updatetype);{/code}
<script>
$.globaldefault = {code} echo json_encode($formdata['field_default']);{/code}
</script>
<style>
.important{color:red;}
.img-box{display: -webkit-box;}
.img-box .img{position:relative;}
.img-box img{width:50px;height:50px;margin-right: 10px;}
.img-upload-btn{width:50px;height:50px;border:1px solid #ccc;text-align: center;font-size:30px;color:#ccc;cursor:pointer;}
.img-box .del-pic{display: block;width: 15px;height: 15px;text-align: center;line-height: 15px;background: #629ee7;color: #fff;border-radius: 50%;position: absolute;top: -7px;right: 4px;cursor:pointer;display:none;}
</style>
<script type="text/javascript">
$(function(){
	
	$('.img-box .img').hover(
			function(){
				$('.del-pic').show();
			},
			function(){
				$('.del-pic').hide();
			}
		);
    $(".del-pic").click(function(){
	   $(".icondel").val("1");
	   $(".img-box").find('img').remove();
	   $('.del-pic').hide();
	});
	
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
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" enctype='multipart/form-data' class="ad_form h_l">
<h2>{$optext}我的模块</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">模块名称: </span>
		<input type="text" name="title"  value="{$title}" style="width:130px;"/>
	</div>
		<div class="form_ul_div clear info">
		<span class="title">模块标识: </span>
		<input type="text" name="mark"  value="{$mark}" style="width:80px;" {if $mark}readonly="true"{/if}/>
	</div>
		<div class="form_ul_div clear pre-option">
		<span class="title">显示:</span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{code}
				$CmySetDisplay = array(
					'1'=>'是',
					'0'=>'否',
				);
			{/code}
			{if is_array($CmySetDisplay)}
			{foreach $CmySetDisplay as $k=>$v}
		         <li ><input type="radio" class="display" name="display" {if ($display==$k)} checked="checked"{/if}  value ="{$k}" _val="{code} echo $v;{/code}"><span>{$v}</span></li>
		    {/foreach}
		    {/if}
			</ul>
		</div>
		<span class="error" id="title_tips" style="display:block;">*是否可以输出至用户资料页</span>
	</div>
	<div class="form_ul_div clear pre-option">
		<span class="title">使用源:</span>
		<div style="width:335px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{code}if(!$_configs['mySetUseSource'])
			{
				$_configs['mySetUseSource'] = array(
					'0'=>'不限制',
					'1'=>'网页端专用',
					'2'=>'手机端专用',
				);
			}
			{/code}
			{if is_array($_configs['mySetUseSource'])}
			{foreach $_configs['mySetUseSource'] as $k=>$v}
		         <li ><input type="radio" class="usesource" name="usesource" {if ($usesource==$k)} checked="checked"{/if}  value ="{$k}" _val="{code} echo $v;{/code}"><span>{$v}</span></li>
		    {/foreach}
		    {/if}
			</ul>
		</div>
	</div>
	
		<div class="form_ul_div clear pre-option">
		<span class="title">数据唯一:</span>
		<div style="width:500px;margin-left:85px;display: -webkit-box;">
			<ul class="type-choose">
			{code}if(!$_configs['uniquetype'])
			{
				$_configs['uniquetype'] = array(
					'0'=>'不限制',
					'1'=>'限制',
				);
			}
			{/code}
			{if is_array($_configs['uniquetype'])}
			{foreach $_configs['uniquetype'] as $k=>$v}
		         <li ><input type="radio" class="uniquetype" name="uniquetype" {if ($uniquetype==$k)} checked="checked"{/if}  value ="{$k}" _val="{code} echo $v;{/code}"><span>{$v}</span></li>
		    {/foreach}
		    {/if}
			</ul>
		</div>
		<span class="error" id="title_tips" style="display:block;">*限制重复数据总开关</span>
	</div>
	
	<div class="form_ul_div clear info">
		<span class="title">模块链接: </span>
		<input type="text" name="url"  value="{$url}" style="width:255x;"/>
		<span class="error" id="title_tips" style="display:block;">*可填手机端内链模块标识如:myUser或者普通URL如:www.hoge.cn</span>
	</div>
</li>
<li class="i icon">
	<div class="form_ul_div clear">
		<span class="title" >模块图标：</span>
			<div class="img-box">
				<div class="img">
					{if $icon}
					{code}$img=hg_fetchimgurl($icon);{/code}
						<img id="img" src="{$img}">
					{/if}
					<span class="del-pic">x</span>
				</div>
				<input type="file" name="img"  style="display: none;" />
				<div class="img-upload-btn">+</div>
			</div>
			<span class="error" id="title_tips" style="display:block;">建议使用透明背景图，自行处理好图片大小</span>
			</div>
		</li>
		{if $icon}<input type="hidden" class="icondel" name="icondel" value>{/if}
</ul>

<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<div class="temp-edit-buttons">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}


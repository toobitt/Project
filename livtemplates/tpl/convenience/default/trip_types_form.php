<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>

{template:head}
{css:ad_style}
{js:area}
{js:common/ajax_upload}
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

	<div class="ad_middle">
	<form name="editform" action="" method="post" class="ad_form h_l" onsubmit="return hg_form_check();">
		<h2>{$optext}出行类型</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">中文名称：</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="zh" id="zh" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$zh}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
						<span class="title">英文标识：</span>
						<div class="input " style="width:210px; float:left;">
							<span class="input_left"></span>
							<span class="input_right"></span>
							<span class="input_middle">
								<input type="text" name="en" id="en" style="width:200px; height:18px; line-height:20px; font-size:12px; padding-left:5px; float:left; border:none;" value="{$en}" /></span>
						</div>
						<span class="error" id="title_tips" style="display:none;"></span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
				    <span class="title" >logo 图片：</span>
				    <div class="photo-area">
					    <div class="add-photo" style="font-size: 12px;margin: 5px 1px;cursor: pointer;display: inline-block;padding: 5px 20px;background: #5b5b5b;color: #fff;border-radius: 2px;">上传图片</div>
							<div class="photo-item" _id="{$id}" style="padding-left:50px;">
							{code}
							$picture=hg_fetchimgurl($logo,200,160);
				            {/code}
							<span><img src="{if $logo}{$picture}{/if}"/></span>
	         				<input type="hidden" name="logoid" value="{$logoid}" class="logoid"/>
         					</div>
					</div>
					    <input type="file" name="pic" accept="image/png,image/jpeg" class="image-file" style="display: none;">
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">
					<div class="col_choose clear">
				    <span class="title" >是否有快速查询：</span>
				    <div class="radio "  >
							<input type="radio" name="is_quick_search" {if $is_quick_search}checked{/if} value="1" />是
							   <input type="radio" name="is_quick_search" {if !$is_quick_search}checked{/if} value="0" />否
					</div>
				</div>
			</li>
			
			
		</ul>
	<input type="hidden" name="a" value="{$action}" />
	<input type="hidden" name="is_del" id="is_del" value="0" />
	<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</br>
	<input type="submit" name="sub" value="{$optext}" id="sub" class="button_6_14" />
	</form>
	</div>
	<div class="right_version" style="width:290px;">
		<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
	</div>
{template:foot}

<script>
$(function($){
	(function($){
		$.widget('logo.upload_img',{
			options : {
				'add-photo' : '.add-photo',
				'logoid' : '.logoid',
				'photo-item' : '.photo-item',
				'image-file' : '.image-file',
				'add-pic-tpl' : '#add-pic-tpl',
			},
	        _create : function(){
	        	this.uploadFile=this.element.find(this.options['image-file']);
	        },
			
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click ' + op['add-photo'] ] = '_addlogo';
				this._on(handlers);
				logoid=$("input[name='logoid']").val();
				var url = "./run.php?mid=" + gMid + "&a=upload_img&logoid="+logoid;
				
				this.uploadFile.ajaxUpload({
					url : url,
					phpkey : 'pic',
					after : function( json ){
						_this._uploadIndexAfter(json);
					}
				});
			},
			
			_uploadIndexAfter : function( json ){
				var op = this.options,
					data = json['data'];
				var info = {};
				info.imginfoid = data.id;
				info.img_info = data.img_info;
				var img = $( op['photo-item'] ).find('img');
				var src = info.img_info,
					id = info.imginfoid;
				img.attr('src',src);
				$( op['logoid'] ).val(id);
			},
			_addlogo : function(){
				var op = this.options;
				$( op['image-file'] ).click();
			}
	});
})($);
$('.col_choose').upload_img();
});
</script>
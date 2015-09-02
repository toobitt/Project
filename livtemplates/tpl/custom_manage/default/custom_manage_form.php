<?php 
/* $Id: stream_form.php 2361 2011-10-28 09:56:50Z lijiaying $ */
?>

{template:head}
{css:ad_style}
{js:jqueryfn/jquery.tmpl.min}
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
{code}//hg_pre($get_sort);{/code}
<script>
jQuery(function($){
	//new PCAS("province", "city", "area");
})
</script>
<script>
$.globaldefault = {code} echo json_encode($formdata['catalog_default']);{/code}
</script>
<style>
.form_ul .info{line-height:35px;}
.form_ul .info input{text-indent: 5px;width: 474px;height: 25px!important;text-align: left;font-family: 'Arial';font-size: 13px;color: #000000;font-weight: normal;font-style: normal;text-decoration: none;}
.form_ul .sort{line-height:25px;}
.form_ul textarea{width:474px;text-indent: 5px;}
.type-choose{margin-left: -20px;margin-top: -7px;}
.type-choose li{float:left;margin: 10px 0px 0px 20px;}
.type-choose li span{margin-left: 10px;}
.default-option{width:484px;margin-left: 82px;margin-bottom: 12px;display:none;}
.default-option p{cursor: pointer;width: 20px;height: 20px;
background: #7bb0e6;display: inline-block;border-radius: 10px;text-align: center;color: #fff;margin: 6px 0 0 15px;font-size: 20px;line-height: 17px;}
.option-contain input{text-indent: 5px;margin:4px 0 4px 3px;width:109px;font-size:14px;}
</style>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<form name="editform" action="" method="post" class="ad_form h_l">
<h2>{$optext}客户信息</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear info">
		<span class="title">客户名称: </span>
		<input type="text" name="authinfo[custom_name]"  value="{$custom_name}" />
	</div>
	<div class="form_ul_div clear info">
		<span class="title">客户标识: </span>
		<input type="text" name="authinfo[bundle_id]"  {if $field} readonly="readonly" {/if} value="{$bundle_id}" />
	</div>
		<div class="form_ul_div clear info">
		<span class="title">项目域名: </span>
		<input type="text" name="authinfo[domain]"  {if $field} readonly="readonly" {/if} value="{$domain}" />
	</div>
	<div class="form_ul_div clear info">
		<span class="title">客户描述: </span><textarea name="authinfo[custom_desc]" id="remark"  cols="45" rows="4" />{$custom_desc}</textarea>
	</div>
		<div class="form_ul_div clear info">
		<span class="title">客户手机: </span>
		<input type="text" name="custominfo[mobile]"  {if $field} readonly="readonly" {/if} value="{$mobile}" />
	</div>
		<div class="form_ul_div clear info">
		<span class="title">客户邮箱: </span>
		<input type="text" name="custominfo[email]"  {if $field} readonly="readonly" {/if} value="{$email}" />
	</div>
</li>

			<li class="i">
				<div class="form_ul_div col_choose clear">
						<span class="title" style="width: 80px;">源码类型：</span>
							<input type="radio" name="authinfo[source]" id="source" {if  $source } checked="checked"{/if} value="1" /><span>加密</span>
							<input type="radio" name="authinfo[source]" id="source" {if  $source==0 } checked="checked"{/if} value="0" /><span>未加密</span>
						<span class="error" id="title_tips" style="display:none;"></span>
				</div>
			</li>
	<li class="i">		
							<div class="form_ul_div col_choose clear">
						<span class="title" style="width: 80px;">安装类型：</span>
							<input type="radio" name="authinfo[install_type]" id="required" {if  $install_type } checked="checked"{/if} value="1" /><span>发布</span>
							<input type="radio" name="authinfo[install_type]" id="required" {if  $install_type==0 } checked="checked"{/if} value="0" /><span>预发布</span>
						<span class="error" id="title_tips" style="display:none;"></span>
				</div>
			</li>
						<li class="i">
				<div class="form_ul_div col_choose clear">
						<span class="title" style="width: 80px;">是否过期：</span>
							<input type="radio" name="authinfo[expire_time]" id="expire_time" {if  $expire_time } checked="checked"{/if} value="1" /><span>是</span>
							<input type="radio" name="authinfo[expire_time]" id="expire_time" {if  $expire_time==0 } checked="checked"{/if} value="0" /><span>否</span>
						<span class="error" id="title_tips" style="display:none;"></span>
				</div>
			</li>
</ul>

<input type="hidden" name="a" value="{$action}" />
<input type="hidden" name="is_del" id="is_del" value="0" />
<input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<div class="temp-edit-buttons" style="height: 50px;">
<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
<input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
</div>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}

<script type="text/javascript">
$(function(){
	(function($){
		$.widget('lbs.lbs_set',{
			options : {
				hasTmpl: '',
				noTmpl : '',
			},
			
			_create : function(){
				this.val =  this.element.find('.data-type').filter(function(){
					return $(this).prop('checked');
				}).closest('li').find('span').text();
			},
			
			_init : function(){
				this._on({
					'click .data-type' : '_type',
					'click .add-option' : '_addoption',
				});
				this._submit();
			},
			
			_type : function(event){
				var self = $(event.currentTarget),
					txt = $.trim(self.attr('_val')),
					input = this.element.find('.default-option input'),
					info={},
					_this = this,
					op = this.options;
				info.txt = txt;
				if(input && txt== _this.val){
					input.remove();
					$.each($.globaldefault,function(k , v){
						info.value = v;
						$(op.hasTmpl).tmpl(info).appendTo('.option-contain');
					})
				}else{
					input.remove();
					$(op.hasTmpl).tmpl(info).appendTo('.option-contain');
					this.element.find('.default-option').css('display','-webkit-box');
				}
			},
			
			_addoption : function(event){
				var self = $(event.currentTarget),
					obj =self.closest('.default-option').find('.option-contain'),
					op = this.options;
				obj.append($(op.noTmpl).html());
			},
			
			_submit : function(){
				var	sform = this.element,
					_this = this;
				sform.submit(function(){
					var val = $.trim(sform.find('input[name="authinfo[custom_name]"]').val()),
						txt = $.trim(sform.find('input[name="authinfo[bundle_id]"]').val()),
						num = /^[0-9]*$/;
					if(!val){
						alert("请填写客户名称");
						return false;
					}
					if(txt && num.test(txt)){
						alert("标识不能全部是数字");
						return false;
					}
				});
			},
		});
	})($);
	$('.ad_form').lbs_set({
		hasTmpl : $('#option-tpl'),
		noTmpl : $('#add-option-tpl')
	});
});

</script>





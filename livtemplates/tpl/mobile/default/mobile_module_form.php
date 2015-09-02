<?php 
/* $Id: mobile_module_form.php 12312 2012-09-22 09:26:38Z lijiaying $ */
?>

{template:head}
{css:ad_style}
{css:app_style}
{css:2013/list}
{js:hg_preview}
{js:mobile_module}
{js:jquery.multiselect.min}
{js:common/common_form}
{js:common}
{css:2013/form}
{css:common/common}
{css:mobile_form}

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
{code}
//echo '<pre>';
//print_r($event);
//echo '</pre>';
{/code}
<style>
.m2o-main .m2o-m{padding-left:40px;}
.pics{margin-top:10px;}
.pic-preview{border-radius:8px;overflow:hidden;width:40px;height:40px;border:1px solid #ccc;margin-right:10px;cursor:pointer;background:url({$RESOURCE_URL}news/suoyin-default.png) center no-repeat;background-size:60%;}
.pic-preview .divInput{display:none;}
.each-app .title{float:none!important;}
.title-name{width:30px!important;}
.ad_form .form_ul .form_ul_div span.title{width:90px;text-align:left;}
.each-app{padding-bottom:10px;border-bottom:1px dashed #ccc;}
.each-app:last-child{border:none;}
.each-app .flag{margin-right:10px;}
.each-app .version{padding:10px 0;}
</style> 
<script type="text/javascript">
	function open_version(obj,app_id)
	{
		if($(obj).attr('checked'))
		{
			 $('#input_version_'+app_id).attr('disabled',false);
			 $('#version_max_'+app_id).attr('disabled',false);
			 
			 $('#version_'+app_id).slideDown(600);
		}
		else
		{
			$('#input_version_'+app_id).attr('disabled','disabled');
			$('#version_max_'+app_id).attr('disabled','disabled');
			$('#version_'+app_id).slideUp(600);
		}
	}
	function hg_addArgumentDom()
	{
		var div = "<div class='form_ul_div clear'><span class='title title-name'>版本: </span><input type='text' name='version_url[]' style='width:90px;' class='title'>&nbsp;&nbsp;<span class='more-index'>URL: </span><input type='text' name='url_ver[]' size='30'/>&nbsp;&nbsp;<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#extend').append(div);
		hg_resize_nodeFrame();
	}
	function hg_optionTitleDel(obj)
	{
		if($(obj).data("save"))
		{
			if(confirm('确定删除该参数配置吗？'))
			{
				$(obj).closest(".form_ul_div").remove();
			}
		}
		else
		{
			$(obj).closest(".form_ul_div").remove();
		}
		hg_resize_nodeFrame();
	}

	function hg_addEvent()
	{
		var div = "<div class='form_ul_div clear'><span class='title title-name'>标识: </span><input type='text' name='event_bs[]' style='width:90px;' class='title'>&nbsp;&nbsp;<span class='more-index'>跳转链接: </span><input type='text' name='outlink[]' size='30'/>&nbsp;&nbsp;<span class='more-index'>跳转提示: </span><input type='text' name='tip[]' size='30'/>&nbsp;&nbsp;<span class='more-index'><span class='option_del_box_event'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span></div>";
		$('#event_extend').append(div);
		hg_resize_nodeFrame();
	}
</script>

	<form name="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l">
		<header class="m2o-header">
	      <div class="m2o-inner">
	        <div class="m2o-title m2o-flex m2o-flex-center">
	            <h1 class="m2o-l">{$optext}手机模块</h1>
	            <div class="m2o-l m2o-flex-one">
	                <input placeholder="填写模块名称" name="name" value="{$name}"  class="m2o-m-title"  />
	            </div>
	            <div class="m2o-btn m2o-r">
	                <input type="submit" value="保存" class="m2o-save" name="sub" id="sub" />
	                <span class="m2o-close option-iframe-back"></span>
	            </div>
	        </div>
	      </div>
	    </header>
	     <div class="m2o-inner">
			<div class="m2o-main m2o-flex">
				 <section class="m2o-m m2o-flex-one">
		 			<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="title title-name">标识:</span>
								<input class="divInput" type="text" name="module_id" value="{$module_id}" />
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title title-name">类型:</span>				
								{code}
									$attr_type = array(
										'class' => 'down_list i',
										'show' => 'item_shows_',
										'width' => 100,/*列表宽度*/		
										'state' => 0, /*0--正常数据选择列表，1--日期选择*/
										'is_sub'=>1,
									);
									
									$default = $type ? $type : -1;
									
									$module_type = $_configs['module_type'];
									$module_type[-1] = '请选择';
								{/code}
								{template:form/search_source,type,$default,$module_type,$attr_type}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title title-name">分类:</span>				
								{code}
									$attr_sort = array(
										'class' => 'down_list i',
										'show' => 'show_sort',
										'width' => 100,/*列表宽度*/		
										'state' => 0, /*0--正常数据选择列表，1--日期选择*/
										'is_sub'=>1,
									);
									
									$default = $sort_id ? $sort_id : -1;
									
									$module_sort = $appendSort[0];
									$module_sort[-1] = '请选择';
								{/code}
								{template:form/search_source,sort_id,$default,$module_sort,$attr_sort}
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title title-name">简介: </span><textarea name="brief" cols="60" rows="5">{$brief}</textarea>
							</div>
						</li>
							<li class="i">
							<div class="form_ul_div">
								<div class="m2o-flex pics">
									<span class="title title-name" style="float:none;">图片:</span>
									<div class="pic-preview"><img width=40 height=40 src="{$icon1}" /><input class="divInput" type="file" name="file[1]" style="width: 8%;"/></div>
									<div class="pic-preview"><img width=40 height=40 src="{$icon2}" /><input class="divInput" type="file" name="file[2]" style="width: 8%;"/></div>
									<div class="pic-preview"><img width=40 height=40 src="{$icon3}" /><input class="divInput" type="file" name="file[3]" style="width: 8%;"/></div>
									<div class="pic-preview"><img width=40 height=40 src="{$icon4}" /><input class="divInput" type="file" name="file[4]" style="width: 8%;"/></div>
								</div>
								<!-- <span style="float:right;margin-right:30%;border:0px solid #DADADA;">{if $img_url}<img width=50 height=40 src="{$img_url}" />{/if}</span> -->
							</div>
						</li>
						<li class="i">
							{if($appendApp[0])}
								{foreach $appendApp[0] as $k=>$v}
								<div class='form_ul_div m2o-flex each-app'>
									<span class='title title-name'>应用: </span>
									<div class="m2o-flex-one">
									<div class="m2o-flex">
									<span class='title'>{$v} </span>
									
									{code}
									$version 	 = '';
									$version_max = '';
									if($confine)
									{
										$icon = '';
										$app_id = '';
										foreach ($confine as $kk=>$vv)
										{
											if($k == $kk)
											{
												$app_id = $kk;
												$version = $vv['version'];
												$version_max = $vv['version_max'];
												$app_icon1 = $vv['app_icon1'];
												$app_icon2 = $vv['app_icon2'];
												$app_icon3 = $vv['app_icon3'];
												$app_icon4 = $vv['app_icon4'];
											}
										}
									}
									{/code}
									<input type='checkbox' name='app_id[]' {if $app_id == $k }checked="true"{/if}value='{$k}' onclick="open_version(this,{$k});">&nbsp;&nbsp;&nbsp;
									</div>
									<div {if !$app_id }style="display:none"{/if} id="version_{$k}">
									<div class="version">
									<span>最低版本: </span>
									<input type='text' id="input_version_{$k}" name='version[]' {if $a == 'update' && !$app_id}disabled="disabled"{/if} value='{$version}'  size='5'/>&nbsp;&nbsp;
									<span>最高版本: </span>
									<input type='text' id="version_max_{$k}" name='version_max[]' {if $a == 'update' && !$app_id}disabled="disabled"{/if} value='{$version_max}'  size='5'/>&nbsp;&nbsp;
									</div>
									<div class="m2o-flex pics">
										<span class="flag">图片: </span>
										<div class="pic-preview">{if $app_id == $k }<img width=40 height=40 src="{$app_icon1}" />{/if}<input class="divInput" type="file" name="app_file_{$k}[1]" style="width: 8%;"/></div>
										<div class="pic-preview">{if $app_id == $k }<img width=40 height=40 src="{$app_icon2}" />{/if}<input class="divInput" type="file" name="app_file_{$k}[2]" style="width: 8%;"/></div>
										<div class="pic-preview">{if $app_id == $k }<img width=40 height=40 src="{$app_icon3}" />{/if}<input class="divInput" type="file" name="app_file_{$k}[3]" style="width: 8%;"/></div>
										<div class="pic-preview">{if $app_id == $k }<img width=40 height=40 src="{$app_icon4}" />{/if}<input class="divInput" type="file" name="app_file_{$k}[4]" style="width: 8%;"/></div>
									</div>
									<!-- <span style="float:right;margin-right:5%;">{if $icon}<img width=40 height=27 src="{$icon}" />{/if}</span> -->
									</div>
									</div>
								</div>
								{/foreach}
							{/if}
						</li>
						<li id="url" class="i">
							<div class="form_ul_div">
								<span class="title title-name">URL:</span>
								<input style="width: 258px" type="text" name="main_url" value="{$url}" />
							</div>
							{if($version_url)}
								{foreach $version_url as $k=>$v}
								<div class='form_ul_div clear'>
									<span class='title title-name'>版本: </span>
									<input type='text' name='version_url[]' value='{$k}' style='width:90px;' class='title'>&nbsp;
									
									<span class="more-index">URL: </span>
									<input type='text' name='url_ver[]' value='{$v}' size='30'/>&nbsp;
									
									<span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>
								</div>
								{/foreach}
							{/if}
							<div id="extend"></div>
							<div class="form_ul_div clear">
								<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 45px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addArgumentDom();">添加URL</span>
							</div>
						</li>
						
						<li id="event" class="i">
							{if($event)}
								{foreach $event as $k=>$v}
								<div class='form_ul_div clear'>
									<span class='title title-name'>标识: </span>
									<input type='text' name='event_bs[]' value='{$k}' style='width:90px;' class='title'>&nbsp;
									
									<span class="more-index">跳转链接: </span>
									<input type='text' name='outlink[]' value='{$v["outlink"]}' size='30'/>&nbsp;
									
									<span class="more-index">跳转提示: </span>
									<input type='text' name='tip[]' value='{$v["tip"]}' size='30'/>&nbsp;
									
									<span class='option_del_box_event'><span name='option_del[]' class='option_del' title='删除' data-save="1" onclick='hg_optionTitleDel(this);' style='display: inline; '></span></span>
								</div>
								{/foreach}
							{/if}
							<div id="event_extend"></div>
							<div class="form_ul_div clear">
								<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 45px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addEvent();">添加事件</span>
							</div>
						</li>
			
				</ul>
				 </section>
			</div>
		</div>
		<input type="hidden" name="a" value="{$action}" id="action" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>

{template:foot}
<script>
$(function(){
	(function($){
		$.widget('mobile.form',{
			_init : function(){
				this._on({
					'click .pic-preview' : '_showFile',
					'click .divInput' : '_stop',
					'change .divInput' : '_preview'
				});
			},
			_showFile : function(event){
				var self = $(event.currentTarget);
				self.find('input').click();
			},
			_preview : function(event){
				var self = $(event.currentTarget),
					parent = self.closest('div'),
					img = parent.find('img'),
					file= event.currentTarget.files[0];
				console.log(file);
				parent.hg_preview({
					box : parent,
					file : file,
					width: 40,
					height: 40,
				});
				parent.find('img').css({
					'width' : '40px',
					'height' : '40px'
				});
			},
			_stop : function(event){
				event.stopPropagation();
			},
		});
	})($);
	$('form').form();
});

</script>
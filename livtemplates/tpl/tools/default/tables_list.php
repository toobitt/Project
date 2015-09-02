<?php 
/* $Id: program_record_list.php 1344 2011-10-13 01:26:04Z lijiaying $ */
?>
{template:head}
{css:tab_btn}
{js:2013/ajaxload_new}
{js:mms_default}
{js:common}
{css:common/common}
{template:list/common_list}
<script type="text/javascript">
	function subform()
	{
		if($('#upload_file').val() == '')
		{
			return;
		}
		hg_ajax_submit('upload_form');
		$("#upload_ing").slideDown();
		//console.log(123);
	}
	function hg_call_import(data)
	{
		var data = eval('('+data+')');
		
		$("#upload_ing").hide();
		if(data == 'success')
		{				
			$("#upload_tips").slideDown().slideUp(500,function(){$(".show-upload").slideUp(500);});
		}
		else
		{
			$("#upload_errors").slideDown().slideUp(500,function(){$(".show-upload").slideUp(500);});
		}
		console.log(data);
	}
	
	function hg_call_table_del(data)
	{
		var data = eval('('+data+')');
		var ids = data.id.split(",");
		for(i=0;i<ids.length;i++)
		{
			$("#r_"+ids[i]).slideUp(1000).remove();
		}
		if($("#checkall").attr('checked'))
		{
			$("#checkall").removeAttr('checked');
		}
		hg_close_opration_info();
	}

</script>
<script>
$(function($){
    {js:domcached/jquery.json-2.2.min}
    {js:domcached/domcached-0.1-jquery}
    {js:common/common_list}
    $.commonListCache('site-list');
});
</script>
<style>
/*
.text_p{font-size:14px;}
.paixu {width: 30px;}
.caozhuo{width: 50px;color:black;}
.qz-time{width:150px;}
.jm-name,.zhuangtai {width: 100px;}
.zhouqi{width:180px;}
.zhouqi span{max-width:170px;display:block;}
.caozhuo-box{position:relative;background:url("{$RESOURCE_URL}common/common-list-info.png") no-repeat;}
.caozhuo-box div{position:absolute;display:none;width:150px;top:-5px;left:0px;}
.caozhuo-box:hover div{display:block;}
.common-list .common-list-head{padding:0 20px;}
*/
.common-publish-button{color:#9f9f9f;display:inline-block;max-width:200px;}
input[type="radio"]{display:none;}
.common-switch{left:2px;display:inline-block;top:0;vertical-align:middle;}
.import-data{ font-size: 12px;}
.show-upload{border: 5px solid #ccc; width: 410px; position: absolute;z-index: 9999999;background: white; left: 30%;top: 80px;border-radius: 2px;}
</style>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增表</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="common-list-content">
	<div class="common-list-search" id="info_list_search">
	    <span class="serach-btn"></span>
		<form name="searchform" id="searchform" action="" method="get">
			<div class="select-search">
		{code}
			$attr_date = array(
				'class' => 'colonm down_list data_time',
				'show' => 'colonm_show',
				'width' => 104,/*列表宽度*/
				'state' => 1,/*0--正常数据选择列表，1--日期选择*/
			);
			$_INPUT['date_search'] = $_INPUT['date_search']?$_INPUT['date_search']:1;
		{/code}
		{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
		<input type="hidden" name="a" value="show" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search" style="padding: 0; border: 0; margin: 0; background: none; cursor: pointer; width: 22px;" />
			</div>
			{template:form/search_input,key,$_INPUT['key']}
		</div>
		</div>
			
		</form>
	</div>
		<form method="post" action="" name="listform" class="common-list-form">
			<ul class="common-list">
				<li class="common-list-head public-list-head clear">
					<div class="common-list-left ">
						<div class="common-list-item paixu">
						    <a class="fz0">排序</a>
						</div>
					</div>
					<div class="common-list-biaoti">
						<div class="common-list-item wd90">名称</div>
						<div class="common-list-item address wd100">表名</div>
						<div class="common-list-item legal wd90">操作</div>
				    </div>
				</li>
			</ul>
			
			<ul class="common-list site-list public-list" id="sitelist">
				{if $list}
				{foreach $list as $k => $v}
				<li class="common-list-data clear" id="r_{$v['id']}">
					<div class="common-list-left">
						<div class="common-list-item paixu">
								<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v[$primary_key]}"/>
						</div>
					</div>
					<div class="common-list-biaoti">
					   <div class="common-list-item biaoti-content biaoti-transition wd90">
								<span class="common-list-overflow max-wd100">{$v['name']}</span>
					  </div>
					  <div class="common-list-item jm-name wd100">
								<span class="common-list-overflow  max-wd100 m2o-common-title" title="{$v['title']}">
								{$v['table_name']}
								</span>
						</div>	
						
						<div class="common-list-item legal wd90">
							<span class="import-data" _id="{$v['id']}" _key="{$v['table_name']}" style="cursor:pointer;">导入数据</span>
							<a href="./run.php?mid={$relate_module_id}&_colid={$v['id']}&table_name={$v['table_name']}&a=show&infrm=1">进入列表</a>
						</div>
					</div>
					<div class="common-list-i"  onclick="hg_show_opration_info({$v['id']});"></div>
					</li>
				{/foreach}	
				{else}
					<li>
						<p style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有内容！</p>
						<script>hg_error_html('#sitelist',1);</script>
					</li>						
				{/if}
			</ul>
			<ul class="common-list  public-list">
				<li class="common-list-bottom clear">
					<div class="common-list-left">	
						<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
						<!--<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" name="batdelete">删除</a>-->
					</div>
					{$pagelink}
				</li>
			</ul>
			<div class="edit_show">
				<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
				<div id="edit_show"></div>
			</div>
		</form>	
</div>
<div id="show_upload" class="show-upload" style="display:none;">
	<span id="upload_tips" style="display:none;color: green;position: absolute;">上传成功！</span><span id="upload_errors" style="display:none;color: red;position: absolute;">上传失败！</span><span id="upload_ing" style="display:none;color: grey;position: absolute;">上传中...</span><span id="btnclose" class="btnclose" style="float:right;margin-right:5px;cursor:pointer;">关闭</span>
	<form enctype="multipart/form-data" method="post" action="run.php?mid={$_INPUT['mid']}" id="upload_form" target="form_pos" name="upload_form" style="clear:both;">
		<span>上传xls</span>
		<input type="file" name="import" id="upload_file"/>
		<input type="button" value="确定" name="sub" onclick="subform()" class="button_2"/>
		<input type="hidden" name="a" value="import_data" />
		<input type="hidden" class="table-name" name="table_name" value="import_data" />
		<div class="m2o-item">
		<span>是否清空</span>
		{code}$is_empty=1; {/code}			
        <div class="common-switch {if !$is_empty}common-switch-on{/if}">
	        <div class="switch-item switch-left" data-number="0"></div>
	        <div class="switch-slide"></div>
	        <div class="switch-item switch-right" data-number="100"></div>
	    </div>
		<input type="radio" name="is_empty" value="1" {if !$is_empty}checked{/if}/>
		<input type="radio" name="is_empty" value="0" {if $is_empty}checked{/if}/>
			
		</div>
	</form>
	<div style="margin-top:10px;line-height:22px;padding-left:8px;">
		<span style="display: inline-block;margin-left: 64px;">(支持xls格式)
			<!-- <a href="###" style="text-decoration: underline;">下载xls模板</a>--><!-- ./download.php?a=download_xls -->
		</span>
	</div>
</div>
<script>
jQuery(function($){
	var old = $('#checkall');
	var clone = old.clone();
	old.after(clone).remove();
	clone.click(function(){
		var state = !!$(this).prop('checked');
		$('#sitelist input:checkbox').each(function(){
			if(!$(this).prop('disabled')){
				$(this).prop('checked', state);
			}	
		});	
	});
	$('.import-data').click(function(){
		$('.table-name').val($(this).attr('_key'));
		$('.show-upload').show();
	});
	$('.btnclose').click(function(){
		$('.show-upload').hide();
		$('.table-name').val('');
	});
	function setStatus(obj,status){
		obj.find('input').attr('checked',false);
		if(status){
			obj.find('input:first').attr('checked',true);
		}else{
			obj.find('input:last').attr('checked',true);
		}
	}
	(function($){
		$('.common-switch').each(function(){
			var val = 0,
			    status = false;
			$(this).hasClass('common-switch-on') ? val = 100 : val = 0;
			var obj = $(this).closest('.m2o-item');
			$(this).hg_switch({
				'value' : val,
				'callback':function(event,val){
					val >= 50 ? status = true : status = false;
					setStatus(obj,status);
				}
			});
		});
	})($);
});
</script>
{template:unit/record_edit}
{template:foot}
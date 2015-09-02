<?php 
/* $Id: program_record_list.php 1344 2011-10-13 01:26:04Z lijiaying $ */
?>
{template:head}
{css:tab_btn}
{code}
$list = $list[0];
$field = $list['field'];
$field_mark = $list['field_mark'];
unset($list['field'],$list['field_mark']);
//hg_pre($field_mark);exit;
{/code}
{template:list/common_list}
<script type="text/javascript">
function hg_data_manager_del(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&table_name=' + $('.hidden-table').val() +'&infrm=1&ajax=1';
		hg_request_to(url);
	}
}

function hg_call_data_del(data)
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
function hg_call_update_data(data)
{
	var data = eval('('+data+')');
	//console.log(data);
	if(data.key_value.replace(/(^\s*)/g, "") == $('.edit-content').val().replace(/(^\s*)/g, ""))
	{
		$('#'+data.primary+'_'+data.key).html(data.key_value);
		$('.bg').click();
	}
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
.caozhuo-box:hover div{display:block;}*/
.common-list .common-list-head{padding:0 20px;}

.bg{width: 2000px; height: 1000px; background-color: rgb(241, 242, 244); position: absolute; z-index: 99; display: none; filter: alpha(opacity=50); -moz-opacity: 0.5;opacity: 0.5;}
.show-upload{border: 5px solid #ccc; width: 410px; position: absolute;z-index: 9999999;background: white; left: 30%;top: 80px;border-radius: 2px;}
.pop{width: 300px;
  height: 200px;
  border: 2px solid rgb(226, 226, 226);
  position: absolute;
  top: 15%;
  left: 35%;
  border-radius: 2px;display:none;z-index: 111;}
.edit-content{width: 270px;
  height: 110px;
  border-radius: 2px;}
.content-out{margin: 8px;}
.button-sub{  cursor: pointer;
  width: 64px;
  height: 36px;
  font-size: 15px;
  border-radius: 3px;
  margin-left: 10px;}
.button-out{  width: 350px;
  height: 150px;
  border: 1px solid;}
 .del-data{}
</style>
<div class='bg'></div>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&id=-1&table_name={$_INPUT['table_name']}&_colid={$_INPUT['_colid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增记录</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="common-list-content"  style="overflow: auto;">
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
					<div class="common-list-left " style="height: 37px;">
						<div class="common-list-item paixu" style="float: left;">
						    <a class="fz0">排序</a>
						</div>
						<div class="common-list-biaoti">
							{foreach $field as $k => $v}
							{if $v == 'id'}
							<div class="common-list-item address wd50">{code} echo $field_mark[$v] ? $field_mark[$v] : $v;{/code}</div>
							{else}
							<div class="common-list-item address wd100">{code} echo $field_mark[$v] ? $field_mark[$v] : $v;{/code}</div>
							{/if}
							{/foreach}
							<div class="common-list-item address wd100">操作</div>
					    </div>
					</div>
				</li>
			</ul>
						
			<ul class="common-list site-list public-list" id="sitelist">
				{if $list}
				{foreach $list as $k => $v}
				<li class="common-list-data clear" id="r_{$v[$primary_key]}">
					<div class="common-list-left">
						<div class="common-list-item paixu">
								<input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"/>
						</div>
					</div>
					<div class="common-list-biaoti">
					   <div class="common-list-item biaoti-content biaoti-transition">
					   		{foreach $field as $kk => $vv}
						   		{if $vv == $primary_key}
								<span style="float:left;" class="common-list-overflow wd50 primary">{$v[$vv]}</span>
								{else}							
								<span style="float:left;" id="{$v[$primary_key]}_{$vv}" class="common-list-overflow wd100 edit_dbclick" _id="{$v[$primary_key]}" _key="{$vv}" onselectstart="return false">{$v[$vv]}</span>
								{/if}
							{/foreach}							
							<span style="float:left;" class="del-data" onclick="hg_data_manager_del({$v[$primary_key]})">删除</span>
							<a style="margin-left:10px;font-size:14px;" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v[$primary_key]}&table_name={$_INPUT['table_name']}&infrm=1">编辑</a>
							
					  </div>
					</div>
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
						<!--<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
						<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" name="batdelete">删除</a>-->
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
	<span id="upload_tips" style="display:none;color: green;position: absolute;">上传成功！</span><span id="upload_errors" style="display:none;color: red;position: absolute;">上传失败！</span><span id="btnclose" class="btnclose" style="float:right;margin-right:5px;cursor:pointer;">关闭</span>
	<form enctype="multipart/form-data" method="post" action="run.php?mid={$_INPUT['mid']}" id="upload_form" target="form_pos" name="upload_form" style="clear:both;">
		<span>上传xls</span>
		<input type="file" name="program" id="upload_file"/>
		<input type="button" value="确定" name="sub" onclick="subform()" class="button_2"/>
		<input type="hidden" name="a" value="import_data" />
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
	
	$('.edit_dbclick').dblclick(function(){
		var id = $(this).attr('_id');
		var key = $(this).attr('_key');
		$('.edit-content').val($(this).html());
		$('.hidden-id').val(id);
		$('.hidden-key').val(key);
		$('.bg').show();
		$('.pop').show();
	});
	$('.bg').click(function(){		
		$('.bg').hide();
		$('.pop').hide();
		$('.edit-content').val('');
		$('.hidden-id').val('');
		$('.hidden-key').val('');
	});
	$('.button-sub').click(function(){
		if(confirm('确定更新记录？！'))
		{
			var url = './run.php?mid=' + gMid + '&a=update_field&id=' + $('.hidden-id').val() + '&key=' + $('.hidden-key').val()+ '&key_value=' + $('.edit-content').val() + '&table_name=' + $('.hidden-table').val() + '&infrm=1&ajax=1';
			hg_request_to(url);
		}
		else
		{
			$('.bg').click();
		}
	});
});
</script>
<div class="pop">
	<div class="content-out"><textarea class="edit-content"></textarea></div>
	<input type="submit" name="sub" value="确认" class="button-sub"/>
	<input type="hidden" class="hidden-id" value=""/>
	<input type="hidden" class="hidden-key" value=""/>
	<input type="hidden" class="hidden-table" value="{$_INPUT['table_name']}"/>
</div>
{template:foot}
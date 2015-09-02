<?php 
/* $Id: program_record_list.php 1344 2011-10-13 01:26:04Z lijiaying $ */
?>
{template:head}
{css:tab_btn}
{template:list/common_list}
<script type="text/javascript">
function hg_data_del(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
}
function hg_create_file(id)
{
	var url = './run.php?mid=' + gMid + '&a=create_file&id=' + id + '&infrm=1&ajax=1';
	hg_request_to(url);
}
function hg_call_data_del(data)
{
	data = data.replace(/'/g, "");
	var ids = data.split(",");
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
function hg_call_create_file(data)
{
	if(data == 'true')
	{
		$(".create-file").html('success').slideDown(1000).slideUp(800);
	}
	else
	{
		$(".create-file").html('failed').slideDown(1000).slideUp(800);
	}
}
function hg_disable_action(str)
{
	jAlert(str);
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
.create-file{  border: 5px solid rgb(215, 213, 213); border-radius: 3px; position: absolute; width: 200px; height: 70px; top: 20%; left: 35%; z-index: 998; background-color: rgb(253, 252, 252); font-size: 36px; text-align: center;}
</style>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增api</em></span>
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
					<div class="common-list-right">
						<div class="common-list-item legal wd90">操作</div>
					</div>
					<div class="common-list-biaoti">
						<div class="common-list-item wd90">文件名</div>
						<div class="common-list-item address wd100">描述</div>
						<div class="common-list-item wd150">访问</div>
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
					<div class="common-list-right">
						<div class="common-list-item legal wd90">
							<span style="cursor:pointer;" onclick="hg_create_file({$v['id']});">生成文件</span>
						</div>				
					</div>
					<div class="common-list-biaoti">
					   <div class="common-list-item biaoti-content biaoti-transition wd90">
								<span class="common-list-overflow max-wd100">{$v['filename']}.php</span>
					  </div>
					  <div class="common-list-item jm-name wd100">
								<span class="common-list-overflow  max-wd100 m2o-common-title" title="{$v['brief']}">
								{code} echo $v['brief'] ? $v['brief'] : '暂无';{/code}
								</span>
						</div>
					   	<div class="common-list-item wd150">
							<span>{$v['link']}</span>
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
});
</script>
<div id="record-edit">
	<div class="record-edit">
		<div class="record-edit-btn-area clear">
			<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1">编辑</a>
			<a href="javascript:void(0);" onclick="hg_data_del(${id});">删除</a>
		</div>
		<div class="record-edit-line mt20"></div>
		<div class="record-edit-info">
		</div>
		<span class="record-edit-close"></span>
	</div>
	<div class="record-edit-confirm">
		<p>确定要删除该内容吗？</p>
		<div class="record-edit-line"></div>
		<div class="record-edit-confirm-btn">
			<a>确定</a>
			<a>取消</a>
		</div>
		<span class="record-edit-confirm-close"></span>
	</div>
</div>
<div class="create-file" style="display:none;"></div>
{template:foot}
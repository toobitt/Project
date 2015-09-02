<?php 
/* $Id: program_record_list.php 1344 2011-10-13 01:26:04Z lijiaying $ */
?>
{template:head}
{css:tab_btn}
{template:list/common_list}
<script type="text/javascript">
/*
function hg_plan_del(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
}
function hg_call_plan_del(data)
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
function hg_disable_action(str)
{
	jAlert(str);
}*/
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
</style>
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="formwin">
		<span class="left"></span>
		<span class="middle"><em class="add">新增企业信息</em></span>
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
		<div style="position: relative;">
			<div id="open-close-box">
				<span></span>
				<div class="open-close-title">显示/关闭</div>
				<ul>
					<li which="caozhuo"><label><input type="checkbox" checked />操作</label></li>
					<li which="zhouqi"><label><input type="checkbox" checked />周期/日期</label></li>
					<li which="qz-time"><label><input type="checkbox" checked />起止时间</label></li>
					<li which="jm-name"><label><input type="checkbox" checked />节目名称</label></li>
					<li which="fabuzhi"><label><input type="checkbox" checked />发布至</label></li>
					<li which="guidan"><label><input type="checkbox" checked />归档分类</label></li>
					<li which="zhuangtai"><label><input type="checkbox" checked />状态</label></li>
					<li which="laiyuan"><label><input type="checkbox" checked />来源</label></li>
					<li which="jihua"><label><input type="checkbox" checked />计划执行</label></li>
				</ul>
			</div>
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
						<!--  <div class="common-list-item caozhuo wd70">操作</div>-->
						<div class="common-list-item wd150">周期/日期</div>
						<div class="common-list-item jihua wd90">计划执行</div>
						<div class="common-list-item fabuzhi wd80">发布至</div>
						<div class="common-list-item guidan wd80">归档分类</div>
						<div class="common-list-item zhuangtai wd60">状态</div>
						<div class="common-list-item qz-time wd150">起止时间</div>
						<div class="common-list-item laiyuan wd120">添加人/时间</div>
					</div>
					<div class="common-list-biaoti">
						<div class="common-list-item wd90">收录频道</div>
						<div class="common-list-item jm-name wd100">节目名称</div>
				    </div>
				</li>
			</ul>
			
			<ul class="common-list site-list public-list" id="sitelist">
				{if $list}
				{foreach $list as $k => $v}
				<li class="common-list-data clear" id="r_{$v['id']}">
					<div class="common-list-left">
						<div class="common-list-item paixu">
								<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v[$primary_key]}" {if $v['program_id'] || $v['plan_id']} disabled="disabled"{/if} />
						</div>
					</div>
					<div class="common-list-right">
					   	<div class="common-list-item wd150">
							<span class="overflow">{$v['cycle']}</span>
						</div>
						<div class="common-list-item jihua wd90">
							<span>{$v['dates']}</span>
						</div>
						<div class="common-list-item guidan wd80">
                                <div class="common-list-pub-overflow wd80">
                                    <a href="javascript:;" target="_blank"><span class="common-list-pub">{$v['column_name']}</span></a>
							</div>
						</div>
						<div class="common-list-item wd80">
								<span>{$v['sort_name']['name']}</span>
						</div>
						<div class="common-list-item zhuangtai wd60">
							<span style="color:{$list_setting['status_color'][$v['action']]};">{$v['action']}</span>
						</div>
						<div class="common-list-item wd150">
								<span class="common-time">{$v['start_time']}~{$v['end_time']}</span>
								<span class="text_b live-duration">{$v['toff_decode']}</span>
						</div>
						<div class="common-list-item laiyuan wd120">
                                <span class="common-name">{$v['user_name']}</span>
                                <span class="common-time">{code} echo date("Y-m-d H:i",$v['create_time']);{/code}</span>
                        </div>
						
					</div>
					<div class="common-list-biaoti">
					   <div class="common-list-item biaoti-content biaoti-transition wd90">
								<span class="common-list-overflow max-wd100">{$v['channel']}</span>
					  </div>
					  <div class="common-list-item jm-name wd100">
								<span class="common-list-overflow  max-wd100 m2o-common-title" title="{$v['title']}">
								{code} echo hg_cutchars($v['title'],12,'');{/code}
							<!-- 	<a class="text_p" title="{$v['title']}">{code} echo hg_cutchars($v['title'],12,'');{/code}</a> -->
								</span>
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
						<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" name="batdelete">删除</a>
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
{template:unit/record_edit}
{template:foot}
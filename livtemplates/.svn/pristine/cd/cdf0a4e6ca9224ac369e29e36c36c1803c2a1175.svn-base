<?php 
/* $Id: program_record_list.php 1344 2011-10-13 01:26:04Z lijiaying $ */
?>
{template:head}
{css:tab_btn}
{template:list/common_list}
{code}
$js_data['status_color'] = $_configs['status_color'];
{/code}
<script>
globalData = window.globalData || {};
$.extend(globalData, {code}echo json_encode($js_data);{/code});
</script>
<script type="text/javascript">
function hg_change_status(obj)
{
   var obj = obj[0];
   var status_text = "";
   if(obj.status == 1)
   {
	   status_text = '已审核';
   }
   else if(obj.status == 2)
   {
	   status_text = '已打回';    
   }
   for(var i = 0;i<obj.id.length;i++)
   {
   	   var color = globalData.status_color[status_text];
   	   
   	   //console.log(globalData.status_color);
	   $('#statusLabelOf'+obj.id[i]).text(status_text).css('color', color);
	   recordCollection.get(obj.id[i]).set('status', obj.status);
   }
   if($('#edit_show'))
   {
	   hg_close_opration_info();
   }
}
gBatchAction['delete'] = './run.php?mid=' + gMid + '&a=delete&infrm=1&ajax=1';
function hg_bill_del(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
}
function hg_call_bill_del(data)
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
</style>
<!--<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增报销单</em></span>
		<span class="right"></span>
	</a>
</div>-->
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
						<div class="common-list-item" style="width:150px;">项目名称</div>
						<div class="common-list-item" style="width:150px;">出差时间</div>
						<div class="common-list-item" style="width:150px;">返回时间</div>
						<div class="common-list-item" style="width:80px;">预支费用</div>
						<div class="common-list-item" style="width:80px;">已花费</div>
						<div class="common-list-item" style="width:80px;">余额</div>
						<div class="common-list-item"  style="width:60px;">状态</div>
						<div class="common-list-item"  style="width:60px;">审批状态</div>
						<div class="common-list-item" style="width:90px;">所属用户</div>
						<div class="common-list-item" style="width:150px;">创建时间</div>
				    </div>
				</li>
			</ul>
			
			<ul class="common-list site-list public-list" id="sitelist">
				{if $list}
				{code}
					$server_item = array();
					foreach($project_info as $kk =>$vv)
					{
						$server_item[$vv['id']] = $vv['name'];
					}
				{/code}
				{foreach $list as $k => $v}
				<li class="common-list-data clear" id="r_{$v['id']}">
					<div class="common-list-left">
						<div class="common-list-item paixu">
								<input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v[$primary_key]}"/>
						</div>
					</div>
					<div class="common-list-biaoti">
					   <div class="common-list-item biaoti-content biaoti-transition">
						<span class="common-list-overflow wd150"><a href="./run.php?mid={$relate_module_id}&bill_id={$v['id']}&locked={$v['locked']}&infrm=1&noaction=1" target="nodeFrame">{$server_item[$v['project_id']]}</a></span>
					  </div>
					  <div class="common-list-item">
					  	<span class="common-list-overflow  wd150">{code}echo date("Y-m-d H:i",$v['business_time']); {/code}</span>
					  </div>
					  <div class="common-list-item">
					  	<span class="common-list-overflow  wd150">{code}echo date("Y-m-d H:i",$v['back_time']); {/code}</span>
					  </div>
					  <div class="common-list-item">
					  	<span class="common-list-overflow  wd80">{$v['advice']}￥</span>
					  </div>
					  <div class="common-list-item">
					  	<span class="common-list-overflow  wd80">{$v['cost']}￥</span>
					  </div>
					  <div class="common-list-item">
					  	<span class="common-list-overflow  wd80">{code}echo $v['advice'] - $v['cost']; {/code}￥</span>
					  </div>
					  <div class="common-switch-status" style="height: 40px;display: table-cell;vertical-align: middle;">
					  	<span  _id="{$v['id']}" _state="{$v['state']}" id="statusLabelOf{$v['id']}" style="font-size: 12px;color:{$_configs['status_color'][$v['state']]};" class="common-list-overflow  wd60">{$_configs['status_show'][$v['state']]}</span>
					  </div>
					  <div class="common-list-item">
					  	<span class="common-list-overflow wd90" style="font-size: 12px;color:{$_configs['status_color'][$v['is_approve']]};">{$_configs['approve'][$v['is_approve']]}</span>
					  <div class="common-list-item">
					  	<span class="common-list-overflow  wd90">{$v['user_name']}</span>
					  </div>
					  <div class="common-list-item">
					  	<span class="common-list-overflow  wd150">{code}echo date("Y-m-d H:i",$v['create_time']);{/code}</span>
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
					<!--<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" />
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax', 'hg_change_status');" name="audit">审核</a>
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=0', 'ajax', 'hg_change_status');" name="back">打回</a>
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
			<!--<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1">编辑</a>-->
			<a href="./run.php?mid={$relate_module_id}&bill_id=${id}&infrm=1&noaction=1">进入记录</a>
			<!--<a href="javascript:void(0);" onclick="hg_bill_del(${id});">删除</a>-->
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
{template:foot}
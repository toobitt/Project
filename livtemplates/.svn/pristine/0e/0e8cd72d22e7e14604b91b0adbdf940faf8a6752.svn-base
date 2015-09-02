<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
if(!$_INPUT['message_status'])
{
	$_INPUT['message_status'] = 0;
}
if(!$_INPUT['nid'])
{
	$_INPUT['nid'] = 101;
}
$commentPoints = $commentPoint[0];
foreach($commentPoints as $k => $v)
{
	if( $tableid ) break;
	$tableid = $k;
}
if(!$_INPUT['comment_year'])
{
	$_INPUT['comment_year'] = $tableid;
}
if($list)
{
	$tableName = $list[0]['tablename'];
}
foreach ($list as $v) {
	$less_list[] = array(
		'id' => $v['id'],
		'tablename' => $v['tablename'],
		'status' => $v['status']
	);
}
$js_data['list'] = $less_list;
$js_data['status_color'] = $_configs['status_color'];
$list_setting['status_color'] = $_configs['status_color'];
{/code}
<script>
globalData = window.globalData || {};
$.extend(globalData, {code}echo json_encode($js_data);{/code});
</script>
{css:common/common_list}
{css:message_list}
<!-- js:underscore -->
<!-- js:Backbone -->
<!-- js:list/record -->
<!-- js:list/record_view -->
<!-- js:jqueryfn/jquery.tmpl.min -->
<!-- js:list/action_box -->
<!-- js:common/list_sort -->
<!-- js:message -->
{js:2013/list}
{js:2013/ajaxload_new}
{js:comments/comment_list}
{code}
$default_nid = $_INPUT['nid'] ? $_INPUT['nid'] : '';
foreach($_configs['node_change'] as $k => $v){
	if( $k != $default_nid ){
		$default_nid = $k;
		break;
	}

}
$default_nid_name = $_configs['node_change'][$default_nid];
{/code}
<div id="hg_page_menu" class="head_op_program">
	<a class="add-button mr10"  href="run.php?mid={$_INPUT['mid']}&a=frame&nid={$default_nid}" target="mainwin">
		{$default_nid_name}
	</a>
	<a class="add-button mr10"  href="run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		添加评论
	</a>
</div>

<div class="search_a" id="info_list_search" style="display: none;">
	<span class="serach-btn"></span>
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="select-search">
			{code}

			$attr_status = array(
			'class' => 'transcoding down_list',
			'show' => 'status_show',
			'width' => 104,/*列表宽度*/
			'state' => 0,/*0--正常数据选择列表，1--日期选择*/
			);
			$attr_date = array(
			'class' => 'colonm down_list data_time',
			'show' => 'colonm_show',
			'width' => 104,/*列表宽度*/
			'state' => 1,/*0--正常数据选择列表，1--日期选择*/
			);
			$attr_node = array(
			'class' => 'colonm down_list data_time',
			'show' => 'node_show',
			'width' => 104,/*列表宽度*/
			'href' => 'run.php?a=frame&mid='.$_INPUT['mid'],
			'state' => 0,/*0--正常数据选择列表，1--日期选择*/
			);
			$attr_comment_year = array(
			'class' => 'colonm down_list comment_year',
			'show' => 'comment_year_show',
			'width' => 104,/*列表宽度*/
			'state' => 0,/*0--正常数据选择列表，1--日期选择*/
			);
			{/code}
			{template:form/search_source,message_status,$_INPUT['message_status'],$_configs['message_status'],$attr_status}
			{template:form/search_source,node_change,$_INPUT['nid'],$_configs['node_change'],$attr_node}
			{template:form/search_source,comment_year,$_INPUT['comment_year'],$_configs['comment_year'],$attr_comment_year}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			<input type="hidden" name="a" value="show" />
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
			<input type="hidden" name="nid" value="{$_INPUT['node_change']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
			<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
		</div>
		<div class="text-search">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}
		</div>
		<div class="custom-search">
			{code}
				$attr_creater = array(
					'class' => 'custom-item',
					'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'place' =>'添加人'
				);
			{/code}
			{template:form/search_input,user_name,$_INPUT['user_name'],1,$attr_creater}
		</div>
		<div class="custom-search">
			{code}
				$attr_ip = array(
					'class' => 'custom-item',
					'state' =>3, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'place' =>'ip地址'
				);
			{/code}
			{template:form/search_input,ip,$_INPUT['ip'],1,$attr_ip}
		</div>
		<div class="custom-search">
			{code}
				$attr_id = array(
					'class' => 'custom-item',
					'state' =>3, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'place' =>'评论id'
				);
			{/code}
			{template:form/search_input,comm_id,$_INPUT['comm_id'],1,$attr_id}
		</div>
		<div class="custom-search">
			{code}
				$attr_url = array(
					'class' => 'custom-item',
					'state' =>3, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
					'place' =>'内容链接'
				);
			{/code}
			{template:form/search_input,content_url,$_INPUT['content_url'],1,$attr_url}
		</div>
		<style>
		.comment_year{display:none;}
		</style>
	</form>
</div>
<style>
.common-list-biaoti .max-wd{max-width:270px;}
</style>
<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
	<form method="get" action="" name="listform" style="position:relative;">
		<div class="common-list">
			<div class="common-list-head m2o-flex">
				<div class="m2o-flex-one"></div>
				<a class="sort-btn" _now="close">开启排序</a>
				<div class="year-search-wrap clear">
					<div class="year-search-inner">
						{code}
						$attr_copy_comment_year = array(
						'class' => 'colonm down_list copy_comment_year',
						'show' => 'copy_comment_year_show',
						'width' => 104,/*列表宽度*/
						'state' => 0,/*0--正常数据选择列表，1--日期选择*/
						);
						$_INPUT['copy_comment_year'] = $_INPUT['comment_year'];
						{/code}
						{template:form/search_source,copy_comment_year,$_INPUT['copy_comment_year'],$commentPoints,$attr_copy_comment_year}
					</div>
				</div>
			</div>
			<div class="tile-list clear">
				{if !$list}
				<p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
				<script>hg_error_html('#emptyTip',1);</script>
				{/if}
				{foreach $list as $k => $v}
				<div class="tile-item" _status="{$v['status']}" _id="{$v['id']}" _orderid="{$v['order_id']}">
					<div class="message-info">
						<div class="tile-inner-item message-content">
							{if $v['last_reply']}
							<a class="message-title" title="{$v['content']}" href="./run.php?mid={$_INPUT['mid']}&a=show&fid={$v['id']}&tablename={$v['tablename']}&infrm=1">{code}echo hg_cutchars($v['content'], 34){/code}</a>
							{else}
							<span class="message-title" title="{$v['content']}">{code}echo hg_cutchars($v['content'], 34){/code}</span>
							{/if}
						</div>
						<div class="tile-inner-item handle-btns m2o-flex">
							<div class="m2o-flex-one">
								{if $v['last_reply']}
								<a class="underline" href="./run.php?mid={$_INPUT['mid']}&a=show&fid={$v['id']}&tablename={$v['tablename']}&infrm=1">查看回复</a>
								{/if}
								<a class="handle-btn2 underline" href="./run.php?mid={$_INPUT['mid']}&a=reply&id={$v['id']}&infrm=1&tablename={$v['tablename']}">回复</a>
								<a class="handle-btn2 underline" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1&tablename={$v['tablename']}">编辑</a>
								<a class="del-btn stop underline">删除</a>
							</div>
							<span class="audit-btn stop" _id="{$v['id']}" _state="{$v['state']}" id="statusLabelOf{$v['id']}" style="color:{$list_setting['status_color'][$v['state']]};">{$v['state']}</span>
						</div>
						<div class="tile-inner-item m2o-flex">
							<span class="title">评论对象：</span>
							<div class="content text-overflow">
								<a class="underline" title="{$v['content_title']}" href="{if $v['cmid']}./run.php?mid={$_INPUT['mid']}&a=show&cmid={$v['cmid']}&infrm=1{else}./run.php?mid={$_INPUT['mid']}&a=show&content_id={$v['contentid']}&app_uniqueid={$v['app_uniqueid']}&mod_uniqueid={$v['mod_uniqueid']}&infrm=1{/if}">
								{$v['content_title']}
								</a>
							</div>
						</div>
						<div class="tile-inner-item m2o-flex">
							<span class="title">内容链接：</span>
							<div class="content text-overflow">
								<a class="underline" title="{$v['content_url']}" href="{$v['content_url']}">
								{$v['content_url']}
								</a>
							</div>
						</div>
					</div>
					<div class="user-info m2o-flex">
						<div class="user-name" title="添加人：{$v['username']}">{$v['username']}</div>
						<div class="user-time m2o-flex-one text-overflow" title="添加时间：{$v['pub_time']}">{$v['pub_time']}</div>
						{if $v['ip']}
						<div class="user-ip text-overflow" title="ip地址：{$v['ip']}">{$v['ip']}</div>
						{/if}
					</div>
				</div>
				{/foreach}
			</div>
			<div class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox"  name="checkall" class="checkAll"  value="infolist" title="全选" rowtag="LI" />
					<a class="batch-btn audit" _status="1">审核</a>
					<a class="batch-btn audit" _status="2">打回</a>
					<a class="batch-btn del">删除</a>
				</div>
				{$pagelink}
			</div>
		</div>
	</form>
</div>
<script>
window.gData = {
		tableName : {code}echo json_encode( $tableName );{/code},
};
window.gConfig = {code}echo json_encode( $_configs );{/code};
</script>

<div id="infotip"  class="ordertip"></div>
{template:unit/record_edit}
<script>
top['tableName' + gMid ] = '{$tableName}' || 0;
top['comment_year' + gMid ] = '{$_INPUT["comment_year"]}';


$(function(){
	$('#searchform').on( 'submit', function(){
		var copy_comment_year = $('#copy_comment_year'),
			val = copy_comment_year.val();
		$('#comment_year').val( val );
		parent.$('#searchform').find('#comment_year').val( val );
	} );
})
</script>
{template:foot}
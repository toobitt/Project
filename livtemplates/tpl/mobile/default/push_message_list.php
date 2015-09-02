<?php 
/* $Id: push_message_list.php 24450 2013-07-26 09:34:33Z hanwenbin $ */
?>
{template:head}
{code}

if(!$_INPUT['advice_status'])
{
	$_INPUT['advice_status'] = -1;
}
$attr_for_edit = array('id', 'state', 'is_send');
foreach ($push_message_list as $k => $v) {
	$less_list[$k] = array();
	foreach ($attr_for_edit as $attr) {
		$less_list[$k][$attr] = $v[$attr];
	}
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
{css:vod_style}
{js:underscore}
{js:Backbone}
{js:list/record}
{js:list/record_view}
{js:jqueryfn/jquery.tmpl.min}
{js:list/action_box}
{js:push_message}
<div id="hg_page_menu" class="head_op_program">
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&exclude_auth=1" class="button_6" style="font-weight:bold;">添加消息</a>
</div>
<div class="search_a" id="info_list_search">
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="right_1">
			{code}
			$attr_status = array(
			'class' => 'transcoding down_list',
			'show' => 'status_show',
			'width' => 104,/*列表宽度*/
			'state' => 0,/*0--正常数据选择列表，1--日期选择*/
			);
			{/code}
			{template:form/search_source,advice_status,$_INPUT['advice_status'],$_configs['advice_status'],$attr_status}
			<input type="hidden" name="a" value="show" />
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		</div>
		<div class="right_2">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}
		</div>
	</form>
</div>

<div class="common-list-content right" style="float:none;">
{if !$push_message_list}
	<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">
		没有您要找的内容！
	</p>
	<script>
		hg_error_html('p', 1);
	</script>
{else}
	<form method="post" action="" name="listform">
		<ul class="common-list" id="list_head">
			<li class="common-list-head clear public-list-head">
				<div class="common-list-left">
					<div class="common-list-item paixu">
						<a class="lb" style="cursor:pointer;"  onclick="hg_switch_order('vodlist');"  title="排序模式切换/ALT+R"><em></em></a>
					</div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item wd60">
						状态
					</div>
					<!-- <div class="common-list-item">消息状态</div>-->
					<div class="common-list-item">
						发送状态
					</div>
					<div class="common-list-item wd120">
						推送时间
					</div>
					<div class="common-list-item wd130">
						添加人/时间
					</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item">
						消息内容
					</div>
				</div>
			</li>
		</ul>
		<ul class="common-list public-list" id="contribute_list">
			{foreach $push_message_list as $k => $v}
			{template:unit/pushlist}
			{/foreach}
		</ul>
		<ul class="common-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1', 'ajax','');"  name="bataudit">审核</a>
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=2', 'ajax','');"  name="bataudit">打回</a>
					<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"  name="batdelete">删除</a>
				</div>
				{$pagelink}
			</li>
		</ul>
	</form>
{/if}
{template:unit/record_edit}
{template:foot}
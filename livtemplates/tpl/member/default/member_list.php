<?php 
/* $Id: member_list.php 21460 2013-05-27 10:29:38Z yizhongyue $ */
?>
{code}
isset($_INPUT['date_search']) || ($_INPUT['date_search'] = 1);
isset($_INPUT['status']) || ($_INPUT['status'] = -1);
$status_key = 'status';
$status_map = array(
	0 => array( 'name' => '待审核', 'next' => 1, 'op_name' => '审核' ),
	1 => array( 'name' => '已审核', 'next' => 0, 'op_name' => '打回'),
	2 => array( 'name' => '待审核', 'next' => 1, 'op_name' => '审核' )
);

$list_setting['status_color'] = $_configs['status_color'];
{/code}

{template:head}
{template:unit/list_base, 0, $list}
{js:jqueryfn/jquery.tmpl.min}
{js:member/action_box}
{js:member/app}
<script>
globalData.statusMap = {code}echo json_encode($status_map);{/code};
</script>

<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增用户</strong></a>
</div>

<!-- 搜索 -->
<div class="search_a" id="info_list_search">
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="right_1">
			{code}
				$attr_state = array(
					'class' => 'transcoding down_list',
					'show' => 'state_show',
					'width' => 80,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					'is_sub'=> 0,
				);
				
				$attr_date = array(
					'class' => 'colonm down_list data_time',
					'show' => 'colonm_show',
					'width' => 104,/*列表宽度*/
					'state' => 1,/*0--正常数据选择列表，1--日期选择*/
				);
				
				$default_node_type = $_INPUT['node_type'] ? $_INPUT['node_type'] : 0;
				
			{/code}
			{template:form/search_source,status,$_INPUT['status'],$_configs['member_state'],$attr_state}
			{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
			<input type="hidden" name="a" value="show" />
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
			<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
		</div>
		<div class="right_2">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}                        
		</div>
	</form>
</div>

<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
{if !$list}
	<p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('#emptyTip',1);</script>
{else}
	<form action="" method="post">
		<!-- 标题 -->
		<ul class="common-list">
			<li class="common-list-head public-list-head clear">
			    <div class="common-list-left">
			        <div class="paixu common-list-item"><a class="common-list-paixu" style="cursor:pointer;"></a></div>
			    </div>
			    <div class="common-list-right">
			        <div class="mem-huiyuan common-list-item open-close" which="mem-huiyuan">所属会员组</div>
			        <div class="mem-email common-list-item wd150" which="mem-email">邮箱</div>
			        <div class="mem-zc common-list-item open-close wd100" which="mem-zc">注册IP</div>
			        <div class="mem-zt common-list-item open-close" which="mem-zt">状态</div>
			        <div class="mem-jh common-list-item open-close" which="mem-jh">邮箱激活</div>
			        <div class="mem-sj common-list-item wd150" which="mem-sj">添加时间</div>
			    </div>
			    <div class="common-list-biaoti">
					<div class="common-list-item">标题</div>
				</div>
			</li>
		</ul>
        <ul class="common-list public-list" id="status_list">
		{foreach $list as $k => $v}
			{template:unit/member_list_list}
		{/foreach}
		</ul>
		<ul class="common-list">
		    <li class="common-list-bottom clear">
			   <div class="common-list-left">
			      <input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI"/>
			      <a onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">删除</a>
		       </div>
		       {$pagelink}
		    </li>
		</ul>
	</form>
</div>

{template:unit/record_edit}

{/if}		

{template:foot}
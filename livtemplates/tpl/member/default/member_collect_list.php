<?php 
/* $Id: member_collect_list.php 10939 2012-08-25 02:59:27Z lijiaying $ */
?>
{template:head}
{js:member}
{css:vod_style}

{code}
if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['status']))
{
    $_INPUT['status'] = -1;
}
{/code}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">

<div class="content clear">
	<div class="f">
		<div class="right v_list_show" style="float:none;">
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
			<form action="" method="post">
				<!-- 标题 -->
				<div class="list_first clear"  id="list_head">
					<span class="left"><a class="lb" style="cursor:pointer;"  {if !$list['colname']}onclick="hg_switch_order('vodlist');"{/if}  title="排序模式切换/ALT+R"><em></em></a></span>
					<span style="width:430px;margin-right: 24px;" class="right"><a class="ml" style="width: 120px;margin-left:18px;">来源</a><a class="fl" style="margin-left: 60px;">操作</a><a class="tjr" style="margin-left: 28px;">用户名/添加时间</a></span><a class="title" style="margin-left: 50px;margin-top: 8px;">标题</a>
				</div>
				<ul class="list ui-sortable ui-sortable-disabled" id="status_list">
					{if $list}
						{foreach $list AS $k => $v}
							{template:unit/member_collect_list_list}
						{/foreach}
						<li class="clear"></li>
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(status_list,1);</script>
					{/if}
				</ul>
				<div class="clear"></div>
				<div class="left" style="width:400px;margin-left:24px;">
					<input type="checkbox" style="position: relative;top: 7px;" name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
					<a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">删除</a>
				</div>
			</form>
		</div>
		{$pagelink}
		</div>
		
	</div>
</div>
</body>
{template:foot}
<?php 
/* $Id: app_list.php 9495 2012-05-30 05:14:09Z lijiaying $ */
?>
{template:head}
{js:ucusers}
{css:vod_style}

{code}
/*hg_pre($list);*/

if(!isset($_INPUT['date_search']))
{
    $_INPUT['date_search'] = 1;
}

if(!isset($_INPUT['state']))
{
    $_INPUT['state'] = -1;
}
{/code}
<script type="text/javascript">
	var apps = new Array();
	var run = 0;
	function testlink() 
	{
		if(apps[run]) 
		{
			var link = ($('#link_' + apps[run]).attr('testlink'));
			$('#status_' + apps[run]).html('正在连接……');
			hg_request_to(link);	
		}
		run++;
	}
	window.onload = testlink;

	function appStatus_back($html,id)
	{
		$('#status_' + id).html(eval($html));
		testlink();
	}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增应用</strong></a>
</div>
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
							
						{/code}
						{template:form/search_source,state,$_INPUT['state'],$_configs['state'],$attr_state}
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
					<span class="left"><a class="lb" style="cursor:pointer;"  {if !$list[0]['colname']}onclick="hg_switch_order('vodlist');"{/if}  title="排序模式切换/ALT+R"><em></em></a></span>
					<span style="width:530px;margin-right: 24px;" class="right"><a class="fb" style="width:200px;">url</a><a class="ml" style="width: 200px;">通信状态</a><a class="fl" style="">操作</a></span><a class="title" style="margin-left: 50px;margin-top: 8px;">应用名</a>
				</div>
				<ul class="list ui-sortable ui-sortable-disabled" id="status_list">
					{if $list[0]}
						{code}
							$i = 0;
						{/code}
						{foreach $list[0] as $k => $v}
							{template:unit/app_list_list}
							{code}
								$i ++;
							{/code}
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
{template:head}
{css:vod_style}
{css:edit_video_list}
{js:vod_opration}
{css:common/common_list}
{template:list/common_list}
<script type="text/javascript">
	function hg_audit_callback(json)
	{
		var obj = eval("("+json+")");
		var con = '';
		if(obj.status == 1)
		{
			con = '已审核';
		}
		else if(obj.status == 2)
		{
			con = '被打回 ';    
		}
		for(var i = 0;i<obj.id.length;i++)
		{
			$('#status_'+obj.id[i]).text(con);
		}
		if($('#edit_show'))
		{
			hg_close_opration_info();
		}	
	}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
<a href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="add-button mr10" style="font-weight:bold;">新增</a>
</div>
<div class="content clear">
	<div class="f">
		<div class="right v_list_show">
			<!-- 搜索 -->
			<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="right_1">
						{code}
						$time_css = array(
							'class' => 'transcoding down_list',
							'show' => 'time_item',
							'width' => 120,	
							'state' => 1,/*0--正常数据选择列表，1--日期选择*/
						);
						$_INPUT['show_time'] = $_INPUT['show_time'] ? $_INPUT['show_time'] : 1;
						{/code}
						{template:form/search_source,show_time,$_INPUT['show_time'],$_configs['date_search'],$time_css}
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
			<form action="" method="post" name="listform">
				<!-- 标题 -->
				<div class="list_first clear"  id="list_head">
					<span class="left">
						<a class="lb" style="cursor:pointer;"><em onclick="hg_switch_order('reporter_list');"  title="排序模式切换/ALT+R"></em></a>
						<a class="fl" style="width:50px">头像</a>
					</span>
					<span class="right" style="width: 680px">
						<a class="fb" >编辑</a>
						<a class="fb" >删除</a>
						<a class="fl" style="width: 100px">姓名</a>
						<a class="fl" >性别</a>
						<a class="fl" style="width: 100px">电话</a>
						<a class="fl" style="width: 100px">Email</a>
						<a class="fl" >状态</a>
						<a class="tjr">添加人/时间</a>
					</span>
					<a class="title" style="margin-left: 10px;margin-top: 8px;">帐户</a>
				</div>
				<ul class="list hg_sortable_list" data-order_name="order_id" id="reporter_list">
					{if $list}
						{foreach $list as $k => $v}
							{template:unit/contribute_reporter_list}
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
					<a style="cursor:pointer;margin-left:10px;text-decoration:underline"  onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&status=1', 'ajax');" name="bataudit">审核</a>
					<a style="cursor:pointer;margin-left:10px;text-decoration:underline"  onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&status=2', 'ajax');" name="bataudit">打回</a>
					<a style="cursor:pointer;margin-left:10px;text-decoration:underline"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">删除</a>										
				</div>
			</form>
		</div>
		{$pagelink}
		</div>
		<div class="edit_show">
			<span class="edit_m" id="arrow_show"></span>
			<div id="edit_show"></div>
		</div>
	</div>

   <div id="infotip"  class="ordertip"></div>
   <div id="getimgtip"  class="ordertip"></div>
</body>
{template:foot}
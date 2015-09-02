{template:head}
{css:jquery.lightbox-0.5}
{js:jquery.lightbox-0.5}
{js:interview_content}
{css:vod_style}
{css:edit_video_list}
{js:vod_opration}
{css:common/common_list}
<style>
   #show_form textarea{width:542px;margin-top:5px;}
</style>
<script type="text/javascript">
$(function(){
	tablesort('content_list','records','order_id');
	$("#content_list").sortable('disable');
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
	</div>
	<div class="content clear">
		<div class="f">
			<div class="right v_list_show">
			<!-- 搜索 -->
				<div class="search_a" id="info_list_search">
					<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
						<div class="right_1">
							{code}
							$pub_css = array(
								'class' => 'transcoding down_list',
								'show' => 'pub_item',
								'width' => 120,	
								'state' => 0,
								'para'=> array('interview_id'=>$_INPUT['interview_id']),
							);
							$default_audit = -1;
							$_configs['pub_state'][$default_audit] = '所有发布状态';
							$_INPUT['pub_state'] = isset($_INPUT['pub_state']) ? $_INPUT['pub_state'] : -1;
							{/code}						
							{template:form/search_source,pub_state,$_INPUT['pub_state'],$_configs['pub_state'],$pub_css}
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
						<a class="lb" style="cursor:pointer;"><em onclick="hg_switch_order('content_list');"  title="排序模式切换/ALT+R"></em></a>
					</span>
					<span class="right" style="width: 260px">
						<a class="fb">编辑</a>
                       	<a class="fb">删除</a>
						<a class="fl" style="text-align:center">发布</a>
						<a class="tjr">发言人/时间</a>
					</span>
					<a class="title" style="margin-left: 10px;margin-top: 8px;">发言内容</a>
				</div>
				<ul class="list" id="content_list">
					{if $interview_content_list}
						{foreach $interview_content_list as $k => $v}
							{template:unit/interview_content_list}
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
					<a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'pub', '发布', 1, 'id', '', 'ajax');" name="bataudit">发布</a>
					<a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'backpub', '取消发布', 1, 'id', '', 'ajax');" name="bataudit">取消发布</a>
					<a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');" name="batdelete">删除</a>					
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

<!--编辑弹出层开始-->
<div id="edit_records" style="width:550px;display:none;position:absolute;z-index:inherit;border:5px solid #999;left:50%;margin-left:-275px;top:0;padding:10px;background:#eee;">
		<div><span style="float:right;"><a href="javascript:void(0)" onclick="$('#edit_records').hide()">关闭</a>&nbsp;&nbsp;</span><span style="font-weight:bold;font-size:14px;">访谈内容修改</span></div>
		<hr/>
		<div id="show_form"></div>

</div>
<!--编辑弹出层结束-->
</body>
{template:foot}

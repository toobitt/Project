<?php 
/* $Id: status_list.php 9995 2012-07-14 06:25:34Z lijiaying $ */
?>
{template:head}
{js:mblog}
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
<div class="wrap biaoz">
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
				<span class="left"><a class="lb" style="cursor:pointer;"  {if !$list['colname']}onclick="hg_switch_order('vodlist');"{/if}  title="排序模式切换/ALT+R"><em></em></a></span>
				<span style="width:430px;margin-right: -15px;" class="right"><a class="fb">转发</a><a class="ml" style="margin-left:20px;">评论</a><a class="fl" style="margin-left:34px;">操作</a><a class="zt" style="margin-left:-29px;">状态</a><a class="tjr">添加人/时间</a></span><a class="title" style="margin-left: 45px;">内容</a>
			</div>
			<ul class="list ui-sortable ui-sortable-disabled" id="status_list">
				{if $list}
					{foreach $list as $k => $v}
						{template:unit/status_list_list}
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
				<a style="cursor:pointer;margin-left:20px;"  onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax', 'hg_status_audit_back');" name="bataudit">审核</a>
				<a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=0', 'ajax', 'hg_status_audit_back');" name="batgoback">打回</a>
				<a style="cursor:pointer;margin-left:10px;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">删除</a>
			</div>
		</div>
	</form>
{$pagelink}
</div>
<!--发布模板-->
<span class="vod_fb" id="vod_fb"></span>
<div id="vodpub" class="vodpub lightbox">
	<div class="lightbox_top">
		<span class="lightbox_top_left"></span>
		<span class="lightbox_top_right"></span>
		<span class="lightbox_top_middle"></span>
	</div>
	<div class="lightbox_middle">
		<span onclick="hg_vodpub_hide();" style="position:absolute;right:25px;top:25px;z-index:1000;background:url('{$RESOURCE_URL}close.gif') no-repeat;width:14px;height:14px;cursor:pointer;display:block;"></span>
		<div id="vodpub_body" class="text" style="max-height:500px;padding:10px 10px 0;">
		
		</div>
	</div>
	<div class="lightbox_bottom">
		<span class="lightbox_bottom_left"></span>
		<span class="lightbox_bottom_right"></span>
		<span class="lightbox_bottom_middle"></span>
	</div>				
</div>
<!--发布-->
{template:foot}
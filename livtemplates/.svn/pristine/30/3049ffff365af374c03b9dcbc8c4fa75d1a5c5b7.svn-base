<?php
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:contribute_style}
{css:vod_style}
{css:edit_video_list}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}
{js:contribute}
{css:common/common_list}
{css:admin_list}
{js:common/common_list}
<script type="text/javascript">
$(function(){
	tablesort('contribute_list','admin','order_id');
	$("#contribute_list").sortable('disable');
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6" style="font-weight:bold;">新增用户</a>
		<a class="gray mr10" href="run.php?mid={$_INPUT['mid']}&a=configuare&infrm=1" target="mainwin">
			<span class="left"></span>
			<span class="middle"><em class="set">配置权限</em></span>
			<span class="right"></span>
	    </a>
	</div>
	<div class="content clear">
 		<div class="f">
	    	<div class="right v_list_show">
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
								$_INPUT['admin_time'] = $_INPUT['admin_time'] ? $_INPUT['admin_time'] : 1;

								$audit_css = array(
									'class' => 'transcoding down_list',
									'show' => 'sort_audit',
									'width' => 120,
									'state' => 0,
								);
								$appendRole = $appendRole[0];
								$default = -1;
								$appendRole[$default] = '所有角色';
								$_INPUT['admin_role'] = $_INPUT['admin_role'] ? $_INPUT['admin_role'] : -1;
							{/code}

							{template:form/search_source,admin_role,$_INPUT['admin_role'],$appendRole,$audit_css}
							{template:form/search_source,admin_time,$_INPUT['admin_time'],$_configs['date_search'],$time_css}
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
	            <form method="post" action="" name="pos_listform">
	                <!-- 标题 -->
                   <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="admin-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('contribute_list');"  title="排序模式切换/ALT+R"></a></div>
                                <div class="contribute-fengmian common-list-item">头像</div>
                            </div>
                            <div class="common-list-right">
                                <div class="admin-bj common-list-item open-close">编辑</div>
                                <div class="admin-sc common-list-item open-close">删除</div>
                                <div class="admin-js common-list-item open-close">角色</div>
                                <div class="admin-bdmb common-list-item open-close">绑定密保</div>
                                <div class="admin-tjr common-list-item open-close">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close server-biaoti">用户名</div>
					        </div>
                        </li>
                    </ul>
		        	<ul class="common-list" id="contribute_list">
						{if $list}
			       			{foreach $list as $k => $v}
			                	{template:unit/adminlist}
			                {/foreach}
			  			{else}
							<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
							<script>hg_error_html(vodlist,1);</script>
		  				{/if}
		            </ul>
			        <ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
			            	<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
						    <a name="batdelete"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" style="cursor:pointer;">删除</a>
						</div>
			              {$pagelink}
			        </li>
			       </ul>
	    		</form>
	    		<div class="edit_show">
					<span class="edit_m" id="arrow_show"></span>
				<div id="edit_show"></div>
				</div>
	    	</div>
		</div>
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
	<div id="infotip"  class="ordertip"></div>
</body>
{template:foot}
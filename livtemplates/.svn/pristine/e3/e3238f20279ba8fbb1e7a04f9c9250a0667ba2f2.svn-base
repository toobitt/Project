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
	tablesort('contribute_list','push_notice','order_id');
	$("#contribute_list").sortable('disable');
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
		<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6" style="font-weight:bold;">发送通知</a>
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
									'width' => 104,	
									'state' => 1,/*0--正常数据选择列表，1--日期选择*/
								);
								$_INPUT['admin_time'] = $_INPUT['admin_time'] ? $_INPUT['admin_time'] : 1;
								
								$audit_css = array(
									'class' => 'transcoding down_list',
									'show' => 'sort_audit',
									'width' => 104,	
									'state' => 0,
								);
								$default = -1;
								$_configs['notice_state'][$default] = '全部状态';
								$_INPUT['notice_state'] = $_INPUT['notice_state'] ? $_INPUT['notice_state'] : -1;
								
								
								$app_css = array(
									'class' => 'transcoding down_list',
									'show' => 'app_show',
									'width' => 104,	
									'state' => 0,
								);
								if($appendApp)
								{
									foreach($appendApp as $k => $v)
									{
										$app[$v['id']] = $v['name'];
									}
								}
								$default = -1;
								$app[$default] = '所有应用';
								$_INPUT['app'] = $_INPUT['app'] ? $_INPUT['app'] : -1;
							{/code}
							{template:form/search_source,app,$_INPUT['app'],$app,$app_css}			
							{template:form/search_source,notice_state,$_INPUT['notice_state'],$_configs['notice_state'],$audit_css}
							{template:form/search_source,admin_time,$_INPUT['admin_time'],$_configs['date_search'],$time_css}
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
	               	</form>
	            </div>
	            <form method="post" action="" name="pos_listform">
	                <!-- 标题 -->
                   <ul class="common-list">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="admin-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('contribute_list');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="wd60 common-list-item open-close">重发</div>
                                <div class="wd60 common-list-item open-close">删除</div>
                                <div class="wd80 common-list-item open-close">接收类型</div>
                                <div class="wd80 common-list-item open-close">推送平台</div>
                                <div class="wd80 common-list-item open-close">反馈信息</div>
                                <div class="wd80 common-list-item open-close">发送结果</div>
                                <div class="common-list-item open-close wd130">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close">通知内容</div>
					        </div>
                        </li>
                    </ul>
		        	<ul class="common-list public-list" id="contribute_list">
						{if $list}
			       			{foreach $list as $k => $v} 
			                	{template:unit/noticelist}
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
	<div id="infotip"  class="ordertip"></div>
</body>
{template:foot}
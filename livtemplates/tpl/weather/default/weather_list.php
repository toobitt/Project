<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:interview}
{js:vod_opration}
{template:list/common_list}
{css:edit_video_list}
{css:common/common_list}
{css:weather_list}
{js:common/common_list}
{js:jquery-ui-1.8.16.custom.min}
<script type="text/javascript">
<!--
	var realtimeId = '';
	function showRealtime(id)
	{
		realtimeId = id;
		var url = "run.php?mid="+gMid+"&a=show_realtime&id="+id;
		hg_ajax_post(url);
	}
	function hg_realtime_back(html)
	{
		if($('#realtime_'+realtimeId).css('display')=='none')
		{
			$('#realtime_'+realtimeId).css({'display':'block'});
			$('#realtime_'+realtimeId).html(html);
			hg_resize_nodeFrame();
		}else{
			$('#realtime_'+realtimeId).css({'display':'none'});
		}		
		
	}
//-->
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	
	
	<div id="hg_page_menu" class="head_op_program">
		<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
			<span class="left"></span>
			<span class="middle"><em class="add">添加城市</em></span>
			<span class="right"></span>
	   </a>
	</div>
	
	
	<div class="content clear">
 		<div class="f">
			<div class="right v_list_show">
			
				<div class="search_a" id="info_list_search">
				    <span class="serach-btn"></span>
	                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="select-search">
							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
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
	                    </form>
	                </div>
	                <form method="post" action="" name="pos_listform">
	                    <!-- 标题 -->
                   <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="weather-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('weather_list');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="weather-day common-list-item open-close">今天</div>
                                <div class="weather-day common-list-item open-close">明天</div>
                                <div class="weather-day common-list-item open-close">第三天</div>
                                <div class="weather-day common-list-item open-close">第四天</div>
                                <div class="weather-day common-list-item open-close">第五天</div>
                                <div class="weather-day common-list-item open-close">第六天</div>
                                <!--<div class="weather-day common-list-item open-close">第七天</div>-->
                                <div class="weather-ck common-list-item open-close">操作</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close server-biaoti">城市名称</div>
					        </div>
                        </li>
                    </ul>
		             <ul class="common-list hg_sortable_list" data-order_name="order_id"  id="weather_list">
							{if $weather_list}
			       			    {foreach $weather_list as $k => $v}
			                      	{template:unit/weather_list}
			                    {/foreach}
			  				{/if}
		                </ul>
			        <ul class="common-list">
				      <li class="common-list-bottom clear">
					   <div class="common-list-left">
			                   <input type="checkbox"  name="checkall"  value="infolist" title="全选" rowtag="LI" />
						       <a name="batdelete"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" style="cursor:pointer;">删除</a>
						   </div>
			               {$pagelink}
			            </li>
			          </ul>	
	    		    </form>
	    		    <!-- 右侧列表 -->
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
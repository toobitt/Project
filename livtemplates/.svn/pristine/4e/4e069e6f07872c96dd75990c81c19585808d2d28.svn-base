<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:interview}
{css:common/common_list}
{css:interview_list}
{css:vod_style}
{css:edit_video_list}
{code}
$attr_for_edit = array('id', 'status', 'iscopy', 'distribution', 'mtype');
foreach ($interview_list as $k => $v) {
	$less_list[$k] = array();
	foreach ($attr_for_edit as $attr) {
		$less_list[$k][$attr] = $v[$attr];
	}
}
$js_data['list'] = $less_list;
{/code}

<script>
globalData = window.globalData || {};
$.extend(globalData, {code}echo json_encode($js_data);{/code});
</script>
{js:underscore}
{js:Backbone}
{js:jqueryfn/jquery.tmpl.min}
{js:list/record}
{js:list/record_view}
{js:list/action_box}
{js:list/list_sort}
{js:interview}
<div class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	
	
	<div id="hg_page_menu" class="head_op_program">
		<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
			<span class="left"></span>
			<span class="middle"><em class="add">添加新访谈</em></span>
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
	                    	{code}
	                    		$time_css = array(
								'class' => 'transcoding down_list',
								'show' => 'time_item',
								'width' => 120,	
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
								);
								$_INPUT['interview_time'] = $_INPUT['interview_time'] ? $_INPUT['interview_time'] : 1;
	                    	{/code}
							{template:form/search_source,interview_time,$_INPUT['interview_time'],$_configs['date_search'],$time_css}
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
                   <ul class="common-list" id="list_head">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('interview_list');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item open-close">主持人</div>
                                <div class="common-list-item open-close interview-kssj">开始时间</div>
                                <div class="common-list-item open-close interview-ftsj">访谈时长</div>
                                <div class="common-list-item open-close interview-jssj">结束时间</div>
                                <div class="common-list-item open-close wd150">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item open-close interview-biaoti">平台名称</div>
					        </div>
                        </li>
                    </ul>
		             <ul class="common-list public-list hg_sortable_list" id="interview_list">
							{if $interview_list}
			       			    {foreach $interview_list as $k => $v}
			                      	{template:unit/interview_list}
			                    {/foreach}
			  				{/if}
		                </ul>
			        <ul class="common-list public-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
			                   <input type="checkbox"  name="checkall"  value="infolist" title="全选" rowtag="LI" />
						       <a name="batdelete"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" style="cursor:pointer;">删除</a>
						   </div>
			               {$pagelink}
			            </li>
			        </ul>	
	    		    </form>
	    		   
	    		    
	    		   
				</div>
			</div>
		</div>
	<div id="infotip"  class="ordertip"></div>
</div>
{template:unit/record_edit}
{template:foot}
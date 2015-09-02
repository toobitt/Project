<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}

if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
if(!$_INPUT['message_status'])
{
	$_INPUT['message_status'] = 0;
}
{/code}

{js:common/common_list}
{css:common/common_list}
{js:vod_opration}
{css:edit_video_list}
{css:vod_style}
{css:message_list}
{css:mark_style}
{template:list/common_list}
<script type="text/javascript">
$(function(){
	tablesort('vodlist','message','order_id');
	$("#vodlist").sortable('disable');
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program">
	<a class="add-button mr10"  href="run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}" >添加设置</a>
</div>
<div class="content clear">
	<div class="common-list-content">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <span class="serach-btn"></span>
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="select-search">
						{code}
							$attr_status = array(
								'class' => 'transcoding down_list',
								'show' => 'status_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);

						{/code}
						{template:form/search_source,message_status,$_INPUT['message_status'],$_configs['message_status'],$attr_status}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
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
                
                <form method="post" action="" name="listform" style="position:relative;">
                     <!-- 标题 -->
               		<ul class="common-list ">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-paixu common-list-item">
                                	<a class="common-list-paixu" style="cursor:pointer;"  onclick="hg_switch_order('vodlist');" title="排序模式切换/ALT+R"></a>
                                </div>
                            </div>
                            <div class="common-list-right" style="margin-right:-20px;">
                                <div class="common-list-item open-close wd60" which="mem-zc">类型</div>
                                <div class="common-list-item open-close wd60">状态</div>
                                <div class="common-list-item wd150" which="mem-zt">添加人/时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item open-close">名称</div>
					        </div>
                        </li>
                	</ul>
                     
               		<ul class="common-list  hg_sortable_list public-list"  id="vodlist" data-order_name="order_id">
					  	{if is_array($list) && count($list)>0}
							{foreach $list as $k => $v}		
		                      {template:unit/commentsetlist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
					<script>hg_error_html(vodlist,1);</script>
		  				{/if}
                	</ul>
	            	<ul class="common-list">
	             		<li class="common-list-bottom clear">
		                	<div class="common-list-left">
			                   <input type="checkbox"  name="checkall"  value="infolist" title="全选" rowtag="LI" />
			                   <!-- <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '&audit=1&tablename={$tableName}', 'ajax','hg_change_comment_status');"    name="bataudit" >审核</a>
						       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '打回', 1, 'id', '&audit=2&tablename={$tableName}', 'ajax','hg_change_comment_status');"    name="bataudit" >打回</a> -->
						       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '&tablename={$tableName}', 'ajax');"    name="batdelete">删除</a>
					    	</div>
	               			{$pagelink}
	             		</li>
	            	</ul>	
    			</form>
 		</div>
	</div>
</div>
<div id="infotip"  class="ordertip"></div>
</body>
{template:foot}
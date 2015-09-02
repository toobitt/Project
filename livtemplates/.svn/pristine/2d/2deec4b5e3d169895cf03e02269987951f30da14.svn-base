<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}
$list=$interview_user_group_list;
$attrs_for_edit = array('user_number');
{/code}
{template:list/common_list}
{css:interview}
{js:vod_opration}
<script type="text/javascript">
	$(function(){
		tablesort('group_list','user_group','order_id');
		$("#group_list").sortable('disable');
	});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	
	
	<div id="hg_page_menu" class="head_op_program">
		<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
		<span class="left"></span>
		<span class="middle"><em class="add">添加新用户组</em></span>
		<span class="right"></span>
	</a>
	</div>
	
	
	<div class="content clear">
 		<div class="f">
			<div class="right v_list_show">
			
				<div class="search_a" id="info_list_search">
	                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
	                    	
							<input type="hidden" name="a" value="show" />
							<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
							<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
							<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
							<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
	                    </div>
	                    <div class="right_2">
						                      
	                    </div>
	                    </form>
	                </div>
	                
	                
	                
	                <form method="post" action="" name="pos_listform">
	                    <ul class="common-list" id="list_head">
                        <li class="common-list-head public-list-head clear">
                            <div class="common-list-left">
                                <div class="common-paixu common-list-item"><a class="common-list-paixu" onclick="hg_switch_order('common-list');"  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                                <div class="common-list-item wd100">角色类型</div>
                                <div class="common-list-item wd150">用户数</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item">用户组名</div>
					        </div>
                        </li>
                    </ul>
	                <ul class="common-list public-list hg_sortable_list" id="group_list">
						{if $interview_user_group_list}
		       			    {foreach $interview_user_group_list as $k => $v}   
		                      {template:unit/interview_user_group_list}
		                    {/foreach}
		  				{/if}
	                </ul>
	                <ul class="common-list public-list">
					     <li class="common-list-bottom clear">
						   <div class="common-list-left">
			                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
			                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id','', 'ajax');"    name="batdelete">删除</a>
						   </div>
			               {$pagelink}
			            </li>
		            </ul>	
	    		    </form>
	    		   
				</div>
			</div>
		</div>
	<div id="infotip"  class="ordertip"></div>
<!-- 关于记录的操作和信息 -->
{template:unit/record_edit}
{template:foot}
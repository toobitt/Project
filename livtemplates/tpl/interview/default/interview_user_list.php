<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:interview}
{js:interview_user}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}
{css:jquery.lightbox-0.5}
{js:jquery.lightbox-0.5}
<style>
#livwindialogbody{overflow:visible;}
</style>
<script type="text/javascript">
	$(function(){
		tablesort('user_list','interview_user','order_id');
		$("#user_list").sortable('disable');
	});
	/*显示大图*/
	function show_pic(id){
		 $('#pic_' +id+ ' a').lightBox();
	}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	
	
	<div id="hg_page_menu" class="head_op_program">
		<a  class="button_6" style="font-weight:bold;" onclick="hg_showUser({$_INPUT['interview_id']})">添加用户</a>
		<!--<a href="?mid={$relate_module_id}&interview_id={$_INPUT['interview_id']}&infrm=1" class="button_6" style="font-weight:bold;" onclick="hg_showUser({$_INPUT['interview_id']})">管理用户</a>
	--></div>
	
	
	<div class="content clear">
 		<div class="f">
			<div class="right v_list_show">
			
				<div class="search_a" id="info_list_search">
	                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
	                    	{code}
							$group_css = array(
								'class' => 'transcoding down_list',
								'show' => 'group_item',
								'width' => 120,	
								'state' => 0,
								'para'=> array('interview_id'=>$_INPUT['interview_id']),
							);
							$default_audit = -1;
							$interview_user_list[0]['group'][$default_audit] = '所有用户组';
							$interview_user_list[0]['group'][0] = '暂未分组';
							$_INPUT['interview_group'] = isset($_INPUT['interview_group']) ? $_INPUT['interview_group'] : -1;
							{/code}						
							{template:form/search_source,interview_group,$_INPUT['interview_group'],$interview_user_list[0]['group'],$group_css}
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
	
	                
	                
	                <div class="list_first clear"  id="list_head">
	                    <span class="left">
	                    	<a class="lb" style="cursor:pointer;"   onclick="hg_switch_order('user_list');"  title="排序模式切换/ALT+R">
	                    		<em></em>
	                    	</a>
	                    	<a class="fl" style="width:50px">头像</a>  
	                    </span>
                        <span class="right"  style="width:800px">
                        	<a style="width:35%">所属用户组</a>
                        	<a style="width:35%">组的角色</a>             
                        	<a>操作</a>                       
                        </span>
                        <span class="title overflow">
                        	 <a>用户名</a>
                        </span>                        	                                 
	                </div>	                
	          
	                <form method="post" action="" name="pos_listform">
		                <ul class="list" id="user_list">
							{if $interview_user_list[0]['list']}
			       			    {foreach $interview_user_list[0]['list'] as $k => $v}		       			    
			                      {template:unit/interview_user_list}
			                    {/foreach}
			  				{/if}
							<li style="height:0px;padding:0;" class="clear"></li>
		                </ul>
			            <div class="bottom clear">
			               <div class="left">
			                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
						       <a name="batdelete"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" style="cursor:pointer;">删除</a>
						   </div>
			               {$pagelink}
			            </div>	
	    		    </form>
	    		    
				</div>
			</div>
		</div>
<!-- other_box -->
		<div id="get_user_box" style="width:620px;height:400px;z-index:10000;border:10px solid #9E9E9E; position:absolute; margin-left:-300px; top: -421px; left: 50%;background:#FFFFFF;border-radius: 15px;">
				<div  style="height:30px;">
					<h3>用户列表</h3>
				<span style="position:absolute;right:6px;top:6px;z-index:1000;" onclick="hg_otherClose();">
					<img width="14" height="14" id="livwindialogClose" src="{$RESOURCE_URL}close.gif" style="cursor: pointer; " />
				</span>
				</div>
				<div id="livwindialogbody" style="height:360px;">
				<iframe style="width:100%;height:100%;" id="user_frame"></iframe>
				</div>
		</div>
<!-- other_box -->
	<div id="infotip"  class="ordertip"></div>
</body>
{template:foot}
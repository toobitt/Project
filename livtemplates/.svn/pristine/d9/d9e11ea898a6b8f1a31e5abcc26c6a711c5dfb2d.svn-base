<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:interview}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}
{template:list/common_list}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	
	
	<div id="hg_page_menu" class="head_op_program">
		<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6" style="font-weight:bold;">添加分类</a>
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
								$_INPUT['sort_time'] = $_INPUT['sort_time'] ? $_INPUT['sort_time'] : 1;
	                    	{/code}
							{template:form/search_source,sort_time,$_INPUT['sort_time'],$_configs['date_search'],$time_css}
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
	                    	<a class="lb" style="cursor:pointer;"   onclick="hg_switch_order('contribute_fastInput_sort');"  title="排序模式切换/ALT+R">
	                    		<em></em>
	                    	</a>
	                    	<a class="fb" style="width:120px;">类别名称</a>
	                    </span>
                        <span class="right" style="width:250px;">
                        	<a class="fb">编辑</a>
                        	<a class="fb">删除</a>
                        	<a class="tjr">添加人/时间</a>    	
                        </span>
                        <a class="fb" style="margin-left:10px;">分类描述</a>                      
	                </div>

	                <form method="post" action="" name="pos_listform">
		                <ul class="list hg_sortable_list" data-order_name="order_id" id="contribute_fastInput_sort">
							{if $list}
			       			    {foreach $list as $k => $v}
			                      	{template:unit/contribute_fastInput_sort_list}
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
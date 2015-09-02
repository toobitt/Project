<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z jeffrey $ */
?>
{template:head}
{css:interview}
{css:edit_video_list}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}
<script type="text/javascript">
	function del_img(id)
	{
		if(confirm("确定要删除自定义图片吗?"))
		var url = "run.php?mid="+gMid+"&a=del_img&id="+id;
		hg_ajax_post(url);
	}
	function hg_del_img_back(json)
	{
		var json_data = $.parseJSON(json);
		for(var a in json_data.id)
		{
			$("#user_img_"+json_data.id[a]).html('');
		}	
	}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	
	
	<div id="hg_page_menu" class="head_op_program">
		<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6" style="font-weight:bold;">新增心情分类</a>
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
	                    	<div class="button_search">
								<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
	                        </div>
							{template:form/search_input,k,$_INPUT['k']}                        
	                    </div>
	                    </form>
	                </div>
	
	                
	                
	                <div class="list_first clear"  id="list_head">
	                    <span class="left">
	                    	<a class="lb" style="cursor:pointer;"   onclick="hg_switch_order('pic_list');"  title="排序模式切换/ALT+R">
	                    		
	                    	</a>
	                    </span>
                        <span class="right" style="width: 850px">
                        	<a class="fl" style="width:80px;">标识</a>
                        	<a class="fl" style="width:80px;">默认值</a>
                        	<a class="fl" style="width:120px;">是否启用</a>
                        	<a class="fl" style="width:120px;">自定义图片</a>
                        	<a class="fl" style="width:240px">发布人/时间</a>
                        	<span>                      		
                        		<a class="fl">操作</a>
                        	</span>	
                        </span>
                        <span class="title overflow">
                        	<a class="fl" style="width:150px;">分类名称</a>
                        </span>                        	                                 
	                </div>	                
	                
	                <form method="post" action="" name="pos_listform">
		                <ul class="list" id="pic_list">
							{if $list}
			       			    {foreach $list as $k => $v}		       			    
			                      {template:unit/active_pic}
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
	    		    <!-- 天气信息模板 -->
	    		    <div class="edit_show">
						<span class="edit_m" id="arrow_show"></span>
					<div id="edit_show"></div>
	    		    
	    		   
				</div>
			</div>
		</div>
	<div id="infotip"  class="ordertip"></div>
</body>
{template:foot}
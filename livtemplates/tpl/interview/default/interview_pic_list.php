<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:interview}
{js:interview_pic}
{js:vod_opration}
{js:jquery-ui-1.8.16.custom.min}
{css:jquery.lightbox-0.5}
{js:jquery.lightbox-0.5}
<script type="text/javascript">
	$(function(){
		tablesort('pic_list','files','order_id');
		$("#pic_list").sortable('disable');
	});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
	
	
	<div id="hg_page_menu" class="head_op_program">
		<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&interview_id={$_INPUT['interview_id']}&kid={$_INPUT['mid']}"" class="button_6" style="font-weight:bold;">文件上传</a>
	</div>
	
	
	<div class="content clear">
 		<div class="f">
			<div class="right v_list_show">
			
				<div class="search_a" id="info_list_search">
	                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
	                    <div class="right_1">
	                    	{code}
							$pic_css = array(
								'class' => 'transcoding down_list',
								'show' => 'pic_item',
								'width' => 120,	
								'state' => 0,
								'para'=> array('interview_id'=>$_INPUT['interview_id']),
							);
							$default_audit = -1;
							$_configs['file_type'][$default_audit] = '所有文件类型';
							$_INPUT['interview_pic'] = $_INPUT['interview_pic'] ? $_INPUT['interview_pic'] : -1;
							{/code}						
							{template:form/search_source,interview_pic,$_INPUT['interview_pic'],$_configs['file_type'],$pic_css}
							{if  $interview_pic_list}
							<input type="hidden" name="cover_pic" value="{$interview_pic_list[0]['cover_pic']}" id="cover_pic"/>
							{/if}
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
	                    <span class="left" style="width:120px">
	                    	<a class="lb" style="cursor:pointer;"   onclick="hg_switch_order('pic_list');"  title="排序模式切换/ALT+R">
	                    		<em></em>
	                    		<a class="fl">索引图</a>
	                    	</a>
	                    </span>
                        <span class="right"  style="width:800px">
                        	<a class="fl">类型</a>
                        	<a class="fl">文件大小</a>
                        	<a class="fl">显示位置</a>
                        	<a class="fl" style="width:120px">上传人</a>
                        	<a class="fl">状态</a>
                        	<a class="fl">控制</a>
                        	<span>
                        		<a class="fl">操作</a>
                        	</span>	
                        </span>
                        <span class="title overflow">
                        	 <a>素材名称</a>
                        </span>                        	                                 
	                </div>	                
	                


	                <form method="post" action="" name="pos_listform">
		                <ul class="list" id="pic_list">
							{if $interview_pic_list}
			       			    {foreach $interview_pic_list as $k => $v}		       			    
			                      {template:unit/interview_pic_list}
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
	<div id="infotip"  class="ordertip"></div>
</body>
{template:foot}
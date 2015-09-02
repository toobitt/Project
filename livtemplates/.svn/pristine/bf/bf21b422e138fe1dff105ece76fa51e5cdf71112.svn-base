<?php 
/* $Id:list.php 9870 2012-06-29 04:58:13Z wangleyuan $ */
?>
{template:head}
{code}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
if(!$_INPUT['column_id'])
{
	$_INPUT['column_id'] = 0;
}
{/code}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{css:common/common_list}
{js:tree/animate}
<script type="text/javascript">
function hg_del_back(id)
{
	var ids=id.split(",");
	for(var i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).remove();
	}	
}
$(function () {
	tablesort('vodlist','article','order_id');
	$("#vodlist").sortable('disable');
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
</div>
<div class="content clear">
	<div class="f">		
	
 		<!-- 新增分类面板 开始-->
 		 <div id="add_auth"  class="single_upload">
 		 	<h2><span class="b" onclick="hg_closeAuth();"></span><span id="auth_title">推送</span></h2>
 		 	<div id="add_auth_tpl" class="add_collect_form">
 		 	   <div class="collect_form_top info  clear" id="auth_form"></div>
 		 	</div>
		 </div>
 	    <!-- 新增分类面板结束-->
 		 
 	
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="right_1">
						{code}
							$attr_date = array(
								'class' => 'down_list data_time',
								'show' => 'app_show',
								'width' => 104,/*列表宽度*/		
								'state' => 1, /*0--正常数据选择列表，1--日期选择*/
								'issub' => 1,
							);
							$attr_column = array(
								'class' => 'down_list data_time',
								'show'  => 'column_show',
								'width' => 104, /*列表宽度*/
								'state' => 0,
							);
							$list[0]['column'][0] = '所有栏目';
						{/code}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						{template:form/search_source,column_id,$_INPUT['column_id'],$list[0]['column'],$attr_column}
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
						{template:form/search_input,key,$_INPUT['key']}                    
				   </div>
	            </form>
			</div>

			<div class="list_first clear"  id="list_head">
                  	<span class="left">
                    	<a class="common-list-paixu" onclick="hg_switch_order('vodlist');" style="margin:10px 0 0 15px;display:inline-block;"></a>
                    </span>
                    <span class="right" style="width:450px;">                 
						 <a class="fl" style="width:90px;">栏目</a>
						 <a class="fl" style="width:90px;">类型</a>
						 <a class="fl" style="width:120px;">发布时间</a>
						 <a class="fl" style="width:100px;">删除</a>
                   </span>
                   <a class="title" style="margin-left:53px;margin-top:8px;">标题</a>  
           </div>

           <form method="post" action="" name="listform">
               <ul class="list" id="vodlist">
					 {if is_array($list[0]['content']) &&!empty($list[0]['content']) && count($list[0]['content'])>0}
						{foreach $list[0]['content'] as $k => $v}		
		                     {template:unit/recommondlist}
		                {/foreach}
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  			{/if}
					<li style="height:0px;padding:0;" class="clear"></li>
               </ul>
	           <div class="bottom clear">
	               <div class="left" style="width:400px;">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" style="margin-left:5px;"/>
					   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'delete','删除',1,'id','','ajax');" name="bataudit" >删除</a>           	               
				   </div>
                   {$pagelink}
			  </div>
           </form>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
function hg_back_audit(obj)
{
   var obj = eval("("+obj+")");
   var status_text = "";
   if(obj.status == 1)
   {
	   status_text = '已审核';
   }
   else if(obj.status == 0)
   {
	   status_text = '未审核';    
   }
   for(var i = 0;i<obj.id.length;i++)
   {
	   $('#statusLabelOf'+obj.id[i]).text(status_text);
   }	
}
</script>
{template:foot}
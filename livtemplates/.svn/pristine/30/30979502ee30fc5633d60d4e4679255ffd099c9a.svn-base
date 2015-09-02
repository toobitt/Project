<?php
/* $Id:list.php 9870 2012-06-29 04:58:13Z wangleyuan $ */
?>
{template:head}
{code}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
{/code}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:tree/animate}
{js:action_ts}
{js:team_apply}
<style>
#add_auth{width:710px;}
</style>
<script type="text/javascript">
function hg_del_back(id)
{
	var ids=id.split(",");
	for(var i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).remove();
	}
}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>

</div>
<div class="content clear">
	<div class="f">
	 		<!-- 新增分类面板 开始-->
	 		 <div id="add_auth"  class="single_upload">
	 		 	<h2><span class="b" onclick="hg_closeAuth();"></span><span id="auth_title">推送</span></h2>
	 		 	<iframe style="width:100%;height:100%;"></iframe>
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
							);
						{/code}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
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
                    	<a class="lb"></a>
                    </span>
                    <span class="right" style="width:560px;">
                   		 <a class="fl" style="width:220px;">活动描述</a>
                   		 <!--<a class="fl" style="width:70px;">编辑</a>-->
                   		 <a class="fl" style="width:70px;">删除</a>
                         <a class="fl" style="width:30px;">编辑</a>
                   		 <a class="fl" style="width:40px;">推送至</a>
						 <a class="fl" style="width:120px;">添加人/添加时间</a>
                   </span>
                   <a class="title" style="margin-left:53px;margin-top:8px;">活动名称</a>
           </div>

           <form method="post" action="" name="listform">
               <ul class="list" id="vodlist">
					{if is_array($activity_list['data']) && !empty($activity_list['data']) && count($activity_list['data']) > 0}
						{foreach $activity_list['data'] as $k => $v}
		                     {template:unit/activitylist}
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
						   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'op','删除',1,'action_id','&state=0','ajax');" name="batdelete">删除</a>
                           <!--
		               <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'audit','审核',1,'id','&audit=1','ajax');" name="bataudit" >审核</a>
		               <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'audit','打回',1,'id','&audit=0','ajax');"  name="bataudit" >打回</a>	      -->
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
   else if(obj.status == 2)
   {
	   status_text = '被打回';
   }
   for(var i = 0;i<obj.id.length;i++)
   {
	   $('#statusLabelOf'+obj.id[i]).text(status_text);
   }
}

function hg_back_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$("#r_"+ids[i]).remove();
	 }
}
</script>
{template:foot}
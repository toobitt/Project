<?php
/* $Id:list.php 9870 2012-06-29 04:58:13Z wangleyuan $ */
?>
{template:head}
{code}
if(!$_INPUT['source'])
{
	$_INPUT['source'] = 'team';
}
if(!$_INPUT['state'])
{
	$_INPUT['state'] = 2;
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
						{template:form/search_source,state,$_INPUT['state'],$_configs['state'],$attr_date}
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
                   		 <a class="fl" style="width:220px;">描述</a>
                   		 <a class="fl" style="width:100px;">状态</a>
						 <a class="fl" style="width:100px;">添加人/添加时间</a>
                   </span>
                   <a class="title" style="margin-left:53px;margin-top:8px;">{$_configs['source'][$_INPUT['source']]}名称</a>
           </div>

           <form method="post" action="" name="listform">
               <ul class="list" id="vodlist">
               {if $report_list}
					{foreach $report_list as $k=>$v}
						{template:unit/reportlist}
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
						   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'delete','删除',1,'rid','','ajax');" name="batdelete">删除举报</a>
                   </div>
                   {$pagelink}
			  </div>
           </form>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
function hg_back_audit(id)
{
	var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$("#r_"+ids[i]).remove();
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
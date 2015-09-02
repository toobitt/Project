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
{template:list/common_list}
<script type="text/javascript">
function put_queue(id)
{
	  $.get("./run.php?mid=" + gMid + "&a=user_put_queue&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,{id:id},
				function (data)	{
				var obj = eval("("+data+")");
				if(obj[0].error)
				{
					$("#queue_"+id).html(obj[0].msg);
					setTimeout("back('获取路况',"+obj[0].id+")",5000);
				}
				else
				{
					$("#queue_" + id).html("正在获取...");
				}
		  });	
}

function back(str,id)
{
	$("#queue_"+id).html(str);
}
</script>

<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
	<a href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6" style="font-weight:bold;">新增用户</a>
</div>
<div class="content clear">
	<div class="f">			
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
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
						{template:form/search_input,key,$_INPUT['key']}                        
					  </div>
	             </form>
			</div>

           <form method="post" action="" name="listform" style="position: relative;">
               <ul class="common-list">
                    <li class="common-list-head clear">
                        <div class="common-list-left">
                        	<div class="common-list-item" style="width:35px;"></div>
                        </div>
                        <div class="common-list-right">
                        	<div class="common-list-item">编辑</div>
                            <div class="common-list-item">删除</div>
                            <div class="common-list-item">微博类型</div>
                            <div class="common-list-item">操作</div>
                            <div class="common-list-item wd100" style="width:100px;">添加人/添加时间</div>
                        </div>
                        <div class="common-list-biaoti">
					        <div class="common-list-item">用户昵称</div>
				        </div>
                    </li>
                </ul>	           
               <ul class="common-list" id="vodlist">
					 {if is_array($list) && count($list)>0}
						{foreach $list as $k => $v}		
		                     {template:unit/userlist}
		                {/foreach}
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  			{/if}
					<li style="height:0px;padding:0;" class="clear"></li>
               </ul>
               
				<ul class="common-list public-list">
					<li class="common-list-bottom clear">
						<div class="common-list-left">
							<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
							 <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax', 'hg_change_status');" name="audit">审核</a>
			         		 <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=0', 'ajax', 'hg_change_status');" name="back">打回</a>
			         		 <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
						</div>
						{$pagelink}
					</li>
				</ul>
           </form>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
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
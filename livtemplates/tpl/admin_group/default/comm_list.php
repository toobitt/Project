<?php 
/* $Id: comm_list.php 9157 2012-05-04 10:07:45Z wangleyuan $ */
?>
{template:head}
{code}
if(!$_INPUT['state'])
{
	$_INPUT['state']=1;
}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
{/code}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:mblog}
<script type="text/javascript">
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
</div>
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="search_a" id="info_list_search">
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="right_1">
						{code}
							$attr_status=array(
								'class' => 'colonm down_list data_time',
								'show' => 'status_show',
								'width' =>104,
								'state' =>0,
							);

							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);

							$attr_group = array(
								'class' => 'colonm down_list data_time',
								'show' => 'group_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
						{/code}
						{template:form/search_source,state,$_INPUT['state'],$_configs['group_status'],$attr_status}
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
						{template:form/search_input,k,$_INPUT['k']}                        
					  </div>
	             </form>
			</div>

			<div class="list_first clear"  id="list_head">
                  <span class="left">
                    	<a class="lb" style="cursor:pointer;"  onclick="hg_switch_order('newlist');"  title="排序模式切换/ALT+R"><em></em></a>
                    </span>
                    <span class="right" style="width:550px;">      
						 <a class="fl" style="width:250px;">原文</a>
						 <a class="fl" style="width:25px;">操作</a>
						 <a class="fl">状态</a>
						 <a class="tjr">添加人</a>
                   </span>
                   <a class="title">评论内容</a>
           </div>

              <form method="post" action="" name="listform">
               		 <ul class="list" id="newlist">
					  	{if is_array($list) && count($list)>0}
							{foreach $list as $k => $v}		
		                      {template:unit/commlist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
                	</ul>
	            <div class="bottom clear">
	               <div class="left" style="width:400px;">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
	                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'post_id', '', 'ajax','');"    name="bataudit" >审核</a>
	                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'back','打回',1,'post_id', '','ajax','');"    name="batback" >打回</a>
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'delete','删除',1,'post_id','','ajax');"    name="batdelete">删除</a>
				   </div>
                   {$pagelink}
				</div>
              </form>
		</div>
</div>
</div>
</body>
<script type="text/javascript">
function hg_fabu(id)
{
	$("#fabu_"+id).show();
}

function hg_back_fabu(id)
{
	$("#fabu_"+id).hide();
}

function hg_call_news_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).remove();
	}
}
function hg_audit_call(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	{
		$('#text_'+ids[i]).text('已审核');
	}
}
function hg_back_call(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$('#text_'+id).text('待审核');
	 }
}


$(document).ready(function(){
 	$(".list li span.right a.cz").hover(function(){
		$(this).parent().children("span.rr_1").hide();
		$(this).parent().children("span.rr_2").show();

	},function(){
		$(this).parent().children("span.rr_1").show();
		$(this).parent().children("span.rr_2").hide();

	});
	$("span.rr_2").hover(function(){
		$(this).show();
		$(this).parent().children("span.rr_1").hide();
		$(this).parent().children("a.cz").children("em.b4").css('background-position','0 -16px');
	},function(){
		$(this).hide();
		$(this).parent().children("span.rr_1").show();
		$(this).parent().children("a.cz").children("em.b4").css('background-position','0 0');
	});
});
</script>
{template:foot}
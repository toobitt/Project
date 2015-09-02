<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{template:head}
{code}
/*hg_pre($list);*/
if(!$_INPUT['state'])
{
	$_INPUT['state']=1;
}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
if(!$_INPUT['group_type'])
{
	$_INPUT['group_type']=-1;
}
{/code}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
<script type="text/javascript">
    var id = '{$id}';
    var frame_type = "{$_INPUT['_type']}";
    var frame_sort = "{$_INPUT['_id']}";

	$(document).ready(function(){
		if(id)
		{
		   hg_show_opration_info(id,frame_type,frame_sort);
		}
	});
/*发布*/
var gStateId = '';
function hg_statePublish(id)
{
	gStateId = id;
	var url = './run.php?mid=' + gMid + '&a=recommend&id=' + id;
	hg_ajax_post(url);
}
/*发布回调函数*/
function hg_show_pubhtml(html)
{
	$('#vodpub_body').html(html);
	hg_vodpub_show(gStateId);
}
function hg_vodpub_hide(id)
{
	$('#vod_fb').hide();
	$('#vodpub').animate({'top':'-440px'});
}
function hg_vodpub_show(id)
{
	var tops=t=0;
	t = $('#r_'+ id).position().top;
	if(t >= 230)
	{
		tops = t-140 ;
	}
	$('#vodpub').animate({'top':tops},
		function(){
			$('#vod_fb').css({'display':'block','top':t+11,'left':'98px'});
		}
	);
}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
	<a href="?mid={$relate_module_id}&a=show{$_ext_link}" class="button_6" style="font-weight:bold;">查看地主申请</a>
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
							
							foreach($group_type as $k => $v)
							{
								$group[$v['typeid']] = $v['type_name'];
							}
							$default=$_INPUT['group_type'] ? $_INPUT['group_type'] : -1;
							$group['-1']='全部分类';
						{/code}
						{template:form/search_source,state,$_INPUT['state'],$_configs['group_status'],$attr_status}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						{template:form/search_source,group_type,$default,$group,$attr_group}
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
                    </span>
                    <span class="right" style="width:600px;">
						 <a class="fl" style="width:30px;">操作</a>
	                     <a class="fl" style="width:80px;">帖子</a>	        
	                     <a class="fl">话题</a>
						 <a class="fl">居民</a>
						 <a class="fl">类型</a>
						 <a class="fl">状态</a>
						 <a class="tjr">添加人</a>
                   </span>
                   <a class="title" style="text-align:left;">地盘名称</a>
           </div>

              <form method="post" action="" name="listform">
               		 <ul class="list" id="newlist">
					  	{if is_array($list) && count($list)>0}
							{foreach $list as $k => $v}		
		                      {template:unit/grouplist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(newlist,1);</script>
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
                	</ul>
	            <div class="bottom clear">
	               <div class="left" style="width:400px;">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
	                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'group_id', '', 'ajax','');"    name="bataudit" >审核</a>
	                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'back',  '打回',1,'group_id', '','ajax','');"    name="batback" >打回</a>
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'delete','删除',1,'group_id','','ajax');"    name="batdelete">删除</a>
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
<!--发布模板-->
<span class="vod_fb" id="vod_fb"></span>
<div id="vodpub" class="vodpub lightbox">
	<div class="lightbox_top">
		<span class="lightbox_top_left"></span>
		<span class="lightbox_top_right"></span>
		<span class="lightbox_top_middle"></span>
	</div>
	<div class="lightbox_middle">
		<span onclick="hg_vodpub_hide();" style="position:absolute;right:25px;top:25px;z-index:1000;background:url('{$RESOURCE_URL}close.gif') no-repeat;width:14px;height:14px;cursor:pointer;display:block;"></span>
		<div id="vodpub_body" class="text" style="max-height:500px;padding:10px 10px 0;">
		
		</div>
	</div>
	<div class="lightbox_bottom">
		<span class="lightbox_bottom_left"></span>
		<span class="lightbox_bottom_right"></span>
		<span class="lightbox_bottom_middle"></span>
	</div>				
</div>
<!--发布-->
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
		$('#text_'+ids[i]).text('待审核');
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
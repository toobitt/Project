<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}

if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
if(!$_INPUT['message_status'])
{
	$_INPUT['message_status'] = 0;
}
{/code}
<script type="text/javascript">
/*审核*/
var message_id = "";
function hg_message_status(id,val)
{
	state = val;
	message_id = id;
	var url = "./run.php?mid=" + gMid + "&a=message_state&id=" + id + "&state=" + state +"";
	hg_ajax_post(url,"","",'hg_message_state');
}
function hg_message_state(obj)
{	
	if(!obj)
	{
		alert('操作失败！');
	}	
}
</script>
{js:message}
{css:vod_style}
{css:mark_style}

<script type="text/javascript">
$(function(){
	tablesort('vodlist','message_reply','order_id');
	$("#vodlist").sortable('disable');
});
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
{if ($_INPUT['type'])}
<div id="hg_page_menu" class="head_op_program">
	<a class="blue mr10"  href="?mid={$relate_module_id}&a=reply&id={$_INPUT['contentid']}&infrm=1" target="formwin">
		<span class="left"></span>
		<span class="middle"><em class="add">添加回复</em></span>
		<span class="right"></span>
	</a>
</div>
{/if}
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search" style="display:none;">
                  <span class="serach-btn"></span>
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="select-search">
						{code}
							$attr_status = array(
								'class' => 'transcoding down_list',
								'show' => 'transcoding_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'colonm_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
						{/code}
						{template:form/search_source,message_status,$_INPUT['message_status'],$_configs['message_status'],$attr_status}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						<input type="hidden" name="a" value="show" />
						<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
						<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
						<input type="hidden" name="_id" value="{$_INPUT['_id']}" />
						<input type="hidden" name="_type" value="{$_INPUT['_type']}" />
                    </div>
                    <div class="text-search">
                    	<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
                        </div>
						{template:form/search_input,k,$_INPUT['k']}                        
                    </div>
                    </form>
                </div>
				<div class="list_first clear"  id="list_head">
                    	<span class="left">
                    		 <div class="common-paixu common-list-item"><a class="common-list-paixu" style="cursor:pointer;"  onclick="hg_switch_order('vodlist');" title="排序模式切换/ALT+R"></a><em></em></div>
                    	</span>
                        <span class="right" style="width:781px;">
	                        <a class="fl" style="width:220px">留言内容</a> 
	                        <a class="fl">所属分组</a>
	                        <a class="fl">留言对象</a>
							<a class="fl">状态</a>
							<a class="tjr" style="width:120px">添加人/时间</a>
							<a class="fl">编辑</a>
	                        <a class="fl">删除</a>
                        </span>
                        <a class="title">回复内容</a>
                </div>
                <form method="post" action="" name="listform">
               		 <ul class="list" id="vodlist">
					  	{if is_array($reply_list) && count($reply_list)>0}
							{foreach $reply_list as $k => $v}		
		                      {template:unit/replylist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
                	</ul>
	            <div class="bottom clear">
	               <div class="left" style="width:400px;">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
	                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'reply_state',  '审核', 1, 'id', '', 'ajax','');"    name="bataudit" >审核</a>
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'back',  '打回', 1, 'id', '', 'ajax','');"    name="bataudit" >打回</a>
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
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
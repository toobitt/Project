<?php 
/* $Id: settings_list.php 9172 2012-05-10 01:09:44Z wangleyuan $ */
?>
{template:head}
{css:vod_style}
<div class="wrap">

<script type="text/javascript">
function hg_screen_delete(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=delete&id=' + id + '&ajax=1';
		hg_request_to(url);
	}
	
}
function hg_screen_call_delete(data)
{
	var obj = new Function("return" + data)();
	if(obj.id)
	{
		$("#plan"+obj.id).slideUp(1000).remove();
	}
}
function hg_screen_state(id,state)
{
	var opt = state ? '开启' : '关闭';
	if(confirm('是否' + opt + '？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=audit&state=' + state + '&id=' + id + '&ajax=1';
		hg_request_to(url);
	}
}

function hg_screen_call_state(data)
{
	var obj = new Function("return" + data)();
	if(obj.id)
	{
		var class_name = obj.state ? 'a' : 'b';
		var title = obj.state ? '已启动' : '未启动';
		$("#a_" + obj.id).attr('class',class_name).attr('title',title).attr('onclick','hg_screen_state(' + obj.id + ',' + (obj.state ? 0 : 1) + ');');
	}
}
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" >
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6"><strong>新增</strong></a>
</div>
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="list_first clear"  id="list_head">
                  <span class="left"></span>
                    <span class="right" style="width:740px;">
						 <a class="zt" style="width:240px;">标识</a>
						 <a class="fl" style="width:240px;">启用/关闭</a>
						 <a class="tjr">操作</a>
                   </span>
                   <a class="title">名称</a>  
           </div>

            <form method="post" action="" name="listform">
               		 <ul class="list" id="vodlist">
					  	{if is_array($list) && count($list)>0}
							{foreach $list as $k => $v}		
							 <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
									<span class="left">
										<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
									</span>
										<span class="right"  style="width:740px; position:relative;" >
											<span class="rr_1" id="rr_1_{$v['id']}">
												<a class="zt" style="width:240px;"><em><span>{$v['mark']}</span></em></a>
												<a class="zt" style="width:240px;"><em><span class="channel_start" style="cursor:pointer;">
													{if !$v['state']}
														<span  title="未启动" id="a_{$v['id']}" class="b" onclick="hg_screen_state({$v['id']},1);"></span>
													{else}
													<span  title="已启动" id="a_{$v['id']}" class="a" onclick="hg_screen_state({$v['id']},0);"></span>
													{/if}
												</span></em></a>
												<span>
													<a title="" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}">编辑</a>&nbsp;&nbsp;
													<a href="javascript:void(0);" onclick="hg_screen_delete({$v['id']});">删除</a>
												</span>
											</span>
									   </span>
									<span class="title overflow"  style="cursor:pointer;"><a>
										<span id="title_{$v['id']}">{$v['name']}</span></a>
									</span>
							</li>
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
						<li style="height:0px;padding:0;" class="clear"></li>
                	</ul>
				    <div class="bottom clear">
						{$pagelink}
					</div>
			</form>
		</div>
</div>
</div>
</body>
{template:foot}
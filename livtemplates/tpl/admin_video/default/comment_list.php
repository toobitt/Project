<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{code}

if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
if(!$_INPUT['comment_status'])
{
	$_INPUT['comment_status'] = 0;
}
{/code}


<script type="text/javascript">
/*流状态控制*/
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
{css:vod_style}
{css:mark_style}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
                <div class="search_a" id="info_list_search">
                  <form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
                    <div class="right_1">
						{code}

							$attr_status = array(
								'class' => 'transcoding down_list',
								'show' => 'status_show',
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
						{template:form/search_source,comment_status,$_INPUT['comment_status'],$_configs['comment_status'],$attr_status}
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
                    		<a class="lb" style="cursor:pointer;"  onclick="hg_switch_order('vodlist');"  title="排序模式切换/ALT+R"><em></em></a>
                    	</span>
                        <span class="right" style="width:817px;">
	                        <a class="zt" style="width:320px">视频名称</a>
							<a class="zt" style="width:100px">状态</a>
							<a class="tjr" style="width:160px">添加人/时间</a>
							<a class="fl">编辑</a>
	                        <a class="fl">删除</a>
                        </span>
                        <a class="title">留言内容</a>
                </div>
                <form method="post" action="" name="listform">
               		 <ul class="list" id="vodlist">
					  	{if is_array($comment_list) && count($comment_list)>0}
							{foreach $comment_list as $k => $v}		
		                      {template:unit/commentlist}
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
	                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'audit',  '审核', 1, 'id', '', 'ajax','');"    name="bataudit" >审核</a>
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');"    name="batdelete">删除</a>
				   </div>
	               {$pagelink}
	            </div>	
    		</form>
 	</div>
</div>
</div>
</body>
{template:foot}
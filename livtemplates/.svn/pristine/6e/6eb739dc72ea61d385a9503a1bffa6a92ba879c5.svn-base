<?php 
/* $Id:group_list.php 9870 2012-06-29 04:58:13Z wangleyuan $ */
?>
{template:head}
{code}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
{/code}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:tree/animate}
<script type="text/javascript">
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
<a href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6" style="font-weight:bold;">添加微博类型</a>
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
                    	<a class="lb"><em></em></a>
                    </span>
                    <span class="right" style="width:580px;">
                    	 <a class="fl">编辑</a><a class="fl">删除</a>
                    	 <!--<a class="fl" style="width:150px;">token过期时间</a>-->      
	                     <a class="fl" style="width:100px;">状态</a>
						 <a class="fl" style="width:120px;">添加人/添加时间</a>
                   </span>
                   <a class="title" style="margin-left: 10px;margin-top: 8px;">微博类型</a>  
           </div>

              <form method="post" action="" name="listform">
               		 <ul class="list" id="vodlist">
					  	{if is_array($list) && count($list)>0}
							{foreach $list as $k => $v}		
		                                       <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');" cname="{$v['cid']}"    corderid="{$v['order_id']}">
                    	<span class="left">
							<a class="lb" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');"   name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
						</span>
	                    <span class="right"  style="width:580px;">
								<a class="fb" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
								<a class="fb" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}" style="width:107px;"><em class="b3" ></em></a>
								<!--<a class="fl"  style="width:150px;" {if $v['href']}href="{$v['href']}"{/if} target="_blank"><em style="width:150px;">{$v['expired_time']}</em></a>-->
								<a class="fl"  style="width:100px;"><em>{if $v['status']}已审核{else}未审核{/if}</em></a>
								<a class="tjr"  style="width:120px;"><em>{$v['user_name']}</em><span>{$v['create_time']}</span></a>
					  </span>
					  <span class="title overflow"  style="cursor:pointer;">
							<a title="{$v['name']}"><span id="title_{$v['id']}">{$v['name']}</span></a>
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
	               <div class="left" style="width:400px;">
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" />
	                   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'delete','删除',1,'id','','ajax');"    name="batdelete">删除</a>
				   </div>
                   {$pagelink}
				</div>
              </form>

			  <div class="edit_show" style="">
			  <span class="edit_m" id="arrow_show"></span>
			  <div id="edit_show"></div>
			  </div>
		</div>
</div>
</div>
</body>

{template:foot}
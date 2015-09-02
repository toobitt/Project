<?php 
/* $Id: recycle_list.php 14327 2012-11-06 02:54:21Z wangleyuan $ */
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
{css:common/common_list}
{css:recycle_list}
{js:common/common_list}
<script type="text/javascript">


    var id = '{$id}';
    var frame_type = "{$_INPUT['_type']}";
    var frame_sort = "{$_INPUT['_id']}";

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
            <form method="post" action="" name="listform">
                     <!-- 标题 -->
                   <ul class="common-list" id="list_head">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="recycle-paixu common-list-item"></div>
                            </div>
                            <div class="common-list-right">
                                <div class="recycle-sc common-list-item open-close">删除</div>
                                <div class="recycle-hy common-list-item open-close">还原</div>
                                <div class="recycle-ssyy common-list-item open-close">所属应用</div>
                                <div class="recycle-scr common-list-item open-close">删除人</div>
                                <div class="recycle-scsj common-list-item open-close">删除时间</div>
                                <div class="recycle-scip common-list-item open-close">删除IP</div>
                            </div>
                            <div class="common-list-biaoti ">
						        <div class="common-list-item open-close recycle-biaoti">标题</div>
					        </div>
                        </li>
                    </ul>
               		 <ul class="common-list" id="vodlist">
					  	{if is_array($recycle_list) && count($recycle_list)>0}
							{foreach $recycle_list as $k => $v}		
		                      {template:unit/recyclelist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  				{/if}
                	</ul>
	            <ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
	                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
					   <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'recover_recycle','还原',1,'id', '','ajax');"    name="batrecover" >还原</a>
				       <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'delete','删除',1,'id','','ajax');"    name="batdelete">删除</a>
				   </div>
                   {$pagelink}
				</li>
		       </ul>
              </form>

			  <div class="edit_show" style="">
			  <span class="edit_m" id="arrow_show"></span>
			  <div id="edit_show"></div>
			  </div>
		</div>
</div>
</div>
</body>
<script type="text/javascript">
function hg_call_recycle_del(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	{
		$("#r_"+ids[i]).remove();
	}
}
function hg_call_recycle_recover(id)
{
	 var ids=id.split(",");
	 for(var i=0;i<ids.length;i++)
	 {
		$("#r_"+ids[i]).remove();
	 }
}
</script>
{template:foot}
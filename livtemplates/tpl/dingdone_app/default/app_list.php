<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{code}
$app_list = $list[0]['info'];
$app_client = $list[0]['client'];
{/code}
{template:head}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{css:common/common_list}
{css:group_list}
{js:common/common_list}
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
</script>
<style type="text/css">
.columnList th,.columnList td {padding:5px; text-align:center; border-bottom:1px solid #EEE;}
.columnList tfoot td {border:0;}
.columnBtn {cursor:pointer;}
#auth_title {font-size:18px;}
.columnForm td {padding:5px;}
.columnForm h3 {font-size:16px; font-weight:bold; margin-left:5px; margin-bottom:10px;}
</style>
{code}
function getVersionName($version)
{
    $version = intval($version);
    $arr = array();
    for ($i = strlen($version); $i--;) {
    	$arr[$i] = substr($version, $i, 1);
    }
    ksort($arr);
    return implode('.', $arr);
}
{/code}
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}></div>
<div class="content clear">
	<div class="f">
		<!-- 新增分类面板 开始-->
 		 <div id="add_auth" class="single_upload">
 		 	<h2><span class="b" onclick="hg_closeAuth();"></span><span id="auth_title">推送</span></h2>
 		 	<div id="add_auth_tpl" class="add_collect_form">
 		 	   <div class="collect_form_top info  clear" id="auth_form"></div>
 		 	</div>
		 </div>
 	    <!-- 新增分类面板结束-->
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="search_a" id="info_list_search">
			    <span class="serach-btn"></span>
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="select-search">
						{code}
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'date_show',
								'width' => 104,/*列表宽度*/
								'state' => 1,/*0--正常数据选择列表，1--日期选择*/
							);
							$attr_client = array(
								'class' => 'colonm down_list data_time',
								'show' => 'client_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							if (!$_INPUT['date_search']) $_INPUT['date_search'] = 1;
							if (!$_INPUT['c_id']) $_INPUT['c_id'] = 0;
							
							$client_info = array();
							$client_info['0'] = '所有打包客户端';
							foreach ($app_client as $v)
							{
								$client_info[$v['id']] = $v['name'];
							}
						{/code}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						{template:form/search_source,c_id,$_INPUT['c_id'],$client_info,$attr_client}
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
              <form method="post" action="" name="listform">
                    <!-- 标题 -->
                    <ul class="common-list">
                        <li class="common-list-head clear">
                            <div class="common-list-left">
                                <div class="group-paixu common-list-item"><a class="common-list-paixu" style="cursor:pointer;"  {if !$list['colname']}onclick="hg_switch_order('vodlist');"{/if}  title="排序模式切换/ALT+R"></a></div>
                            </div>
                            <div class="common-list-right">
                            	<div class="group-cz common-list-item open-close" style="padding-right:5px;">状态</div>
                            	<div class="group-tz common-list-item open-close" style="padding-right:5px;">操作</div>
                                <div class="group-tjr common-list-item open-close">创建时间</div>
                            </div>
                            <div class="common-list-biaoti">
						        <div class="common-list-item open-close group-title">APP名称</div>
					        </div>
                        </li>
                    </ul>
               		<ul class="common-list" id="newlist">
					  	{if is_array($app_list) && count($app_list)>0}
							{foreach $app_list as $k => $v}	
		                      {template:unit/applist}
		                    {/foreach}
						{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(newlist,1);</script>
		  				{/if}
                	</ul>
	            <ul class="common-list">
	              <li class="common-list-bottom clear">
	                <div class="common-list-left">
	                   <input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
	                   <!--
	                   <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '', 'ajax', '');" name="bataudit">审核</a>
	                   <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'back', '打回', 1, 'id', '', 'ajax', '');" name="batback">打回</a>
	                   -->
				       <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="batdelete">删除</a>
				   </div>
                   {$pagelink}
                  </li>
				</ul>
              </form>
			  <div class="edit_show">
			  <span class="edit_m" id="arrow_show"></span>
			  <div id="edit_show"></div>
			  </div>
		</div>
</div>
</div>
</body>
<script type="text/javascript">
function hg_delete_call(id)
{
	var ids = id.split(',');
	for (var i = 0; i < ids.length; i++)
	{
		$('#r_' + ids[i]).remove();
	}
}

function hg_audit_call(id)
{
	var ids = id.split(',');
	for (var i = 0; i < ids.length; i++)
	{
		$('#text_'+ids[i]).text('已审核');
	}
}

function hg_back_call(id)
{
	var ids = id.split(',');
	for (var i = 0; i < ids.length; i++)
	{
		$('#text_'+ids[i]).text('未通过');
	}
}

function hg_recover_call()
{
	location.reload();
}
</script>
{template:foot}
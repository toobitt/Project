<?php 
/* $Id:list.php 9870 2012-06-29 04:58:13Z wangleyuan $ */
?>
{template:head}
{code}

$audit_label = '启用';
$back_value = '0';
$back_label ='未启用';
if(!class_exists('publishsys'))
{
    include_once(ROOT_DIR . 'lib/class/publishsys.class.php');
    $pub = new publishsys();
}
//获取所有站点
$hg_sites = $pub->getallsites();

if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
if(!$_INPUT['site_id'])
{
	$_INPUT['site_id'] = 0;
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
</script>
<style sytle="text/html">
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
	<a target="nodeFrame" type="button" class="button_6" style="font-weight:bold;" onclick="hg_add_style()">添加套系</a>
</div>
<div class="content clear">
	<div class="f">			
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="common-list-search" id="info_list_search">		
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="select-search">
						{code}
							$attr_site = array(
								'class'  => 'colonm down_list date_time',
								'show'   => 'app_show',
								'width'  => 104,
								'state'  => 0,
							);
							$attr_date = array(
								'class' => 'colonm down_list data_time',
								'show' => 'site_show',
								'width' => 104,/*列表宽度*/		
								'state' => 1, /*0--正常数据选择列表，1--日期选择*/
							);
							$hg_sites[0] = '所有站点';
						{/code}
						{template:form/search_source,site_id,$_INPUT['site_id'],$hg_sites,$attr_site}
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
						{template:form/search_input,key,$_INPUT['key']}                        
					  </div>
	             </form>
			</div>
            <form method="post" action="" name="listform" style="position: relative;">
               <ul class="common-list">
                    <li class="common-list-head public-list-head clear">
                        <div class="common-list-left">
                            <div class="common-list-item" style="width:35px;"></div>
                        </div>
                        <div class="common-list-right">
                            <div class="common-list-item open-close wd50">编辑</div>
                            <div class="common-list-item open-close wd50">删除</div>
                            <div class="common-list-item open-close wd80">标识</div>
                            <div class="common-list-item open-close wd60">所属站点</div>
                            <div class="common-list-item open-close wd60" >状态</div>
                            <div class="common-list-item open-close wd60" >当前使用</div>
                            <div class="common-list-item wd100">添加人/添加时间</div>
                        </div>
                        <div class="common-list-biaoti ">
					        <div class="common-list-item open-close">名称</div>
				        </div>
                    </li>
                </ul>
           		<ul class="common-list" id="circlelist">
				  	{if is_array($list) && count($list)>0}
						{foreach $list as $k => $v}		
	                      {template:unit/stylelist}
	                    {/foreach}
					{else}
					<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;margin:0 10px">没有您要找的内容！</p>
					<script>hg_error_html(vodlist,1);</script>
	  				{/if}
                </ul>
	            <ul class="common-list">
				     <li class="common-list-bottom clear">
					   <div class="common-list-left">
	                   		<input type="checkbox"  name="checkall" value="infolist" title="全选" rowtag="LI" />
	                   		<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'audit','启用',1,'id','&audit=1','ajax','hg_change_status');"  name="bataudit">启用</a>
	                   		<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'audit','关闭',1,'id','&audit=0','ajax','hg_change_status');"  name="bataudit">关闭</a>
		               		<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'delete','删除',1,'id','','ajax');"    name="batdelete">删除</a>
				   	   </div>
                  	 {$pagelink}
					</li>
				</ul>
				<div class="edit_show">
					<span class="edit_m" id="arrow_show" style="position:absolute;"></span>
					<div id="edit_show"></div>
				</div>
             </form>
		</div>
</div>
</div>
</body>
<script type="text/javascript">
function hg_add_style()
{
	if({$_INPUT['site_id']} == 0)
	{
		top.jAlert('请选择站点!','错误提示');
		return false;
	}
	window.location.href = "./run.php?mid={$_INPUT['mid']}&a=form&site_id={$_INPUT['site_id']}&infrm=1";
}
</script>
{template:foot}
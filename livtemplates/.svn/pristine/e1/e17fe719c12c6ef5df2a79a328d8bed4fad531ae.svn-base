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
{css:ad_style}
{css:vod_style}
{css:mark_style}
{css:edit_video_list}
{js:vod_opration}
{template:list/common_list}
{css:circle_list}
<script type="text/javascript">
	var id = '{$id}';
	var frame_type = "{$_INPUT['_type']}";
	var frame_sort = "{$_INPUT['_id']}";
	$(document).ready(function(){
		if(id)
		{
		   hg_show_opration_info(id,frame_type,frame_sort);
		}
		tablesort('circlelist','circle','order_id');
		$("#vodlist").sortable('option', 'cancel', '.common-list-head');
		$("#vodlist").sortable('disable');
	});
	function hg_check_auth()
	{
		if($("#auth-info").css("display") == 'none')
		{
			$("#auth-info").show();
			$.get("./run.php?mid=" + gMid + "&a=show_plat_auth&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,{key:''},
					function (data)	{
					$("#auth-info").html(data);
			 });	
		}
		else
		{
			var str='<div id="auth-loading"></div>';
			$("#auth-info").html(str);
			$("#auth-info").hide();	
		}
	}
	function hg_request_auth(platid,type)
	{
		$.get("./run.php?mid="+gMid+"&a=request_auth&platid="+platid+"&type="+type+"&admin_id=" + gAdmin.admin_id +"&admin_pass="+gAdmin.admin_pass,{key:''},
					function(data) {
					var obj = eval('('+data+')');
					var url = obj[0].url;
					//location.href = "http://mcp.dev.hogesoft.com:233/access_plat/index.php?oauth_url=https%3A%2F%2Fopen.t.qq.com%2Fcgi-bin%2Foauth2%2Fauthorize%3Fclient_id%3D801168487%26response_type%3Dcode%26redirect_uri%3Dhttp%253A%252F%252Fmcp.dev.hogesoft.com%253A233%252Faccess_plat%25";
					window.open(url);
				});
	}
</script>
<style sytle="text/html">
#auth-info{position:absolute;right:0px;top:0px;border:1px solid #DDDDDD;border-top:none;background:#EFEFEF;width:400px;min-height:200px;float:left;z-index:4;display:none;padding:10px 10px;}
#auth-info li{margin-bottom:10px;}
#auth-loading{background:url("{$RESOURCE_URL}loading.gif") left no-repeat;width:50px;height:50px;}
</style>
<div id="auth-info">
	<div id="auth-loading"></div>
</div>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
	<a class="blue mr10" id="auth-check" onclick="hg_check_auth();">
		<span class="left"></span>
		<span class="middle"><em class="add">查看授权</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="content clear">
	<div class="f">			
          <div class="right v_list_show">
		  	<div id="infotip"  class="ordertip"></div>
	        <div id="getimgtip"  class="ordertip"></div>
			<div class="search_a" id="info_list_search">
			    <span class="serach-btn"></span>
				<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
					<div class="select-search">
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
					  <div class="text-search">
						<div class="button_search">
							<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
						</div>
						{template:form/search_input,key,$_INPUT['key']}                        
					  </div>
                        <div class="custom-search">
                            {code}
                                $attr_creater = array(
                                    'class' => 'custom-item',
                                    'state' =>2, /*0--正常数据选择列表，1--日期选择, 2--input自动检索*/
                                    'place' =>'添加人',
                                    'search_btn' => 1, /*添加搜索按钮*/
                                );
                            {/code}
                            {template:form/search_input,uname,$_INPUT['uname'],1,$attr_creater}
                        </div>					  
	             </form>
			</div>
            <form method="post" action="" name="listform" style="position: relative;">
               <ul class="common-list public-list-head">
                    <li class="common-list-head clear">
                        <div class="common-list-left">
                            <div class="common-list-item circle-paixu"></div>
                        </div>
                        <div class="common-list-right">
                        	<div class="common-list-item open-close wd80">所属圈子</div>
                            <div class="common-list-item open-close wd60">状态</div>
                            <div class="common-list-item open-close wd80" >微博类型</div>
                            <div class="common-list-item wd150">添加人/添加时间</div>
                        </div>
                        <div class="common-list-biaoti ">
					        <div class="common-list-item open-close">微博内容</div>
				        </div>
                    </li>
                </ul>
           		<ul class="common-list public-list" id="circlelist">
				  	{if is_array($list) && count($list)>0}
						{foreach $list as $k => $v}		
	                      {template:unit/list}
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
		               		<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'audit','还原',1,'id','&audit=1','ajax', 'hg_change_status');"    name="bataudit" >审核</a>
		               		<a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'audit','打回',1,'id','&audit=0','ajax', 'hg_change_status');"    name="bataudit" >打回</a>	                	             
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
<script type="text/javascript">
	function hg_call_sort_del(id)
	{
		 var ids=id.split(",");
		 for(var i=0;i<ids.length;i++)
		 {
			$("#r_"+ids[i]).remove();
		 }
		 if($('#edit_show'))
		 {
			hg_close_opration_info();
		 }
	}
</script>
</body>
{template:foot}
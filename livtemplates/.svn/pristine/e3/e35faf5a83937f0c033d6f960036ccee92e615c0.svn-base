<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{code}
$app_list = $list[0]['info'];
$app_client = $list[0]['client'];
{/code}
{template:head/head_jquerynew}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{css:common/common_list}
{css:group_list}
{css:bootstrap/3.3.0/bootstrap.min}

{js:bootstrap/3.3.0/bootstrap.min}
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{js:common/common_list}
{js:2013/ajaxload_new}
{js:app_plant/app_list}
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
#auth_title {font-size:18px; }
.columnForm td {padding:5px;}
.columnForm h3 {font-size:16px; font-weight:bold; margin-left:5px; margin-bottom:10px;}
.versionName{ font-size:14px; font-weight:0}
.versionNum{ font-size:12px; font-weight:0}
.linkClass{ font-size:14px; color:#63b9e9}
.noLinkClass{font-size:14px}
.wd87{ width:87px!important}
.wd83{ width:83px!important}
.app-store-pop .modal-dialog{position: fixed;top:0;right:10px;}
#popup_container #popup_content{box-sizing:content-box;width:auto;}
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
	<div class="f" style=' min-height:440px!'>
		<!-- 新增分类面板 开始-->
 		 <div id="add_auth" class="single_upload" style='height:430px!important;min-height:440px!important;'>
 		 	<h2><span class="b" onclick="hg_closeAuth();"></span><span id="auth_title">推送</span></h2>
 		 	<div id="add_auth_tpl" class="add_collect_form">
 		 	   <div class="collect_form_top  clear" id="auth_form"></div>
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
							$attr_shelves = array(
								'class' => 'colonm down_list data_time',
								'show' => 'shelves_show',
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
							
							/*按照打包时间排序*/
							$attr_package_time = array(
								'class' => 'colonm down_list data_time',
								'show' => 'package_time_show',
								'width' => 104,/*列表宽度*/
								'state' => 0,/*0--正常数据选择列表，1--日期选择*/
							);
							
							$package_time_conf = array(
								0 => '选择打包时间',
								1 => '按打包时间降序',
								2 => '按打包时间升序',
							);
							
							if(!isset($_INPUT['by_package_time']))
                            {
                                $_INPUT['by_package_time'] = 0;
                            }
                            
                            if(!isset($_INPUT['is_shelves']))
                            {
                                $_INPUT['is_shelves'] = 0;
                            }
                            
						{/code}
						{template:form/search_source,date_search,$_INPUT['date_search'],$_configs['date_search'],$attr_date}
						
						<!--  {template:form/search_source,c_id,$_INPUT['c_id'],$client_info,$attr_client}-->
					 	{template:form/search_source,by_package_time,$_INPUT['by_package_time'],$package_time_conf,$attr_package_time}
					 	
						{template:form/search_source,is_shelves,$_INPUT['is_shelves'],$_configs['shelves_search'],$attr_shelves}
						
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
                            	<div class="common-list-item wd80">测试版</div>
                            	<div class="common-list-item wd80">正式版</div>
                            	<div class="common-list-item wd50">上架</div>
                            	<div class="common-list-item wd150">打包时间</div>
                                <div class="common-list-item wd150">创建时间</div>
                            	<div class="common-list-item wd150">操作</div>
                            </div>
                            <div class="common-list-biaoti group-bt">
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

<div class="modal fade app-store-pop">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data">
					<input name="appstore_address" class="form-control appstore-address" placeholder="请填写上架地址"/>
					<input name="a" type="hidden" value="shelves"/>
					<input name="id" type="hidden" value=""/>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default close-pop" data-dismiss="modal">取消</button>
				<button type="button" class="btn btn-primary save-pop">保存</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade app-code-pop">
	<div class="modal-dialog">
		<div class="modal-content">
			
		</div>
	</div>
</div>

<style>
.app-code-pop .modal-dialog{width:300px;}
.app-code-pop .code-pic{width:200px;height:200px;margin:0 auto;}
.app-code-pop .code-pic img{max-width:100%;max-height:100%;}
.app-code-pop .download-boxes{width:200px;margin:0 auto;}
.app-code-pop .download-boxes p{margin:10px 0 0;}
</style>
<script type="text/x-jquery-tmpl" id="app-code-pop-tpl">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	<h4 class="modal-title">{{= appName}}
		<small>{{if type=='debug'}}测试版{{else type=='release'}}正式版{{/if}}</small>
	</h4>
</div>
<div class="modal-body">
	<div class="code-pic">
		<img src="{{= qrcodeUrl}}?id={{= uuid}}&type={{= type}}">
	</div>
	<div class="row download-boxes">
		<div class="download-box ios col-md-6">
			<p>IOS:</p>
			{{if iosUrl}}
			<button type="button" class="btn btn-info" _add="{{= iosUrl}}">
				<span class="glyphicon glyphicon-cloud-download"></span> 下载
			</button>
			{{else}}
			打包失败
			{{/if}}
		</div>
		<div class="download-box ios col-md-6">
			<p>安卓:</p>
			{{if androidUrl}}
			<button type="button" class="btn btn-success" _add="{{= androidUrl}}">
				<span class="glyphicon glyphicon-cloud-download"></span> 下载
			</button>
			{{else}}
			打包失败
			{{/if}}
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-default" data-dismiss="modal">ok</button>
</div>
</script>

<script>
$(function(){
	var appListData = {code} echo $app_list ? json_encode($app_list) : '[]';{/code};
	console.log( appListData );
	$.myconfig = $.extend({}, $.myconfig||{}, {
		qrcode_url : "{$_configs['qrcode_url']}",
	});
});
</script>
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
function hg_qrcode(app_name, url, uuid, version,versionNum,ipaUrl,apkUrl)
{
	version = version.split(',');
	if(version){
		for(var i = 0, l = version.length; i < l; i++){
			var versionName='';
			if(version[i] == 'release'){
				versionName = '正式版';
			}
			else if(version[i] == 'debug'){
				versionName = '测试版';
			}
		}	
	}
	if(ipaUrl!=""){
		var ipaLink = '<a class="linkClass" onclick="window.open('+ "'" + ipaUrl + "'" +')">下载</a>';
//		var ipaLink = "<a class='linkClass' href='" + ipaUrl + "' target='_blank' >下载IPA</a>";
// 		var ipaLink = "<a class='linkClass' href='http://www.baidu.com' target='_blank' >IPA</a>";
	}else{
		var ipaLink = "<span class='noLinkClass'>打包失败</span>";
	}

	if(apkUrl!=""){
		var apkLink = '<a class="linkClass" onclick="window.open('+ "'" + apkUrl + "'" +')">下载</a>';
// 		var apkLink = "<a class='linkClass' href='" + apkUrl + "' target='_blank'>下载APK</a>";
	}else{
		var apkLink = "<span class='noLinkClass'>打包失败</span>";
	}
	$('#auth_title').html(app_name + " " + "<span class='versionName'>" + versionName + "</span> " + "<span class='versionNum'>V"+versionNum +"</span>");
	if ($('#add_auth').css('display') == 'none')
	{
		var data = '<div style="text-align:center; padding:0 0 0 0;">';
		if (version)
		{
			for (var i = 0, l = version.length; i < l; i++) {
				var img_url = url+'?id='+uuid+'&type='+version[i];
				data += '<div style="margin-top:20px;">';
				data += '<img src="'+img_url+'" style="vertical-align:middle; width:329px;height:329px" /></div>';
			}
		}
		data += '</div>';
		var downLoadDiv = "<div style='vertical-align:middle; width:286px;height:30px;padding:0;margin-left:145px'>";
		downLoadDiv += "<div style='height:30px;width:140px; float:left; text-align:center;font-size:14px'>iOS:"+ipaLink+"</div>";
		downLoadDiv += "<div style='height:30px;width:140px; float:right; text-align:center;font-size:14px'>Android:"+apkLink+"</div>";
		downLoadDiv += "</div>";	
		data +=downLoadDiv ;
		$('#auth_form').html(data);
	    $('#add_auth').css({'display':'block'});
	    $('#add_auth').animate({'right':'50%','margin-right':'-300px'},'normal',function(){
		    hg_resize_nodeFrame();
	    });
	}
	else
	{
		hg_closeAuth();
	}
}		  				
//关闭面板
function hg_closeAuth()
{
	$('#log_box').html();
	$('#add_auth').animate({'right':'120%'},'normal',function(){
		$('#add_auth').css({'display':'none','right':'0'});
		hg_resize_nodeFrame();
	});
}
</script>
{template:foot}
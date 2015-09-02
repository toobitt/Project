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
{js:jquery-ui-1.8.16.custom.min}
{js:vod_opration}
{css:vod_style}
{css:edit_video_list}
{css:mark_style}
{js:tree/animate}
{js:team_apply}
<script type="text/javascript">
var swfu;
function swf_upload() {
	var settings = {
		flash_url : RESOURCE_URL+"swfupload/swfupload.swf",
		upload_url: "./run.php?mid=" + gMid + "&a=upload&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,	
		post_params: {"access_token": gToken},
		file_size_limit : "100 MB",
		file_types : "*.jpg;*.gif;*.png;*.jpeg;*.bmp;",
		file_types_description : "选择图标",
		file_upload_limit : 0,  //配置上传个数
		file_queue_limit : 1,
		custom_settings : {
			progressTarget : "",
			cancelButtonId : ""
		},
		debug: false,

		// Button settings
		button_image_url: RESOURCE_URL+"news_from_cpu.png",
		button_width: "100",
		button_height: "75",
		button_placeholder_id: "circle_upload",
		button_text: '',
		button_text_style: ".theFont { font-size: 12px;color:#FFFFFF;line-height:24px;display:inline-block;text-align:center;height:24px; }",
		button_text_left_padding: 0,
		button_text_top_padding: 4,
		
		//file_queued_handler : fileQueued,
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_start_handler : uploadStart,
		upload_progress_handler : uploadProgress,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		//queue_complete_handler : queueComplete,	
	};
	swfu = new SWFUpload(settings);
 };
</script>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
</div>
<div class="content clear">
	<div class="f">		
	
 		<!-- 新增分类面板 开始-->
 		 <div id="add_auth"  class="single_upload">
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
                    	<a class="lb"></a>
                    </span>
                    <span class="right" style="width:750px;">                 
						 <a class="fl" style="width:280px;">申请说明</a>
						 <a class="fl" style="width:120px;">申请小组类型</a>
						 <a class="fl" style="width:90px;">联系人</a>
						 <a class="fl" style="width:120px;">联系方式</a>
						 <a class="fl" style="width:90px;">创建小组</a>
                   </span>
                   <a class="title" style="margin-left:53px;margin-top:8px;">申请单位</a>  
           </div>

           <form method="post" action="" name="listform">
               <ul class="list" id="vodlist">
               {code}
//             	print_r($list);
               {/code}
					 {if is_array($list) && !empty($list) && count($list)>0}
						{foreach $list as $k => $v}		
		                     {template:unit/teamapplylist}
		                {/foreach}
					{else}
						<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
						<script>hg_error_html(vodlist,1);</script>
		  			{/if}
					<li style="height:0px;padding:0;" class="clear"></li>
               </ul>
	           <div class="bottom clear">
	               <div class="left" style="width:400px;">
	               		<!--
	                   <input type="checkbox"  name="checkall" id="checkall" value="infolist" title="全选" rowtag="LI" style="margin-left:5px;"/>
		               <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'audit','审核',1,'team_id','','ajax');" name="bataudit" >审核</a>
		               <a style="cursor:pointer;"  onclick="return hg_ajax_batchpost(this,'back','打回',1,'team_id','','ajax');"  name="batback" >打回</a>	-->           	               
				   </div>
                   {$pagelink}
			  </div>
           </form>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
function hg_back_audit(obj)
{
   var obj = eval("("+obj+")");
   var status_text = "";
   if(obj.status == 1)
   {
	   status_text = '已审核';
   }
   else if(obj.status == 0)
   {
	   status_text = '未审核';    
   }
   for(var i = 0;i<obj.id.length;i++)
   {
	   $('#statusLabelOf'+obj.id[i]).text(status_text);
   }	
}

function hg_apply_back(id)
{
	hg_closeAuth();
	var id = eval('('+id+')');
	$("#r_"+id).remove();
}
</script>
{template:foot}
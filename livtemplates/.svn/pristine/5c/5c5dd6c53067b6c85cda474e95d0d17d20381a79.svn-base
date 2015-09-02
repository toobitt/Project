{template:head}
{code}
	if($id)
	{
		$optext="更新";
		$ac="update";
	}
	else
	{
		$optext="新增";
		$ac="create";
	}
{/code}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;	
		{/code}
	{/foreach}
{/if}
{css:ad_style}
{js:ad}

{js:wb_circle}
<script type="text/javascript">
var swfu;
window.onload = function() {
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
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}分类</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">分类名称：</span>
			<div style="display:inline;float:left;margin-right:10px;">
				<input type="text" value='{$name}' name='name' class="title" style="width:200px;"  disabled = "disabled">
			</div>
			<div class="form-title-option clearfix" style="display:inline;">
					<span class="form-title-color"></span>
			</div>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">分类图标：</span>
		<div id="log_box" style="float:left;margin-top:10px;">
		{code}
			$img = '';
			if($log_img)
			{
				$img = $log_img['host'] . $log_img['dir'] .'100x75/'. $log_img['filepath'] . $log_img['filename'];
			}
		{/code}
		{if $img}
			<img src="{$img}" alt = "" width="100" height="75" />
			<input type="hidden" name="log" value='{$log}' />
		{/if}
		</div>
		<div id="circle_upload" style="float: left;"></div>
	</div>
</li>
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}分类" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>

{template:foot}
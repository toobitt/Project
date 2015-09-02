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
{css:column_node}
{js:column_node}
{js:swf_upload}
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
		file_queue_limit : 0,
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
		upload_success_handler : uploadSuccessTemplateStyle,
		upload_complete_handler : uploadComplete,
		//queue_complete_handler : queueComplete,	
	};
	swfu = new SWFUpload(settings);
 };
</script>
<style type="text/css">
.material_log{float:left;position:relative;margin:0 5px 5px 0;}
.material_log span{position:absolute;top:0px;right:0px;background:black;width:15px;height:15px;color:#FFFFFF;text-align:center;display:none;cursor:pointer;}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}套系</h2>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">套系名称：</span><input type="text" value='{$title}' name='title' class="title">
	</div>
</li>
{if !$isdefault}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">套系标示：</span><input type="text" value="{$mark}" name="mark" class="title"/>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">示意图：</span>
		<div id="log_box" style="float:left;margin-top:10px;">
		{if is_array($pic) && count($pic) > 0}
			{foreach $pic as $k => $v}
				{code}
					$img='';
					if($v)
						$img = $v['host'] . $v['dir'] . '100x75/' . $v['filepath'] . $v['filename'];
				{/code}	
				{if $img}
					<div id="mateiral_{$v['id']}" class="material_log">
						<img src="{$img}" alt="" width="100" height="75" />
						<span class="material_del">X</span>
						<input type="hidden" name="log[]" value="{$pic_json[$k]}"/>
					</div>
				{/if}
			{/foreach}
		{/if}
		</div>
		<div id="circle_upload" style="float: left;"></div>
	</div>
</li>
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">是否启用：</span>
		<label>
			<input type="checkbox" class="n-h" value="1" name="state" {if $state}checked="checked"{/if}/>
			<span>启用</span>
		</lalbel>
	</div>
</li>
{/if}
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}套系" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	add_close_hover = function (els) {
		els.each(function () {
			$(this).hover(
				function(){
					$(this).find("span").fadeIn(100);
				},
				function(){
					$(this).find("span").fadeOut(100);
				}
			);
		});
	}
	add_close_hover($(".material_log"));	
	$('body').on('click', ".material_del", function(){
		$(this).parent().remove();
	});
});
</script>
{template:foot}
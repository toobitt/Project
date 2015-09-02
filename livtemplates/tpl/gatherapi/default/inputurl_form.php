{code}
//hg_pre($hg_attr);exit;
{/code}
{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:teditor}
{js:teditor}
{js:column_node}
{code}
$list = $formdata[0];
$site_id= $_INPUT['site_id'];
$client = $clients[0];

if(count($client))
{
	$flag = 1;
}
$template_styles = $template_style[0];

$css_attr['style'] = 'style="width:100px"';
$re = $list['sort_id']?$list['sort_name']:'请选择分类';
{/code}
{if is_array($formdata)}
	{foreach $formdata[0] as $key => $value}
		{code}
			$$key = $value;	 
		{/code}
	{/foreach}
{/if}
<script type="text/javascript">
function isvalidatefile(obj){
    var style = $("#file_data").val().substring($("#file_data").val().lastIndexOf(".")+1);
	if(style=="jpeg"||style=="jpg"){
		document.getElementById("pic_data").style.display="block";
	}
	else
	{	
		document.getElementById("pic_data").style.display="none";
	}	
}
</script>
<style>
body{height:auto !important;}
</style>
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
.down_list ul, .down_list .ul{background:white;border-bottom: 0;display: block;width:100%;clear: both;top: 24px;left: 0;position: absolute;z-index:110;max-height: 200px;overflow-y:auto;}
.transcoding{z-index:1!important;}
</style>
{css:ad_style}
{css:column_node}

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear" style="padding-bottom:30px;position:relative;">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l {if $_INPUT['id']}form-compare{/if}" id="form-template">
				<h2 class="template-edit-title">{if $_INPUT['id']}编辑接入地址{else}新增接入地址{/if}</h2>
				<div class="template-edit">
						<div class="edit-area">
						<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
							<span  class="title" style="width:80px">接入名称</span>
								<input type="text" value="{$formdata['title']}" name="title"  class="long" style="width:150px"> 
							</div>
						</li>
						 <!-- <li class="i">
							<div class="form_ul_div" >
								<span  class="title">接入地址</span>
								<input type="text" value="" name="urladdress"  class="long" style="width:150px">
							</div>
						</li> -->
						<li class="i">
							<div class="form_ul_div">
								<span class="title" style="width:80px">接入地址</span>
								<textarea name="urladdress" onfocus="textarea_value_onfocus(this,'这里输入描述');" onblur="textarea_value_onblur(this,'这里输入描述');">{$formdata['url']}</textarea>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div" >
								<span  class="title" style="width:80px">间隔时间</span>
								<input type="text" value="{$formdata['mk_time']}" name="mk_time"  class="long" style="width:150px">
								<span>秒</span>
							</div>
						</li>
						{if $_INPUT['id']} 
							<li class="i">
							<div class="form_ul_div" >
								<span  class="title" style="width:80px">下次执行时间</span>
								<input type="text" value="{$formdata['next_time']}" name="next_time"  class="long" style="width:150px">
								<span>秒</span>
							</div>
							</li>
						{/if}
						<li class="i">
							<div class="form_ul_div" >
								<span  class="title" style="width:80px">是否启用</span>
								<input type="checkbox" value="1" name="is_open" {code}if($formdata['is_open']) echo "checked"; {/code} class="long">开启
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div" >
								<span  class="title" style="width:80px">是否自动签发</span>
								<input type="checkbox" value="1" name="auto_publish" {code}if($formdata['auto_publish']) echo "checked"; {/code} class="long">开启
							</div>
				 		</li>
				 		
				 		<li class="i">
							<div class="form_ul_div clearfix" style="float:none;">
								<span  class="title" style="width:80px">签发到栏目</span>
								{code}
									$hg_attr['node_en'] = 'gather';
									$site_id = $column_form[0]['site_id'];
									$hg_attr['expand'] = array('site_id'=>$site_id);
									//hg_pre($column_form);exit;
								{/code}
								{template:unit/class,column_fid,$column_form[0]['fid'],$node_data}
								</div>
				 		</li>		
					</ul>
					
					<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type= "hidden" name ="referto" value= "{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
				<input type="hidden" name="html" value="true" />
				<div style="clear:both"></div>
				<div class="temp-edit-buttons" style="">
					<input type="submit" name="sub" value="{$optext}" class="edit-button submit"/>
					<a class="edit-button cancel" href="./run.php?mid={$_INPUT['mid']}&infrm=1&nav=1">取消</a>
				</div>
				
				
					</div>
					</div>
				
				
				<div class="cover"></div>
			</form>
	<!--  <div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div> -->
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

$(function ($) {
	$("#Filedata1").on('change', function (e) {
		var file = this.files[0];
		var type = file.type;
		var is_img = /image/.test(type);
		
		if ( $('#a_action').val() == 'create' ) {
			if ( !/.+\.(html|htm|zip)$/.exec(file.name) ) {
				alert('文件格式不对');
				this.value = '';
				return;
			}else{
				$('.file-long').val(file.name);
				$('.long').val(file.name);
			}
		} else {
			if ( !/.+\.(html|htm|css|js|swf)$/.exec(file.name) && !is_img ) {
				alert('文件格式不对');
				this.value = '';
			} else {
				if ( /.+\.(html|htm|)$/.exec(file.name) ) {
					$('#a_action').val('update_tem');
					$('.long').val(file.name);
				} else {
					$('#a_action').val('update');
				}
				$('.file-long').val(file.name);
			}
		}
		
	});

	$('.select-file-button').on('click',function(){
		$("#Filedata1").trigger('click');
	});

	$('#form-template').submit(function(){
		if($(this).hasClass('form-compare')){
			$(this).ajaxSubmit({
				dataType: 'html',
				beforeSubmit :function(){
					$('#top-loading').show();
				},
				success :function(data){
					$('.cover').html(data).addClass('cover-show');
					$('#top-loading').hide();
				}
			});
			return false;
		}
		var sort_id = $('#sort_id').val();
		
	});

	$('.cover').on('click','#compare-cancel',function(){
		$(this).closest('.cover').removeClass('cover-show');
	});

	$('.cover').on('click','#compare-ok',function(){
		$('input[name="a"]').val('update');
		$('input[name="upcell"]').appendTo('#form-template');
		$('input[name="updata"]').appendTo('#form-template');
		$('#form-template').removeClass('form-compare').submit();
	});
});
</script>
{template:foot}
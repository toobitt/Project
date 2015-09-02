{code}
$arr_s['file_title'] = '单图片上传';
$arr_s['upload_url'] = $_configs['tuji_api']['protocol'] . $_configs['tuji_api']['host'] . '/' . $_configs['tuji_api']['dir'] . 'tuji.php?a=upload';
$arr_s['file_types'] = $_configs['flash_image_type'];
$arr_s['description'] = 'Single Image Upload';
$arr_s['flagId'] = 'single_imageFlag';
$arr_s['button_mode'] = true;
$arr_s['button_left'] = '800px';
$arr_s['button_top'] = '290px';
$arr_s['padding_left'] = '5';
$arr_s['padding_top'] = '2';
$arr_s['admin_name'] = $_user['user_name'];
$arr_s['admin_id'] = $_user['id'];
$arr_s['upload_type'] = 1;
$params_s = json_encode($arr_s); 
{/code}
<script type="text/javascript">
	var params_s = '{$params_s}';
	
	$(function() {
		top.livUpload.showTemplate(params_s);
	});

	function hg_startImageUpload()
 	{
 		top.livUpload.startUploadFile(true,true);
 	}

	function hg_fixStartImageUpload()
	{
		top.livUpload.startUploadFile(false,true);
	}
 	
	
</script>
<form class="iframe" action="./run.php?mid={$_INPUT['mid']}"  id="single_image_upload_form" name="single_image_upload_form"  method="post" enctype="multipart/form-data">
<div class="bg_middle" > 
	 <div class="info clear" style="padding:10px 0;position:relative;">
	    <div style="float:left;">
	    	<input id="get_contents" name="get_contents" style="height:16px;width:142px;" class="input_words" type="text" autocomplete="off" onfocus="hg_getcollect_video();" onblur="hg_hide_contents();" onkeyup="hg_getcollect_video();">
		</div>
		{code}
				$attr_tuji_sortname = array(
					'class' => 'transcoding down_list',
					'show' => 'tuji_sort_show',
					'width' => 104,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					'is_sub' => 1
				);
				
				$tuji_sort_default = -1;
				$tuji_sortarray[$tuji_sort_default] = '图集类别';
				foreach($tuji_sortname[0] as $k => $v)
				{
					$tuji_sortarray[$v['id']] = $v['sort_name'];
				}
		{/code}
		<div id="tuji_sort_content"  class="localurl" style="display:none;">
			{template:form/search_source,tuji_sort_name,$tuji_sort_default,$tuji_sortarray,$attr_tuji_sortname}
		</div>
		<div id="single_imageFlag" class="localurl"  style="float:left;width:94px;height:20px;"></div>
		<div id="image_localurl"   class="localurl"  style="float:left;"></div>
		<div id="content_list" style="position:absolute;width:145px;border:1px solid gray;left:0px;top:34px;max-height:360px;overflow:hidden;overflow-y:scroll;display:none;background:white;"></div>
    </div>
	<div  style="width:584px;height:148px;float:left;padding:10px 0;" class="info clear">
		<input  type="text" name="single_title"  class="info-title info-input-left t_c_b"  style="width:578px;" value="在这里添加标题" onfocus="text_value_onfocus(this,'在这里添加标题');" onblur="text_value_onblur(this,'在这里添加标题');" />
		<textarea rows="2" 	name="single_comment"  class="info-description info-input-left t_c_b"  style="height:96px;width:578px;margin-top:10px;"  onfocus="text_value_onfocus(this,'这里输入描述');" onblur="text_value_onblur(this,'这里输入描述');">这里输入描述</textarea>
	</div>
	<input class="button_6_14" type="button" onclick="hg_fixStartImageUpload();" value="确定并继续添加" />
	<input class="button_6_14" type="button" onclick="hg_startImageUpload();" value="确定" />
</div>
</form>
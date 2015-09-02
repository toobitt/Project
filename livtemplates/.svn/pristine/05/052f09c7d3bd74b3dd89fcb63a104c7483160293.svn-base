{code}
$arr['file_title'] = '图片批量上传';
$arr['upload_url'] = $_configs['tuji_api']['protocol'] . $_configs['tuji_api']['host'] . '/' . $_configs['tuji_api']['dir'] . 'tuji.php?a=upload';
$arr['file_types'] = $_configs['flash_image_type'];
$arr['description'] = 'More Image Upload';
$arr['flagId'] = 'more_imageFlag';
$arr['button_left'] = '900px';
$arr['button_top'] = '125px';
$arr['padding_left'] = '5';
$arr['padding_top'] = '2';
$arr['admin_name'] = $_user['user_name'];
$arr['admin_id'] = $_user['id'];
$arr['upload_type'] = 1;
$params = json_encode($arr);
{/code}
<script type="text/javascript">
    var params = '{$params}';
    $(function(){
    	top.livUpload.showTemplate(params);
    });
</script>
<form class="iframe" action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data"   id="more_image_upload_form" name="more_image_upload_form" >
	<div class="bg_middle">
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
			<div id="more_imageFlag"  class="localurl"  style="float:left;"></div>
			<div id="content_list" style="position:absolute;width:145px;border:1px solid gray;left:0px;top:34px;max-height:360px;overflow:hidden;overflow-y:scroll;display:none;background:white;"></div>
		</div>
		<div class="more_upload">
			<div id="imagesinfo" ></div>
			<div class="uploadStatus_content"  id="uploadStatus_content"   style="display:none;">
				<div class="upload_input">
					<input id="imageUploadstart"   type="button"  value="开始上传"  class="button_4"  onclick="hg_uploadMoreImages();"  />
					<input id="imagecancelUpload"  type="button"  value="全部取消" class="button_4"  onclick="hg_removeAllImagesQueue();" />
				</div>
				<span id="uploadStatus"></span>
			</div>
		</div>
	</div>      
</form>





















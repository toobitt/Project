{code}
$image_resource = RESOURCE_URL;
$video_type = $_configs['video_type'];

$HTTP_HOST = $_SERVER['HTTP_HOST'];
$port = explode(':', $HTTP_HOST);
if ($port[1])
{
	$port = ':' . $port[1];
}
else
{
	$port = '';
}
$arr_s['file_title'] = '单视频上传';
$arr_s['upload_url'] = $_configs['App_mediaserver']['protocol'] . $_configs['App_mediaserver']['host'] . $port . '/' . $_configs['App_mediaserver']['dir'] . 'admin/create.php';
$arr_s['file_types'] = $_configs['flash_video_type'];
$arr_s['description'] = 'Single Video Upload';
$arr_s['flagId'] = 'singleFlag';
$arr_s['button_mode'] = true;
$arr_s['button_left'] = '800px';
$arr_s['button_top'] = '290px';
$arr_s['padding_left'] = '5';
$arr_s['padding_top'] = '2';
$arr_s['admin_name'] = $_user['user_name'];
$arr_s['admin_id'] = $_user['id'];
$arr_s['token'] = $_user['token'];
$arr_s['mid'] = $_INPUT['mid'];
$arr_s['upload_type'] = 0;
$params_s = json_encode($arr_s); 

{/code}
<script type="text/javascript">

    var params_s = '{$params_s}';

	$(function() {
		top.livUpload.showTemplate(params_s);
	});


 	function hg_start_upload()
 	{
 	 	if(!hg_panduan_video())
 	 	{
 	 	 	return;
 	 	}
 		top.livUpload.startUploadFile(true);

 	}

 	function hg_fix_upload()
 	{
 		if(!hg_panduan_video())
 	 	{
 	 	 	return;
 	 	}
 		top.livUpload.startUploadFile();
 	}


</script>

<form class="iframe" action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data" name="single_video_form" id="single_video_form"  onsubmit="return single_video_submit();">
<div class="bg_middle"  id="vod_single_video_form">
            <div class="info clear">
               <div style="float:left;">
               {code}
					$item_sort = array(
						'class' => 'down_list',
						'show' => 'sort_show',
						'width' => 100,	
						'state' => 0, 
						'is_sub'=>1,
					);
					$single_default = -1;
					$vod_sorts[$single_default] = '选择分类';
					foreach($vod_sort[0] as $k =>$v)
					{
						$vod_sorts[$v['id']] = $v['name'];
					}
					
				{/code}
				{template:form/search_source,vod_sort_id,$single_default,$vod_sorts,$item_sort}
				</div>
				<div id="singleFlag"     class="localurl"  style="float:left;width:94px;height:20px;"></div>
				<div id="video_localurl" class="localurl"  style="float:left;"></div>
				<!--<input type="file"  id="upload_video"  name="Filedata"  onchange="hg_show_localurl(this);"/>-->
                <!--<a id="volume" href="javascript:void(0);" onclick="hg_switch_upload();" id="switch_upload"><span class="mark-bg"></span>切换到批量模式</a>-->
            </div>
			{code}
				$hg_attr['multiple'] = 1;
			{/code}
              {template:unit/vod_form,_vod} 
              <div class="submit clear">
              	<input type="button" class="fix" value="确定并继续添加"  onclick="hg_fix_upload();" />
                <input type="button" class="fix" value="确定"  id="single_video"  name="single_video"  onclick="hg_start_upload();" />
              </div>
            </div>
			  <input type="hidden" value="{$video_type}" name="video_type" id="video_type" />
			  <input type="hidden" value="single_upload" name="a" />
			  <input type="hidden" value="columnid_vod"  name="colname_vod"  id="colname_vod" />
			  <input type="hidden" value="{$$primary_key}" name="{$primary_key}" />
			  <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
			  <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>




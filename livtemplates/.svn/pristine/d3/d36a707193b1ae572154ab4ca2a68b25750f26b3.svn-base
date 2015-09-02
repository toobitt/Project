{js:vod_video_edit}
{code}
$upload_url = $_configs['App_mediaserver']['protocol'] . $_configs['App_mediaserver']['host'] . '/' . $_configs['App_mediaserver']['dir'] . 'admin/create.php';
$arr['file_title'] = '视频批量上传';
$arr['upload_url'] = $upload_url;
$arr['file_types'] = $_configs['flash_video_type'];
$arr['description'] = 'Videos Upload';
$arr['flagId'] = 'moreFlag';
$arr['button_left'] = '900px';
$arr['button_top'] = '125px';
$arr['padding_left'] = '5';
$arr['padding_top'] = '2';
$arr['admin_name'] = $_user['user_name'];
$arr['admin_id'] = $_user['id'];
$arr['token'] = $_user['token'];
$arr['mid'] = $_INPUT['mid'];
$arr['upload_type'] = 0;
$params = json_encode($arr);
{/code}
<script type="text/javascript">
    var params = '{$params}';
    $(function(){
    	top.livUpload.showTemplate(params);
    });

    function hg_uploadMoreVideos()
    {
        if(top.livUpload.checkQueue())
        {
        	top.livUpload.startUploadFile(true);
        }
        else
        {
            alert('请选择视频文件再上传');
        }
    }

    function hg_removeAllMoreVideos()
    {
       /*清除掉可能在多视频过程中添加的视频，但是此时还没有点击确定*/
       if(top.gMoreFileIds)
       {
    	   	for(var i=0;i<top.gMoreFileIds.length;i++)
    	   	{
    	   		top.livUpload.SWF.cancelUpload(top.gMoreFileIds[i]);
    	   	}
       }
       hg_removeAllQueue();
    }

    
</script>
<form class="iframe" action="./run.php?mid={$_INPUT['mid']}"  method="post" enctype="multipart/form-data">
<div class="bg_middle" >
<div class="info clear">
                    <div style="float:left;">
	               {code}
						$item_msort = array(
							'class' 	=> 'down_list',
							'show' 		=> 'msort_show',
							'width' 	=> 100,	
							'state' 	=> 0, 
							'is_sub'	=> 1,
						);
						$more_default = -1;
						$vod_msorts[$more_default] = '选择分类';
						foreach($vod_msort[0] as $k =>$v)
						{
							$vod_msorts[$v['id']] = $v['name'];
						}
						$vcr_type_style = array(
							'class' => 'down_list',
							'show' => 'vcr_type_show',
							'width' => 100,	
							'state' => 0, 
							'is_sub'=>1,
						);
						$default_vcr_type = -1;
					{/code}
					{template:form/search_source,vod_sort_ids,$more_default,$vod_msorts,$item_msort}
					</div>
					<div style="float:left;margin-left:5px;">
					{template:form/search_source,vcr_type,$default_vcr_type,$_configs['vcr_type'],$vcr_type_style}
					</div>					
	<div id="videos_localurl" class="localurl"  style="float:left;"></div>
	<div id="moreFlag"        class="localurl"  style="float:left;"></div>
</div>
<div class="more_upload">
<div id="videoinfo" ></div>
<div class="uploadStatus_content"  id="uploadStatus_content"   style="display:none;">
	<div class="upload_input">
		<input id="Uploadstart"  type="button"  value="开始上传"  class="button_4"  onclick="hg_uploadMoreVideos();"  />
		<input id="cancelUpload"  type="button"  value="全部取消" class="button_4"  onclick="hg_removeAllMoreVideos();" />
	</div>
	<span id="uploadStatus"></span>
</div>
</div>
</div>      
</form>
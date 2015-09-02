<?php

/**
 * 根据视频id取视频内容；
 * */
define('M2O_ROOT_PATH','./');
require(M2O_ROOT_PATH . 'global.php');
$id = intval($_REQUEST['id']);
$colid = intval($_REQUEST['colid']);
if ($id)
{
    if (ISIOS || ISANDROID)
    {
        $curl = new curl($gGlobalConfig['App_livmedia']['host'], $gGlobalConfig['App_livmedia']['dir']);
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('id',$id);
        $ret = $curl->request('vod.php');
        $video = $ret[0];
        $videourl = $video['vodurl'] . $video['video_filename'] . '.m3u8';
        if (ISIOS)
        {
            $videourl = str_replace('.mp4', '.m3u8', $videourl);
        }
        $s = '<div class="m2o-livmedia" align="center"><video src="' . $videourl . '" width="320" height="240" controls="controls"></video></div>';
        $exec = 'try { hg_video_show({"id":"' . $video['id'] . '","title":"' . addslashes($video['title']) . '", "is_audio":"' . $video['is_audio'] . '","duration":"' . addslashes($video['duration']) . '","img_info" : {"host" : "' . $video['img_info']['host'] . '","dir" : "' . $video['img_info']['dir'] . '","filepath" : "' . $video['img_info']['filepath'] . '","filename" : "' . $video['img_info']['filename'] . '"}})} catch (e) {}';
    }
    else
    {
    	$curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
        $curl->setReturnFormat('json');
        $curl->initPostData();
        $curl->addRequestData('a','get_content_by_other');
        $curl->addRequestData('content_fromid',$id);
        $curl->addRequestData('bundle_id','livmedia');
        $curl->addRequestData('module_id','vod');
        $curl->addRequestData('client_type',2);
        $ret = $curl->request('content.php');
        if(isset($ret[0]))
        {
	        $ret = $ret[0];
        }        
         $s = '<div class="m2o-livmedia" align="center"><div style="width:500px;height:405px;background: #000;"><object type="application/x-shockwave-flash" data="http://player.hoge.cn/player.swf" width="100%" height="100%"><param name="movie" value="http://player.hoge.cn/player.swf"/><param name="allowscriptaccess" value="always"><param name="allowFullScreen" value="true"><param name="wmode" value="transparent"><param name="flashvars" value="config=' . CUSTOM_APPID . 'vod.xml&video=' . $id . '&sideBarType=1&autoPlay=1&extendParam=' . urlencode($ret['id'] . '&colid='.$colid) .'"></object></div></div>';
    }
}
?>
document.write('<?php echo $s; ?>');
<?php echo  $exec;?>
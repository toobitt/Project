<?php
/*
 * 从视频里面截图
 * 
 */
class SnapFromVideo
{
	public function __construct()
	{
		if(!class_exists('mediainfo'))
		{
			require_once(CUR_CONF_PATH . 'lib/mediainfo.class.php');
		}
	}
	
	public function snapPicture($source_path,$targerdir)
	{
		$mediainfo = new mediainfo($source_path);
		$data = $mediainfo->getMeidaInfo();
		$_snap_pos = (defined('SNAP_PIC_POS') && intval(SNAP_PIC_POS))?intval(SNAP_PIC_POS):3;
		$snaptime = intval($data['General']['Duration'] / $_snap_pos);
		$snapw = $data['Video']['Width'] ? $data['Video']['Width'] : 480;
		$snaph = $data['Video']['Height'] ? $data['Video']['Height'] : 360;
		$preview = hg_snap($snaptime,$targerdir, $snapw, $snaph,$source_path, 0, 'preview');
		if (!$preview)
		{
			return false;
		}
		if(defined("TARGET_VIDEO_DOMAIN"))
		{
			$server_host = ltrim(TARGET_VIDEO_DOMAIN,'http://');
		}
		else 
		{
			global $gGlobalConfig;
			$server_host = $gGlobalConfig['videouploads']['host'];
		}
		$path = str_replace(TARGET_DIR,'http://' . $server_host . '/',$targerdir.$preview);
		return $path;
	}
}
?>
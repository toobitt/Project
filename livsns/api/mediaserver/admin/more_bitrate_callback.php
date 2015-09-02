<?php
/*
 * 转码完成之后的回调操作(更新视频信息)
 * 
 */
require('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
set_time_limit(0);

class more_bitrate extends adminBase
{
	private $video_info;
	public function __construct()
	{
		global $_INPUT;
		$input = &$_INPUT;
		$this->video_info = json_decode(html_entity_decode($input['data']),1);
		if($input['app_id'])
		{
			$input['appid']  = $input['app_id'];
		}
		else
		{
			$input['appid']  = $this->video_info['app_id'];
		}
		
		if($input['app_key'])
		{
			$input['appkey'] = $input['app_key'];
		}
		else
		{
			$input['appkey'] = $this->video_info['app_key'];
		}
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//上传文件转码完成的回调
	public function bitrate_callback()
	{
		$video_info = $this->video_info;
		if(!$video_info || !$video_info['id'])
		{
			$this->errorOutput(NODATA);
		}
		
		//获取视频id
		$_id = explode('_',$video_info['id']);
		$id = $_id[0];
		
		//如果存在退出码，表明执行多码流失败了
		if($video_info['exit_status'])
		{
			$is_morebitrate = 0;//失败
		}
		else
		{
			$is_morebitrate = 1;//成功
		}
		
		//更新视频is_morebitrate状态
		$sql = " UPDATE "  . DB_PREFIX . "vodinfo SET is_morebitrate = '" .$is_morebitrate. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		
		if(!$is_morebitrate)
		{
			$this->errorOutput(FAILMOREBIT);
		}

		//生成ism与ismv文件
		foreach($video_info['outputFiles'] AS $k => $v)
		{
			$video_names = explode('.',$v['output_filename']);
			//$this->create_ism(TARGET_DIR . $v['targetDir'] . '/',$video_names[0],$v['output_filename']);
			if(!defined('NOT_CREATE_ISMV') || !NOT_CREATE_ISMV)
			{
				$this->create_ism($v['targetDir'] . '/',$video_names[0],$v['output_filename']);
			}
		}
		echo json_encode(array('return' => 'success'));
	}
	
	//用命令生成视频的ism文件
	private function create_ism($target,$video_name,$fromvideo)
	{
		$cmd = MP4SPLIT_CMD . $target . $video_name . '.ismv ' . $target . $fromvideo;
		$cmd .= "\n" . MP4SPLIT_CMD . $target . $video_name . '.ism ' . $target . $video_name . '.ismv';
		exec($cmd);
	}
}

$out = new more_bitrate();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'bitrate_callback';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>
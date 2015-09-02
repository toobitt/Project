<?php
require_once('global.php');
define('MOD_UNIQUEID','transcode_manger');//模块标识
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . 'lib/SnapFromVideo.class.php');
set_time_limit(0);
class snapVideoPic extends adminBase
{
	private $snap;
	private $material;
	public function __construct()
	{
		parent::__construct();
		$this->snap = new SnapFromVideo();
		$this->material = new material();
	}
	
	//截图
	public function run()
	{
		$condition = '';
		if($this->input['start'])
		{
			$condition .= " AND id >= '" . $this->input['start'] . "' ";
		}
		
		if($this->input['end'])
		{
			$condition .= " AND id <= '" . $this->input['end'] . "' ";
		}
		
		if($this->input['ids'])
		{
			$condition .= " AND id IN (" . $this->input['ids'] . ") ";
		}
		
		if($this->input['vod_sort_id'])
		{
			$condition .= " AND vod_sort_id = '" . $this->input['vod_sort_id'] . "' ";
		}
		
		if(!$condition)
		{
			$this->errorOutput('条件不能为空');
		}

		$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if(!file_exists($r['video_base_path'] . $r['video_path'] . $r['video_filename']))
			{
				echo "id:" . $r['id'] . " 文件找不到 <br/>";
				continue;
			}

			if (!hg_mkdir($r['video_base_path'] . $r['video_path'] . 'new_preview/') || !is_writeable($r['video_base_path'] . $r['video_path'] . 'new_preview/'))
			{
				$this->errorOutput(NOWRITE);
			}
		
			$path = $this->snap->snapPicture($r['video_base_path'] . $r['video_path'] . $r['video_filename'] ,$r['video_base_path'] . $r['video_path'] . 'new_preview/');
			if($path)
			{
		    	$img_info = $this->material->localMaterial($path,$r['id']);
		    	if($img_info && $img_info[0])
		    	{
			    	$img_info = $img_info[0];
			    	$image_info = array(
			    		'host' 		=> $img_info['host'],
						'dir' 		=> $img_info['dir'],
						'filepath' 	=> $img_info['filepath'],
						'filename' 	=> $img_info['filename'],
						'imgwidth' 	=> $img_info['imgwidth'],
						'imgheight' => $img_info['imgheight'],
			    	);
			    	$sql = " UPDATE ".DB_PREFIX."vodinfo SET img_info = '".serialize($image_info)."'  WHERE id = '" . $r['id'] . "'";
			    	$this->db->query($sql);
		    	}
			}
			
			echo "id:" . $r['id'] . " 完成 <br/>";
			
		}
		
		echo "完成 <br/>";
	}
}

$out = new snapVideoPic();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'run';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>
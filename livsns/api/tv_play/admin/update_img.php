<?php
define('MOD_UNIQUEID','tv_play');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/tv_play_mode.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
class update_img extends adminBase
{
	private $material;
    public function __construct()
	{
		parent::__construct();
		$this->material = new material();
	}
	
	public function run()
	{
       	if(!$this->input['tv_play_id'])
       	{
       		$this->errorOutput(NOID);
       	}

       	$sql = "SELECT * FROM " .DB_PREFIX. "tv_episode WHERE tv_play_id IN (" .$this->input['tv_play_id']. ")";
       	$q = $this->db->query($sql);
       	while ($r = $this->db->fetch_array($q))
       	{
       		$picPath = $this->get_first_frame($r['video_id']);
       		if(!$picPath)
       		{
       			continue;
       		}

	    	$img_info = $this->material->localMaterial($picPath,$r['id']);
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
		    	$sql = " UPDATE ".DB_PREFIX."tv_episode SET img = '".serialize($image_info)."'  WHERE id = '" . $r['id'] . "'";
		    	$this->db->query($sql);
	    	}
       	}
       	
       	$this->addItem('success');
       	$this->output();
	}
	
 	//获取视频的第一帧图片
    public function get_first_frame($video_id)
    {
        $curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
        $curl->setSubmitType('get');
        $curl->initPostData();
        $curl->addRequestData('id',$video_id);
        $curl->addRequestData('count', 2);
        $curl->addRequestData('stime', 0);
        $ret  = $curl->request('snap.php');
        if($ret && $ret[0] && $ret[0][1] && !strstr($ret[0][1],'_fail'))
        {
        	return $ret[0][1];
        }
        else 
        {
        	return false;
        }
    }
}

$out = new update_img();
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
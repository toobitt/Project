<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
define('MOD_UNIQUEID', 'vod');
class vod_down extends outerReadBase
{
    private $curl;
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public  function show()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
		$arr = $this->db->query_first($sql);
		
		if(!$arr)
		{
			$this->errorOutput('指定视频不存在');
		}
		$filename = iconv('UTF-8', 'GBK', $arr['title']);
		hg_mkdir(UPLOAD_DIR . 'tmp/download/');
		$tempfile = UPLOAD_DIR . 'tmp/download/' . time() . mt_rand(1, 99999) . '.mp4';
		$count = 0;
		while(is_file($tempfile) && $count < 50)
		{
			$tempfile = UPLOAD_DIR . 'tmp/download/' . time() . mt_rand(1, 99999) . '.mp4';
		}
		$cmd = 'curl "http://' . $this->settings['App_mediaserver']['host'] . '/' . $this->settings['App_mediaserver']['dir'] . 'admin/download.php?auth=' . $this->settings['App_mediaserver']['token'] . '&id=' . $arr['id'] . '" > ' . $tempfile;
		//$filesize = strlen($content);
		exec($cmd);
		$filesize = filesize($tempfile);
		header("Content-Type: application/force-download");
		header("Content-Transfer-Encoding: binary\n");
		header('Content-Length: ' . $filesize);
		header("Content-Disposition: attachment; filename=".$filename . '.mp4');
		readfile($tempfile);
		$sql = 'UPDATE ' . DB_PREFIX . 'vodinfo SET downcount=downcount+1 WHERE id = ' . $arr['id'];
		$this->db->query($sql);
		@unlink($tempfile);
	}
	
	public function detail()
	{
		
	}
	
	public function count()
	{
	
	}
	
}

$out = new vod_down();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
	
?>
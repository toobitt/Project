<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','feedback_address');
require(ROOT_DIR . 'global.php');
require(CUR_CONF_PATH.'lib/functions.php');
require_once(CUR_CONF_PATH . 'lib/feedback_mode.php');
$_INPUT['appid'] = APPID;
$_INPUT['appkey'] = APPKEY;
class address extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->mode = new feedback_mode();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show_province()
	{
		$province = $this->mode->show_province();
		$this->addItem($province);
		$this->output();
	}
	
	public function show_city()
	{
		$province_id = intval($this->input['province_id']);
		$city = $this->mode->show_city($province_id);
		$this->addItem($city);
		$this->output();
	}
	
	public function show_area()
	{
		$city_id = intval($this->input['city_id']);
		$area = $this->mode->show_area($city_id);
		$this->addItem($area);
		$this->output();
	}
	
	public function download_winner()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$filename = 'winner_result'.$id.'.txt';
		if(!file_exists(CACHE_DIR.$filename))
		{
			$this->errorOutput('该文件尚未生成');
		}
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=".basename('中奖名单'.TIMENOW.'.txt'));  
		readfile(CACHE_DIR.$filename);
		exit();
	}
	
	public function show(){}
	public function detail(){}
	public function count(){}
}

$out = new address();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show_province';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>
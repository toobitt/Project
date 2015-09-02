<?php
define('MOD_UNIQUEID','update_barimg');
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . 'lib/Barcodegen.class.php');
class update_barcode extends cronBase
{
	private $barcode;
	private $material;
    public function __construct()
	{
		parent::__construct();
		$this->barcode = new Barcodegen();
		$this->material = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function run()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "market_member WHERE 1 AND is_barimg = 0 ORDER BY id DESC LIMIT 0,10";
		$q = $this->db->query($sql);
		$_ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			//如果原来有条形码，就不执行了
			if($r['barcode_img'])
			{
				continue;
			}
			$_ids[] = $r['id'];
			//根据卡号生成条形码图片
			$barcode_img_path = CACHE_DIR . $r['card_number'] . '.png';
			$img_path = 'http://' . $this->settings['App_supermarket']['host'] . '/' .  $this->settings['App_supermarket']['dir'] . 'cache/' . $r['card_number'] . '.png';
			if($img_info = $this->createBarCode($r['card_number'],$barcode_img_path,$img_path))
			{
				$sql = "UPDATE " . DB_PREFIX . "market_member SET barcode_img = '" .addslashes(serialize($img_info)). "',is_barimg = 1 WHERE id = '" .$r['id']. "'";
				$this->db->query($sql);
			}
		}
		
		$this->addItem(implode(',',$_ids));
		$this->output();
	}
	
	//创建条形码并且提交到图片服务器(卡号，条形码图片存放的目录,生成之后图片的访问链接)
	private function createBarCode($card_number = '',$img_dir = '',$img_url = '')
	{
		if(!$img_dir || !$card_number || !$img_url)
		{
			return false;
		}
		
		if(!$this->barcode->create($card_number,$img_dir))
		{
			return false;
		}
		
		$img_info = $this->material->localMaterial($img_url);
		if($img_info && $img_info[0] && $img_info[0]['id'])
		{
			$img_info = $img_info[0];
			$img_info = array(
				'host'     => $img_info['host'],
				'dir'      => $img_info['dir'],
				'filepath' => $img_info['filepath'],
				'filename' => $img_info['filename'],
				'imgwidth' => $img_info['imgwidth'],
				'imgheight'=> $img_info['imgheight'],
			);
			@unlink($img_dir);
			return $img_info;
		}
		return false;
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '更新条形码',	 
			'brief' => '更新条形码',
			'space' => '2',//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}

	protected function verifyToken(){}
}

$out = new update_barcode();
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
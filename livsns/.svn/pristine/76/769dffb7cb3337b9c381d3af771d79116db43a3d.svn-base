<?php
require_once('global.php');
define('MOD_UNIQUEID','index');//ฤฃฟ้ฑ๊สถ
class index extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function show()
	{
		$array = array(
			'diskspace' => disk_free_space(UPLOAD_DIR)	
		);
		$this->addItem($array);
		$this->output();
	}
	
}

$out = new index();
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
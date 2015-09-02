<?php
/** 清理图片编辑痕迹 **/
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
define('MOD_UNIQUEID','photoedit_clear');
set_time_limit(0);
class photoedit_clear extends cronBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '清理图片编辑痕迹',	 
			'brief' => '清理图片编辑痕迹',
			'space' => '30',//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function run()
	{
		$sql = "SELECT id,filename FROM " .DB_PREFIX. "photoedit_list WHERE is_delete = 1";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$data[] = array(
				'id' => $row['id'],
				'filename' => $row['filename'],
			);
		}
		if(!$data || !$data[0])
		{
			exit;
		}
		//删除数据和文件
		for($i=0;$i<count($data);$i++)
		{
			$sql = "DELETE FROM " .DB_PREFIX. "photoedit_list WHERE id = " .$data[$i]['id'];
			$this->db->query($sql);
			@unlink(DATA_DIR . $data[$i]['filename']);
		}
		
		$this->addItem('success');
		$this->output();
	}
	
}

$out = new photoedit_clear();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>
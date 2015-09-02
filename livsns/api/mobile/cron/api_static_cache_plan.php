<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
***************************************************************************/
define('MOD_UNIQUEID','api');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH."global.php");
define('SCRIPT_NAME', 'StaticCache');
class StaticCache extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '更新接口静态缓存',	 
			'brief' => '更新接口静态缓存',
			'space' => '300',			//运行时间间隔，单位秒
			'is_use' => 1,				//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//查询静态缓存开启的接口
		$con = " AND m.static_cache = 1";
		$sql = "SELECT m.file_name,s.sort_dir FROM " . DB_PREFIX . "mobile_deploy m 
				LEFT JOIN ".DB_PREFIX."mobile_sort s
					ON m.sort_id=s.id 
				WHERE 1 " . $con;
		$q = $this->db->query($sql);
		$return = array();
		while($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		//生成文件，支持批量
		if(is_array($return) && count($return))
		{
			include_once(ROOT_PATH.'lib/class/curl.class.php');
			
			$host = $this->settings['App_mobile']['host'];
			$dir = $this->settings['App_mobile']['dir'].'data/';
			
			foreach($return as $k=>$v)
			{
				$req_dir = '';
				$req_dir = $dir . $v['sort_dir'];
				$curl = new curl($host,$req_dir);
				$curl->setSubmitType('post');
				$curl->initPostData();
				
				$data = $curl->request($v['file_name']);
				
				if($data && !$data['ErrorCode'] && !$data['ErrorText'])
				{
					if(is_array($data))
					{
						$data = json_encode($data);
					}
					$file_name = array();
					$file_name = explode('.', $v['file_name']);
					
					//写入文件
					@file_put_contents(DATA_DIR . $v['sort_dir'] . $file_name[0].'.json', $data);
					@file_put_contents(DATA_DIR . $v['sort_dir'] . 'ca_' . $file_name[0].'.json', 'm2oCallback(' . $data . ');');
				}
			}
		}
		$this->addItem('success');
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');
?>
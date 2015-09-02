<?php
require('global.php');
require_once(ROOT_PATH . "lib/class/curl.class.php");
define('MOD_UNIQUEID','vod_export');
set_time_limit(0);
class vod_export extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '视频导出',	 
			'brief' => '视频倒数',
			'space' => '30',//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function run()
	{
		//取默认配置 $config
		$sql = "SELECT id,config FROM " .DB_PREFIX. "export_config WHERE is_default = 1";
		$q = $this->db->query_first($sql);
		$config = $q;
		foreach((array)unserialize($q['config']) as $k => $v)
		{
			$config[$k] = $v;
		}
		if(!$q)
		{
			$this->errorOutput('没有默认配置');
		}
		//获取xml配置模板
		$xmlinfo = $this->getxmlinfo($config['xml_id']);
		$config['xmlinfo'] = serialize($xmlinfo['xml']);
		$config['xml_sort_id'] = $xmlinfo['xml_sort_id'];
		
		unset($config['config'], $config['xml_id']);
		//发送导出参数
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		foreach((array)$config as $k => $v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$this->curl->addRequestData('export_count', $this->settings['export_count']);
		$this->curl->addRequestData('a', 'xmlExportCron');
		$this->curl->addRequestData('html', 'true');
		$ret = $this->curl->request('xml.php');
		$ret = $ret[0];
		if($ret)
		{
			//file_put_contents('../admin/1.txt', var_export($ret,1));
			$sql = "UPDATE " .DB_PREFIX. "export_config SET percent = " .$ret['percent']. " WHERE is_default = 1";
			$this->db->query($sql);
			//将已经提交过的信息记录下来 (计划任务导出的数据可以不记录)
			/*
			$insert = $ret[0];
			$sql = " INSERT INTO " . DB_PREFIX . "export(vod_id,dir,need_file) VALUES";
			foreach ($insert AS $k => $v)
			{
				$sql .= "('{$k}','{$v}','{$need_file}'),";
			}
			$sql = trim($sql,',');
			$this->db->query($sql);
			*/
		}
		$this->addItem($ret);
		$this->output();
	}
	
	/*
	 * 获取xml模板
	 */
	public function getxmlinfo($id = '')
	{
		$sql = "SELECT id,content,type_id FROM " .DB_PREFIX. "xml WHERE id IN (" .$id. ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$xml[] =array(
				'id' => $row['id'],
				'content' => $row['content'],
			);
			$xml_sort_id = $row['type_id'];
		}
		$re = array(
			'xml' => $xml,
			'xml_sort_id' => $xml_sort_id,
		);
		return $re;
	}
}

$out = new vod_export();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'run';
}
$out->$action();
?>
<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_auto.php 6444 2012-04-18 05:18:47Z repheal $
***************************************************************************/
set_time_limit(0);
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','txt_import_data');
$file = CUR_CONF_PATH . 'data/db';
if(!file_exists($file))
{
	$db_data = array(
		'import' => 'dev_tools_switch',
		'used' => 'dev_tools',
	);
	file_put_contents($file,json_encode($db_data));
}
define('DB_SWITCH',TRUE);
require_once(CUR_CONF_PATH . 'lib/functions.php');
require_once(ROOT_DIR.'global.php');
class txtImportDataApi extends cronBase
{
	private $media;
	private $play;
	function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/data_manager.class.php');
		$this->obj = new data_manager();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => 'txt文件未完全导入的数据', 
			'brief' => 'txt文件未完全导入的数据',
			'space' => '300',	//运行时间间隔，单位秒 ,5分钟一次
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		if($this->input['debug'])
		{
			return '';
		}
		$sql = "SELECT table_name,is_empty FROM " .DB_PREFIX. "table_info WHERE 1 and is_data=1 ORDER BY RAND()";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			file_put_contents(DATA_DIR . 'db_queue',1);
			$info[] = $row;
			if($row['is_empty'])
			{
				 $sql = "TRUNCATE TABLE `" . DB_PREFIX . $row['table_name'] ."`";
				 $this->db->query($sql);
				 $sql = "UPDATE " .DB_PREFIX. "table_info SET is_empty=0,update_time=" . TIMENOW . " WHERE table_name='" . $row['table_name'] . "'";
				 $this->db->query($sql);
			}
			else
			{
				$sql = "UPDATE " .DB_PREFIX. "table_info SET update_time=" . TIMENOW . " WHERE table_name='" . $row['table_name'] . "'";
				$this->db->query($sql);
			}
			
			if(file_exists(CACHE_DIR.$row['table_name'].'_queue'))
			{
				if((time() - file_get_contents(CACHE_DIR.$row['table_name'].'_queue')) > 60)
				{
					@unlink(CACHE_DIR.$row['table_name'].'_queue');
				}
				else
				{
					continue;
				}
			}
			file_put_contents(CACHE_DIR . $row['table_name'] . '_queue',time());
			$table_info = $this->obj->get_field($row['table_name']);
			$new_table_info = array();
			foreach($table_info as $k => $v)
			{
				if($v != 'id')
				{
					$new_table_info[] = $v;
				}						
			}
			$ret = $this->txt2array($new_table_info,$row['table_name']);
			if($ret)
			{
				$sql = "UPDATE " .DB_PREFIX. "table_info SET is_data=0,update_time=" . TIMENOW . " WHERE table_name='" . $row['table_name'] . "'";
				 $this->db->query($sql);
				@unlink(CACHE_DIR.$row['table_name'].'_queue');
			}
		}
		if(empty($info))
		{
			if(file_exists(DATA_DIR."db"))
			{
				if(file_exists(DATA_DIR . 'db_queue'))
				{
					$db_data = json_decode(file_get_contents(DATA_DIR."db"),1);
					$tmp_data = $db_data;
					$db_data['import'] = $tmp_data['used'];
					$db_data['used'] = $tmp_data['import'];
					file_put_contents(DATA_DIR."db",json_encode($db_data));
					@unlink(DATA_DIR . 'db_queue');
				}		
			}
		}
	}
	public function txt2array($table_info,$table_name)
	{
		$return = array();
		$fp = opendir(DATA_DIR);
		$data = array();
		while(false != $file = readdir($fp))
		{
			if($file != '.' && $file != '..' && strstr($file,$table_name.'_'))
		    {
		    	$tmp_total = hg_get_total_line(DATA_DIR.$file);
		    	$data = hg_getFileLines(DATA_DIR.$file,1,$tmp_total);
		    	@unlink(DATA_DIR.$file);
		    	break;
		    }
		}
		closedir($fp);
		
		//hg_pre($return);exit;
		if($data)
		{
			$table_info_str = $space = '';
			foreach($table_info as $k => $v)
			{
				$table_info_str .= $space . "`" . $v . "`";
				$space = ',';	
			}
			$sql = '';
			if($table_info_str)
			{
				$sql = "INSERT INTO " . DB_PREFIX . $table_name .  "(" . $table_info_str . ") values ";
			}
			$extra = $space = '';
			foreach($data as $k => $v)
			{
				$v_error = $v;
				$v = str_replace(array('|',',','\\'),array(',',',',''),$v);
				$tmp_data = explode(',',$v);
				$tmp_extra = $tmp_space = '';
				$length = count($tmp_data);
				if($length != count($table_info))
				{
					file_put_contents(CACHE_DIR . 'error.log',trim(iconv("gbk","UTF-8",trim($v_error)),'"').'-----------',FILE_APPEND);
					continue;
				}
				$tmp_extra = "(";
				for($i = 0; $i < $length;$i++)
				{
					$tmp_extra .= $tmp_space . ( trim($tmp_data[$i]) ? '"' . trim(iconv("gbk","UTF-8",trim($tmp_data[$i])),'"') . '"' : '""');
					$tmp_space = ',';
				}
				$tmp_extra .= ")";
				$extra .= $space . $tmp_extra;
				$space = ',';
				unset($tmp_data);
			}
			$sql .= $extra;
			$this->db->query($sql);
	    	unset($data);
	    	//echo memory_get_usage(); 
	    	//echo "<br/>";
	    	$this->txt2array($table_info,$table_name);				
		}
		else
		{
			return true;	
		}
	}
	
	
}
$out = new txtImportDataApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
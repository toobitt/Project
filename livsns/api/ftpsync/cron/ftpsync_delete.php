<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','ftpsync_delete');//模块标识
define('SCRIPT_NAME', 'ftpsync_delete');
require_once('../lib/functions.php');
require_once(ROOT_DIR.'global.php');
require_once(ROOT_PATH.'lib/class/ftp.class.php');
require_once(CUR_CONF_PATH.'lib/log.class.php');
class ftpsync_delete extends cronBase
{
	protected $log;
	protected $ftp;
	function __construct()
	{
		parent::__construct();
		$this->log = new ftplog();
		$this->ftp = new Ftp();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> MOD_UNIQUEID,	 
			'name' 			=> 'FTP同步删除文件',	 
			'brief' 		=> '',
			'space'			=> '5',	//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function show()
	{
		$limit = ' LIMIT 0, 10';
		$sql = 'SELECT * FROM  ' . DB_PREFIX .'ftpfile_del';
		$sql = $sql . $limit;
		$query = $this->db->query($sql);
		$files = array();
		while($row = $this->db->fetch_array($query))
		{
			$files[$row['app']][$row['id']] = array('fp'=>$row['filepath'],'pt'=>$row['pathtype']);
		}
		if(!$files)
		{
			return;
		}
		$apps = @array_keys($files);
		if(!$apps)
		{
			return;
		}
		$sql = 'SELECT server_id,server_dir,app_dir,app FROM ' . DB_PREFIX . 'ftpsync WHERE app = "'. implode('","', $apps) .'"';
		$query = $this->db->query($sql);
		$server_id = array();
		$server_info = array();
		$sync_info = array();
		while($row = $this->db->fetch_array($query))
		{
			$server_id[] = $row['server_id'];
			$sync_info[$row['app']][] = $row;
		}
		
		if($server_id)
		{
			$server_id = array_unique($server_id);
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'ftpserver WHERE id IN('.implode(',', $server_id).')';
			$query = $this->db->query($sql);
			while($r = $this->db->fetch_array($query))
			{
				$server_info[$r['id']] = array(
					'hostname'=>$r['hostname'],
					'username'=>$r['user'],
					'password'=>hg_encript_str($r['pass'], false),
					'port'=>$r['port'],
				//	'server_dir'=>$r['server_dir'],
				//	'app_dir'=>$r['app_dir'],
				);
			}
		}

		foreach ($files as $app=>$filepath)
		{
			if(!$filepath)
			{
				return;
			}
			if(empty($sync_info[$app]))
			{
				return;
			}
			foreach($sync_info[$app] as $sinfo)
			{
				$config = $server_info[$sinfo['server_id']];
				
				if(!$config)
				{
					continue;
				}
				//$config['server_dir'] = $sinfo['server_dir'];
				$this->ftp->connect($config);
				foreach($filepath as $kid=>$fl)
				{
					$this->db->query('DELETE FROM  ' . DB_PREFIX . 'ftpfile_del WHERE id='.$kid);
					
					$server_dir = trim($sinfo['server_dir'], '/') . '/';
					//绝对路径
					if($fl['pt'])
					{
						$fl['fp'] = str_replace($sinfo['app_dir'], '', $fl['fp']);
					}
					if($server_dir)
					{
						$fl['fp'] = $server_dir . $fl['fp'];
					}
					$this->ftp->delete_file($fl['fp']);
				}
				$this->ftp->close();
			}
		}
	}
}
include(ROOT_PATH . 'excute.php');
<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','ftpsync_error');//模块标识
define('SCRIPT_NAME', 'ftpsync');
require_once('../lib/functions.php');
require_once(ROOT_DIR.'global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(CUR_CONF_PATH.'lib/log.class.php');
class ftpsync extends cronBase
{
	protected $curl;
	protected $log;
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl();
		$this->log = new ftplog();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function initcron()
	{
		$array = array(
			'mod_uniqueid' 	=> MOD_UNIQUEID,	 
			'name' 			=> 'FTP同步失败的文件',	 
			'brief' 		=> '尝试重新上传出错误的文件',
			'space'			=> '15',	//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function show()
	{
		$where = ' WHERE status < ' . (TRY_TIMES-1);
		$orderby = ' ORDER BY create_time ASC';
		$limit = ' limit 0, ' . ERROR_LIMIT;
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'ftplog_error ' . $where . $orderby . $limit;
		$query = $this->db->query($sql);
		$server_id = array();
		$server_info = array();
		$error = array();
		$sync_id = array();
		$sync_info = array();
		while($row = $this->db->fetch_array($query))
		{
			$server_id[] = $row['server_id'];
			$error[$row['sync_id']][$row['id']] = $row['sfile'];
			$sync_id[]  = $row['sync_id'];
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
					//'server_dir'=>$r['server_dir'],
					//'app_dir'=>$r['app_dir'],
				);
			}
		}
		if($sync_id)
		{
			$sql = 'SELECT id,server_dir,app_dir,app,server_id FROM ' . DB_PREFIX . 'ftpsync WHERE id IN('.implode(',', $sync_id).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$sync_info[$row['id']] = $row;
			}
		}
		//print_r($sync_info);
		if(!$error)
		{
			return;
		}
		foreach($error as $syncid=>$errorfile)
		{
			$config = $server_info[$sync_info[$syncid]['server_id']];
			$app = $sync_info[$syncid]['app'];
			if(!$config || !$app || !$errorfile)
			{
				return;
			}
			//
			$config['app_dir'] = $sync_info[$syncid]['app_dir'];
			$config['server_dir'] = $sync_info[$syncid]['server_dir'];
			
			$responce = $this->upload_app_files($app, $errorfile, $config);
			
			foreach($errorfile as $kid=>$file)
			{
				if($responce['error']==4 && $responce['message'][$file])
				{
					//上传失败
					$this->db->query('UPDATE ' . DB_PREFIX . 'ftplog_error SET status=status+1 WHERE id='.$kid);
				}
				else
				{
					$logadta = array(
							'server_id'=>$sync_info[$syncid]['server_id'],
							'sync_id'=>$syncid,
							'app'=>$app,
							'sfile'=>$file,
							'dfile'=>'',
							'status'=>0,
							'message'=>'上传成功',
							);
					//成功入日志库
					$this->log->create($logadta);
					$this->db->query('DELETE FROM ' . DB_PREFIX . 'ftplog_error WHERE id ='.$kid);
				}
			}
		}
	}
	protected function initcurl($app)
	{
		//echo $app;
		$apphost = $this->settings['App_' . $app]['host'];
		$appdir = $this->settings['App_' . $app]['dir'];
		$this->curl->setUrlHost($apphost, $appdir);
		//exit;
	}
	//文件上传
	function upload_app_files($app, $files, $config)
	{
		$this->initcurl($app);
		$this->curl->addRequestData('a', 'upload2ftp');
		$this->curl->addRequestData('files', json_encode($files));
		$this->curl->addRequestData('html', 1);
		$this->curl->addRequestData('config', json_encode($config));
		$is_sucess = $this->curl->request('configuare.php');
		$is_sucess = $is_sucess[0];
		
		return $is_sucess;
	}
}
include(ROOT_PATH . 'excute.php');
<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
define('MOD_UNIQUEID','ftpsync');//模块标识
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
			'name' 			=> 'FTP同步文件',	 
			'brief' 		=> '将文件安装配置同步之指定服务器',
			'space'			=> '15',	//运行时间间隔，单位秒
			'is_use' 		=> 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	function show()
	{
		//从队列中读取需要同步的服务器信息
		$sql = 'SELECT fq.*,fse.hostname,fse.user,fse.pass,fse.port,fsy.max_number,fsy.server_id,fsy.allow_ftype,fsy.server_dir,fsy.app,fsy.app_dir,fsy.setinterval FROM ' . DB_PREFIX . 'ftpqueue fq LEFT JOIN ' . DB_PREFIX . 'ftpsync fsy ON fq.sync_id=fsy.id LEFT JOIN ' . DB_PREFIX . 'ftpserver fse ON fsy.server_id=fse.id';
		$where = ' WHERE fq.run_time = ' . floor(TIMENOW/60) . ' AND fq.status > 0 AND is_stop = 0';
		$sql = $sql . $where;
		$query = $this->db->query($sql);
		$queues = array();
		while($row  = $this->db->fetch_array($query))
		{
			$queues[$row['status']][] = $row;
			$update_run_time_sql = 'UPDATE ' . DB_PREFIX . 'ftpqueue SET run_time=run_time+'.$row['setinterval'] . ' WHERE id='.$row['id'];
			$this->db->query($update_run_time_sql);
		}
		$update_run_time_sql = '';
		//初次建立索引文件
		if($queues[1])
		{
			foreach ($queues[1] as $v)
			{
				$files = $this->create_file_index($v['app'], $v['app_dir'], $v['allow_ftype']);
				if($files &&  $this->create_index($v['sync_id'],$files))
				{
					$this->db->query('UPDATE ' . DB_PREFIX . 'ftpqueue SET status = 2 WHERE sync_id = '.$v['sync_id']);
				}
			}
		}
		//获取修改或者新增的文件
		if($queues[2])
		{
			foreach($queues[2] as $r)
			{
				$sql = 'SELECT * FROM ' . DB_PREFIX . 'ftpfile_index WHERE sync_id = '.$r['sync_id'];
				$limit = ' LIMIT 0,'.$r['max_number'];
				$orderby = ' ORDER BY id ASC ';
				$sql = $sql . $orderby . $limit;
				
				$query = $this->db->query($sql);
				$files = array();
				while ($row = $this->db->fetch_array($query))
				{
					$files[$row['id']] = $row['filepath'];
				}
				if($files)
				{
					$config = array(
					'hostname'=>$r['hostname'],
					'username'=>$r['user'],
					'password'=>hg_encript_str($r['pass'], false),
					'port'=>$r['port'],
					'server_dir'=>$r['server_dir'],
					'app_dir'=>$r['app_dir'],
					);
					$responce = $this->upload_app_files($r['app'], $files,$config);
					print_r($responce);
					if($responce['error'] && $responce['error']!=4)
					{
						$logadta = array(
								'sync_id'=>$r['sync_id'],
								'server_id'=>$r['server_id'],
								'app'=>$r['app'],
								'sfile'=>'',
								'dfile'=>'',
								'status'=>$responce['error'],
								'message'=>$responce['message'],
								);
						$this->log->create($logadta);
					}
					else
					{
						foreach($files as $kid=>$fl)
						{
							$logadta = array(
								'sync_id'=>$r['sync_id'],
								'server_id'=>$r['server_id'],
								'app'=>$r['app'],
								'sfile'=>$fl,
								'dfile'=>'',
								'status'=>0,
								);
							if($responce['error']==4 && $responce['message'][$fl])
							{
								//上传失败
								$logadta['dfile'] = $responce['message'][$fl];
								$logadta['status'] = -1;
								$this->log->create_error($logadta);
								
							}
							else
							{
								$logadta['message'] = '上传成功';
								$this->log->create($logadta);
							}
							//成功与否都删除
							$del_file_sql = 'DELETE FROM ' . DB_PREFIX . 'ftpfile_index WHERE id='.$kid;
							$this->db->query($del_file_sql);
						}
					}
				}
				//查找新增或者修改的文件
				$app_files = $this->create_file_index($r['app'], $r['app_dir'], $r['allow_ftype'], $r['setinterval']);
				$this->create_index($r['sync_id'], $app_files);
			}
		}
	}
	//获取的文件入库 等待队列执行上传
	protected function create_index($sync_id,$files)
	{
		if(!is_array($files) || empty($files))
		{
			return false;
		}
		if(!$sync_id)
		{
			return false;
		}
		$sql = 'INSERT INTO ' . DB_PREFIX .'ftpfile_index VALUES';
		foreach($files as $file)
		{
			$sql .= '(null,' .$sync_id.', "'.$file.'"),';
		}
		$sql = trim($sql, ',');
		$this->db->query($sql);
		return true;
	}
	protected function initcurl($app)
	{
		//echo $app;
		$apphost = $this->settings['App_' . $app]['host'];
		$appdir = $this->settings['App_' . $app]['dir'];
		$this->curl->setUrlHost($apphost, $appdir);
		//exit;
	}
	//初次建立索引 并上传文件
	function create_file_index($app, $dir, $file_type, $setinterval=0)
	{
		//
		$this->initcurl($app, $dir);
		$this->curl->addRequestData('a', 'get_upload_files');
		$this->curl->addRequestData('setinterval', $setinterval);
		$this->curl->addRequestData('file_type', $file_type);
		
		$this->curl->addRequestData('upload_dir', $dir);
		$index_files = $this->curl->request('configuare.php');
		$index_files = $index_files[0];
		
		if(!$index_files || empty($index_files))
		{
			return false;
		}
		return $index_files;
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
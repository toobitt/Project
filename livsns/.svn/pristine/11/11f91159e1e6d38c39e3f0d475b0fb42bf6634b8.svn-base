<?php
require('./global.php');
define('MOD_UNIQUEID', 'ftpsync');
class ftpsync extends adminBase
{
	var $filed;
	var $data = array();
	function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		//表字段
		$this->filed = array(
		'id'=>false,
		'syncname'=>true,
		'brief'=>false,
		'server_id'=>true,
		'server_dir'=>false,
		'app'=>true,
		'app_dir'=>true,
		'setinterval'=>false,
		'max_number'=>false,
		'allow_ftype'=>false,
		);
		$this->initdata();
	}
	protected function initdata()
	{
		if($this->input['a'] == 'update')
		{
			foreach($this->filed as $field=>$must)
			{
				if($must && !$this->input[$field])
				{
					$this->errorOutput("请检查必填项是否都已全部正确输入！" . $field);
				}
				$this->data[$field] = $this->input[$field];
			}
			$this->autofill();
		}
	}
	protected function autofill()
	{
		
		if(!$this->data['id'])
		{
			$this->data['user_id'] = $this->user['user_id'];
			$this->data['user_name'] = $this->user['user_name'];
			$this->data['create_time'] = TIMENOW;
		}
		else
		{
			$this->data['update_user_id'] = $this->user['user_id'];
			$this->data['update_user_name'] = $this->user['user_name'];
			$this->data['update_time'] = TIMENOW;
		}
		if($this->data['app_dir'])
		{
			$this->data['app_dir'] = '/' . trim($this->data['app_dir'], '/');
		}
		if($this->data['server_dir'])
		{
			$this->data['server_dir'] = trim($this->data['server_dir'], '/');
		}
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($this->data,1));
	}
	function __destruct()
	{
		parent::__destruct();
	}
	protected function create()
	{
		$sql = 'INSERT INTO ' . DB_PREFIX . 'ftpsync('.implode(',', array_keys($this->data)).')';
		$sql .= ' VALUES("'.implode('","', $this->data).'")';
		//$this->errorOutput($sql);
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$this->data['id'] = $id;
		$this->addItem($this->data);
		$this->output();
	}
	function unknown()
	{
		//
	}
	function update()
	{
		if(!$this->data['id'])
		{
			$this->create();
		}
		//在运行的任务无法更新 即审核之后
		$is_runing = $this->db->query_first('SELECT status FROM ' . DB_PREFIX . 'ftpqueue WHERE sync_id='.$this->data['id']);
		if($is_runing['status'])
		{
			$this->errorOutput("任务已经进入队列无法修改");
		}
		$id = $this->data['id'];
		unset($this->data['id']);
		$sql = 'UPDATE ' . DB_PREFIX . 'ftpsync SET ';
		foreach ($this->data as $field=>$value)
		{
			$sql .= '`' . $field . '`' . '="' . $value . '",';
		}
		$sql = rtrim($sql, ',');
		$where = ' WHERE id = '.$id;
		//$this->errorOutput($sql . $where);
		$this->db->query($sql . $where);
		$this->data['id'] = $id;
		$this->addItem($this->data);
		$this->output();
	}
	function audit()
	{
		$id = intval($this->input['id']);
		//
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'ftpsync WHERE id = '.$id;
		$sync = $this->db->query_first($sql);
		if(!$sync)
		{
			$this->errorOutput("配置不存在或已经删除!");
		}
		$sync['status'] = $sync['status'] ? 0 : 1;
		if($sync['status'])
		{
			$queue_ex = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'ftpqueue WHERE sync_id='.$sync['id']);
			$setintval = floor(TIMENOW/60 + $sync['setinterval']);
			if(!$queue_ex)
			{
			//插入队列
			$queue_sql = "INSERT INTO " . DB_PREFIX . "ftpqueue(id,sync_id,run_time,status) value(null,{$sync['id']}, {$setintval}, 1)";
			//$this->errorOutput($queue_sql);
			
			}
			else
			{
			$queue_sql = "UPDATE " . DB_PREFIX . "ftpqueue SET run_time={$setintval},is_stop=0 WHERE sync_id = ".$sync['id'];

			}
			$this->db->query($queue_sql);
		}
		else
		{
			$queue_sql = "UPDATE " . DB_PREFIX . "ftpqueue SET is_stop=1 WHERE sync_id = ".$sync['id'];
			//$this->errorOutput($queue_sql);
			$this->db->query($queue_sql);
		}
		//更改配置状态
		$this->db->query('UPDATE ' . DB_PREFIX . 'ftpsync SET status = '.$sync['status'].' WHERE id IN('.$id.')');
		$this->addItem($sync);
		$this->output();
	}
	function delete()
	{
		$id_str = $this->input['id'];
		if(is_array($id_str))
		{
			$id_str = implode(',', $id_str);
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'ftpsync WHERE id IN('.$id_str.')';
		
		
		$query = $this->db->query($sql);
		$id_str = '';
		while($row = $this->db->fetch_array($query))
		{
			$id_str .= $row['id'] . ',';
		}
		$id_str = rtrim($id_str, ',');
		if(!$id_str)
		{
			$this->errorOutput("删除未知的选项");
		}
		$sql = 'DELETE FROM ' . DB_PREFIX .'ftpsync WHERE id IN('.$id_str.')';
		$this->db->query($sql);
		
		//删除队列
		$sql = 'DELETE FROM ' . DB_PREFIX .'ftpqueue WHERE sync_id IN('.$id_str.')';
		$this->db->query($sql);
		
		//删除索引文件
		$sql = 'DELETE FROM ' . DB_PREFIX . 'ftpfile_index WHERE sync_id IN('.$id_str.')';
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
		
	}
}
$o = new ftpsync();
$action = $_INPUT['a'];
$action = $action && method_exists($o, $action) ? $action : 'unknown';
$o->$action();
?>
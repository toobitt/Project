<?php
define('SCRIPT_NAME', 'mobile_sort');
define('MOD_UNIQUEID','api_sort');
require_once('./global.php');
require(CUR_CONF_PATH."lib/functions.php");
class mobile_sort extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
	}
	function show()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$order = ' ORDER BY id DESC ';
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):20;
		$limit = " limit {$offset}, {$count}";
		
		$condition = $this->get_condition();
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'mobile_sort WHERE 1 ';
		$sql = $sql . $condition . $order . $limit;
		
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$arr[] = $row;
		}
		$this->addItem($arr);
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND sort_name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval($this->input['id']);
		}
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'mobile_sort '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	//添加分组
	function add_sort()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		if(!$this->input['sort_name'])
		{
			$this->errorOutput("请输入分组名称");
		}
		$data = array(
			'sort_name'		=> urldecode($this->input['sort_name']),
			'sort_dir'		=> trim(urldecode($this->input['sort_dir'])),
			'agent_switch'	=> intval($this->input['agent_switch']),
			'agent'			=> trim($this->input['agent']),
		);
		
		$sort_dir = substr($data['sort_dir'], -1,1);
		//如果结尾没有‘/’自动加上
		if($sort_dir != '/')
		{
			$data['sort_dir'] = $data['sort_dir'].'/';
		}
		else 
		{
			$data['sort_dir'] = $data['sort_dir'];
		}
		//判断分类目录是否存在
		if($data['sort_dir'])
		{
			$sql = "SELECT id FROM ".DB_PREFIX."mobile_sort WHERE sort_dir = '".$data['sort_dir']."'";
			$res = $this->db->query_first($sql);
			if($res['id'])
			{
				$this->errorOutput('目录已经存在');
			}
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'mobile_sort SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		if($this->db->query($sql))
		{
			$data['id'] = $this->db->insert_id();
		}
		if($data)
		{
			$this->addItem($data);
		}
		else
		{
			$this->addItem('error');
		}
		$this->output();
	}
	function detail()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }	
	    
	    $id = intval($this->input['id']);
	    $type = $this->input['type'];
	    if(!$type)
	    {
			
			$condition = ' WHERE id = '.$id;
			
			$sql = "SELECT * FROM " . DB_PREFIX . "mobile_sort".$condition;
			$data = $this->db->query_first($sql);
	    }
	    else 
	    {
	    	$data = array(
	    		'type' 	=> $type,
	    		'id'	=> $id,
	    	);
	    }
	    
		$this->addItem($data);
		$this->output();
	}
	function update()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
	    $id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('未找到分类id');
		}
		
		$data = array(
			'sort_name'		=> urldecode($this->input['sort_name']),
			'sort_dir'		=> trim(urldecode($this->input['sort_dir'])),
			'agent_switch'	=> intval($this->input['agent_switch']),
			'agent'			=> trim($this->input['agent']),
		);
		//如果结尾没有/自动加上
		$sort_dir = substr($data['sort_dir'], -1,1);
		
		if($sort_dir != '/')
		{
			$data['sort_dir'] = $data['sort_dir'].'/';
		}
		
		//判断分类目录是否存在
		if($data['sort_dir'])
		{
			$sql = "SELECT sort_dir FROM ".DB_PREFIX."mobile_sort WHERE id = ".$id;
			$res = $this->db->query_first($sql);
			if($res['sort_dir'] != $data['sort_dir'])
			{
				$sql = "SELECT id FROM ".DB_PREFIX."mobile_sort WHERE sort_dir = '".$data['sort_dir']."'";
				$res2 = $this->db->query_first($sql);
				if($res2['id'])
				{
					$this->errorOutput('目录已经存在');
				}
			}
		}
		$sql = "UPDATE ".DB_PREFIX."mobile_sort SET ";
		foreach($data as $k=>$v)
		{
			$sql .= "`".$k . "`='" . $v . "',";
		}
		
		$sql = rtrim($sql,',');
		$sql = $sql.' WHERE id = '.$id;
		$this->db->query($sql);
		
		if($res['sort_dir'] != $data['sort_dir'])
		{
			//模板文件路径
			if(!defined('MOBILE_API_TPL'))
			{
				define('MOBILE_API_TPL','../api/apitpl.php');
			}
			
			$tpl = MOBILE_API_TPL;
			
			//获取模板文件
			$tpl_str = @file_get_contents($tpl);
			if($tpl_str)
			{
			
				$con = " AND m.sort_id = ".$id;
				
				//查询文件配置
				$sql = "SELECT m.*,s.sort_dir FROM " . DB_PREFIX . "mobile_deploy m 
						LEFT JOIN ".DB_PREFIX."mobile_sort s
							ON m.sort_id=s.id 
						WHERE 1 " . $con;
				$g = $this->db->query($sql);
				while($j = $this->db->fetch_array($g))
				{
					$return[] = $j;
				}
				//生成文件，支持批量
				if(is_array($return) && count($return))
				{
					foreach($return as $k=>$v)
					{
						mobile_build_file($v, $tpl_str);
					}
				}
				rmdir(DATA_DIR.$res['sort_dir']);
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
	
	public function export_file()
	{
		$sort_id = intval($this->input['sort_id']);
		if(!$sort_id)
		{
			$this->errorOutput('分类Id不存在');
		}
		
		$sort_dir = $this->input['sort_dir'];
		
		if(!$sort_dir)
		{
			$sql = "SELECT sort_dir FROM ".DB_PREFIX."mobile_sort WHERE id = ".$sort_id;
			$res = $this->db->query($sql);
			
			$sort_dir = $res['sort_dir'];
		}
		
		if(!$sort_dir)
		{
			$this->errorOutput('sort_dir不存在');
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."mobile_deploy WHERE sort_id = ".$sort_id;
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			$data[] = $r;
		}
		
		if($data)
		{
			$filepath = DATA_DIR . $sort_dir;
			if (!hg_mkdir($filepath) || !is_writeable($filepath))
			{
				$this->errorOutput(NOWRITE);
			}
			$json = json_encode($data);
			
			$filename = date('Y-m-d',TIMENOW) . '-' . TIMENOW . hg_rand_num(6) . '.json';
			@file_put_contents($filepath.$filename, $json);
			
			$file = @fopen($filepath . $filename,"r");
			Header("Content-type: application/octet-stream");
			Header("Accept-Ranges: bytes");
			Header("Accept-Length: ".filesize($filepath . $filename));
			
			$file_name = substr($sort_dir, 0,-1).'_export_file.json';
			Header("Content-Disposition: attachment; filename=".$file_name);
			// 输出文件内容
			echo fread($file,filesize($filepath . $filename));
			fclose($file);
			
			unlink($filepath.$filename);
			exit();
		}
	}
	function unknown()
	{
		$this->erroroutput(NO_METHOD);
	}
}
include(ROOT_PATH . 'excute.php');
<?php
define('SCRIPT_NAME', 'mobile_api_settings');
define('MOD_UNIQUEID','api');
require_once('./global.php');
require(CUR_CONF_PATH."lib/functions.php");
class mobile_api_settings extends adminReadBase
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
		$order = ' ORDER BY m.file_name ';
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";

		$sql = 'SELECT m.*,s.sort_name,s.id as sid,s.sort_dir FROM '.DB_PREFIX.'mobile_deploy m 
				LEFT JOIN '.DB_PREFIX.'mobile_sort s
				ON m.sort_id=s.id WHERE 1';
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $order . $limit;
		
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$arr[] = $row;
		}
		$this->addItem($arr);
		$this->output();
	}
	function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('未找到文件id');
		}
		
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$sql = "SELECT m.*,s.sort_name,s.id as sid,s.sort_dir FROM " . DB_PREFIX . "mobile_deploy m 
				LEFT JOIN ".DB_PREFIX."mobile_sort s
				ON m.sort_id=s.id WHERE m.id=".$this->input['id'];
		$q = $this->db->query_first($sql);
		
		
		if($this->input['type'] == 'map')
		{
			if($q['map'])
			{
				$map = array();
				$map = unserialize($q['map']);
				$this->addItem($map);
			}
		}
		else
		{
			
			if($q['argument'])
			{
				$q['argument'] = unserialize($q['argument']);
			}
			
			if($q['map_val'])
			{
				$q['map_val'] = unserialize($q['map_val']);
			}
			
			if($q['extend_api'])
			{
				$q['extend_api'] = unserialize($q['extend_api']);
			}
		
			$this->addItem($q);
		}
		$this->output();
	}
	function update_map()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }

		$map = $this->input['map'];
		$mod = $this->input['mod'];
		if(is_array($map))
		{
			foreach($map as $k => $v)
			{
				$arr[$v] = $mod[$k];
			}
		}
		$arr = serialize($arr);
		$sql = "UPDATE ".DB_PREFIX."mobile_deploy SET map='" . $arr ."'";
		
		$sql = $sql.' WHERE id = '.$this->input['id'];
		
		$this->db->query($sql);
		
		//生成映射后重新生成接口文件
		$this->build_api_file();
		
		$this->addItem('success');
		$this->output();
	}
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND m.file_name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND m.id = '.intval($this->input['id']);
		}
		if($this->input['sortid'] && $this->input['sortid'] != -1)
		{
			$condition .= ' AND m.sort_id = '.$this->input['sortid'];	
		}
		if($this->input['bundle'] && $this->input['bundle'] != -1)
		{
			$condition .= ' AND m.bundle = "' . $this->input['bundle'] . '"';	
		}
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'mobile_deploy m WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	function append_sort()
	{
		$group_sql = "SELECT * FROM " . DB_PREFIX . "mobile_sort ORDER BY id DESC";
		$g = $this->db->query($group_sql);
		$return = array();
		while($j = $this->db->fetch_array($g))
		{
			$return[$j['id']] = $j['sort_name'];
		}
		$this->addItem($return);
		$this->output();
	}
	//获取应用模块
	public function get_app()	
	{	
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->pub = new Auth();
		$app_modules = $this->pub->get_app('id,name,bundle');
		if($app_modules)
		{
			foreach($app_modules as $k=>$v)
			{
				$apps[$v['bundle']] = $v['name'];
			}
		}
		$this->addItem($apps);
		$this->output();
	}	
	function unknown()
	{
		$this->erroroutput(NO_METHOD);
	}
	/**
	 * 
	 * 生成接口文件（支持生成分类下接口）
	 * @param id 接口文件Id
	 * @param sort_id 分类id
	 */
	function build_api_file()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$ids = urldecode($this->input['id']);//接口文件Id
		$sort_ids = urldecode($this->input['sort_id']);//分类id
		
		if(!$ids && !$sort_ids)
		{
			$this->errorOutput(NOID);
		}
		
		//模板文件路径
		if(!defined('MOBILE_API_TPL'))
		{
			define('MOBILE_API_TPL','../api/apitpl.php');
		}
		
		$tpl = MOBILE_API_TPL;
		if(!is_readable($tpl))
		{
			$this->errorOutput(NOT_ALLOW_READ);
		}
		
		$tpl_str = '';
		//获取模板文件
		$tpl_str = @file_get_contents($tpl);
		if(!$tpl_str)
		{
			$this->errorOutput(NOT_ALLOW_READ);
		}
		
		$con = '';
		if($ids)
		{
			$con = " AND m.id IN (".$ids.")";
		}
		else if($sort_ids)//支持分类下接口文件重建
		{
			$con = " AND m.sort_id IN (".$sort_ids.")";
		}
		
		//查询文件配置
		$sql = "SELECT m.*,s.sort_dir,s.agent_switch,s.agent FROM " . DB_PREFIX . "mobile_deploy m 
				LEFT JOIN ".DB_PREFIX."mobile_sort s
					ON m.sort_id=s.id 
				WHERE 1 " . $con;
		$g = $this->db->query($sql);
		while($j = $this->db->fetch_array($g))
		{
			if(!$j['agent_switch'])
			{
				$j['agent'] = '';
			}
			unset($j['agent_switch']);
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
		
		$this->addItem('success');
		$this->output();
	}
	
	//导出接口
	public function export_file()
	{
		$ids = $this->input['id'];
		if(!$ids)
		{
			$this->errorOutput('id不存在');
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."mobile_deploy WHERE id IN (" . $ids . ")";
		$q = $this->db->query($sql);
		
		$data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$data[] = $r;
		}
		
		if(!empty($data))
		{
			$filepath = DATA_DIR;
			if (!hg_mkdir($filepath) || !is_writeable($filepath))
			{
				$this->errorOutput(NOWRITE);
			}
			$json = json_encode($data);
			
			$filename = date('Y-m-d',TIMENOW) . '-' . TIMENOW . hg_rand_num(6) . '.json';
			@file_put_contents($filepath.$filename, $json);
			
			//$file = @fopen($filepath . $filename,"r");
			Header("Content-type: application/octet-stream");
			Header("Accept-Ranges: bytes");
			Header("Accept-Length: ".filesize($filepath . $filename));
			
			$file_name = date('Y-m-d-H-i-s') . '.json';
			Header("Content-Disposition: attachment; filename=".$file_name);
			// 输出文件内容
			
			$timeout = array(  
			    'http'=> array(  
			        'timeout'=>10//设置一个超时时间，单位为秒  
			    )  
			);  
			$ctx = stream_context_create($timeout);  

			echo file_get_contents($filepath.$filename,0,$ctx);
			//echo fread($file,filesize($filepath . $filename));
			//fclose($file);
			
			unlink($filepath.$filename);
			exit();
		}
	}
	
	function check_file()
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
			$this->errorOutput('id不存在！');
		}
		
		$sql = "SELECT m.file_name,s.sort_dir FROM " . DB_PREFIX . "mobile_deploy m 
				LEFT JOIN " . DB_PREFIX . "mobile_sort s
					ON m.sort_id = s.id 
				WHERE m.id = {$id}";
		
		$res = $this->db->query_first($sql);
		
		if($res['sort_dir'] && $res['file_name'])
		{
			$filepath = DATA_DIR . $res['sort_dir'] . $res['file_name'];
		
			if(@$file_res = file_get_contents($filepath))
			{
				$this->addItem($file_res);
			}
		}
		$this->output();
	}
	
	
	public function data_preview()
	{
		
		$id = intval($this->input['id']);
		
		if(!$id)
		{
			$this->errorOutput('id不存在！');
		}
		
		$sql = "SELECT m.file_name,s.sort_dir FROM " . DB_PREFIX . "mobile_deploy m 
				LEFT JOIN " . DB_PREFIX . "mobile_sort s
					ON m.sort_id = s.id 
				WHERE m.id = {$id}";
		
		$res = $this->db->query_first($sql);
		if($res['sort_dir'] && $res['file_name'])
		{
			include_once(ROOT_PATH.'lib/class/curl.class.php');
			
			$app_info = $this->settings['App_mobile'];
			
			$host = $app_info['host'];
			$dir = 	$app_info['dir'] . 'data/'. $res['sort_dir'];
			
			$curl = new curl($host,$dir);
			$curl->setSubmitType('post');
			$curl->initPostData();
			$response = $curl->request($res['file_name']);
		
			$this->addItem($response);
			$this->output();
		}
	}
}
include(ROOT_PATH . 'excute.php');
<?php
define('SCRIPT_NAME', 'PushNotice');
define('MOD_UNIQUEID','push_platform');
require_once('./global.php');
class PushNotice extends adminReadBase
{
	function __construct()
	{
		$this->mPrmsMethods = array(
		'notice_manage'=>'推送管理',
		'_node'=>array(
			'name'=>'应用名称',
			'filename'=>'push_platform_node.php',
			'node_uniqueid'=>'push_platform_node',
			),
		);
		
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
		$this->verify_content_prms(array('_action'=>'notice_manage'));
		
		$order = ' ORDER BY id DESC';
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";

		$sql = 'SELECT * FROM '.DB_PREFIX.'notice  WHERE 1';
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $order . $limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			switch (intval($row['platform_type'])) 
			{
				case 1:
				    $row['platform_type'] = '信鸽';
					break;
				case 2:
					$row['platform_type'] = '极光';
					break;
				case 3:
					$row['platform_type'] = 'AVOS';
					break;
				default:
					break;
			}
			if($row['send_time'])
			{
				$row['send_time'] = date('Y-m-d H:i',$row['send_time']);
			}
			if($row['create_time'])
			{
				$row['create_time'] = date('Y-m-d H:i',$row['create_time']);
			}
			$this->addItem($row);
		}
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		//权限判断
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND org_id IN ('.$this->user['slave_org'].')';
			}
			
			$nodes = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($nodes)
			{
				$condition .= ' AND app_id IN ('.implode(',', $nodes).')';
			}
		}
		
		if($this->input['k'])
		{
			$condition .= ' AND content LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		
		//创建者
		if($this->input['user_name'] || trim(($this->input['user_name']))== '0')
		{
			$condition .= " AND user_name = '".trim($this->input['user_name'])."'";
		}
		
		$id = intval($this->input['id']);
		if($id)
		{
			$condition .= ' AND id = '.$id;
		}
		
		$node_id = intval($this->input['_id']);
		if($node_id)
		{
			$condition .= ' AND app_id = '.$node_id;
		}
		
		if(isset($this->input['notice_state']) && $this->input['notice_state'] != '-1')
		{
			if($this->input['notice_state'] == 1)
			{
				$condition .= ' AND errcode = 0';
			}
			else 
			{
				$condition .= ' AND errcode != 0';
			}
		}
		if($this->input['app'] && $this->input['app'] != -1)
		{
			$condition .= ' AND app_id='.$this->input['app'];
		}
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'notice  WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	function detail()
	{
		$this->verify_content_prms(array('_action'=>'notice_manage'));
		
		if(!$this->input['id'])
		{
			$this->errorOutput('未找到应用id');
		}
		$condition = $this->get_condition();
		$sql = "SELECT * FROM ".DB_PREFIX."notice WHERE 1 ".$condition;
		
		$q = $this->db->query_first($sql);
		
		if($q['send_time'])
		{
			$q['send_time'] = date('Y-m-d H:i',$q['send_time']);
		}
		$this->addItem($q);
		$this->output();
	}
	
	
	public function AppendAppInfo()
	{
		$sql = "SELECT id,name FROM " . DB_PREFIX . "app_info ORDER BY id DESC";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$data[$r['id']] = $r['name'];
		}
		$this->addItem($data);
		$this->output();
	}
	
	public function get_platform_type()
	{
		$id = intval($this->input['app_id']);
		if(!$id)
		{
			return FALSE;
		}
		
		$sql = "SELECT platform_type FROM " . DB_PREFIX . "app_info WHERE id = " . $id;
		$res = $this->db->query_first($sql);
		
		$this->addItem($res);
		$this->output();
	}
	
	public function append_mobile_module()
	{
		if($this->settings['App_mobile'])
		{
			include_once(ROOT_PATH.'lib/class/curl.class.php');
			
			$app_info = $this->settings['App_mobile'];
			
			$curl = new curl($app_info['host'],$app_info['dir'] . 'admin/');
			$curl->setSubmitType('post');
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->addRequestData('count',100);
			$data = $curl->request('mobile_module.php');
		
			if($data)
			{
				foreach ($data as $k => $v)
				{
					if(!$v['module_id'])
					{
						continue;
					}
					$arr[$v['module_id']] = $v['name'];
				}
			}
		}
		
		$this->addItem($arr);
		$this->output();
	}
	
	function unknown()
	{
		$this->erroroutput(NO_METHOD);
	}
}
include(ROOT_PATH . 'excute.php');
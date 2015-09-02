<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','client');//模块标识
class clientApi extends adminupdateBase
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include client.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH."lib/common.php");
		include(CUR_CONF_PATH . 'lib/client.class.php');
		$this->obj = new client();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		/**权限判断*/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
		$client_id = '';
		$clientdata = array();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
			
		$clientdata = $this->obj->get_client(' * ',$this->get_condition(),$offset,$count);
		$this->addItem($clientdata);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."client ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}

	private function get_condition()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			if($this->user['prms']['client']['show']['node'])
			{
				$cpids = '';
				$cpid_arr = $this->user['prms']['client']['show']['node']['site_node'];
				$cpids = implode(',',$cpid_arr);
			}
			$condition .= " AND id in  (".$cpids.")";	
		}
		$condition = '';
		if($keyword = urldecode($this->input['keyword']))
		{
			$condition = " AND name like '%".$keyword."%' ";
		}
		return $condition;	
	}
	
	public function client_form()
	{
		/**权限判断*/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
		
		if($id = intval($this->input['id']))
		{
			$clientdata = $this->obj->get_client_by_id($id);
			$this->addItem($clientdata);
			$this->output();
		}
	}
	
	public function create_update()
	{
		/**权限判断*/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
		
		$client_id = intval($this->input['client_id']);
		$data = array(
			'name' 			=> $this->input['name'],
			'sign' 			=> $this->input['sign'],
			'is_open' 		=> $this->input['is_open'],
			'need_deploy' 	=> $this->input['need_deploy'],
		);
		if(empty($data['name']))
		{
			$this->errorOutput("填写信息不全");
		}
		if($client_id)
		{
			//更新
			if(!$client = $this->obj->update_client($client_id,$data))
			{
				$this->errorOutput("更新失败！");
			}
			$this->addItem($client);
			$this->output();
		}
		else
		{
			//插入
			if(!$this->obj->insert_client($data))
			{
				$this->errorOutput("添加失败！");
			}
		}
	}
	
	public function delete()
	{
		/**权限判断*/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(empty($this->user['prms']['app_prms'][APP_UNIQUEID]['setting']))
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
		
		$client_id = intval($this->input['client_id']);
		if($client_id)
		{
			$this->obj->delete($client_id);
		}
		else
		{
			$this->errorOutput("删除失败！");
		}
	}
	
	public function get_pub_client()
	{
		$condition = urldecode($this->input['condition']);
		$client = $this->obj->get_all_client('*',$condition);
		$this->addItem($client);
		$this->output();
	}
	
	public function get_client_first()
	{
		$client_id = intval($this->input['client_id']);
		if(!$client_id)
		{
			$this->addItem(array());
			$this->output();
		}
		$sql = "SELECT * FROM ".DB_PREFIX."client WHERE id=".$client_id;
		$data = $this->db->query_first($sql);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
	
	public function create(){}
	public function update(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}
}

$out = new clientApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
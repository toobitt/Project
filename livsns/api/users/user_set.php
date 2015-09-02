<?php 
define('ROOT_DIR','../../');
require_once(ROOT_DIR . "global.php");

/**
 * 用户设置API
 */
class usetApi extends appCommonFrm
{
	private $mUser;
	var $user_info;
	
	function __construct()
	{
		parent::__construct();
		
		require_once(ROOT_PATH . 'api/lib/user.class.php');
		$this->mUser = new user();
		
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 获取用户登录信息
	 */
	public function is_login() 
	{
		$user_info = $this->mUser->verify_user();
		/*if(!$user_info)
		{
			$this->errorOutput(USENAME_NOLOGIN);//用户未登录
		}*/
		$this->user_info = $user_info;
	}
	
	/**
	 * 获取所有用户设置
	 */
	public function get_user_set()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "user_set WHERE 1";
		$query_id = $this->db->query($sql);
		while(false !=($rows = $this->db->fetch_array($query_id)))
		{
			$this->addItem($rows);
		}
		
		$this->output();
	}
	
	/**
	 * 添加用户设置
	 */
	public function add_user_set() 
	{
		$usetArr = unserialize(urldecode($this->input['usetArr']));
		$creatime = @time();
		
		$sql = "SELECT name FROM ".DB_PREFIX."user_set WHERE name='{$usetArr['uset_name']}'";
		$q = $this->db->query($sql);
		
		if(!$this->db->num_rows($q))
		{
			$sql = "INSERT INTO ".DB_PREFIX."user_set  SET 
					name='{$usetArr['uset_name']}',
					identi='{$usetArr['uset_identi']}',
					status='{$usetArr['uset_status']}',
					style='{$usetArr['uset_style']}',
					descripion='{$usetArr['uset_descripion']}',
					creattime='$creatime',
					creator='{$usetArr['username']}'";
			$this->db->query($sql);
		}
	}
	
	/**
	 * 删除用户设置
	 */
	public function del_user_set()
	{
		//$this->is_login();
		$id = $this->input['id'];
		$q = $this->db->query("SELECT id FROM " .DB_PREFIX. "user_set WHERE id in('$id')");
		if($this->db->num_rows($q))
		{
			$this->db->query("DELETE FROM " .DB_PREFIX. "user_set WHERE id='$id'");
		}
		else
		{
			exit("无效的用户设置id");
		}
	}
	
	/**
	 * 批量更新用户设置
	 */
	public function update_user_set()
	{
		//$this->is_login();
		$updateArr = unserialize(urldecode($this->input['updateArr']));
		foreach ($updateArr as $id=>$value)
		{
			$updatetime = @time();
			$sql = "UPDATE " .DB_PREFIX. "user_set SET status='$value',updatetime='$updatetime' WHERE id='$id'";
			$this->db->query($sql);
		}
	}
	
	/**
	 * 获取指定标识的用户设置
	 */
	public function get_desig_uset()
	{
		$identi = unserialize(urldecode($this->input['serialize']));
		$this->setXmlNode('identi','return');
		if(!is_array($identi))
		{
			$q = "SELECT * FROM " .DB_PREFIX. "user_set WHERE identi='$identi'";
			$return = $this->db->fetch_all($q);//单个记录
			if($return)
			{
				$return['result'] = 1;
			}
			else
			{
				$return['result'] = 0;
			}
		}
		else 
		{
			$usetArr = array();
			$str = '';
			foreach ($identi as $key=>$value)
			{
				$str .="'$value',";
			}
			$str = substr($str,0,strlen($str)-1);
			$q = "SELECT * FROM " .DB_PREFIX. "user_set WHERE identi in ($str)";
			$return = $this->db->fetch_all($q);
			if($return)
			{
				$return['result'] = 1;
			}
			else
			{
				$return['result'] = 0;
			}
		}
		$this->addItem($return);
		$this->output();
	}
	/*
	 * 把返回值统一为数组
	 */
	public function get_uset_array()
	{
		$identi = unserialize(urldecode($this->input['serialize']));
		$this->setXmlNode('identi','return');
		if(!is_array($identi))
		{
			$q = "SELECT identi,status FROM " .DB_PREFIX. "user_set WHERE identi='$identi'";
			$return = $this->db->fetch_all($q);//单个记录
			if($return)
			{
				$return = $return[0];
				$returns[$return['identi']] = $return['status'];
				$returns['result'] = 1;
			}
			else
			{
				$returns['result'] = 0;
			}
		}
		else 
		{
			$usetArr = array();
			$str = '';
			foreach ($identi as $key=>$value)
			{
				$str .="'$value',";
			}
			$str = substr($str,0,strlen($str)-1);
			$q = "SELECT identi,status FROM " .DB_PREFIX. "user_set WHERE identi in ($str)";
			$return = $this->db->fetch_all($q);
			if($return)
			{
				foreach($return as $k => $v)
				{
					$returns[$v['identi']] = $v['status'];
				}
				$returns['result'] = 1;
			}
			else
			{
				$returns['result'] = 0;
			}
		}
		$this->addItem($returns);
		$this->output();
	}
}

$out = new usetApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'get_user_set';
}

$out->$action();

?>
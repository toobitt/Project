<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
define('MOD_UNIQUEID','auth');
define('SCRIPT_NAME', 'member');
require(CUR_CONF_PATH . 'lib/MibaoCard.class.php');
class member extends outerReadBase
{
	private $mibao;
	public function __construct()
	{
		parent::__construct();
		$this->mibao = new MibaoCard();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		
	}
	public function count(){}
	public function detail(){}
	public function getMemberById()
	{
		$id = $this->input['id'] ? urldecode($this->input['id']) : '';
		if(empty($id))
		{
			$this->erroroutput('未传入用户id');
		}
		$con = " AND a.id IN (" . $id . ")";
		$info = $this->getUserInfo($con);//type=1 id,0 name
		$this->addItem($info);
		$this->output();
	}
	
	public function getMemberByName()
	{
		$name = $this->input['name'] ? urldecode($this->input['name']) : '';
		if(empty($name))
		{
			$this->erroroutput('未传入用户名');
		}
		$con = " AND a.user_name IN ('" . $name . "')";
		$info = $this->getUserInfo($con);//type=1 id,0 name
		$this->addItem($info);
		$this->output();
	}
	
	public function getMemberByOrg()
	{
		$org_id = $this->input['id'] ? $this->input['id'] : ($this->user['org_id'] ? $this->user['org_id'] : 0);
		if (empty($org_id))
		{
			$this->erroroutput('无部门id');
		}
		
		$con = ' AND o.id=' . $org_id;
		$info = $this->getUserInfo($con);
		$this->addItem($info);
		$this->output();
	}
	
	private function getUserInfo($con)
	{
		$sql = "SELECT a.id ,a.cardid as is_bind_card,a.admin_role_id,a.father_org_id as org_id,a.user_name,a.brief,a.avatar,a.create_time,o.name as org_name FROM " . DB_PREFIX . "admin a 
				LEFT JOIN " . DB_PREFIX . "admin_org o ON a.father_org_id = o.id
				WHERE 1 " . $con ;
		$q = $this->db->query($sql);
		$info = array();
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$row['avatar'] = unserialize($row['avatar']);
			$row['is_open_card'] = $this->settings['mibao']['open'];
			$info[] = $row;
		}
		return $info;
	}
	
	public function getUserOrg()
	{
		$con = '';
		if(isset($this->input['fid']))
		{
			$con .= ' AND fid='.intval($this->input['fid']);
		}
		$sql = "SELECT * FROM ".DB_PREFIX."admin_org WHERE 1 ".$con;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function getAllUser()
	{
		$sql = "SELECT a.id ,a.admin_role_id,a.father_org_id as org_id,a.user_name,a.brief,a.avatar,a.create_time,o.name as org_name, r.name AS role_name FROM " . DB_PREFIX . "admin a 
				LEFT JOIN " . DB_PREFIX . "admin_org o ON a.father_org_id = o.id
				LEFT JOIN " . DB_PREFIX . "admin_role r ON a.admin_role_id = r.id
				WHERE 1 " ;
		$q = $this->db->query($sql);
		$info = array();
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$row['avatar'] = unserialize($row['avatar']);
			$info[] = $row;
			$this->addItem($row);
		}
		$this->output();
	}
	
	/********************************************密保卡的一些操作****************************************************/
	
	//获取密保信息
	public function get_mibao_info()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$card = $this->mibao->get_mibao_info($this->input['id']);
		$this->addItem($card);
		$this->output();
	}
	
	//重新绑定
	public function bind_card()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mibao->bind_card($this->input['id']);
		$this->addItem($ret);
		$this->output();
	}

	//取消绑定密保
	public function cancel_bind()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mibao->cancel_bind($this->input['id']);
		$this->addItem($ret);
		$this->output();
	}
	
	//为所有用户绑定密保($this->input['is_retain']指明是否保留原有已经绑定的密保)
	public function bind_all_user()
	{
		$ret = $this->mibao->bind_all_user($this->input['is_retain']);
		$this->addItem($ret);
		$this->output();
	}
	
	/********************************************密保卡的一些操作****************************************************/
	
	
	function update_password()
	{
		$id = intval($this->input['id']);
		$sql = 'SELECT password,salt FROM '.DB_PREFIX.'admin WHERE id = '.$id;
		$userinfo = $this->db->query_first($sql);
		if (!$userinfo)
		{
			$this->errorOutput(NOID);
		}
		$password = '';
		$password = trim($this->input['password']);
		$password_again=trim($this->input['password_again']);
		$oldpass = trim($this->input['old_password']);
		$data = array();
		if ($password)
		{
			if(!$oldpass || ($userinfo['password'] != md5(md5(trim($oldpass)).$userinfo['salt'])))
			{
				$this->addItem(array('error'=>-1));
				$this->output();
			}
			$salt = '';
			$salt = hg_generate_salt();
			$password = md5(md5(trim($password)).$salt);
			$data = array(
				'password'=>$password,
				'salt'=>$salt,
				'update_time'=>TIMENOW,
			);
		}
		if ($_FILES['Filedata'])
		{
			$material = $this->uploadToPicServer($_FILES, intval($this->input['id']));
			if ($material)
			{
				$avatar = array(
					'host'=>$material['host'],
					'dir'=>$material['dir'],
					'filepath'=>$material['filepath'],
					'filename'=>$material['filename'],
				);
				$data['avatar'] = addslashes(serialize($avatar));
				$data['update_time'] = TIMENOW;
			}
		}
		if (!empty($data))
		{
			$sql = 'UPDATE '.DB_PREFIX.'admin SET ';
			foreach($data as $k=>$v)
			{
				$sql .= '`'.$k . '`="' . $v . '",';
			}
        	$sql = rtrim($sql,',');
			$sql = $sql.' WHERE id = '.$this->user['user_id'];
			$this->db->query($sql);
			$this->addItem($data);
		}
		$this->output();
	}	
	public function uploadToPicServer($file,$content_id)
	{
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
		$material = $this->material->addMaterial($file,$content_id); //插入图片服务器
		return $material;
	}
}
include(ROOT_PATH . 'excute.php');
?>
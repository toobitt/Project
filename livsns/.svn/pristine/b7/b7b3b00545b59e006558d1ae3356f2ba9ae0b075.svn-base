<?php
require('global.php');
define('MOD_UNIQUEID', 'share');
class userApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->obj = new share();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function index(){}
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit = " LIMIT " . $offset . "," . $count; 
		$order = " ORDER BY id DESC";
		$sql = "SELECT pu.*,t.access_token,t.openid,t.addtime,p.name plat_name FROM ".DB_PREFIX."plat_user pu " .
				"LEFT JOIN ".DB_PREFIX."token t  " .
						"ON pu.token = t.token " .
				"LEFT JOIN ".DB_PREFIX."plat p " .
						"ON pu.platId = p.id " .
				"WHERE 1 " . $condition . $order . $limit; 
		$q = $this->db->query($sql);
		while( false != ($row = $this->db->fetch_array($q)))
		{
			$row['access_token'] = json_decode($row['access_token'],1);
			$row['expired'] = !check_token_time($row['addtime'], $row['access_token']['expires_in']);
			$row['expired_time'] = date('Y-m-d H:i', $row['addtime']+$row['access_token']['expires_in']);
			$row['create_time'] = date('Y-m-d H:i', $row['create_time']);
			if($row['avatar'])
			{
				$row['avatar'] = array('host'=> $row['avatar'], 'dir'=>'', 'filepath' => '', 'filename' => '');
			}
            $row['mode_type'] = $row['mode_type'] ? unserialize($row['mode_type']) : array();
			$this->addItem($row);
		}
		$this->output();
	}
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NOID');	
		}
		$sql = "SELECT * FROM ".DB_PREFIX."plat_user WHERE 1 AND id = " . $id;
		$info = $this->db->query_first($sql);
		$info['roles'] = explode(',', $info['roles']);
		$this->addItem($info);
		$this->output();
	}
	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."plat_user WHERE 1  " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}
	public function get_condition()
	{
		$condition = '';
		if($this->input['platid'])
		{
			$condition .= " AND platId IN( " . $this->input['platid'] .")";
		}
		if($this->input['auth'] && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$sql = "SELECT user_id FROM ".DB_PREFIX."user_role WHERE role_id IN(".$this->user['slave_group'].")";
			$q = $this->db->query($sql);
			$user_id = array();
			while($row = $this->db->fetch_array($q))
			{
				$user_id[] = $row['user_id'];	
			}
			$user_id = implode(',', $user_id);
			$condition .= " AND (id IN('".$user_id."') OR user_id = " . $this->user['user_id'] .")";			
		}
		return $condition;
	}
	public function get_plat()
	{
		$app = $this->obj->get_app_by_systemId($this->user['appid']);
		$platdatas = $this->obj->get_plat_supportid('id,name,picurl,type',$app['platIds'],'id');
		$this->addItem($platdatas);	
		$this->output();
	}
	public function get_role()
	{
		include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
		$role = $this->auth->get_role_list(0,1000);
		if(is_array($role) && count($role) > 0)
		{
			foreach ( $role as $key => $value ) 
			{
       			$this->addItem( $value );
			}
		}
		$this->output();
	}
}
$out = new userApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>

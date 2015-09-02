<?php
define('MOD_UNIQUEID','gather_menu');//模块标识
require_once ('./global.php');
require_once (CUR_CONF_PATH . 'lib/gatherMenu.class.php');
class gatherMenuApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->gMenu = new gatherMenu();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE && !$this->user['prms']['app_prms'][APP_UNIQUEID]['is_complete'])
		{
			$this->guestShow();			
		}
		else
		{
			$this->adminShow();
			
		}
	}
	
	public function count()
	{
		if($this->user['group_type'] > MAX_ADMIN_TYPE && !$this->user['prms']['app_prms'][APP_UNIQUEID]['is_complete'])
		{
			$this->guestCount();
		}
		else
		{
			$this->adminCount();
		}
	}
	
	public function detail()
	{
		
	}
		
	private function adminShow()
	{
		$count = $this->input['page_num'] ? intval($this->input['page_num']) : 20 ;
		$pp	= $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$offset = intval(($pp - 1)*$count);		
		$list = $this->gMenu->adminShow($offset, $count);
		//分页信息
		$sql = 'SELECT count(*) as total FROM (SELECT DISTINCT(user_id) FROM '.DB_PREFIX.'gather WHERE user_id != 0)gather';
		$ret = $this->db->query_first($sql);
        $total_num = $ret['total'];//总的记录数
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$return['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$return['total_page']    = intval($total_num/$count) + 1;
		}
		$return['total_num'] = $total_num;//总的记录数
		$return['page_num'] = $count;//每页显示的个数
		$return['current_page']  = $pp;//当前页码
		
		$data['info'] = $list;
		$data['page_info'] = $return;
		$this->addItem($data);
		$this->output();
	}
	
	private function guestShow()
	{
		$count = $this->input['page_num'] ? intval($this->input['page_num']) : 20 ;
		$user_id = $this->user['user_id'] ? intval($this->user['user_id']) : 0;
		$pp	= $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页
		$offset = intval(($pp - 1)*$count);
		if ($this->user['prms'])
		{
			$nodes = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			$nodes[] = 0;
			if ($nodes && !empty($nodes) && is_array($nodes))
			{
				$sortIds = implode(',', $nodes);
			}
		}
		if ($user_id && $sortIds)
		{
			$list = $this->gMenu->guestShow($offset, $count, $user_id, $sortIds);
			//总数
			$sql = 'SELECT count(*) as total 
					FROM (SELECT FROM_UNIXTIME( create_time,  "%Y-%m-%d" ) AS format_date
					FROM '.DB_PREFIX.'gather WHERE user_id = '.$user_id.' AND sort_id IN ('.$sortIds.') GROUP BY format_date)gather';
			//echo $sql;exit;
			$ret = $this->db->query_first($sql);
			
			$total_num = $ret['total'];//总的记录数
			//总页数
			if(intval($total_num%$count) == 0)
			{
				$return['total_page']    = intval($total_num/$count);
			}
			else 
			{
				$return['total_page']    = intval($total_num/$count) + 1;
			}
			$return['total_num'] = $total_num;//总的记录数
			$return['page_num'] = $count;//每页显示的个数
			$return['current_page']  = $pp;//当前页码
			
			$data['info'] = $list;
			$data['page_info'] = $return;
			
			$this->addItem($data);
		}
		$this->output();
	}
	
	private function adminCount()
	{
		$sql = 'SELECT count(*) as total FROM (SELECT DISTINCT(user_id) FROM '.DB_PREFIX.'gather WHERE user_id != 0)gather';
		$res = $this->db->query_first($sql);
		echo json_encode($res);
	}
	
	private function guestCount()
	{
		$user_id = $this->user['user_id'] ? intval($this->user['user_id']) : 0;
		if ($this->user['prms'])
		{
			$nodes = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if ($nodes && !empty($nodes) && is_array($nodes))
			{
				$sortIds = implode(',', $nodes);
			}
		}
		if ($user_id && $sortIds)
		{
			$sql = 'SELECT count(*) as total 
					FROM (SELECT FROM_UNIXTIME( create_time,  "%Y-%m-%d" ) AS format_date
					FROM '.DB_PREFIX.'gather WHERE user_id = '.$user_id.' AND sort_id IN ('.$sortIds.') GROUP BY format_date)gather';
			//echo $sql;exit;
			$res = $this->db->query_first($sql);
			echo json_encode($res);
		}
	}
	//输出有权限的分类
	public function sorts()
	{
		$sort = array();
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms'])
			{
				$nodes = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
				if ($nodes && !empty($nodes) && is_array($nodes))
				{
					$sortIds = implode(',', $nodes);
					//查询分类
					$sql = 'SELECT id,name FROM '.DB_PREFIX.'sort WHERE id IN ('.$sortIds.')';
					$query = $this->db->query($sql);
					while ($row = $this->db->fetch_array($query))
					{
						$sort[] = $row;
					}
				}
			}
		}
		else 
		{
			$sql = 'SELECT id,name FROM '.DB_PREFIX.'sort';
			$query = $this->db->query($sql);
			while ($row = $this->db->fetch_array($query))
			{
				$sort[] = $row;
			}	
		}
		$this->addItem($sort);
		$this->output();
	}
}

$out = new gatherMenuApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

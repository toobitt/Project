<?php
require('global.php');
define('MOD_UNIQUEID','webvod_sort');
require_once(ROOT_PATH . 'frm/node_frm.php');
class webvodSortApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/webvod_sort.class.php');
		$this->obj = new webvodSort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function  show()
	{	
		//$this->verify_content_prms();
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):30;
		$limit = " limit {$offset}, {$count}";
		if($this->input['fid'])
		{
			$ret = $this->obj->show_webchannel($condition,$limit);
		}
		else
		{
			$ret = $this->obj->show($condition,$limit);
		}
		if(!empty($ret) && is_array($ret))
		{
			foreach($ret as $v)
			{
				$this->addItem($v);	
			}	
		}
		
		$this->output();	
	}
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{	
		if($this->input['fid'])
		{
			$sql = 'SELECT count(*) as total from '.DB_PREFIX.'categorys WHERE 1 '.$this->get_condition();
		}
		else
		{
			$sql = 'SELECT count(*) as total from '.DB_PREFIX.'categorys_tv WHERE 1 '.$this->get_condition();
		}
		$webvod_total = $this->db->query_first($sql);
		echo json_encode($webvod_total);	
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function get_condition()
	{		
		$condition = '';
		####增加权限控制 用于显示####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			if($this->user['prms']['app_prms']['webvod']['nodes'])
			{
				$cpids = '';
				$cpid_arr = $this->user['prms']['app_prms']['webvod']['nodes'];
				$cpids = implode(',',$cpid_arr);
			}
			$condition .= " AND cpid in  (".$cpids.")";	
		}
		####增加权限控制 用于显示####
		//查询应用分组
		if($this->input['fid'])
		{
			$condition .= " AND cpid  = ". $this->input['fid'];	
		}
		return $condition;
	}
	
		//获取选中的节点树状
	public function get_selected_node_path()
	{
		$ids = urldecode($this->input['id']);
		$sql = 'SELECT * from '.DB_PREFIX.'categorys_tv WHERE cpid IN (' . $ids . ')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$tree[$row['cpid']][$row['cpid']] = array(
					'id'		=> 		$row['cpid'],
					'name'		=> 		$row['cp_name'],
					'fid'		=> 		0,
					'parents'	=> 		$row['cpid'],
					'childs'	=> 		$row['cpid'],
					'is_last'	=> 		1,
					'depath'	=> 		1,
					'is_auth'	=> 		1,
			);
		}
		
		if(!$ids)
		{
			$this->errorOutput(NO_ID);
		}
		if($tree)
		{
			foreach($tree as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	function index()
	{	
	}
	function detail()
	{	
	}
}

$out = new webvodSortApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

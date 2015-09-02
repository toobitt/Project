<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','scenic_sort');//模块标识
class scenicSortApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->setNodeTable('scenic_sort');
		$this->setNodeVar('scenic_sort');
		include(CUR_CONF_PATH . 'lib/scenic_sort.class.php');
		$this->obj = new scenicSort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function  show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show($condition,$limit);
		foreach($ret as $v)
		{
			$this->addItem($v);	
		}
		$this->output();		
	}

	function detail()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'scenic_sort WHERE id = '.$this->input['id'];
		$r = $this->db->query_first($sql);
		$ret[] = $r;
		$this->addItem($ret);
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
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'scenic_sort WHERE 1 '.$this->get_condition();
		$templates_total = $this->db->query_first($sql);
		echo json_encode($templates_total);	
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
	
		if(intval($this->input['fid']))
		{
			$condition .=" AND fid =". intval($this->input['fid']);
		}
		else
		{
			$condition .=" AND fid = 0";
		}
		if($keyword = $this->input['keyword'])
		{
			$condition = " AND name like '%".$keyword."%' ";
		}
		return $condition;
	}
}

$out = new scenicSortApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

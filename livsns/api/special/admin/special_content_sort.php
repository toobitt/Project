<?php
//nousing
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','special_content_sort');//模块标识
class specialContentSortApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/special_content_sort.class.php');
		$this->obj = new specialContentSort();
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
		//if($special_id = intval($this->input['speid']))
		//{
		//	$str .=" AND special_id =". $special_id;
		//}
		$ret['sorts'] = $this->get_consort($str);
		$this->addItem($ret);	
		$this->output();		
	}

	function detail()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'special_content_sort WHERE id = '.$this->input['id'];
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
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'special_content_sort WHERE 1 '.$this->get_condition();
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
	public function get_condition()
	{	
		$condition = '';
		//查询应用分组
		//获取站点id或者父分类id
	 	//$sql = "SELECT site_id FROM ".DB_PREFIX."site_tem_sort WHERE id =".intval($this->input['_id']);
		//$r = $this->db->query_first($sql);
		if($speid = intval($this->input['speid']))
		{
			$condition .=" AND special_id =". $speid;
		}
		if(intval($this->input['consort']) && intval($this->input['consort']) != '-1')
		{
			$condition .=" AND id =". intval($this->input['consort']);
		}
		if(intval($this->input['fid']))
		{
			$condition .=" AND fid =". intval($this->input['fid']);
		}
		else
		{
			$condition .=" AND fid = 0";
		}
		if($this->input['k'])
		{
			$condition = " AND name like '%".urldecode($this->input['k'])."%' ";
		}
		return $condition;
	}
	
	//获取内容分类
	public function get_consort($str)	
	{	
		$sql = "SELECT id,name
				FROM  " . DB_PREFIX ."special_content_sort 
				WHERE 1".$str;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$info[$row['id']] = $row['name'];
		}
		return 	$info;
	}	
}

$out = new specialContentSortApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','mode_sort');//模块标识
class modeSortApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/mode_sort.class.php');
		$this->obj = new modeSort();
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	
	function show()
	{	
//		if($this->user['group_type'] > MAX_ADMIN_TYPE)
//		{
//			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
//			if(!in_array('mode_sort',$action))
//			{
//				$this->errorOutput("NO_PRIVILEGE");
//			}
//		}
		
		//$site_id = isset($this->input['site_id']) ? $this->input['site_id'] :1;
		//$condition = $this->get_condition($site_id);
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show($condition,$limit);	
		$sites = $this->pub->get_site();
		foreach ($sites as $k=>$v)
		{			
			$row[$v['id']] = $v['site_name'];
		}
		$ret[] = $row;
		$this->addItem($ret);	
		$this->output();		
	}

	function detail()
	{	
		$sql = 'SELECT id,name
				FROM '.DB_PREFIX.'cell_mode_sort WHERE id = '.intval($this->input['id']);
		$r = $this->db->query_first($sql);
		/*require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
		//取站点
		$sites = $this->pub->get_site();
		foreach ($sites as $k=>$v)
		{			
				$row[$v['id']] = $v['site_name'];
		}*/
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
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'cell_mode_sort WHERE 1 '.$this->get_condition();
		$mode_sorts_total = $this->db->query_first($sql);
		echo json_encode($mode_sorts_total);	
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
		//查询应用分组	
		if (intval($this->input['_id']))
		{	
				$condition .=" AND fid =".intval($this->input['_id']);	
		}	
		/*if($site_id)
		{	
			$condition .=" AND site_id =".$site_id;
		}
		/*if ($this->input['sites']!=''&&($this->input['sites']!= -1))
		{	
			$condition .= " AND site_id = ". intval($this->input['sites']) . "";		
		}*/	
		return $condition;
	}
	
	function index()
	{	
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
}

$out = new modeSortApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

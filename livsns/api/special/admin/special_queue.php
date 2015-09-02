<?php
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','special_queue');//模块标识
class specialQueueApi extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/special_queue.class.php');
		$this->obj = new specialQueue();
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
		$this->addItem($ret);	
		$this->output();		
	}

	function detail()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'special_queue WHERE id = '.$this->input['id'];
		$r = $this->db->query_first($sql);
		$ret[] = $r;
		$this->addItem($ret);
		$this->output();
	}
	
	
	function query()
	{	
		require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->puscont = new publishcontent();
		$data = array(
		);
		$ret = $this->puscont->get_content($data);
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
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'special_queue WHERE 1 '.$this->get_condition();
		$content_total = $this->db->query_first($sql);
		echo json_encode($content_total);	
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{	
		$condition = '';
		//查询应用分组
		//获取站点id或者父分类id
	 	//$sql = "SELECT site_id FROM ".DB_PREFIX."site_tem_sort WHERE id =".intval($this->input['_id']);
		//$r = $this->db->query_first($sql);
		if($this->input['site']&&(-1 != $this->input['site']))
		{
			$condition .=" AND site_id=".intval($this->input['site']);
		}
		/*if(intval($this->input['fid']))
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
		}*/
		return $condition;
	}
}

$out = new specialQueueApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

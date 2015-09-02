<?php
define('MOD_UNIQUEID','subway_service_sort');//模块标识
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class subwayServiceSortApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/subway_service_sort.class.php');
		$this->obj = new subwayServiceSort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show($condition,$limit);	
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}		
	}

	public function detail()
	{	
		$column_id = $this->input['id'];
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'subway_service_sort WHERE column_id = '.$column_id;
		$ret = $this->db->query_first($sql);
		$id = $ret['id'];
		//取所有的素材
		$sqlm = 'SELECT * FROM ' .DB_PREFIX. 'subway_materials WHERE cid = '.$id .' AND cid_type = 5';
		$qm = $this->db->query($sqlm);
		while ($rm = $this->db->fetch_array($qm))
		{
			if ($rm['mark'] == 'img')
			{
				$ret['indexpic'][] = array(
					'id'=>$rm['id'],
					'host'=>$rm['host'],
					'dir'=>$rm['dir'],
					'filepath'=>$rm['filepath'],
					'filename'=>$rm['filename'],
					'imgwidth'=>$rm['imgwidth'],
					'imgheight'=>$rm['imgheight'],
				);
			}
		}
		$re[] = $ret;
		$this->addItem($re);
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
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'subway_service_sort WHERE 1 '.$this->get_condition();
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
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		$condition .= " AND column_id !='' ";
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	
	public function index()
	{	
	}
}

$out = new subwayServiceSortApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

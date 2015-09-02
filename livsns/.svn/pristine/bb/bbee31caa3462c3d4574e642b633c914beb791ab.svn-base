<?php
define('MOD_UNIQUEID','subway_service');//模块标识
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class subwayServiceApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/news.class.php');
		$this->news = new news();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  show()
	{	
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		/*$sorts = $this->get_service_sort('1');
		if($sorts && is_array($sorts))
		{
			$arr = array_keys($sorts);
			$sort_ids = implode(',',$arr);
		}*/
		$data = array();
		$data = array(
			'app_uniqueid'	 => 	APP_UNIQUEID,
			'count' 		 => 	$count,
			'offset' 		 => 	$offset,
			'key' 		 	 => 	$this->input['k'],
			'article_status' => 	$this->input['state'],
			'date_search'	 => 	$this->input['date_search'],
			'start_time'	 => 	$this->input['start_time'],
			'end_time'	 	 => 	$this->input['end_time'],
		);
		$service_info = $this->news->show($data);
		$sorts = array();
		$sql_ = "select title,id from " . DB_PREFIX . "subway_service_sort where 1";
		$q = $this->db->query($sql_);
		
		while($row = $this->db->fetch_array($q))
		{
			$sorts[$row['id']] = $row['title']; 	
		}
		
		if($service_info && is_array($service_info))
		{
			foreach($service_info as $k=>$v)
			{
				
				$service_info[$k]['cre_time'] = date("Y-m-d H:i",$v['create_time']);
				$service_info[$k]['sort_name'] = $sorts[$v['para']];
			}
		}
		if(!empty($service_info))
		{
			foreach($service_info as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}		
	}

	public function detail()
	{	
		$id = $this->input['id'];
		$service_info = $this->news->detail($id);
		
		$this->addItem($service_info);
		$this->output();
	}
	
	public function get_service_sort($flag='')
	{	
		$sql_ = "select column_id as id,title from " . DB_PREFIX . "subway_service_sort   where 1 AND column_id !=0 ORDER BY order_id DESC ";	
		$q_ = $this->db->query($sql_);
		while($r = $this->db->fetch_array($q_))
		{
			$ret[$r['id']] = $r['title'];
		}
		if($flag)
		{
			return $ret;
		}
		else
		{
			$this->addItem($ret);
			$this->output();
		}
		
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
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		global $gGlobalConfig;
		$curl = new curl($gGlobalConfig['App_news']['host'],$gGlobalConfig['App_news']['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
	    $curl->addRequestData('a','count');
		$curl->addRequestData('app_uniqueid', APP_UNIQUEID);
		$curl->addRequestData('flag', '1');
		$curl->addRequestData('offset', $offset);
		$curl->addRequestData('count', $count);
		if($this->input['k'])
		{
			$curl->addRequestData('key', $this->input['k']);
		}
		if($this->input['state'])
		{
			$curl->addRequestData('article_status', $this->input['state']);
		}
		if($this->input['date_search'])
		{
			$curl->addRequestData('date_search', $this->input['date_search']);
		}
		if($this->input['start_time'])
		{
			$curl->addRequestData('start_time', $this->input['start_time']);
		}
		if($this->input['end_time'])
		{
			$curl->addRequestData('end_time', $this->input['end_time']);
		}
		$re = $curl->request('news.php');
		
		echo json_encode($re);	
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
		return $condition;
	}
	
	
	public function index()
	{	
	}
}

$out = new subwayServiceApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

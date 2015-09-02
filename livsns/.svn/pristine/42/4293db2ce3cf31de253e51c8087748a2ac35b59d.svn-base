<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH.'lib/weather.class.php';
define('MOD_UNIQUEID','weather');//模块标识
class weatherApi extends adminReadBase
{
	function __construct()
	{
		$this->mPrmsMethods = array(
		'manage'		=>'管理',
		);
		parent::__construct();
		$this->weather = new weather();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function index()
	{

	}
	public function show()
	{
		$this->verify_content_prms(array('_action'=>'manage'));
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY order_id ASC ';
		$condition = $this->get_condition();
		$data = $this->weather->show($condition,$orderby,$offset,$count);
		if (!empty($data) && is_array($data))
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}

	public function count()
	{
		$ret = $this->weather->count($this->get_condition());
		echo json_encode($ret);
	}
	private function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$keywords = $this->input['k'];
			$sql = 'SELECT id FROM '.DB_PREFIX.'weather_city WHERE name like "%'.$keywords.'%" or en_name like "%'.$keywords.'%" or abbr_name like "%'.$keywords.'%"';
			$query = $this->db->query($sql);
			$k = array();
			while ($row = $this->db->fetch_array($query))
			{
				$k[] = $row['id'];
			}
			if (!empty($k))
			{
				$k = implode(',', $k);
				$condition .= ' AND id IN ('.$k.') ';
			}
		}
		if ($this->input['_id'])
		{
			$id  = $this->input['_id'];
			//$sql = 'SELECT childs FROM '.DB_PREFIX.'weather_city WHERE id = '.$id;
			//$ret  = $this->db->query_first($sql);
			//$ids = $ret['childs'];
			//if ($ids)
			//{
			//$condition .= ' AND id IN ('.$ids.')';
			//}
			$condition .= ' AND id ='.intval($id);
		}
		return $condition;
	}

	public function detail()
	{
		$data = $this->weather->detail($this->input['id']);
		$fields = $this->weather->getField();
		$ret = array(
			'data' => $data,
			'fields' => $fields,
		);
		$this->addItem($ret);
		$this->output();
	}
	public function show_realtime()
	{

		$id = $this->input['id'];
		if (!$id)
		{
			return false;
		}
		$ret = $this->weather->show_realtime($id);
		$this->addItem($ret);
		$this->output();
	}

	public function selectpm()
	{
		$this->verify_content_prms(array('_action'=>'manage'));
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):1;
		$orderby = ' ORDER BY '.DB_PREFIX.'aqi_data.time_point DESC ';
		//查询条件
		$condition = '';

		if ($this->input['id'])
		{
			$id  = $this->input['id'];
			$sql = 'SELECT name FROM '.DB_PREFIX.'weather_city WHERE id = '.$id;
			$ret  = $this->db->query_first($sql);
			$names = trim($ret['name']);
			$condition .= ' AND area ='."'$names'";
		}
		//查询数据
		$limit = " limit {$offset}, {$count}";
		$sql='SELECT * FROM ' . DB_PREFIX . 'aqi_data WHERE 1 '.$condition.$orderby.$limit;
		$datapm = $this->db->query_first($sql);
		//$datapm['time_point']=date('Y-m-d H:i:s',$datapm['time_point']);
		$this->addItem($datapm);
		$this->output();
	}

	public function config_detail()
	{
		$id = $this->input['id'];
		$ret  = $this->weather->config_detial($id);
		$this->addItem($ret);
		$this->output();
	}
}

$ouput= new weatherApi();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
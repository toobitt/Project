<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/weather_img.class.php';
define('MOD_UNIQUEID','weather_img');//模块标识
class weatherImgApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->img = new imgWeather();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function index()
	{
		
	}
	public function show()
	{
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$ret = $this->img->show($condition,' ORDER BY id ASC ',$limit);
		if (!empty($ret))
		{
			foreach ($ret as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
		
	}
	public function get_condition()
	{
		$condition ='';
		if($this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		return $condition;
	}
	public function count()
	{
		$ret = $this->img->count($this->get_condition());
		echo json_encode($ret);
	}
	public function detail()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = intval($this->input['id']);
		$ret = $this->img->detail($id);
		$this->addItem($ret);
		$this->output();
	}
	public function get_many_material()
	{
		$offset = intval($this->input['start'])?intval($this->input['start']):0;
		$count = intval($this->input['num'])?intval($this->input['num']):100;
		$limit = "  LIMIT {$offset}, {$count}";
		$condition = $this->get_condition();
		$order = " ORDER BY id ASC ";
		$sql = "SELECT * FROM ".DB_PREFIX."weather_material WHERE 1 ".$condition.$order.$limit;
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$pic = array();
			if ($row['is_update'])
			{
				$pic = unserialize($row['user_img']);
			}else {
				$pic = unserialize($row['system_img']);
			}
			$row['pic'] = $pic;
			unset($row['user_img']);
			unset($row['system_img']);
			$k['material'][$row['id']] = $row;	
		}
		$this->addItem($k);
		$this->output();
	}
	
	public function get_apps()
	{
		$data = $this->img->get_apps();
		$this->addItem($data);
		$this->output();
	}	
}
$ouput = new weatherImgApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}else {
	$action = $_INPUT['a'];
}
$ouput->$action();
<?php
define('MOD_UNIQUEID','topic');
require('./global.php');
define('SCRIPT_NAME', 'topic');
require_once (CUR_CONF_PATH . 'lib/attach.class.php');
class topic extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		$this->attachlib = new attach();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$limit = '';
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 10;
		if($offset || $count)
		{
			$limit = " limit $offset,$count";
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'topic WHERE 1 '.$this->get_conditions() . ' ORDER BY order_id DESC, id DESC' .$limit;
		
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row['format_create_time'] = hg_tran_time($row['create_time']);
			$row['indexpic'] = ($tmp = unserialize($row['indexpic'])) ? $tmp  : array();
			$row['latest'] = ($tmp = json_decode($row['latest'],1)) ? $tmp  : array();
			$row['avatar'] = ($tmp = unserialize($row['avatar'])) ? $tmp : array();
			$this->addItem($row);
		}
		$this->output();
	}
	public function get_conditions()
	{
		$conditions = '';
		if($this->input['id'])
		{
			$ids = explode(',', $this->input['id']);
			foreach ($ids as $id)
			{
				if(!intval($id))
				{
					$this->errorOutput(PARAMETER_ERROR);
				}
			}
			$conditions .= ' AND id IN('.$this->input['id'].')';
		}
		if($this->input['user_id'])
		{
			$conditions .= ' AND user_id =  ' . $this->input['user_id'];
		}
		if($this->input['sid'])
		{
			$conditions .= ' AND sid =  ' . $this->input['sid'];
		}
		$distance = intval($this->input['distance']);
		$lon = $this->input['lon'];
		$lat = $this->input['lat'];
		$gpsx = $this->input['gpsx'];
		$gpsx = $this->input['gpsy'];
		if($lat && $lon)
		{
			$loc = FromBaiduToGpsXY($lat,$lon);
			$gpsx = $loc['x'];
			$gpsy = $loc['y'];
		}
		if ($gpsx && $gpsy && $distance)
		{
			$range = 180 / pi() * $distance / 6372.797; //里面的 $distance 就代表搜索 $distance 之内，单位km
			$lngR = $range / cos($gpsx * pi() / 180);
			//echo $range;exit()
			$maxLat = $gpsx + $range;//最大纬度
			$minLat = $gpsx - $range;//最小纬度
			$maxLng = $gpsy + $lngR;//最大经度
			$minLng = $gpsy - $lngR;//最小经度
			$condition 	.= ' AND gpsy >='.$minLng.' AND gpsy <='.$maxLng
			.' AND gpsx >='.$minLat.' AND gpsx <= '.$maxLat
			.' AND gpsy != 0.00000000000000 AND gpsx != 0.00000000000000 ';
		}
		return $conditions;
	}
	public function detail()
	{
		$sql = 'SELECT * FROM '  . DB_PREFIX . 'topic WHERE id = '.intval($this->input['id']);
		$data = $this->db->query_first($sql);
		if(!$data)
		{
			$this->errorOutput("话题不存在或被删除");
		}
		$sql = 'SELECT id,name FROM ' . DB_PREFIX . 'sort WHERE id = ' . $data['sid'];
		$sort = $this->db->query_first($sql);
		$data['sort_name'] = $sort['name'];
		$data['avatar'] = ($tmp = unserialize($data['avatar'])) ? $tmp : array();
		if($data['indexpic'])
		{
			$data['indexpic'] = unserialize($data['indexpic']);
		}
		if($data['aid'])
		{
			$data['material'] = $this->attachlib->get_attach_by_aid($data['aid']);
		}
		$this->addItem($data);
		$this->output();
	}
	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'topic where 1 ' . $this->get_conditions();
		exit(json_encode($this->db->query_first($sql)));
	}
}
include(ROOT_PATH . 'excute.php');
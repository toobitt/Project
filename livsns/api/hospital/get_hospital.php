<?php
require ('./global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','hospital');
define('SCRIPT_NAME', 'Hospital');
class Hospital extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function count(){}	
	
	public function show()
	{
		$cond = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT t1.id,t1.name,t1.address,t1.level,t1.hospital_id,t3.host,t3.dir,t3.filepath,t3.filename FROM " . DB_PREFIX . "hospital t1 
				LEFT JOIN " . DB_PREFIX . "materials t3
					ON t1.indexpic_id = t3.id
				WHERE 1 " . $cond . $limit; 
		$q = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($q)) 
		{	
			if($row['level'])
			{
				$row['level'] = $this->settings['hospital_level'][$row['level']];
			}
			
			if($row['host'] && $row['dir'] && $row['filepath'] && $row['filename'])
			{
				
				$row['indexpic'] = array(
					'host'		=> $row['host'],
					'dir'		=> $row['dir'],
					'filepath'	=> $row['filepath'],
					'filename'	=> $row['filename'],
				);
			}
			else 
			{
				$row['indexpic'] = array();
			}
			unset($row['host'],$row['dir'],$row['filepath'],$row['filename']);
			$this->addItem($row);
		}
		
		
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		//站点名称
		if($this->input['name'])
		{
			$condition .= ' AND t1.name LIKE "%'.trim($this->input['name']).'%"';
		}
		
		$condition .= " AND t1.status = 1";
		
		$condition .= ' ORDER BY t1.order_id  DESC ';
		
		return $condition ;
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT t1.name,t1.level,t1.address,t1.baidu_longitude,t1.baidu_latitude,t1.hospital_id,t1.telephone,t1.traffic,t2.content FROM " . DB_PREFIX . "hospital t1 
				LEFT JOIN " . DB_PREFIX . "content t2 
					ON t1.id = t2.cid 
				WHERE t1.status =1 AND t1.id = {$id}";
		$data = $this->db->query_first($sql);
		
		if($data['telephone'])
		{
			$data['telephone'] = @unserialize($data['telephone']);
		}
		else 
		{
			$data['telephone'] = array();
		}
		
		//ios传入gps坐标，转百度坐标
		if($this->input['jd'] || $this->input['wd'])
		{
			//gps坐标转百度坐标
			$baidu_zuobiao = GpsToBaidu($this->input['jd'],$this->input['wd']);
			$this->input['baidu_longitude'] = $baidu_zuobiao['x'];
			$this->input['baidu_latitude'] = $baidu_zuobiao['y'];
		}
		
		if($data['baidu_longitude'] && $data['baidu_latitude'] && $this->input['baidu_longitude'] && $this->input['baidu_latitude'])
		{
			//计算距离
			$data['distance'] = GetDistance($data['baidu_latitude'], $data['baidu_longitude'], $this->input['baidu_latitude'], $this->input['baidu_longitude'], 2);
			$data['distance'] .= 'KM';
		}
		else 
		{
			$data['distance'] = '距离不祥';
		}
		
		//医院等级
		if($data['level'])
		{
			$data['level'] = $this->settings['hospital_leval'][$data['level']];
		}
			
		//获取图片信息
		$sql = 'SELECT host,dir,filepath,filename FROM '.DB_PREFIX.'materials  WHERE cid = '.$id.' ORDER BY id DESC';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{				
			$data['pic_info'][] = $row;
		}	
		$this->addItem($data);
		$this->output();
	}
	
	public function yuyue_rule()
	{
		$hospital_id = intval($this->input['hospital_id']);
		if(!$hospital_id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT yuyue_rule FROM " . DB_PREFIX . "hospital WHERE id = {$hospital_id}";
		$res = $this->db->query_first($sql);
		
		$data['about'] = $res['yuyue_rule'] ? $res['yuyue_rule'] : '';
		
		$this->addItem($data);
		
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>
<?php
define('MOD_UNIQUEID','lbs_sort');//模块标识
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/lbs_node.class.php') ;
class LBSSortApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->sort = new ClassLBSSort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$orderby = ' ORDER BY order_id  DESC';
		$res = $this->sort->show($this->get_condition(),$orderby);
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}

	//lbs新接口
	public function show_new()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 10;
		
		$limit = " limit {$offset}, {$count}";
		$orderby = ' ORDER BY order_id  ASC';
		$field = 'id,name,fid,is_last,image';
		$res = $this->sort->show($this->get_condition(),$orderby,$field,$limit);
		if (!empty($res))
		{
			foreach ($res as $val)
			{
				if(!$val['is_last'])
				{
					$fid[] = $val['id'];
				}
				$data[$val['id']] = $val;
			}
		}
		if($fid)
		{
			$sql = "SELECT id,name,fid,image FROM " . DB_PREFIX . "sort WHERE fid IN (" . implode(',', $fid) . ") ORDER BY order_id ASC";
			$q = $this->db->query($sql);
			while ($row = $this->db->fetch_array($q))
			{
				if($row['image'])
				{
					$row['image'] = $row['image'] ? unserialize($row['image']) : '';
				}
				
				if($data[$row['fid']])
				{
					//if(count($data[$row['fid']]['subset'])<6)
					//{
						$data[$row['fid']]['subset'][] = $row;
					//}
				}
			}
		}
		
		if($data)
		{
			foreach ($data as $v)
			{
				$this->addItem($v);
			}
		}
		
		$this->output();
	}
	
	public function get_all_sort()
	{
		if ($this->input['exclude_id'])
		{
			$condition .= ' AND id NOT IN (' . intval($this->input['exclude_id']) . ')';
		}
		$fid = intval($this->input['fid']);
		if($fid)
		{
			$condition .= ' AND fid = ' . $fid;
		}
		else 
		{
			$this->errorOutput('fid不存在');
		}
		
		$selected_id = '';
		$id = intval($this->input['id']);
		
		$selected_id = $id ? $id : $fid;
		
		$orderby = ' ORDER BY order_id  ASC';
		$field = 'id,name,fid,is_last';
		
		$sql = "SELECT name FROM " . DB_PREFIX . "sort WHERE id = " . $fid; 
		$fid_name = $this->db->query_first($sql);
		$sort[$fid] = array(
			'id' 		=> $fid,
			'name'		=> '全部'.$fid_name['name'],
			'selected'	=> $fid == $selected_id ? 1 : 0,
		);
		$res = $this->sort->show($condition,$orderby,$field);
		if (!empty($res))
		{
			foreach ($res as $val)
			{
				if(!$val['is_last'])
				{
					$fids[] = $val['id'];
				}
				$sort[$val['id']]['id'] 		= $val['id'];
				$sort[$val['id']]['name'] 		= $val['name'];
				$sort[$val['id']]['selected']	= $val['id'] == $selected_id ? 1 : 0;
			}
		}
		
		if($fids)
		{
			$sql = "SELECT id,name,fid FROM " . DB_PREFIX . "sort WHERE fid IN (" . implode(',', $fids) . ") ORDER BY order_id ASC";
			$q = $this->db->query($sql);
			while ($row = $this->db->fetch_array($q))
			{
				
				if($sort[$row['fid']])
				{
					$sort[$row['fid']]['subset'][] = $row;
				}
			}
		}
		
		if(!empty($sort))
		{
			foreach ($sort as $v)
			{
				$data['sort'][] = $v;
			}
		}
		$city_name = $this->input['city'];
		
		$lbs_distance = $this->settings['lbs_distance'];
		if($lbs_distance)
		{
			foreach ($lbs_distance as $k => $v)
			{
				$distance_arr[] = array(
					'id' 		=> $k,
					'name'		=> $v,
				);
			}
		}
		$data['areas'][0] = array(
			'id'		=> 0,
			'name'		=> '附近',
			'subset'	=> $distance_arr,
		);
		if($city_name)
		{
			
			$sql = "SELECT id FROM " . DB_PREFIX . "city WHERE city LIKE '%" . $city_name . "%'";
			$city_id = $this->db->query_first($sql);
			
			if($city_id['id'])
			{
				$sql = "SELECT id,area FROM " . DB_PREFIX . "area WHERE city_id = " . $city_id['id'];
				$q = $this->db->query($sql);
				while ($r = $this->db->fetch_array($q))
				{
					$data['areas'][] = array(
						'id'		=> $r['id'],
						'name'		=> $r['area'],
						'subset'	=> array(
							'id'	=> $r['id'],
							'name'	=> '全部',
						),
					);
				}
			}
		}
		if($data)
		{
			foreach ($data as $k => $v)
			{
				$this->addItem_withkey($k, $v);
			}
		}
		
		$this->output();
	}

	public function get_condition()
	{
		$condition = '';
		if ($this->input['id'])
		{
			$condition .= ' AND fid = '.intval($this->input['id']);
		}
		else
		{
			$condition .= ' AND fid = 0';
		}
		if ($this->input['exclude_id'])
		{
			$condition .= ' AND id NOT IN (' . $this->input['exclude_id'] . ')';
		}
		return $condition;
	}

	public function count()
	{

	}

	public function detail()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->sort->detail($id);
		$this->addItem($data);
		$this->output();
	}
}
$ouput = new LBSSortApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>
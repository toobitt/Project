<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/contribute.class.php';
define('MOD_UNIQUEID','contribute');//模块标识
class contributeApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->con = new contribute();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])	 : 10;
		$orderby = ' ORDER BY c.order_id  DESC';
		$res = $this->con->show($this->get_condition(),$orderby,$offset,$count, intval($this->input['getbody']));
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				if (!$this->input['showtel'])
				{
					unset($val['tel']);
				}
				
				if (is_numeric($val['user_name']) && strlen($val['user_name'])==11)
	         	{
	         		$val['user_name'] = str_replace(substr($val['user_name'], 3,4), '****', $val['user_name']);
	         	}
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		$since_id = intval($this->input['since_id']);
		if ($since_id)
		{
			$condition = ' AND c.id > ' . $since_id;
		}
		
		/**************权限控制开始**************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['flag'])
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND c.user_id = '.$this->user['user_id'];//不允许查看他人数据
			}
			elseif ($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_org'])
			{
				$condition .= ' AND c.org_id IN (' . $this->user['slave_org'] .')';//查看组织内的数据
			}
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				$authnode_str = '';
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if ($authnode_str === '0')
				{
					$condition .= ' AND c.sort_id IN(' . $authnode_str . ')';
				}
				if ($authnode_str)
				{
					$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
					$sql = 'SELECT id,childs FROM '.DB_PREFIX.'sort WHERE id IN('.$authnode_str.')';
					$query = $this->db->query($sql);
					$authnode_array = array();
					while($row = $this->db->fetch_array($query))
					{
						$authnode_array[$row['id']]= explode(',', $row['childs']);
					}
					$authnode_str = '';
					foreach ($authnode_array as $node_id=>$n)
					{
						if($node_id == intval($this->input['_id']))
						{
							$node_father_array = $n;
							if(!in_array(intval($this->input['_id']), $authnode))
							{
								continue;
							}
						}
						$authnode_str .= implode(',', $n) . ',';
					}
					$authnode_str = true ? $authnode_str . '0' : trim($authnode_str,',');
					if(!$this->input['_id'])
					{
						$condition .= ' AND c.sort_id IN(' . $authnode_str . ')';
					}
					else
					{
						$authnode_array = explode(',', $authnode_str);
						if(!in_array($this->input['_id'], $authnode_array))
						{
							if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
							{
								
								$this->errorOutput(NO_PRIVILEGE);
							}
							$condition .= ' AND c.sort_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
				
			}
		}

		/**************权限控制结束**************/
		
		if ($this->input['id'])
		{
			$condition .= ' AND c.id = '.intval($this->input['id']);
		}
		if ($this->input['sort_id'])
		{
			//$condition .= ' AND c.sort_id = '.intval($this->input['sort_id']);
			$sort_id = trim($this->input['sort_id']);
			$sql = 'SELECT childs FROM '.DB_PREFIX.'sort WHERE id IN ('.$sort_id.')';
			
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$sort_ids .= $r['childs'].',';
			}
			if ($sort_ids)
			{
				$condition .= ' AND c.sort_id IN ( '.trim($sort_ids,',') . ')';
			}
			else
			{
				$condition .= ' AND c.sort_id IN ('.$sort_id.')';
			} 
		}
		if ($this->input['self'])
		{
			$condition .= ' AND c.user_id='.intval($this->user['user_id']);
		}
		if ($this->input['title'])
		{
			$condition .= ' AND c.title LIKE "%'.addslashes($this->input['title']).'%"';
		}
		if ($this->input['user_id'])
		{
			$condition .= ' AND c.user_id = '.intval($this->input['user_id']);
			//取新会员个人数据
			if ($this->input['new_member'] == 1)
			{
				$condition .= ' AND c.new_member = 1 ';
			}
			else 
			{
				$condition .= ' AND c.new_member = 0 ';
			}
		}
		if ($this->input['user_name'])
		{
			$condition .= ' AND c.user_name LIKE "'.addslashes($this->input['user_name']).'"';
		}
		if ($this->input['audit'])
		{
			$condition.= ' AND c.audit IN ('.trim($this->input['audit']).')';
		}
		if ($this->input['start_time'])
		{
			$condition.= ' AND c.create_time > '.intval($this->input['start_time']);
		}
		if ($this->input['end_time'])
		{
			$condition.= ' AND c.create_time < '.intval($this->input['end_time']);
		}
		//1是已发布，2是未发布
		if ($this->input['is_pub'])
		{
			$condition .= ' AND c.expand_id != 0';
		}
		return $condition;
	}
	
	public function count()
	{
		$ret = $this->con->count($this->get_condition());
		echo json_encode($ret);
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->con->detail($id);
		//对手机帐户名就行处理
        if (is_numeric($data['user_name']) && strlen($data['user_name'])==11)
        {
        	$data['user_name'] = str_replace(substr($data['user_name'], 3,4), '****', $data['user_name']);
        }
		if (!$this->input['showtel'])
		{
			unset($data['tel']);
		}
		$this->addItem($data);
		$this->output();
	}
	public function sort()
	{
		$id = intval($this->input['id']);
		$exclude_id = $this->input['exclude_id'];
		$limit_id = $this->input['limit_id'];
		$fid = $this->input['fid'];
		$flag = $this->input['flag'];
		if ($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			$flag = 0;
		}
		
		switch($this->input['order'])
		{ 
		   case 1: 
			  $orderby = ' ORDER BY satisfy_score-unsatisfy_score DESC';
		   break;
           case 2:
			  $orderby = ' ORDER BY satisfy_score-unsatisfy_score ASC';
		   break;
		   default:
			  $orderby = ' ORDER BY order_id ASC';
		   break;
		}
		$data = $this->con->sort($id, $exclude_id, $flag, $this->user, $limit_id, $fid,$orderby);
		if (!empty($data))
		{
			foreach ($data as $k=>$v)
			{	
				$this->addItem($v);
			}
		}
		$this->output();
	}
	public function fastInput()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(noid);
		}
		$id = $this->input['id'];
		$data = $this->con->fastInput($id);
		if ($data)
		{
			foreach ($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function getConfig()
	{
		$this->addItem(array('is_verifycode' => IS_VERIFYCODE));
		$this->output();
	}
	
	public function get_address_by_xy()
	{
		if(!$this->input['location'])
		{
			$this->errorOutput(NO_DATA);
		}
		$url = BAIDU_GEOCODER_DOMAIN . 'ak='  . BAIDU_AK. '&location=' .$this->input['location']. '&output=json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$address = json_decode($response,1);
		$address_arr = $address['result']['addressComponent'];
		if($address_arr)
		{
			$street = $address_arr['district'].$address_arr['street'].$address_arr['street_number'];
		}
		$this->addItem(array('address' => $street));
		$this->output();
	}
}
$ouput = new contributeApi();
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
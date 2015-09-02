<?php
define('MOD_UNIQUEID','lottery');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/lottery_mode.php');
class lottery extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'创建',
		'update'	=>'修改',
		'delete'	=>'删除',
		'audit'		=>'审核',
		'_node'         => array(
			'name'=>'抽奖分类',
			'filename'=>'sort.php',
			'node_uniqueid'=>'sort',
		),
		);
		parent::__construct();
		$this->mode = new lottery_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		
		/**************权限控制开始**************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND l.user_id = '.$this->user['user_id'];//不允许查看他人数据
			}
			elseif ($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_org'])
			{
				$condition .= ' AND l.org_id IN (' . $this->user['slave_org'] .')';//查看组织内的数据
			}
			if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
			{
				$authnode_str = '';
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if ($authnode_str === '0')
				{
					$condition .= ' AND l.sort_id IN(' . $authnode_str . ')';
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
						$authnode_str .= implode(',', $n) .',';
					}
					//显示无分类
					$authnode_str = $authnode_str . '0';
					
					if(!$this->input['_id'])
					{
						$condition .= ' AND l.sort_id IN(' . $authnode_str . ')';
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
							$condition .= ' AND l.sort_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
			}
		}
		/**************权限控制结束**************/
		
		//搜索标签
        if ($this->input['searchtag_id']) {
            $searchtag = $this->searchtag_detail(intval($this->input['searchtag_id']));
            foreach ((array)$searchtag['tag_val'] as $k => $v) {
                if ( in_array( $k, array('_id') ) )
                {
                    //防止左边栏分类搜索无效
                    continue;
                }
                $this->input[$k] = $v;
            }
        }
        
		if($this->input['id'])
		{
			$condition .= " AND l.id IN (".($this->input['id']).")";
		}
		if(isset($this->input['status']) && $this->input['status'] != -1)
		{
			$status = intval($this->input['status']);
			$condition .= " AND l.status = ".$status;
		}
		
		if($this->input['_id'])
		{
			$sql = " SELECT childs,fid FROM ".DB_PREFIX."sort WHERE  id = '".$this->input['_id']."'";
			$arr = $this->db->query_first($sql);
			if($arr)
			{
				$condition .= " AND l.sort_id IN (".$arr['childs'].")";
			}
		}
		
		if($this->input['user_name'])
		{
			$condition .= ' AND  l.user_name  = "'.trim(($this->input['user_name'])).'"';
		}
		if($this->input['key'] || trim(($this->input['key']))== '0')
		{
			$condition .= ' AND  l.title  LIKE "%'.trim(($this->input['key'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND l.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND l.create_time <= '".$end_time."'";
		}
		
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
					$condition .= " AND  l.create_time > '".$yesterday."' AND l.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  l.create_time > '".$today."' AND l.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  l.create_time > '".$last_threeday."' AND l.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  l.create_time > '".$last_sevenday."' AND l.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	public function detail()
	{
		$this->verify_content_prms(array('_action'=>'show'));
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	
	public function lottery()
	{
		$id = intval($this->input['id']);
		
		if(!$id)
		{
			return false;
		}
		
		
		$data = $this->mode->detail($id);
		
		
		//查询活动中奖记录
		$sql = "SELECT w.*,p.name,p.type,p.prize FROM " . DB_PREFIX . "win_info w
				LEFT JOIN " . DB_PREFIX . "prize p 
					ON w.prize_id = p.id 
				WHERE w.lottery_id = " . $data['id'] . " 
					AND w.prize_id != '' 
				ORDER BY w.create_time DESC LIMIT 0,2";
		
		$q = $this->db->query($sql);
		$info = array();
		$member_id = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['create_time']	= hg_tran_time_tv($r['create_time']);
			$info[] 			= $r;
			$member_id[] 		= $r['member_id'];
		}
		if(!empty($member_id))
		{
			include_once CUR_CONF_PATH . 'lib/win_info_mode.php';
			$obj = new win_info_mode();
			$member_info = $obj->get_memberInfo($member_id);
		}
		
		
		$arr = $prize = $award = array();
		
		if(!empty($info) && $member_info)
		{
			$win_info = array();
			foreach ($info as $val)
			{
				foreach ($val as $k => $v)
				{
					if($k == 'member_id' && $member_info[$v])
					{
						$val['member_name'] 	= $member_info[$v]['member_name'];
						$val['phone_num']	 	= $member_info[$v]['phone_num'];
						$val['avatar']	 		= $member_info[$v]['avatar'];
					}
				}
				$win_info[] = $val;
			}
			
			$award['win_info'] = $win_info;
		}
		
		$feedback 	= $data['feedback'];
		$prize_arr 	= $data['prize'];
		//unset($data['prize'],$data['feedback']);
		
		foreach ($prize_arr as $key => $val) 
		{ 
			$chance = array();
			$chance = explode('/', $val['chance']);
			
		    $arr[$val['id']] = $chance[0]; 
		    
		    if(!$sum)
		    {
		    	$sum = $chance[1];
		    }
		} 
		$sum = $sum ? $sum : 1000;
		$prize_id = get_rand($arr,$sum); //根据概率获取奖项id 
		 
		
		
		if($prize_id)
		{
			$prize = $prize_arr[$prize_id];
			
			$prize_indexpic = array(
				'host' 		=> $prize['host'],
				'dir'		=> $prize['dir'],
				'filepath'	=> $prize['filepath'],
				'filename'	=> $prize['filename'],
			);
			$award['id'] 	= $prize_id; //奖品名称
			$award['prize'] = $prize['prize']; //奖品名称
			$award['name'] 	= $prize['name']; //奖项名称
			$award['tip'] 	= $prize['tip']; //奖品名称
			$award['prize_indexpic'] = $prize_indexpic; //奖品索引图
		}
		else 
		{
			$award['id'] 	= 0; //奖品id
			$feedback_count = count($feedback);
			$rand_num = mt_rand(0, $feedback_count-1);
			$award['tip'] 	= $feedback[$rand_num]; //奖品名称
			$award['name'] 	= '谢谢参与'; //奖项名称
		}
			
		$this->addItem($award);
		
		$this->output();
			
	}
	
	//根据地图坐标获取地址信息
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

$out = new lottery();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>
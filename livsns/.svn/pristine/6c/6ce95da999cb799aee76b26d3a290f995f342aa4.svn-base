<?php
define('MOD_UNIQUEID','verify_code');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/verify_code_mode.php');
class verify_code extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new verify_code_mode();
		$this->mPrmsMethods = array(
			'manage_verify_see'		=>'查看',
			'manage_verify_change'	=>'管理',
			'audit'					=>'审核',
		);
		
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
		$orderby = '  ORDER BY v.order_id DESC,v.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$v['create_time'] = date('Y-m-d H:i',$v['create_time']);
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
		//权限
		$this->verify_content_prms(array('_action'=>'manage_verify_see'));
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND v.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND v.org_id IN (' . $this->user['slave_org'] .')';
			}
		}
		
		if($this->input['id'])
		{
			$condition .= " AND v.id IN (".($this->input['id']).")";
		}
		if($this->input['_id'])
		{
			$condition .= " AND v.type_id IN (".($this->input['_id']).")";
		}
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  v.name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		if($this->input['state'] >= '0')
		{
			$condition .= " AND v.status = " . $this->input['state'];
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND v.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND v.create_time <= '".$end_time."'";
		}
		
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND v.weight >=" . $this->input['start_weight'];
		}
		
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND v.weight <= " . $this->input['end_weight'];
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
					$condition .= " AND  v.create_time > '".$yesterday."' AND v.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  v.create_time > '".$today."' AND v.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  v.create_time > '".$last_threeday."' AND v.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  v.create_time > '".$last_sevenday."' AND v.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$condition = " AND v.id = ".$this->input['id'];
			//权限
			//$this->verify_content_prms(array('_action'=>'manage_verify_change'));
			/**************编辑数据权限判断***************
			$sql = "select * from " . DB_PREFIX ."verify where id = " . $this->input['id'];
			$q = $this->db->query_first($sql);
			$info['id'] = $q['id'];
			$info['org_id'] = $q['org_id'];
			$info['user_id'] = $q['user_id'];
			$info['_action'] = 'manage_verify_change';
			$this->verify_content_prms($info);
			*********************************************/
			if($this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				//查看他人数据
				if(!$this->user['prms']['default_setting']['show_other_data'])
				{
					$condition .= ' AND v.user_id = '.$this->user['user_id'];
				}
				else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
				{
					$condition .= ' AND v.org_id IN (' . $this->user['slave_org'] .')';
				}
			}
			
			$ret = $this->mode->detail($this->input['id'],$condition);
			if($ret)
			{
				//字体大小
				if(substr_count($ret['fontsize'],','))
				{
					$ret['is_size'] = 1;
					$ret['fontsize'] = explode(',',$ret['fontsize']);
				}
				else
				{
					$ret['is_size'] = 0;
				}
				//旋转角度
				$angel_tmp = explode(',',$ret['angle']);
				if($angel_tmp[0] ==0 && $angel_tmp[1] ==0)
				{
					$ret['is_angle'] = 0;
				}
				else
				{
					$ret['is_angle'] = 1;
				}
				$ret['angle'] = $angel_tmp;
				//字体颜色
				if($ret['fontcolor'] == 1)
				{
					$ret['is_color'] = 1;
					//$ret['fontcolor'] = '#'.dechex(rand(0,255)).dechex(rand(0,255)).dechex(rand(0,255));
				}
				else
				{
					$ret['is_color'] = 0;
				}
				//背景
				if($ret['bgpicture_id'])
				{
					$ret['is_bgcolor'] = 1;
					$verify = $this->settings['App_verifycode'];
					$ret['src'] = $verify['protocol'].$verify['host'].'/'.$verify['dir'].'data/pictures/'.$ret['bg_pic'].'.'.$ret['pic_type'];
				}
				else
				{
					$ret['is_bgcolor'] = 0;
				}
				//宽高
				if($ret['width'] ==0 && $ret['height'] ==0)
				{
					$ret['is_wid_hei'] = 1;
				}
				else
				{
					$ret['is_wid_hei'] = 0;
				}
				$this->addItem($ret);
				$this->output();
			}
		}
		else
		{
			$this->errorOutput(NOID);
		}
	}
	
	/**
	 * 预览
	 * Enter description here ...
	 */
	public function preview()
	{
		$parameter = $this->input['parameter'];
		$parameter = json_decode($parameter,1);
		require_once(CUR_CONF_PATH.'lib/captche.class.php');
		$captche = new Captche($parameter);
		//向浏览器输出图像
		$captche->showImage();
	}
}

$out = new verify_code();
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
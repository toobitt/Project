<?php
define('MOD_UNIQUEID','special_offer');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/special_offer_mode.php');
require_once(ROOT_PATH .'lib/class/recycle.class.php');
class special_offer_update extends adminUpdateBase
{
	private $mode;
	private $recycle;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new special_offer_mode();
		$this->recycle = new recycle();
		/******************************权限*************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$actions = (array)$this->user['prms']['app_prms']['supermarket']['action'];
			if(!in_array('manger',$actions))
			{
				$this->errorOutput('您没有权限访问此接口');
			}
		}
		/******************************权限*************************/
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if(!$this->input['market_id'])
		{
			$this->errorOutput(NOID);
		}
		
		if (!$this->input['title'])
		{
			$this->errorOutput(NO_TITLE);
		}
		
		if (!$this->input['start_time'])
		{
			$this->errorOutput('没有开始时间');
		}
		else 
		{
			$start_time = strtotime($this->input['start_time']);
		}
		
		if (!$this->input['end_time'])
		{
			$this->errorOutput('没有结束时间');
		}
		else 
		{
			$end_time = strtotime($this->input['end_time']);
		}
		
		if($start_time >= ($end_time + 24 * 3600))
		{
			$this->errorOutput('开始时间不能大于结束时间');
		}

		$activity_store = '';
		if($this->input['activity_store'] && is_array($this->input['activity_store']))
		{
			$activity_store = implode(',',$this->input['activity_store']);
		}

		$data = array(
			'market_id' 		=> $this->input['market_id'],
			'title'				=> $this->input['title'],
			'start_time'		=> $start_time,
			'end_time'			=> $end_time,
			'store_id'			=> $activity_store,
			'user_name'			=> $this->user['user_name'],
			'user_id'			=> $this->user['user_id'],
			'update_user_name' 	=> $this->user['user_name'],
			'update_user_id' 	=> $this->user['user_id'],
			'org_id' 			=> $this->user['org_id'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'ip'				=> hg_getip(),
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建特惠活动',$data,'','创建特惠活动' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		if (!$this->input['title'])
		{
			$this->errorOutput(NO_TITLE);
		}
		
		if (!$this->input['start_time'])
		{
			$this->errorOutput('没有开始时间');
		}
		else 
		{
			$start_time = strtotime($this->input['start_time']);
			
		}
		
		if (!$this->input['end_time'])
		{
			$this->errorOutput('没有结束时间');
		}
		else 
		{
			$end_time = strtotime($this->input['end_time']);
		}
		
		if($start_time >= ($end_time + 24 * 3600))
		{
			$this->errorOutput('开始时间不能大于结束时间');
		}
		
		$activity_store = '';
		if($this->input['activity_store'] && is_array($this->input['activity_store']))
		{
			$activity_store = implode(',',$this->input['activity_store']);
		}
		
		$update_data = array(
			'title'				=> $this->input['title'],
			'start_time'		=> $start_time,
			'end_time'			=> $end_time,
			'store_id'			=> $activity_store,
			'update_user_name' 	=> $this->user['user_name'],
			'update_user_id' 	=> $this->user['user_id'],
			'update_time'		=> TIMENOW,
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新特惠活动',$ret,'','更新特惠活动' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			foreach ($ret AS $k => $v)
			{
				//记录回收站的数据
				$recycle[$v['id']] = array(
					'title' 		=> $v['title'],
					'delete_people' => $this->user['user_name'],
					'cid' 			=> $v['id'],
					'content'		=> array('special_offer_activity' => $v),
				);
			}
			
			/********************************回收站***********************************/
			if($recycle)
			{
				foreach($recycle as $key => $value)
				{
					$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				}
			}
			/********************************回收站***********************************/

			$this->addLogs('删除特惠活动',$ret,'','删除特惠活动' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new special_offer_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>
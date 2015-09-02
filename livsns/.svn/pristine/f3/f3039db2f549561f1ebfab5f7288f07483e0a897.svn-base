<?php
require( 'global.php');

class activityUpdate extends outerUpdateBase
{
function __construct()
	{
		parent::__construct();
		require_once  CUR_CONF_PATH.'lib/activity.class.php';
		$this->libactivity = new activityLib();
		require_once (ROOT_PATH . 'lib/class/team.class.php');
		$this->team = new team();
		require_once (ROOT_PATH . 'lib/class/mark.class.php');
		$this->libmark = new mark();
		require_once (ROOT_PATH . 'lib/class/option.class.php');
		$this->liboption = new option();
	}
	
	//参数获取
	public function getData()
	{
		$data = array();
		$data = $this->libactivity->checkUserExit();
		//活动id
		if($this->input['action_id'])
		{
			$data['action_id'] = trim($this->input['action_id']);
		}
		//名称
		if($this->input['action_name'])
		{
			$data['action_name'] = trim(htmlspecialchars_decode(urldecode($this->input['action_name'])));
		}
		//分类
		if($this->input['action_sort'])
		{
			$data['action_sort'] = trim(htmlspecialchars_decode(urldecode($this->input['action_sort'])));
		}
		//活动logo
		if($this->input['action_img'])
		{
			$data['action_img'] = trim(htmlspecialchars_decode(urldecode($this->input['action_img'])));
		}
		//活动报名截至时间
		if($this->input['register_time'])
		{
			$data['register_time'] = trim(htmlspecialchars_decode(urldecode($this->input['register_time'])));
		}
		//活动开始时间
		if($this->input['start_time'])
		{
			$data['start_time'] = trim(htmlspecialchars_decode(urldecode($this->input['start_time'])));
		}
		//活动结束时间
		if($this->input['end_time'])
		{
			$data['end_time'] = trim(htmlspecialchars_decode(urldecode($this->input['end_time'])));
		}
		//活动地点
		if($this->input['province'])
		{
			$data['province'] = trim(htmlspecialchars_decode(urldecode($this->input['province'])));
		}
		if($this->input['city'])
		{
			$data['city'] = trim(htmlspecialchars_decode(urldecode($this->input['city'])));
		}
		if($this->input['area'])
		{
			$data['area'] = trim(htmlspecialchars_decode(urldecode($this->input['area'])));
		}
		if($this->input['address'])
		{
			$data['address'] = trim(htmlspecialchars_decode(urldecode($this->input['address'])));
		}
		//活动简介
		if($this->input['summary'])
		{
			$data['summary'] = trim(htmlspecialchars_decode(urldecode($this->input['summary'])));
		}
		//活动简介
		if($this->input['slogan'])
		{
			$data['slogan'] = trim(htmlspecialchars_decode(urldecode($this->input['slogan'])));
		}
		//分组id
		if($this->input['team_id'])
		{
			$data['team_id'] = trim(htmlspecialchars_decode(urldecode($this->input['team_id'])));
		}
		//分组id
		if($this->input['location'])
		{
			$data['location'] = trim(htmlspecialchars_decode(urldecode($this->input['location'])));
		}
		//分组id
		if($this->input['review'])
		{
			$data['review'] = trim(htmlspecialchars_decode(urldecode($this->input['review'])));
		}
		//分组id
		if($this->input['team_type'])
		{
			$data['team_type'] = trim(htmlspecialchars_decode(urldecode($this->input['team_type'])));
		}
		/**报名用户设置**/
		//报名是否需要审核
		
		if($this->input['need_info'])
		{
			$data['need_info'] = trim(urldecode($this->input['need_info']));
		}
		
		//活动是否需要支付
		if($this->input['need_pay'])
		{
			$data['need_pay'] = trim(urldecode($this->input['need_pay']));
		}
		//活动是否限制人数
		if($this->input['need_num'])
		{
			$data['need_num'] = trim(urldecode($this->input['need_num']));
		}
		return $data;
	}
	
	//逻辑处理
	public function processData($data = array())
	{
		if(is_array($data))
		{
			//活动名判断
			if(defined('ABLE_SAME_NAME') && !ABLE_SAME_NAME)
			{
				$sname = '';
				$sname = $this->libactivity->getActivity('count(action_id) as total', array(), 0, 1, '');
				if($sname)
				{
					$this->errorOutput("活动名重复");
				}
			}
			//活动时间判断
			if(!is_numeric($data['start_time']) || !is_numeric($data['end_time']))
			{
				$this->errorOutput("活动时间格式不对");
			}
			else
			{
				if($data['end_time'] < $data['start_time'])
				{
					$this->errorOutput("活动时间设置不对");
				}
			}
		}
	}
	//创建
	public function create()
	{
		$data = array();
		//加载数据
		$data = $this->getData();
		$result = $this->team->get_permission($data['team_id'], $data['user_id'], 'ADD_ACTIVITY');
		if(!$result['permission'] )
		{
			//$this->errorOutput("你没有权限操作");
		}
		if(defined('ABLE_SAME_ACTION'))
		{
			$total = $this->libactivity->get('activity','count(action_id) as total',array('user_id'=>$data['user_id'],'state'=>1), 0, 1, array());
			if(ABLE_SAME_ACTION)
			{
				if($total > ABLE_SAME_ACTION)
				{
					$this->errorOutput("你的创建数目已经达到系统的设置的最大数");
				}
			}
		}
		//验证数据
		//$this->processData($data);
		//加载创建时间
		$data['create_time'] = TIMENOW;
		//来源ip
		$data['from_ip'] = hg_getip();
		//来源部分
		$data['app_name'] = $this->user['display_name'];
		//来源客户端
		$data['client'] = $_SERVER['HTTP_USER_AGENT'];
		//加载审核
		$data['state'] = 1;//有效
		
		$reslt = $this->libactivity->insertActivity($data);
		if($reslt)
		{
			$this->team->update_total(array('action_num'=>1,'team_id'=>$data['team_id']));
			if(isset($this->input['mark']) && strlen($this->input['mark']))
			{
				$this->libmark->create_source_id_mark(array('source_id'=>$reslt,'action'=>'keywords','source'=>'activity','name'=>trim(urldecode($this->input['mark']))));		
			}
			//添加活动召见者
			$post = array();
			$post['user_id'] = $data['user_id'];
			$post['user_name'] = $data['user_name'];
			$post['from_ip'] = $data['from_ip'];
			$post['client'] = $data['client'];
			$post['app_name'] = $data['app_name'];
			$post['apply_time'] = $data['create_time'];
			$post['action_id'] = $reslt;
			$post['apply_status'] = 0;
			$post['levl'] = 2;
			$this->libactivity->insert('activity_apply',$post);
		}
		$this->setXmlNode('activity', 'create');
		$this->addItem_withkey('action_id',$reslt);
		$this->output();
	}
	
	//更新数据
	public function update()
	{
		$data = $this->getData();
		
		if(!$data['action_id'] || !is_numeric($data['action_id']))
		{
			$this->errorOutput("你搜索得活动id参数不合法");
		}
		//获取原始数据
		$rawData = $this->libactivity->getActivity('*', array('action_id'=>$data['action_id']), 0, 1, '');
		
		if(!$rawData)
		{
			$this->errorOutput("你更新的活动不存在");
		}
		//更新人员问题
		$result = $this->team->get_permission($rawData['team_id'], $data['user_id'], 'ADD_ACTIVITY');
		if(!$result['permission'])
		{
			$this->errorOutput("你没有权限操作");
		}

		//更新次数
		if(defined(EDITS))
		{
			if($rawData['edit_count'] < EDITS)
			{
				$data['edit_count'] = $rawData['edit_count'] + 1;
			}
			else 
			{
				$this->errorOutput("你的编辑次数过多");
			}
		}
		
		//数据校对
		$this->processData($data);
		//更新数据
		$result = $this->libactivity->updateActivity($data, false, true);
		if(isset($this->input['mark']) && strlen($this->input['mark']))
		{
			$this->libmark->update_source_id_mark(array('source_id'=>$data['action_id'],'action'=>'keywords','source'=>'activity','name'=>trim(urldecode($this->input['mark']))));		
		}
		//比较数据，调用对应更新接口
		$currentData = $this->libactivity->getActivity('*',array('action_id'=>$data['action_id']), 0, 1, '');
		//更新小组数据
		if($rawData['team_id'] != $currentData['team_id'])
		{
			//TODO
			if($this->team->update_total(array('action_num'=>-1,'team_id'=>$rawData['team_id'])))
			{
				$this->team->update_total(array('action_num'=>1,'team_id'=>$currentData['team_id']));
			}
		}
		//更新审核权限
		if($rawData['rights'] && !$currentData['rights'])
		{
			$this->updateRights($data['action_id']);
		}
		
		$this->setXmlNode('activity', 'update');
		$this->addItem_withkey('action_id',$data['action_id']);
		$this->output();
	}
	//更新审核关系只有
	public function updateRights($action_id)
	{
		//获取所有待审核的数据
		$result = $this->libactivity->getActivityApply('id,action_id', array('action_id'=>$action_id,'apply_status'=>1,'state'=>1), 0, -1, '');
		if($result)
		{
			$ids = $sp = '';
			foreach ($result as $k => $v)
			{
				$ids .= $sp . $v['id'];
				$sp = ',';
			}
			//更新待审核为免审核
			if($this->libactivity->updateActivityApply(array('id'=>ids,'action_id'=>action_id,'apply_status'=>0), true))
			{
				//更新申请通过的人数
				$this->libactivity->updateActivity(array('yet_join'=>count($result),'action_id'=>action_id), true, true);
			}
		}
		
		return $result;
	}
	//关闭
	public function delete()
	{
		$data = $this->getData();
		
		if(!$data['action_id'] || !is_numeric($data['action_id']))
		{
			$this->errorOutput("你搜索得活动id参数不合法");
		}
		$rawData = $this->libactivity->get('activity', 'team_id,state', array('action_id'=>$data['action_id']), 0, 1, array());
		if($rawData['state'])
		{
			$this->errorOutput("你搜索得活动不存在");
		}
		$state = isset($this->input['state']) ? intval($this->input['count']) : 1;
		//关闭
		$tata['state'] = $state;
		$result = $this->team->get_permission($rawData['team_id'], $data['user_id'], 'ADD_ACTIVITY');
		if(!$result['permission'])
		{
			$this->errorOutput("你没有权限操作");
		}
		if(isset($this->input['review']))
		{
			$reviewData = $this->libactivity->get('activity_review', '*', array('action_id'=>$data['action_id'],'subject'=>1), 0, 1, array());
			$result = false;
			if($reviewData)
			{
				$result = $this->libactivity->update('activity_review',array('content'=>trim(urldecode($this->input['review']))), array('id'=>$reviewData['id']), array());
			}
			else 
			{
				$result = $this->libactivity->insert('activity_review', array('content'=>trim(urldecode($this->input['review'])),
																			  'action_id'=>$data['action_id'],'subject'=>1,'pub_time'=>TIMENOW,
																			  'user_id'=>$data['user_id'],'user_name'=>$data['user_name']));
			}
			if(!$result)
			{
				$this->errorOutput("你的活动回顾更新失败");
			}
		}
		$result = $this->libactivity->update('activity', $tata, array('action_id'=>$data['action_id']), array());
		if($result && $tata['state'] != $rawData['state'])
		{
			if($tata['state'] == 2 && $rawData['state'] == 1)
			{
				$this->team->update_total(array('action_num'=>-1,'team_id'=>$rawData['team_id']));
			}
			else if($tata['state'] == 1 && $rawData['state'] == 2)
			{
				$this->team->update_total(array('action_num'=>1,'team_id'=>$rawData['team_id']));
			}
			else 
			{
				//nothing todo
			}
		}
		if($state == 0)
		{
			$this->libmark->delete_source_id_mark(array('source_id'=>$data['action_id'],'source'=>'activity','action'=>'keywords'));
		}
		$this->setXmlNode('activity', 'delete');
		$this->addItem_withkey('action_id',$data['action_id']);
		$this->output();
	}
	
	
	public function getRevData()
	{
		$data = array();
		
		if(isset($this->input['yet_join']) && is_numeric($this->input['yet_join']))
		{
			$data['yet_join'] = trim($this->input['yet_join']);
		}
		if(isset($this->input['apply_num']) && is_numeric($this->input['apply_num']))
		{
			$data['apply_num'] = trim($this->input['apply_num']);
		}
		if(isset($this->input['collect_num']) && is_numeric($this->input['collect_num']))
		{
			$data['collect_num'] = trim($this->input['collect_num']);
		}
		if(isset($this->input['thread_num']) && is_numeric($this->input['thread_num']))
		{
			$data['thread_num'] = trim($this->input['thread_num']);
		}
		if(isset($this->input['reply_num']) && is_numeric($this->input['reply_num']))
		{
			$data['reply_num'] = trim($this->input['reply_num']);
		}
		if(isset($this->input['scan_num']) && is_numeric($this->input['scan_num']))
		{
			$data['scan_num'] = trim($this->input['scan_num']);
		}
		if(isset($this->input['share_num']) && is_numeric($this->input['share_num']))
		{
			$data['share_num'] = trim($this->input['share_num']);
		}
		if(isset($this->input['praise_num']) && is_numeric($this->input['praise_num']))
		{
			$data['praise_num'] = trim($this->input['praise_num']);
		}
		if(isset($this->input['heat_num']) && is_numeric($this->input['heat_num']))
		{
			$data['heat_num'] = trim($this->input['heat_num']);
		}
		return $data;
	}
	/***
	 * 数据增加更新接口
	 ***/
	
	public function updateAddData()
	{
		$data = array();
		$data = $this->getRevData();
		if(isset($this->input['action_id']) && is_numeric($this->input['action_id']))
		{
			$pata['action_id'] = trim($this->input['action_id']);
		}
		if(!$data || !$pata['action_id'])
		{
			$this->errorOutput("你传递的参数不合法");
		}
		//$result = $this->libactivity->updateActivity($data, true, true);
		$result = $this->libactivity->update('activity', $data, $pata, $data);
		$this->setXmlNode('activity', 'updateAddData');
		$this->addItem(array('state'=>$result));
		$this->output();
	}
	
	/**
	 * 
	 * 编辑活动回顾
	 * Enter description here ...
	 */
	public function updateReview()
	{
		$data = array();
		$data = $this->libactivity->checkUserExit();
		$img = unserialize(trim(htmlspecialchars_decode(urldecode($this->input['img']))));
		$review = trim(htmlspecialchars_decode(urldecode($this->input['img'])));
		$result = $this->libactivity->get('activity','*', array('action_id'=>$data['action_id']),0 ,1,array());//;
		if($result['state'] != 1)
		{
			$this->errorOutput("你操作的行动已关闭");
		}
		if($this->libactivity->update('activity', array('review'=>$review), array('action_id'=>$data['action_id']),array()))
		{
			if($this->libactivity->delete('material', array('action_id'=>$data['action_id'])))
			{
				if($img)
				{
					foreach($img as $k=>$v)
					{
						$data['team_id'] = $v['img_info'];
						$data['team_id'] = $v['img_intro'];
						$data['team_id'] = $result['team_id'];
						$data['create_time'] =TIMENOW;
						$this->libactivity->insert('material',$data);
					}
				}
			}
		}
		$this->setXmlNode('activity', 'updateReview');
		$this->addItem_withkey('info',true);
		$this->output();
	}
		
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
	//失误方法
	function unknow()
	{
		$this->errorOutput("你搜索得方法不存在");
	}
	function __destruct()
	{
		parent::__destruct();
		unset($this->libactivity);
	}
	
}

$out = new activityUpdate();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
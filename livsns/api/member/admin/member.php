<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: member.php 22073 2013-05-13 08:05:27Z zhuld $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
require('global.php');
define('UC_CLIENT_PATH', '../');
class memberApi extends adminReadBase
{
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'manage'	=>'管理',
		'_node'=>array(
			'name'=>'会员分类',
			'filename'=>'member_node.php',
			'node_uniqueid'=>'member_node',
			),
		);
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$width = $this->settings['avatar_size']['width'];
		$height = $this->settings['avatar_size']['height'];
		$members = $this->mMember->show($condition, $offset, $count);

		$this->setXmlNode('member','info');
		if (!empty($members))
		{
			foreach ($members AS $member)
			{
			//	hg_pre($member);
				if ($member['filename'])
				{
					$member['avatar_url'] = hg_material_link($member['host'], $member['dir'], $member['filepath'], $member['filename'], $width.'x'.$height.'/');
				}
				$this->addItem($member);
			}
		}
	
		$this->output();
	}
	
	public function detail()
	{
		$id = urldecode($this->input['id']);
		$info = $this->mMember->detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mMember->count($condition);
		echo json_encode($info);
	}
	
	public function index()
	{
		
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$binary = '';//不区分大小些
			if(defined('DIFFER_SIZE') && !DIFFER_SIZE)//区分大小些
			{
				$binary = 'binary ';
			}	
			$condition .= ' AND ' . $binary . ' m.member_name like \'%'.trim($this->input['k']).'%\'';
		}
		
		if(isset($this->input['nick_name']) && !empty($this->input['nick_name']))
		{
			$condition .= ' AND m.nick_name like \'%'.trim($this->input['nick_name']).'%\'';
		}
		
		if (isset($this->input['_id']) && intval($this->input['_id']))
		{
			$condition .= " AND m.node_id = " . intval($this->input['_id']);
		}
		
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND m.create_time >= '".$start_time."'";
		}
		
		if(isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND m.create_time <= '".$end_time."'";
		}
		
		if($this->input['status'] && trim($this->input['status'])!= -1)
		{
			$condition .= " AND m.status = '".trim($this->input['status'])."'";
		}
		else if(trim($this->input['status']) == '0')
		{
			$condition .= " AND m.status = 0 ";
		}
		
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  m.create_time > '".$yesterday."' AND m.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  m.create_time > '".$today."' AND m.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  m.create_time > '".$last_threeday."' AND m.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  m.create_time > '".$last_sevenday."' AND m.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}

}

$out = new memberApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
<?php
/***************************************************************************
* $Id: basic_info_update.php 16564 2013-01-10 03:39:19Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','basic_info');
require('global.php');
class basicInfoUpdateApi extends BaseFrm
{
	private $mBasicInfo;
	private $mInteractiveProgram;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/basic_info.class.php';
		$this->mBasicInfo = new basicInfo();
		
		require_once CUR_CONF_PATH . 'lib/interactive_program.class.php';
		$this->mInteractiveProgram = new interactiveProgram();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function edit()
	{
		$channel_id		= intval($this->input['channel_id']);
		//互动节目单id
		$in_program_id 	= intval($this->input['in_program_id']);
		//频道节目单id
		$program_id 	= intval($this->input['program_id']);
		$director_id 	= $this->input['director_id'];
		$presenter_id 	= $this->input['presenter_id'];
		$member_id 		= $this->input['member_id'];
		
		$topic_id 		= $this->input['topic_id'];
		$topic_name 	= $this->input['topic_name'];
		
		$site_id 			 = $this->input['site_id'];
		$site_guests_name 	 = $this->input['site_guests_name'];
		$site_guests_profile = $this->input['site_guests_profile'];
		
		$otc_id 			 = $this->input['otc_id'];
		$otc_guests_name 	 = $this->input['otc_guests_name'];
		$otc_guests_profile  = $this->input['otc_guests_profile'];
	
		$dates 		= urldecode($this->input['dates']);
		$start_end 	= urldecode($this->input['start_end']);
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
	
		if (!$dates)
		{
			$this->errorOutput('日期不能为空');
		}
		
		if ($dates < date('Y-m-d', TIMENOW))
		{
			$this->errorOutput('只能添加今天及以后的节目');
		}
		
		if (!$start_end)
		{
			$this->errorOutput('请选择节目');
		}
		
		$start_end_array = explode(',', $start_end);
		$start_time 	 = strtotime($dates . ' ' . $start_end_array[0]);
		$end_time	 	 = strtotime($dates . ' ' . $start_end_array[1]);
		$toff			 = $end_time - $start_time;
		
		//验证节目单是否存在
		$program_input = array(
			'channel_id'	=> $channel_id,
			'program_id'	=> $program_id,	//频道节目单id
			'member_id' 	=> $member_id ? serialize($member_id) : '',
			'start_time'	=> $start_time,
			'toff'			=> $toff,
			'dates'			=> $dates,
		);
		
		$ret_program_info = $this->mInteractiveProgram->get_program_by_id($in_program_id);

		if (empty($ret_program_info))	//添加
		{
			$ret_program = $this->mInteractiveProgram->program_add($program_input);
		}
		else 	//更新
		{
			$ret_program = $this->mInteractiveProgram->program_edit($in_program_id, $program_input);
		}
		
		if (!$ret_program['id'])
		{
			$this->errorOutput('编辑节目失败');
		}
		
		//重置节目单id
		$in_program_id = $ret_program['id'];
		
		//导播
		//删除
		$ret_director_delete = $this->mBasicInfo->delete_by_program_id($in_program_id, 'director');
		
		if (!$ret_director_delete)
		{
			$this->errorOutput('导播数据编辑失败');
		}
		
		if (!empty($director_id))
		{
			//添加
			foreach ($director_id AS $k => $v)
			{
				if ($v)
				{
					$director_input = array(
						'program_id'	=> $in_program_id,
						'director_id'	=> $v,
					);
					
					$ret_director = $this->mBasicInfo->dir_pre_add($director_input, 'director');
					if (empty($ret_director))
					{
						$this->errorOutput('导播数据添加失败');
					}
				}
			}
		}
		
		//主持人
		//删除
		$ret_presenter_delete = $this->mBasicInfo->delete_by_program_id($in_program_id, 'presenter');
		
		if (!$ret_presenter_delete)
		{
			$this->errorOutput('主持人数据编辑失败');
		}
		
		if (!empty($presenter_id))
		{
			//添加
			foreach ($presenter_id AS $k => $v)
			{
				if ($v)
				{	
					$presenter_input = array(
						'program_id'	=> $in_program_id,
						'presenter_id'	=> $v,
					);
					
					$ret_presenter = $this->mBasicInfo->dir_pre_add($presenter_input, 'presenter');
					if (empty($ret_presenter))
					{
						$this->errorOutput('主持人数据添加失败');
					}
				}
			}
		}
		
		//微博账号

		//删除
		$ret_member_delete = $this->mBasicInfo->delete_by_program_id($in_program_id, 'weibo_member');
		
		if (!$ret_member_delete)
		{
			$this->errorOutput('微博数据编辑失败');
		}
		
		if (!empty($member_id))
		{
			//添加
			foreach ($member_id AS $k => $v)
			{
				if ($v)
				{	
					$member_input = array(
						'program_id'	=> $in_program_id,
						'member_id'		=> $v,
					);
					
					$ret_member = $this->mBasicInfo->dir_pre_add($member_input, 'weibo_member');
					if (empty($ret_member))
					{
						$this->errorOutput('微博数据添加失败');
					}
				}
			}
		}
	
		//话题
		$ret_topic_id = array();
		if (!empty($topic_name))
		{
			foreach ($topic_name AS $k => $v)
			{
				if ($v)
				{
					$topic_input = array(
						'program_id'	=> $in_program_id,
						'name'			=> trim(urldecode($v)),
					);
					
					if (!$topic_id[$k])
					{
						$ret_topic[$k] = $this->mBasicInfo->create($topic_input, 'topic');
					}
					else 
					{
						$ret_topic[$k] = $this->mBasicInfo->update($topic_input, 'topic', $topic_id[$k]);
					}
				
					if (!$ret_topic[$k]['id'])
					{
						$this->errorOutput('编辑话题失败');
					}
					$ret_topic_id[$k] = $ret_topic[$k]['id'];
				}
			}
		}
		
		//现场嘉宾
		$ret_site_id = array();
		if (!empty($site_guests_name))
		{
			foreach ($site_guests_name AS $k => $v)
			{
				if ($v)
				{
					$site_input = array(
						'program_id'	=> $in_program_id,
						'name'			=> trim(urldecode($v)),
						'profile'		=> trim(urldecode($site_guests_profile[$k])),
					);
					
					if (!$site_id[$k])
					{
						$ret_site[$k] = $this->mBasicInfo->create($site_input, 'site_guests');
					}
					else 
					{
						$ret_site[$k] = $this->mBasicInfo->update($site_input, 'site_guests', $site_id[$k]);
					}
					
					if (!$ret_site[$k]['id'])
					{
						$this->errorOutput('编辑现场嘉宾失败');
					}
					$ret_site_id[$k] = $ret_site[$k]['id'];
				}
			}
		}
		
		//场外嘉宾
		$ret_otc_id = array();
		if (!empty($otc_guests_name))
		{
			foreach ($otc_guests_name AS $k => $v)
			{
				if ($v)
				{
					$otc_input = array(
						'program_id'	=> $in_program_id,
						'name'			=> trim(urldecode($v)),
						'profile'		=> trim(urldecode($otc_guests_profile[$k])),
					);
					
					if (!$otc_id[$k])
					{
						$ret_otc[$k] = $this->mBasicInfo->create($otc_input, 'otc_guests');
					}
					else 
					{
						$ret_otc[$k] = $this->mBasicInfo->update($otc_input, 'otc_guests', $otc_id[$k]);
					}
					
					if (!$ret_otc[$k]['id'])
					{
						$this->errorOutput('编辑场外嘉宾失败');
					}
					$ret_otc_id[$k] = $ret_otc[$k]['id'];
				}
			}
		}
		
		$ret = array(
			'in_program_id'	=> $in_program_id,
			'topic_id'		=> $ret_topic_id,
			'site_id'		=> $ret_site_id,
			'otc_id'		=> $ret_otc_id,
		);
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete_basic_info()
	{
		$id   = $this->input['id'];
		$type = intval($this->input['type']);
		
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$type_array = array(1,2,3);
		if (!in_array($type, $type_array))
		{
			$this->errorOutput('不合法类型');
		}
		
		//表名称
		$table = array(
			'1' => 'topic',			//话题
			'2' => 'site_guests',	//现场嘉宾
			'3' => 'otc_guests',	//场外嘉宾
		);
		
		$ret_delete = $this->mBasicInfo->delete($id,$table[$type]);
		
		if (!$ret_delete)
		{
			$this->errorOutput('删除失败');
		}
		
		$ret = array(
			'type'	=> $type,
			'id'	=> $id,
		);
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		$program_id = intval($this->input['program_id']);
		if (!$program_id)
		{
			$this->errorOutput('未传入节目单id');
		}
		$ret_program = $this->mBasicInfo->delete($program_id, 'program');
		$ret_director = $this->mBasicInfo->delete_by_program_id($program_id, 'director');
		$ret_presenter = $this->mBasicInfo->delete_by_program_id($program_id, 'presenter');
		$ret_topic = $this->mBasicInfo->delete_by_program_id($program_id, 'topic');
		$ret_site_guests = $this->mBasicInfo->delete_by_program_id($program_id, 'site_guests');
		$ret_otc_guests = $this->mBasicInfo->delete_by_program_id($program_id, 'otc_guests');
		$this->addItem($program_id);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
}

$out = new basicInfoUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_plan_update.php 5408 2011-12-21 01:54:29Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_plan');
class programPlanUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program_plan.class.php');
		$this->obj = new programPlan();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function create()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput("频道不为空");
		}
		$channel_id = intval($this->input['channel_id']);
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$nodes['_action'] = 'manage';
		$nodes['nodes'][$channel['id']] = $channel['id'];
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if(!trim($this->input['title']))
		{
			$this->errorOutput("标题不为空");
		}
		
		if(!trim($this->input['start_time']))
		{
			$this->errorOutput("开始日期不为空");
		}
		else
		{
			$reg = "/[0-2]{1}[0-9]{1}:[0-5]{1}[0-9]{1}/i";
			
			if(!preg_match($reg, trim($this->input['start_time'])))
			{
				$this->errorOutput("开始时间格式不正确");
			}
		}
		//exit();
		if(!trim($this->input['start_date']))
		{
			$this->errorOutput("开始日期不为空");
		}
		else
		{
			$reg = "/[1-3]{1}[0-9]{3}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1}/i";
			if(!preg_match($reg, trim($this->input['start_date'])))
			{
				$this->errorOutput("开始日期格式不正确");
			}
		}
		$data = array(
			'channel_id' => intval($this->input['channel_id']),
			'program_name' => rawurldecode($this->input['title']),
			'start_time' => strtotime($this->input['start_date'] . ' ' . trim($this->input['start_time'])),
			'toff' => $this->input['end_date'] ? (strtotime($this->input['end_date'] . ' ' . trim($this->input['start_time'])) - strtotime($this->input['start_date'] . ' ' . trim($this->input['start_time']))) : 0,
			'indexpic' => trim($this->input['indexpic']),
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'ip' => hg_getip(),
			'week_day' => $this->input['week_day'] ? $this->input['week_day'] : '',
		);

		$week = $this->obj->verify($data['start_time'],$data['toff'],$channel_id);
		
		if(!empty($week))
		{
			if($data['toff'])
			{
				$result = hg_array_sameItems($data['week_day'], $week);
			}
			else
			{
				$result = trim($this->input['end_date']) ? $week : hg_array_sameItems($data['week_day'], $week);
			}
			if(is_array($result) && !empty($result))
			{
				$weeks = $space = "";
				foreach($result as $k => $v)
				{
					$weeks .= $space . $v;
					$space = ",";
				}			
				$al = array(1,2,3,4,5,6,7);
				$ch = array('一', '二' , '三' , '四' , '五' , '六' , '日' );
				$output  = str_replace($al, $ch, $weeks);
				$this->errorOutput("星期" . $output . "已经包含" . $start_time . "~" . $end_time . "的节目，请选择节目计划的时间段");
			}
		}
		$info = $this->obj->create($data);
		if(!$info)
		{
			$this->errorOutput("创建失败！");
		}
		else
		{
			$this->addLogs('新增节目计划','',$info,'','',$info['program_name'].$info['id']);
			$this->addItem($info);
			$this->output();
		}
	}

	function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput("未传入更新ID");
		}
		$id = intval($this->input['id']);
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan WHERE 1 AND id=" . $id;
		$f = $this->db->query_first($sql);
		if(!$this->input['channel_id'])
		{
			$this->errorOutput("频道不为空");
		}

		$channel_id = intval($this->input['channel_id']);
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$nodes['_action'] = 'manage';
		$nodes['nodes'][$channel['id']] = $channel['id'];
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if(!trim($this->input['title']))
		{
			$this->errorOutput("标题不为空");
		}
		
		if(!trim($this->input['start_time']))
		{
			$this->errorOutput("开始日期不为空");
		}
		else
		{
			$reg = "/[0-2]{1}[0-9]{1}:[0-5]{1}[0-9]{1}/i";
			if(!preg_match($reg, trim($this->input['start_time'])))
			{
				$this->errorOutput("开始时间格式不正确");
			}
		}
		//exit();
		if(!trim($this->input['start_date']))
		{
			$this->errorOutput("开始日期不为空");
		}
		else
		{
			$reg = "/[1-3]{1}[0-9]{3}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1}/i";
			if(!preg_match($reg, trim($this->input['start_date'])))
			{
				$this->errorOutput("开始日期格式不正确");
			}
		}
/*
		if(!trim($this->input['end_date']))
		{
			$this->errorOutput("结束日期不为空");
		}
		else
		{
			$reg = "/[1-3]{1}[0-9]{3}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1}/i";
			if(!preg_match($reg, trim($this->input['end_date'])))
			{
				$this->errorOutput("结束日期格式不正确");
			}
		}
*/
		$data = array(
			'channel_id' => $channel_id,
			'program_name' => rawurldecode($this->input['title']),
			'start_time' => strtotime($this->input['start_date'] . ' ' . trim($this->input['start_time'])),
			'toff' => $this->input['end_date'] ? (strtotime($this->input['end_date'] . ' ' . trim($this->input['start_time'])) - strtotime($this->input['start_date'] . ' ' . trim($this->input['start_time']))) : 0,
			'indexpic' => trim($this->input['indexpic']),
			'week_day' => $this->input['week_day'] ? $this->input['week_day'] : '',
		);
		$week = $this->obj->verify($data['start_time'],$data['toff'],$channel_id,$id);
		if(!empty($week))
		{
			if($data['toff'])
			{
				$result = hg_array_sameItems($data['week_day'], $week);
			}
			else
			{
				$result = trim($this->input['end_date']) ? $week : hg_array_sameItems($data['week_day'], $week);
			}
			if(is_array($result) && !empty($result))
			{
				$weeks = $space = "";
				foreach($result as $k => $v)
				{
					$weeks .= $space . $v;
					$space = ",";
				}			
				$al = array(1,2,3,4,5,6,7);
				$ch = array('一', '二' , '三' , '四' , '五' , '六' , '日' );
				$output  = str_replace($al, $ch, $weeks);
				$this->errorOutput("星期" . $output . "已经包含" . trim($this->input['start_time']) . "~开始的节目，<br/>请选择节目计划的时间段");
			}
		}
		$info = $this->obj->update($data,$id);
		if(!$info)
		{
			$this->errorOutput("更新失败！");
		}
		else
		{
			if($info['id'])
			{
				$this->addLogs('更新节目计划',$f,$info,'','',$info['program_name'].$info['id']);
			}
			$this->addItem($info);
			$this->output();
		}
	}
	
	function check_plan()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput("未传入对象ID");
		}
		$ret = $this->obj->check_plan($this->input['id']);
		
		$this->addItem(array('plan_result' => $ret ? 1 : 0));
		$this->output();
	}
	
	/**
	 * 上传外链索引图片
	 */
	public function upload_indexpic()
	{
		//外链索引图片
		$material = parent::upload_indexpic();
		if(!empty($material))
		{
			$material['pic'] = json_encode(array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			));
			$this->addItem($material);
		}
		else
		{
			$return = array(
				'error' => '文件上传失败',
			);
			
			$this->addItem($return);
		}
		$this->output();
	}

	function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput("未传入对象ID");
		}
		$id = intval($this->input['id']);
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		
		$channel_id = $f['channel_id'];
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$nodes['_action'] = 'manage';
		$nodes['nodes'][$channel['id']] = $channel['id'];
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		$info = $this->obj->delete(intval($this->input['id']));
		if(!$info)
		{
			$this->errorOutput("删除失败！");
		}
		if($info['id'])
		{
			$this->addLogs('删除节目计划',$f,'','','',$f['program_name'].$f['id']);
		}
		$this->addItem(array('id'=>$info));
		$this->output();
	}
	public function audit(){}
	public function sort(){}
	public function publish(){}
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}
$out = new programPlanUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>		
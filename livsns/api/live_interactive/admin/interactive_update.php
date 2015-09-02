<?php
/***************************************************************************
* $Id: interactive_update.php 16538 2013-01-09 06:13:39Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','interactive');
require('global.php');
class interactiveUpdateApi extends BaseFrm
{
	private $mInteractive;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/interactive.class.php';
		$this->mInteractive = new interactive();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function delete()
	{
		$id  = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$ret = $this->mInteractive->delete($id);
		if (!$ret)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($id);
		$this->output();
	}
	
	public function is_delete()
	{
		$id  = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$ret = $this->mInteractive->is_delete($id);
		if (!$ret)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($id);
		$this->output();
	}
	
	public function audit()
	{
		$id    = $this->input['id'];
		$audit = $this->input['audit'];
		
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
	
		$audit_array = array(1,2);
		if (!in_array($audit, $audit_array))
		{
			$this->errorOutput('不合法类型');
		}
		
		//审核时间
		$status_time = ($audit == 1) ? TIMENOW : 0;
		
		$ret = $this->mInteractive->audit($id, $audit, $status_time);
		
		if (!$ret)
		{
			$this->errorOutput('操作失败');
		}

		$return = array(
			'audit' => $audit,
			'id' 	=> $id,
		);
		$this->addItem($return);
		$this->output();
	}
	
	public function type()
	{
		$id    = $this->input['id'];
		$type  = $this->input['type'];
		
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		if (!$type)
		{
			$this->errorOutput('未传入类型数值');
		}
		
		$type_array = array(1,2,3,4);
		if (!in_array($type, $type_array))
		{
			$this->errorOutput('不合法类型');
		}
		
		//推荐时间
		$recommend_time = ($type == 2) ? TIMENOW : 0;

		$ret = $this->mInteractive->type($id, $type, $recommend_time);
		
		if (!$ret)
		{
			$this->errorOutput('操作失败');
		}
		
		if ($type == 3)
		{
			$ret_interactive = $this->mInteractive->get_interactive_by_id($id);

			if (!empty($ret_interactive))
			{
				foreach ($ret_interactive AS $interactive)
				{
					$this->mInteractive->warn_add($interactive['member_id'], $interactive['id']);
				}
			}
		}
		
		$return = array(
			'type' => $type,
			'id'   => $id,
		);

		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 互动数据操作 (推送, 推荐, 警告, 屏蔽)
	 * Enter description here ...
	 * @param string $id
	 * @param int $type (1,2,3,4)
	 * @param int $flag (0,1)
	 * @return $return array
	 */
	public function interactive_operate()
	{
		$id    = $this->input['id'];
		$type  = $this->input['type'];
		$flag  = $this->input['flag'];
		
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		if (!$type)
		{
			$this->errorOutput('未传入类型数值');
		}
		
		$type_array = array(1,2,3,4);
		if (!in_array($type, $type_array))
		{
			$this->errorOutput('不合法类型');
		}
		
		//推荐时间
		$recommend_time = ($type == 2) ? TIMENOW : 0;
		
		//互动操作字段
		$field = array(
			'1' => 'is_push',		//推送
			'2' => 'is_recommend',	//推荐
			'3' => 'is_warning',	//警告
			'4' => 'is_shield',		//屏蔽
		);
		
		$ret = $this->mInteractive->interactive_operate($id, $field[$type], $flag, $recommend_time);
		
		if (!$ret)
		{
			$this->errorOutput('操作失败');
		}
		
		$ret_interactive = array();
		if ($type == 3 || $type == 4)
		{
			$ret_interactive = $this->mInteractive->get_interactive_by_id($id);
		}
		
		if (!empty($ret_interactive))
		{
			//警告
			if ($type == 3)
			{
				foreach ($ret_interactive AS $k => $v)
				{
					$warn_data[$k] = array(
						'member_id' 		=> $v['member_id'],
						'content_id' 		=> $v['id'],
					//	'plat_id' 			=> $plat_id,
					//	'plat_member_id'	=> $plat_member_id,
						'dates'				=> date('Y-m-d'),
						'create_time' 		=> TIMENOW,
						'update_time' 		=> TIMENOW,
						'ip' 				=> hg_getip(),
					);
	
					$this->mInteractive->warn_add($warn_data[$k]);
				}
			}
			
			//屏蔽
			if ($type == 4)
			{
				foreach ($ret_interactive AS $k => $v)
				{
					$shield_data[$k] = array(
						'member_id' 		=> $v['member_id'],
						'content_id' 		=> $v['id'],
					//	'plat_id' 			=> $plat_id,
					//	'plat_member_id'	=> $plat_member_id,
						'dates'				=> date('Y-m-d'),
						'create_time' 		=> TIMENOW,
						'update_time' 		=> TIMENOW,
						'ip' 				=> hg_getip(),
					);
	
					$this->mInteractive->shield_add($shield_data[$k]);
				}
			}
		}
		
		$return = array(
			'type' => $type,
			'flag' => $flag,
			'id'   => $id,
		);

		$this->addItem($return);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('未实现的空方法');
	}
}

$out = new interactiveUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
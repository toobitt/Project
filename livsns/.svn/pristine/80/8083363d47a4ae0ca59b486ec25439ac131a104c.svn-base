<?php
include_once('./global.php');
define('MOD_UNIQUEID','cp_report_m');//模块标识

class reportupdateApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
		require_once  CUR_CONF_PATH.'lib/reportLib.class.php';
		require_once (ROOT_PATH . 'lib/class/team.class.php');
		require_once (ROOT_PATH . 'lib/class/activity.class.php');
	}
	
	public function unknow()
	{
		$this->errorOutput("你搜索得方法不存在");
	}
	
	
	/**
	 * 创建举报
	 * 提交来源：source
	 * 提交来源id：source_id
	 * 默认提交人：user_id
	 * 提交原因： 	comtent
	 */
	public function create()
	{
		//TODO
	}
	/**
	 * 审核举报
	 * 举报对象id：rid
	 * 提交来源：source
	 * 提交来源id：source_id
	 * 默认审核人：user_id
	 * 审核原因： 	comtent
	 */
	public function update()
	{
		$data['rid'] = trim(htmlspecialchars_decode(urldecode($this->input['rid'])));
		
		if ($data['rid'])
		{
			$this->aReportLib = new reportLib();
			$result = array();
			$result = $this->aReportLib->get('report','rid,source,source_id', $data, 0, -1, array(), array(), array());
			
			if ($result)
			{
				$post = array();
				$post['state'] = $state = !empty($this->input['state']) ? trim($this->input['state']) : DATA_NO_KEEP;
				$this->setDataState($post);
				//更新原始数据审核结果
				if ($this->aReportLib->update('report', array('state'=>$state), $data))
				{
					//插入审核纪录
					$sp = '';
					foreach($result as $k)
					{
						$new = array();
						$new['source'] = $$post['source'] = $k['source'];
						$post['source_id'] .= $sp . $k['source_id'];
						$new['source_id'] = $k['source_id'];
						$sp = ',';
						$new['user_id'] = SYSTEAM_USER;
						$new['create_time'] = TIMENOW;
						$new['rid'] = $k['rid'];
						$new['state'] = $state;
						$this->aReportLib->insert('report_result',$new);
					}
					//存在删除操作
					if($post && !$state)
					{
						$this->setDataState($post);
					}
				}
			}
		}
		$this->addItem($data['rid']);
		$this->output();
	}
	/**
	 * 处理举报问题
	 * Enter description here ...
	 * @param array $data
	 */
	public function setDataState($data = array())
	{
		if ($data['source'] == 'team')
		{
			//删除小组
			$this->team = new team();
			$this->team->drop_team($data['source_id']);
		}
		elseif ($data['source'] == 'activity')
		{
			//删除活动
			$this->activity = new activity();
			$this->activity->updateDeleteState(array('action_id'=>$data['source_id']));
		} 
		elseif ($data['source'] == 'topic') 
		{
			//删除话题
			$this->team = new team();
			$this->team->drop_topic($data['source_id']);
		}
		elseif ($data['source'] == 'reply')
		{
			//删除回复
			$this->team = new team();
			$this->team->drop_reply($data['source_id']);
		}
		else 
		{
			//TODO
		}
	}
	/**
	 * 删除举报
	 * Enter description here ...
	 */
	public function delete()
	{
		$data = array();
		if (isset($this->input['user_id']))
		{
			$data['user_id'] = trim(htmlspecialchars_decode(urldecode($this->input['user_id'])));
		}
		if (isset($this->input['source']))
		{
			$data['source'] = trim(htmlspecialchars_decode(urldecode($this->input['source'])));
		}
		if (isset($this->input['source_id']))
		{
			$data['source_id'] = trim(htmlspecialchars_decode(urldecode($this->input['source_id'])));
		}
		if (isset($this->input['rid']))
		{
			$data['rid'] = trim(htmlspecialchars_decode(urldecode($this->input['rid'])));
		}
		/*
		if (isset($this->input['']))
		{
			$data[''] = trim(htmlspecialchars_decode(urldecode($this->input[''])));
		}
		*/
		
		if($data)
		{
			$result = array();
			$this->aReportLib = new reportLib();
			$result = $this->aReportLib->get( array('report'=>''), array('rid','source','source_id'), $data, 0, -1, array(), array(), array());
			if ($result)
			{
				foreach ($result as $k=>$v)
				{
					$this->aReportLib->delete('report_result', $v, array());
				}
				$this->aReportLib->delete('report', $data, array());
			}
			$this->addItem($data['rid']);
		}
		
		$this->output();
	}
	function __destruct()
	{
		parent::__destruct();
	}
}
/**
 *  程序入口
 */
$out = new reportupdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'unknow';
}
$out->$action();
?>

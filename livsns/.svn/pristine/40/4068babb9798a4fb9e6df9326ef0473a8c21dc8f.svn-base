<?php
require_once './global.php';
define('MOD_UNIQUEID','seekhelp_comment');//模块标识
require_once CUR_CONF_PATH.'lib/seekhelp_comment.class.php';
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
require_once ROOT_PATH.'lib/class/members.class.php';
class seekhelpCommentUpdate extends adminUpdateBase
{
	private $comment;
	private $seekhelp;
	private $members;
	public function __construct()
	{
		parent::__construct();
		$this->comment = new ClassSeekhelpComment();
		$this->seekhelp = new ClassSeekhelp();
		$this->members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function publish(){}
	public function create(){}
	
	
	public function update()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = array(
			'content'=>trim($this->input['content']),
			'update_time'=>TIMENOW,
			'update_org_id'=>$this->user['org_id'],
			'update_user_id'=>$this->user['user_id'],
			'update_user_name'=>$this->user['user_name'],
			'update_ip'=>$this->user['ip'],
		);
		//屏蔽字验证
		if ($this->settings['App_banword'])
		{
			require_once(ROOT_PATH.'lib/class/banword.class.php');
			$this->banword = new banword();
			$str = $data['content'];
			$banword = $this->banword->exists($str);
			if ($banword && is_array($banword))
			{
				$banword_content = '';
				foreach ($banword as $key=>$val)
				{
					$banword_content .= $val['banname'].',';
				}
				$data['banword'] = $banword_content ? rtrim($banword_content,',') : '';						
			}
		}
		$ret = $this->comment->update($id, $data);
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		
		$result = $this->comment->comment_detail($ids);
		foreach ($result as $k=>$v)
		{
			$this->SetCommentNum($v['comment_type'], $v);
			$this->updateMemberCount($v['member_id'], 'delete');
		}
		
		$data = $this->comment->delete($ids);
		if($data)
		{
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function audit()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$status = intval($this->input['status']);
		$status = ($status==1 || $status==2) ? $status : 0;
		$data = $this->comment->audit($ids,$status);
		$this->addItem($data);
		$this->output();
	}
	
	public function sort()
	{
		$this->addLogs('更改报料排序', '', '', '更改报料排序');
		$ret = $this->drag_order('comment', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	
	//计划任务审核
	public function planAudit()
	{
		$start_time = intval($this->input['start_time']);
		$end_time = intval($this->input['end_time']);
		$status = intval($this->input['status']);
		$state = '';
		if ($start_time && $end_time && $status)
		{
			switch ($status)
			{
				case 1:$state = 0;break;
				case 2:$state = 1;break;
				case 3:$state = 2;break;
			}
			$sql = 'UPDATE '.DB_PREFIX.'comment SET status = '.$state.' 
					WHERE status = 0 AND banword = "" AND create_time>'.$start_time.' AND create_time<'.$end_time;
			$this->db->query($sql);
		}
		$this->addItem(true);
		$this->output();
		
	}
	
	/**
	 * 评论数
	 * @param unknown $type
	 * @param unknown $id
	 */
	private function SetCommentNum($type, $comment_data)
	{
		if(!$comment_data)
		{
			return false;
		}
		$commentId = $comment_data['id'];
		$contentId = $comment_data['cid'];
		$create_time = $comment_data['create_time'];
		if ($type == 'vice')
		{
			//comment表评论数-1
			$new_comment_num = $comment_data['comment_num'] - 1;
			if($new_comment_num < 0)
			{
				$new_comment_num = 0;
			}
			$res = $this->comment->update($commentId, array('comment_num' => intval($new_comment_num)));
		}
		else
		{
			//seekhelp表评论数-1
			$seekhelp_data = $this->seekhelp->seekhelp_detail($contentId);
			if ($seekhelp_data['comment_latest_id'])
			{
				$comment_id_arr = explode(",", $seekhelp_data['comment_latest_id']);
				foreach ($comment_id_arr as $k=>$v)
				{
					if($v == $commentId)
					{
						array_splice($comment_id_arr,$k,1);
					}
				}
				$comment_latest_Ids_new = implode(",", $comment_id_arr);
			}
			else 
			{
				$comment_latest_Ids_new = '';
			}
	
			$new_comment_num = $seekhelp_data['comment_num'] - 1;
			if($new_comment_num < 0)
			{
				$new_comment_num = 0;
			}
			$data = array(
					'comment_num' => intval($new_comment_num),
					'comment_latest_id'=> $comment_latest_Ids_new,   //最新评论的三个id
					'comment_latest_time' => $create_time,  //记录最新评论时间
			);
			
			$res = $this->seekhelp->update_status($data, $contentId);
		}
		return $res;
	}
	
	/**
	* 会员帖子数量统计
	*/
	private function updateMemberCount($member_id, $operation)
	{
	    $mycountInfo = $this->members->getMycount($member_id);
	    $action = 'comment';
	    if(empty($mycountInfo))
	    {
            $res = array();
	    }
	    else
	    {
	        $old_num = $mycountInfo[$action];
	        if($operation == 'create')
	        {
	            $new_num = $old_num + 1;
	        }
	        elseif ($operation == 'delete')
	        {
	            $new_num = $old_num - 1;
	        }
	        $res = $this->members->updateMycount($member_id, $action, $new_num);
	    }
	     
	    return $res;
	}
}
$ouput= new seekhelpCommentUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
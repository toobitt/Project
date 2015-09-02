<?php
define('ROOT_DIR', './../../');
define('MOD_UNIQUEID','interact');//模块标识
define('SCRIPT_NAME', 'interact');
require(ROOT_DIR . 'global.php');
class interact extends adminBase
{
	private $data = array();
	private $is_interacted = false;
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$this->initdata();
		
		$method = $this->input['type'];
		if(!method_exists($this, $method))
		{
			$this->errorOutput("无效操作");
		}
		if($this->is_interacted)
		{
			$op = 'UPDATE ';
			$where = ' WHERE tid = ' . $this->data['tid'] . ' AND thid = ' . $this->data['thid'];
			
			$data = '';
		}
		else
		{
			$op = 'INSERT INTO ';
			$where = '';
						
			
			$data = ',tid='.$this->data['tid'] . ', thid='.$this->data['thid'];
			
		}
		$this->$method($op, $where, $data);
		$this->addItem($this->data);
		$this->output();
	}
	protected function ding($op = '', $where = '', $data='')
	{
		$sql = $op . DB_PREFIX . 'interaction SET ding = ding + ' . $this->data['ding'] . $data .$where;
		$this->db->query($sql);
		$this->do_stat('ding', 1);
	}
	protected function forward($op = '', $where = '', $data='')
	{
		$sql = $op . DB_PREFIX . 'interaction SET forward = forward + ' . $this->data['forward'] . $data .$where;
		$this->db->query($sql);
		$this->do_stat('forward', 1);
	}
	protected function comment($op = '', $where = '', $data='')
	{
		if(!$this->data['content'])
		{
			$this->errorOutput("评论内容不能为空");
		}
		$sql = $op . DB_PREFIX . 'interaction SET `comment` = `comment` + 1 ' . $data .$where;
		$this->db->query($sql);
		//评论内容入库
		$sql = 'INSERT INTO ' . DB_PREFIX . 'comment SET ';
		foreach(array('forward', 'ding') as $val)
		{
			unset($this->data[$val]);
		}
		foreach($this->data as $key=>$val)
		{
			$sql .= '`'.$key.'`="'.$val.'",';
		}
		$sql = trim($sql, ',');
		$this->db->query($sql);
		$this->do_stat('comment', 1);
	}
	protected function initdata()
	{
		$this->data = array(
		'tid'			=> intval($this->input['topic_id']),
		'thid'			=> intval($this->input['thread_id']),
		'content'		=> addslashes($this->input['content']),
		'forward'		=> 1,
		//'share'		=> intval($this->input['share']),
		'ding'			=> 1,
		'user_id'		=> $this->user['user_id'],
		'user_name'		=> $this->user['user_name'],
		'ip'			=> hg_getip(),
		'create_time'	=> TIMENOW,
		);
		if(!$this->data['tid'] && !$this->data['thid'])
		{
			$this->errorOutput('无效数据，操作失败');
		}
		$sql = 'SELECT id FROM ' . DB_PREFIX . 'interaction WHERE tid = ' . $this->data['tid'] . ' AND thid = '.$this->data['thid'];
		if($this->db->query_first($sql))
		{
			$this->is_interacted = true;
		}
	}

	function favorite()
	{
		$op = $this->input['op'];
		if(!in_array($op, array('focus', 'ignore')))
		{
			$this->errorOutput("未知的操作");
		}
		$user_id = $this->input['user_id'] ? $this->input['user_id'] : $this->user['user_id'];
		$this->data = array(
		'tid'			=> intval($this->input['topic_id']),
		'thid'			=> intval($this->input['thread_id']),
		);
		$tid = intval($this->input['topic_id']);
		if(!$this->data['tid'] && !$this->data['thid'] )
		{
			$this->errorOutput("关注对象不存在");
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'favorite WHERE user_id = ' . $user_id;
		$fa = $this->db->query_first($sql);
		$counter = 0;
		if($fa)
		{
			$sql = ' UPDATE ';
			if($fa['tid'])
			{
				$faved = explode(',', $fa['tid']);
				if($op == 'focus')
				{
					if(in_array($tid, $faved))
					{
						$this->errorOutput("您已关注");
					}
					$faved = implode(',', $faved) . ',' . $tid;
					$counter++;
				}
				else
				{
					unset($faved[array_search($tid, $faved)]);
					if($faved)
					{
						$faved = implode(',', $faved);
					}
					else 
					{
						$faved = '';
					}
					$counter--;
				}
				
			}
			else
			{
				if($op == 'focus')
				{
					$faved = $tid;
					$counter++;
				}
				else
				{
					$this->errorOutput("取消失败，对象不存在");
				}
			}
			$where = ' where user_id ='.$user_id;
		}
		else
		{
			$sql = 'INSERT INTO ';
			$faved = $tid;
			$where = '';
			$counter++;
		}
		$faved = trim($faved, ',');
		$sql .= DB_PREFIX . 'favorite SET user_id='.$user_id . ', tid="'.$faved .'"' .$where;
		$this->db->query($sql);
		//
		$this->do_stat('favorite', $counter);		
		$this->addItem(array(
		'user_id'=>$user_id,
		'tid'=>$tid,
		));
		$this->output();
	}
	function do_stat($field='', $step=0)
	{
		$set = "{$field}={$field}".($step>0 ? "+" :"-") . abs($step);
		$this->db->query('UPDATE ' . DB_PREFIX . 'topic set '.$set.' WHERE id='.$this->data['tid']);
		$this->db->query('UPDATE ' . DB_PREFIX . 'thread set '.$set.' WHERE id='.$this->data['thid']);
	}
}
include ROOT_PATH . 'excute.php';
?>
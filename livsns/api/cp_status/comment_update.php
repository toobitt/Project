<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: comment_update.php 8430 2012-07-27 03:33:01Z hanwenbin $
***************************************************************************/
define('ROOT_DIR', '../../');
define('SCREEN', 1);
define('MOD_UNIQUEID','mblog_comment_m');
require ROOT_DIR.'global.php';
class CommentUpdateApi extends outerUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}
	function  __destruct()
	{
		parent::__destruct();
		$this->db->close;
	}
	
	public function create()
	{
		
	}
	
	public function update()
	{
		
	}
	
	function delete()
	{
		$this->preFilterId();
		$sql = "select * from ".DB_PREFIX."status_comments where id in(".$this->input['id'].")";
		$r = $this->db->query($sql);
		while($row = $this->db->fetch_array($r))
		{
			$data[$row['id']] = array(
				'title' => $row['content'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
			);
			$data[$row['id']]['content']['status_comments'] = $row;
		}
		if(!empty($data))
		{
			foreach($data as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
		}
		if($res['sucess'])
		{
			$sql = 'delete from '.DB_PREFIX.'status_comments where id in('.$this->input['id'].')';
			$r = $this->db->query($sql);
			if($r)
			{
				$this->addItem('success');
			}
		}
		else 
		{
			$this->errorOutput('删除失败！');
		}
		
		$this->output();
	}

	function delete_comp()
	{
		return true;
	}
	/**
	 * 点滴评论审核方法 将数据库中status字段设置为1 屏蔽
	 */
	public function audit()
	{
		$this->preFilterId();
	//	$sql = 'UPDATE '.DB_PREFIX.'status_comments'.' SET flag = '.SCREEN.' WHERE id in('.$this->input['id'].')';
	
		$state = intval($this->input['audit']) == 0 ? 1 : 0; //0 － 为审核通过， 1－ 为屏蔽

		$sql = 'UPDATE ' . DB_PREFIX . 'status_comments' . ' SET flag = ' . intval($state) . ' WHERE id IN (' . $this->input['id'] . ')';
		
		//exit($sql);
		/*$this->db->query($sql);
		if($rows = $this->db->affected_rows())
		{
			$this->addItem('success');
		}
		else
		{
			$this->addItem('error');
		}*/
		if ($this->db->query($sql))
		{
			$ids[] = explode(',', $this->input['id']);
		}
		$this->addItem($ids);
		$this->output();
	}
	/**
	 * 预处理参数ID 格式必须为id = 1,2,3 或者单个id = 1
	 */
	private function preFilterId()
	{
		if(isset($this->input['id']) && !empty($this->input['id']))
		{
			$this->input['id'] = urldecode($this->input['id']);
			$ids = explode(',', $this->input['id']);
			//批量删除不能大于20个
			if(count($ids)>20)
			{
				$this->errorOutput('批处理上限');
			}
			foreach ($ids as $id)
			{
				
				if(!preg_match('/^\d+$/', $id))
				{
					$this->errorOutput('参数不合法');
				}
			}
			$this->input['id'] = implode(',', array_unique($ids));
		}
		else 
		{
			$this->errorOutput('参数不合法');
		}
	}
	public function unknow()
	{
		$this->errorOutput('方法不存在');
	}
}
	$object = new CommentUpdateApi();
	if(!method_exists($object, $_INPUT['a']))
	{
		$a = 'unknow';
	}
	else
	{
		$a = $_INPUT['a'];
	}
	$object->$a();
?>
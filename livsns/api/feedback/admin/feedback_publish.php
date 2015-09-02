<?php
define('MOD_UNIQUEID','feedback_publish');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
require(ROOT_PATH . 'frm/publish_interface.php');
class FeedbackPublish extends adminUpdateBase implements publish
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}		
	
	function get_content()
	{
		$id = intval($this->input['from_id']);
		$sort_id = intval($this->input['sort_id']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$num = $this->input['num'] ? intval($this->input['num']) : 10;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $num;
		if($id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."feedback WHERE id = {$id}";
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."feedback WHERE node_id = {$sort_id}";
		}
		$info = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($info))
		{
			$row['bundle_id'] = APP_UNIQUEID;
			$row['module_id'] = MOD_UNIQUEID;
			$row['struct_id'] = 'feedback';
			$row['struct_ast_id'] = '';
			$row['expand_id'] = '';
			$row['content_fromid'] = $row['id'];
			$row['ip'] = hg_getip();
			$row['user_id'] = $row['user_id'];
			$row['create_user'] = $row['user_name'];
			unset($row['id']);
			$ret[] = $row;
		}
		$this->addItem($ret);
		$this->output();		
	}
 	
 	/**
 	 * 更新内容expand_id,发布内容id
 	 *
 	 */
 	function update_content()
 	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."feedback WHERE id = " . $data['from_id'];
		$ret = $this->db->query_first($sql);
		if($ret['status'] != 1)
		{
			$sql = "UPDATE " . DB_PREFIX ."feedback SET expand_id = 0, column_url = '' WHERE id = " . $data['from_id'];
		}
		else 
		{
			$column_id = unserialize($ret['column_id']);	   //发布栏目		
			$column_url = unserialize($ret['column_url']);	   //栏目url，发布对比，有删除栏目则删除对于栏目url
			$url = array();
			if(!empty($column_url) && is_array($column_url))
			{
				foreach($column_url as $k => $v)
				{
					if($column_id[$k])
					{
						$url[$k] = $v;
					}
				}
			}
			if(!empty($data['content_url']) && is_array($data['content_url']))
			{
				foreach($data['content_url'] as $k => $v)
				{
					$url[$k] = $v;
				}
			}
			$sql = "UPDATE " . DB_PREFIX . "feedback SET expand_id = " . $data['expand_id'] . ", column_url = '" .serialize($url). "' where id = " . $data['from_id'];
		}
		$this->db->query($sql);
		
		$this->addItem('true');
		$this->output();				
 	}
 	
 	/**
 	 * 删除这条内容的发布
 	 *
 	 */
 	function delete_publish()
 	{
		$data = $this->input['data'];
		if(empty($data))
		{
			return false;
		}
		if($data['is_delete_column'])   //只删除某一栏目中内容
		{
			$sql = "SELECT column_id,column_url FROM " . DB_PREFIX ."feedback WHERE id = " . $data['from_id'];
			$ret = $this->db->query_first($sql);
			$column_id = unserialize($ret['column_id']);
			$column_url = unserialize($ret['column_url']);
			$del_columnid = explode(',',$data['column_id']);
			if(is_array($del_columnid))
			{
				foreach($del_columnid as $k => $v)
				{
					unset($column_id[$v],$column_url[$v]);	
				}
			}
			$sql = "UPDATE " . DB_PREFIX ."feedback SET column_id = '".addslashes(serialize($column_id))."', column_url = '".addslashes(serialize($column_url))."' WHERE id = " . $data['from_id'];
			$this->db->query($sql);						
		}	
		else		//全部删除
		{
			$sql = "UPDATE " . DB_PREFIX . "feedback  
					SET expand_id = '', column_id = '', column_url = '' 
					WHERE id = " . $data['from_id'];
			$this->db->query($sql);
		}
		$this->addItem('true');
		$this->output();
 	}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new FeedbackPublish();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'unknow';
}
$out->$action(); 
?>

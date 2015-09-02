<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: option.php 12691 2012-10-19 09:30:41Z daixin $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class optionApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/action.class.php');
		$this->action = new action();
		
	}
	public function getData()
	{
		$data = array();
		if(isset($this->input['source']) && strlen($this->input['source']))
		{
			$data['source'] = trim(urldecode($this->input['source']));
		}
		if(isset($this->input['source_id']))
		{
			$data['source_id'] = trim($this->input['source_id']);
		}
		if(isset($this->input['action']) && strlen($this->input['action']))
		{
			$data['action'] = trim(urldecode($this->input['action']));
		}
		return $data;
	}
	public function checkUserExit()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput("用户没有登录");
		}
		return $this->user['user_id'];
	}
	//创建操作
	public function create()
	{
		$post = $data = array();
		$post = $data = $this->getData();
		if(!$data['action'] && !$data['source'] && !$data['source_id'])
		{
			$this->errorOutput("缺少重要参数");
		}
		$data['user_id'] = isset($this->input['user_id']) ? trim($this->input['user_id']) : $this->checkUserExit();
		
		$this->setXmlNode('option', 'create');
		$ret = $this->action->get('option', 'count(*) as total', $data,  0, 1,  array());
		
		if($ret)
		{
			$this->addItem_withkey('state', $ret);
			$this->addItem_withkey('hasing', true);
		}
		else 
		{
			$data['create_time'] = TIMENOW;
			$id = $this->action->insert('option', $data);
			
			if($id)
			{
				$res = $this->action->get('option_sign', '*', $post,  0, 1,  array());
			
				if($res)
				{
					$this->action->update('option_sign',array('num'=>1), $post, array('num'=>1));
				}
				else 
				{
					$post['num'] = 1;
					$this->action->insert('option_sign', $post);
					
				}
			}
			
			$this->addItem_withkey('state', $id);
		}
		$this->output();	
	} 
	//删除操作
	public function delete()
	{
		$post = $data = array();
		$post = $data = $this->getData();
		if(!$data['source'] || !$data['source_id'] || !$data['action'])
		{
			$this->errorOutput("缺少查询参数");
		}
		$data['user_id'] = isset($this->input['user_id']) ? trim($this->input['user_id']) : $this->checkUserExit();
		
		$this->setXmlNode('option', 'delete');
		$ret = $this->action->get('option', '*', $data,  0, 1,  array());
		if($ret)
		{
			$id = $this->action->delete('option', $data);
			
			if($id)
			{
				
				$res = $this->action->get('option_sign', '*', $post,  0, 1,  array());
			
				if($res)
				{
					$this->action->update('option_sign',array('num'=>-1), $post, array('num'=>1));
				}
				else 
				{
					$total = $this->action->get('option', 'count(id) as total', $post,  0, 1,  array());
					$post['num'] = $total['total'];
					$this->action->insert('option_sign', $post);
				}
				
			}
			$this->addItem_withkey('state', true);
		}
		else 
		{
			$this->addItem_withkey('state', false);
		}
		
		$this->output();
	} 
	//显示某些条件下的具体信息
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$data = array();
		$data = $this->getData();
		//默认获取状态为正常的数据
		$data['state'] = 1;
		$this->setXmlNode('option', 'show');
		if($this->input['user_id'])
		{
			$data['user_id'] = intval(trim($this->input['user_id']));
		}
		if($data['user_id'] && !($data['source'] && $data['source_id']))
		{
			$result = $this->action->get('option', 'DISTINCT (`action`),user_id', $data,  0, -1,  array());
			if(!$result)
			{
				$this->addItem_withkey('total', 0);
			}
			else 
			{
				$t = array();
				foreach($result as $k => $v)
				{
					$arr = array('total'=>0,'infos'=>array());
					$arr['total'] = $this->action->get('option', 'count(id) as total', $v,  0, 1,  array());
					
					if($arr['total'])
					{
						$arr['infos'] = $this->action->get('option', '*', $v,  $offset, $count,  array('action'=>'DESC','source'=>'DESC','create_time'=>'DESC'));
					}
					$t[$v['action']] = $arr;
				}
				$this->addItem_withkey('data', $t);
			}
		}
		else if(!$data['user_id'] && ($data['source'] && $data['source_id']))
		{
			$result = $this->action->get('option_sign', 'action,source,source_id', $data,  0, -1,  array());
			
			if(!$result)
			{
				$this->addItem_withkey('total', 0);
			}
			else 
			{
				$t = array();
				foreach($result as $k => $v)
				{
					$arr = array('total'=>0,'infos'=>array());
					$arr['total'] = $this->action->get('option_sign', 'num', $v,  0, 1,  array());
					
					if($arr['total'])
					{
						$arr['infos'] = $this->action->get('option', '*', $v,  $offset, $count,  array('action'=>'DESC','source'=>'DESC','create_time'=>'DESC'));
					}
					$t[$v['action']] = $arr;
				}
				$this->addItem_withkey('data', $t);
			}
		}
		else if($data['user_id'] && ($data['source'] && $data['source_id']))
		{
			if(strpos($data['action'], ',') || !$data['action'])
			{
				$result = $this->action->get('option', 'DISTINCT (`action`),user_id,source,source_id', $data,  0, -1,  array());
				if(!$result)
				{
					$this->addItem_withkey('total', 0);
				}
				else 
				{
					$t = array();
					foreach($result as $k => $v)
					{
						$arr = array('total'=>0,'infos'=>array());
						$arr['total'] = $this->action->get('option', 'count(id) as total', $v,  0, 1,  array());
						
						if($arr['total'])
						{
							$arr['infos'] = $this->action->get('option', '*', $v,  $offset, $count,  array('action'=>'DESC','source'=>'DESC','create_time'=>'DESC'));
						}
						$t[$v['action']] = $arr;
					}
					$this->addItem_withkey('data', $t);
				}
			}
			else 
			{
				$result = $this->action->get('option', 'count(id) as total,source,source_id,user_id,action,create_time', $data,  0, 1,  array());
				$this->addItem_withkey('state', $result['total']);
				if($result['total'])
				{
					unset($result['total']);
					$this->addItem_withkey('data', $result);
				}
			}
		}
		else 
		{
			$this->errorOutput("缺少重要参数");
		}
		$this->output();
	}
	
	public function detail()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$data = array();
		$data = $this->getData();
		$this->setXmlNode('option', 'show');
		//默认获取状态为正常的数据
		$data['state'] = 1;
		$total = $this->action->get('option', 'count(id) as total', $data,  0, 1,  array());
		if(!$total)
		{
			$total = 0;
		}
		if($total)
		{
			$arr = array('total'=>$total,'infos'=>array());
			$arr['infos'] = $this->action->get('option', '*', $data,  $offset, $count,  array('action'=>'DESC','source'=>'DESC','create_time'=>'DESC'));
			$t[$data['action']] = $arr;
			$this->addItem_withkey('data', $t);
		}
		$this->output();
	}
	//获取总数
	public function count()
	{
		//获取选取条件
		$data = array();
		$data = $this->getData();
		$this->setXmlNode('option', 'count');
		if($this->input['user_id'])
		{
			$data['user_id'] = intval(trim($this->input['user_id']));
		}
		$result = 0;
		//默认获取状态为正常的数据
		$data['state'] = 1;
		if($data['action'])
		{
			$result = $this->action->get('option', 'count(id) as total', $data,  0, 1,  array());
		}
		$this->setXmlNode('option', 'count');
		$this->addItem_withkey('total',$result);
		$this->output();
	}
	
	//
	public function getTotalAndUse()
	{
		$data = array();
		$data = $this->getData();
		
		if(strpos($data['source_id'], ','))
		{
			$arr = explode(',', $data['source_id']);
			foreach($arr as $k=>$v)
			{
				$use['total'] = 0;$use['info'] = 0;
				unset($data['user_id']);
				$data['source_id'] = $v;
				$total = $this->action->get('option', 'count(id) as total', $data,  0, 1,  array());
				$this->setXmlNode('option', 'getTotalAndUse');
				$use['total'] = $total;
				if($total)
				{
					$data['user_id'] = intval(trim($this->input['user_id']));
					
					if($data['user_id'])
					{
						$use['info'] = $this->action->get('option', 'count(id) as total', $data,  0, 1,  array());
					}
				}
				$this->addItem_withkey($v, $use);
			}
		}
		else 
		{
			$total = $this->action->get('option', 'count(id) as total', $data,  0, 1,  array());
			$this->setXmlNode('option', 'getTotalAndUse');
			$this->addItem_withkey('total', $total);
			if($total)
			{
				$data['user_id'] = intval(trim($this->input['user_id']));
				
				if($data['user_id'])
				{
					$use = $this->action->get('option', 'count(id) as total', $data,  0, 1,  array());
				}
			}
			$this->addItem_withkey('info', $use);
		}
		$this->output();
	}
	//
	function updateState()
	{
		$pa = $data = array();
		$data = $this->getData();
		
		$pa['state'] = isset($this->input['state']) ? trim($this->input['state']) :1 ;
		if($pata['state'] ==1)
		{
			$data['type_state'] = trim(urldecode($this->input['type']));
			$pa['type_state'] = '';
		}
		else 
		{
			$pa['type_state'] = trim(urldecode($this->input['type']));
			$data['state'] = 1;
		}
		$result = $this->action->update('option', $pa, $data,   array());
		$this->setXmlNode('option', 'updateState');
		$this->addItem_withkey('state', true);
		$this->output();
	}
	
	public function show_attention()
	{
		$source = trim(urldecode($this->input['source']));
		$action = trim(urldecode($this->input['action']));
		$sql = 'SELECT id, source_id FROM ' . DB_PREFIX . 'option 
		WHERE source = "' . $source . '" AND action = "' . $action . '"';
		$query = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($query))
		{
			$info[$row['source_id']][] = $row['id'];
		}
		$this->addItem($info);
		$this->output();
	}
	
	public function del_attention()
	{
		$ids = trim(urldecode($this->input['ids']));
		$audit_ids = trim(urldecode($this->input['audit_ids']));
		$back_ids = trim(urldecode($this->input['back_ids']));
		$val = trim(urldecode($this->input['val']));
		if (!empty($ids))
		{
			$sql = 'DELETE FROM ' . DB_PREFIX . 'option WHERE id in (' . $ids .')';
			$result = $this->db->query($sql);
		}
		if (!empty($audit_ids))
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'option SET state = 0, type_state = "team" WHERE id in (' . $audit_ids . ')';
			$result = $this->db->query($sql);
		}
		//更新删除状态
		if (!empty($back_ids) && !empty($val))
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'option SET state = 0, type_state = "'.$val.'" WHERE id in (' . $back_ids . ')';
			$result = $this->db->query($sql);
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 获取除组长的关注成员
	 */
	public function attention_members()
	{
		if (isset($this->input['creater_id']))
		{
			$creater_id = intval($this->input['creater_id']);
		}
		$source_id = isset($this->input['source_id']) ? intval($this->input['source_id']) : -1;
		if ($source_id < 0) $this->errorOutput(OBJECT_NULL);
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'option 
		WHERE state = 1 AND source = "team" AND action = "attention" AND source_id = ' . $source_id;
		if ($creater_id)
		{
			$sql .= ' AND user_id != ' . $creater_id;
		}
		$sql .= ' ORDER BY id DESC';
		$sql .= $data_limit;
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	
	/**
	 * 获取除组长的关注成员总数
	 */
	public function attention_count()
	{
		if (isset($this->input['creater_id']))
		{
			$creater_id = intval($this->input['creater_id']);
		}
		$source_id = isset($this->input['source_id']) ? intval($this->input['source_id']) : -1;
		if ($source_id < 0) $this->errorOutput(OBJECT_NULL);
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'option 
		WHERE state = 1 AND source = "team" AND action = "attention" AND source_id = ' . $source_id;
		if ($creater_id)
		{
			$sql .= ' AND user_id != ' . $creater_id;
		}
		$result = $this->db->query_first($sql);
		echo json_encode($result);
	}
	
	function __destruct()
	{
		parent::__destruct();
		unset($this->action);
	}
}

$out = new optionApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>
<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','adv_group');//模块标识
class adv_group_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		$this->verify_setting_prms();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function delete()
	{
		
		if(!$this->input['id'])
		{
			$this->errorOutput(ID_ERROR);
		}
		//检测客户端是否存在广告位 如果存在 必须先删除对应的广告位
		$sql = 'SELECT * FROM '.DB_PREFIX.'group_pos gp LEFT JOIN '.DB_PREFIX.'advgroup g ON gp.group_flag = g.flag WHERE g.id IN('.$this->input['id'].')';
		$check = $this->db->query_first($sql);
		if($check)
		{
			$this->errorOutput(GROUP_HAS_POS);
		}
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "advgroup WHERE id in (".urldecode($this->input['id']).")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			//系统内置
			if($row['is_system'])
			{
				$this->errorOutput(SYSTEM_CLIENT);
			}
			
			
			//记录日志
			$this->addLogs('删除广告客户端', $row, array(),$row['name'], $row['id']);
			//记录日志结束	
			
			
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['advgroup'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$sql = 'DELETE FROM '.DB_PREFIX.'advgroup WHERE id in('.urldecode($this->input['id']).')';
			$this->db->query($sql);
			$this->addItem('success');
			$this->output();
		}
		
	}
	function update()
	{
		
		$data = array(
		'id'=>intval($this->input['id']),
		'brief'=>trim($this->input['brief']),
		'model_name'=>trim($this->input['model_name']),
		'is_use'=>intval($this->input['is_use']),
		'ip'=>hg_getip(),
		);
		$sql = 'SELECT * FROM '.DB_PREFIX.'advgroup where id = '.$data['id'];
		$group = $this->db->query_first($sql);
		if(!$group)
		{
			$this->errorOutput(ID_ERROR);
		}
		$condition = ' WHERE id = '.$data['id'];
		unset($data['id']);
		$sql = 'UPDATE '.DB_PREFIX.'advgroup SET ';
		foreach ($data as $field=>$value)
		{
			$sql .= "{$field}='{$value}',";
		}
		$sql = rtrim($sql, ',') . $condition;
		//$this->errorOutput($sql);
		$this->db->query($sql);
		if($this->db->affected_rows())
		{
			$this->db->query("UPDATE ".DB_PREFIX.'advgroup set update_user_id='.$this->user['user_id'].', update_user_name="'.$this->user['user_name'].'" WHERE id = '.intval($this->input['id']));
		}
		$this->addItem('success');
		//记录日志
		$this->addLogs('更新广告客户端',$group, $data,$group['name']);
		//记录日志结束
		$this->output();
	}
	function audit()
	{
		
	}
	//记录可用于发布策略的字段 序列化数据
	function dopolicy()
	{
		
		if(!$this->input['id'])
		{
			return;
		}
		$sql = 'SELECT id,name FROM '.DB_PREFIX.'advgroup where id='.intval($this->input['id']);
		$group = $this->db->query_first($sql);
		if(!$group)
		{
			$this->errorOutput("数据不存在");
		}
		$policy = array();
		if($this->input['policy'])
		{
			foreach($this->input['policy'] as $v)
			{
				$tmp = explode('@',rawurldecode($v));
				$policy[$tmp[1]] = $tmp[0];
			}
		}
		$policy = serialize($policy);
		$sql = 'UPDATE '.DB_PREFIX.'advgroup SET policy = \''.$policy.'\' WHERE id = '.intval($this->input['id']);
		$this->db->query($sql);
		//记录日志
		$this->addLogs("发布策略数据修改",$group, array(), $group['name'], $group['id']);
		//记录日志结束
		$this->addItem('success');
		$this->output();
	}
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	function create()
	{
		
		$data = array(
		'name'=>trim($this->input['name']),
		'brief'=>trim($this->input['brief']),
		'model_name'=>trim($this->input['model_name']),
		'flag'=>trim($this->input['flag']),
		'is_use'=>intval($this->input['is_use']),
		'ip'=>hg_getip(),
		'create_time'=>TIMENOW,
		'user_name'=>$this->user['user_name'],
		'user_id'=>$this->user['user_id'],
		);
		if(!$data['name'])
		{
			$this->errorOutput(NO_CLIENT_NAME);
		}
		if(!$data['flag'])
		{
			$this->errorOutput(NO_CLIENT_FLAG);
		}
		$sql = 'SELECT id FROM '.DB_PREFIX.'advgroup WHERE flag = "'.$data['flag'].'"';
		$re = $this->db->query_first($sql);
		if($re)
		{
			$this->errorOutput(CLIENT_FLAG_REPEAT);
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'advgroup SET ';
		foreach ($data as $field=>$value)
		{
			$sql .= "{$field}='{$value}',";
		}
		$sql = rtrim($sql, ',');
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		//记录日志
		$this->addLogs('新增广告客户端', array(),$data,$data['name'], $data['id']);
		//记录日志结束	
		$this->addItem('success');
		$this->output();
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
}
$ouput= new adv_group_update();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>
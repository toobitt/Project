<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','adv_pos');//模块标识
class adv_pos_update extends adminUpdateBase
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
			return;
		}
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "advpos WHERE id in (".urldecode($this->input['id']).")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($row['is_system'])
			{
				$this->errorOutput(SYSTEM_ADV_POS);
			}
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['zh_name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['advpos'] = $row;
			//记录日志
			$this->addLogs("删除广告位", $row, array(), $row['zh_name']);
			//记录日志结束	
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$sql = 'DELETE FROM '.DB_PREFIX.'advpos  WHERE id in('.trim(urldecode($this->input['id'])).')';
			$this->db->query($sql);
			//删除广告位分组映射表
			$this->db->query('DELETE FROM ' . DB_PREFIX . 'group_pos WHERE pos_id IN('.trim(urldecode($this->input['id'])).')');
			$this->addItem('success');
			$this->output();
		}
		
	}
	function check_sys()
	{
		//检测更新的是否是系统内置广告位
		$sql = "SELECT is_system FROM " . DB_PREFIX . "advpos WHERE id in (".urldecode($this->input['id']).")";
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			if($row['is_system'])
			{
				$this->errorOutput(SYSTEM_ADV_POS);
			}
		}
	}
	
	function update()
	{
		
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'advpos WHERE id = '.intval($this->input['id']);
		$advpos = $this->db->query_first($sql);
		if(!$advpos)
		{
			$this->errorOutput(NOID);
		}
		$this->check_sys();
		$para = $form_style = array();
		if($this->input['para_en'] && is_array($this->input['para_en']))
		{
			foreach ($this->input['para_en'] as $k=>$v)
			{
				if(!$v || !$this->input['para_zh'][$k])
				{
					continue;
				}
				$v = trim($v);
				$para[$v] = trim($this->input['para_zh'][$k]);
				$form_style[$v] = trim(urldecode($this->input['form_style'][$k]));
			}
		}
		$data = array(
		'id'=>intval(urldecode($this->input['id'])),
		'is_use'=>intval(trim(urldecode($this->input['is_use']))),
		'multi'=>intval($this->input['multi']),
		'ani_id'=>intval(trim(urldecode($this->input['ani']))),
		'priority'=>intval(trim(urldecode($this->input['priority']))),
		'weight'=>intval(trim(urldecode($this->input['weight']))),
		'para'=>$para ? serialize($para) : '',
		'form_style'=>$form_style ? serialize($form_style) : '',
		);
		$group_temp = array();
		if($this->input['select_group'])
		{
			$select_group =  $this->input['select_group'];
			$select_group_str = implode('\',\'',$select_group);
			//获取分组名称和标志信息
			$sql = 'SELECT flag,name FROM '.DB_PREFIX.'advgroup WHERE flag in(\''.$select_group_str.'\')';
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$group[$r['flag']] = $r['name'];
			}
			//遍历分组映射关系
			foreach ($select_group as $v)
			{
				$group_temp[$v] = $group[$v];
			}
		}
		$data['group_flag'] = serialize($group_temp);
		$sql = 'UPDATE '.DB_PREFIX.'advpos SET '.
		'is_use = "'.$data['is_use'].'",'.
		'multi = "'.$data['multi'].'",'.
		'ani_id = "'.$data['ani_id'].'",'.
		'form_style = \''.$data['form_style'].'\','.
		'group_flag = \''.$data['group_flag'].'\','.
		'para = \''.$data['para'].'\' WHERE id = '.$data['id'];
		$this->db->query($sql);
		if($this->db->affected_rows())
		{
			$this->db->query("UPDATE ".DB_PREFIX.'advpos set update_user_id='.$this->user['user_id'].', update_user_name="'.$this->user['user_name'].'" WHERE id = '.$data['id']);
		}
		//记录日志
		$this->addLogs('更新广告位', $advpos, $data, $advpos['zh_name']);
		//记录日志结束	
		//查询出广告位已属分组
		$sql = 'SELECT * FROM '.DB_PREFIX.'group_pos WHERE pos_id = '.$data['id'];
		$q = $this->db->query($sql);
		$group_owned_pos = array();
		while($r = $this->db->fetch_array($q))
		{
			$group_owned_pos[$r['id']] = $r['group_flag'];
		}
		//需要删除的分组
		$update_data = array_diff($group_owned_pos, $this->input['select_group']);
		if($update_data)
		{
			foreach ($update_data as $v)
			{
				$sql = 'DELETE FROM '.DB_PREFIX.'group_pos WHERE pos_id = '.$data['id'].' AND group_flag="'.$v.'"';
				$this->db->query($sql);
			}	
		}
		//需要添加的分组
		$update_data = array();
		$update_data = array_diff($this->input['select_group'],$group_owned_pos);
		if($update_data)
		{
			foreach ($update_data as $v)
			{
				$sql = 'INSERT INTO '.DB_PREFIX.'group_pos SET pos_id = '.$data['id'].', group_flag="'.$v.'"';
				$this->db->query($sql);
			}	
		}
		$this->addItem('success');
		$this->output();
	}
	function create()
	{
		
		if(!$this->input['name'])
		{
			$this->errorOutput('广告位置名称必须');
		}
		//用户自定义参数处理为en=>zh
		$para = $form_style = array();
		if($this->input['para_en'] && is_array($this->input['para_en']))
		{
			foreach ($this->input['para_en'] as $k=>$v)
			{
				if(!trim($v) || !$this->input['para_zh'][$k])
				{
					continue;
				}
				$v = trim($v);
				$para[$v] = trim($this->input['para_zh'][$k]);
				$form_style[$v] = trim(urldecode($this->input['form_style'][$k]));
			}
		}
		//表单数据处理
		$data = array(
		'id'=>intval($this->inputp['id']),
		'name'=>trim($this->input['name']),
		'zh_name'=>trim($this->input['zh_name']),
		'is_use'=>intval(trim($this->input['is_use'])),
		'multi'=>intval($this->input['multi']),
		'ani_id'=>intval(trim($this->input['ani'])),
		'para'=>$para ? serialize($para) : '',
		'create_time'=>TIMENOW,
		'form_style'=>$form_style ? serialize($form_style) : '',
		'user_name'=>$this->user['user_name'],
		'user_id'=>$this->user['user_id'],
		);
		//广告位分发至客户端
		if($this->input['select_group'])
		{
			$select_group =  $this->input['select_group'];
			$select_group_str = implode('\',\'',$select_group);
			//获取分组名称和标志信息
			$sql = 'SELECT flag,name FROM '.DB_PREFIX.'advgroup WHERE flag in(\''.$select_group_str.'\')';
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$group[$r['flag']] = $r['name'];
			}
			//遍历分组映射关系
			foreach ($select_group as $v)
			{
				$group_temp[$v] = $group[$v];
			}
			$data['group_flag'] = serialize($group_temp);
		}
		//生成sql串
		$sql = 'INSERT INTO '.DB_PREFIX.'advpos SET '.
		'name = "'.$data['name'].'",'.
		'zh_name = "'.$data['zh_name'].'",'.
		'is_use = "'.$data['is_use'].'",'.
		'multi = "'.$data['multi'].'",'.
		'ani_id = "'.$data['ani_id'].'",'.
		'create_time = "'.$data['create_time'].'",'.
		'user_name = "'.$data['user_name'].'",'.
		'user_id = "'.$data['user_id'].'",'.
		'form_style = \''.$data['form_style'].'\','.
		'group_flag = \''.$data['group_flag'].'\','.
		'para = \''.$data['para'].'\'';
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		//记录日志
		$this->addLogs("新增广告位置", '', $data, $data['name'],$data['id']);
		//记录日志结束
		
		$id = $this->db->insert_id();
		//更新排序字段
		$this->db->query('UPDATE '.DB_PREFIX.'advpos SET order_id = '.intval($id) . ' WHERE id = '.intval($id));
		//分发数据入库
		if($this->input['select_group'])
		{
			foreach ($this->input['select_group'] as $v)
			{
				$sql = 'INSERT INTO '.DB_PREFIX.'group_pos SET pos_id = '.$id.', group_flag="'.$v.'"';
				$this->db->query($sql);
			}
		}
		
		$this->addItem('success');
		$this->output();
		
	}
	function audit()
	{

	}
	function sort()
	{
		
	}
	function publish()
	{
		
	}
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new adv_pos_update();
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
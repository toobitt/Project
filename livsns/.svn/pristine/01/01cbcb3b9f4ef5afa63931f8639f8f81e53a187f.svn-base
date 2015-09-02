<?php
include_once('./global.php');
define('MOD_UNIQUEID','cp_mark_m');//模块标识

class markupdateApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
		include_once './lib/mark.class.php';
		$this->marklib = new markLib();
	}
	//用户处理
	public function checkUserExit()
	{
		//$this->user = array('user_id'=>84);
		if(!$this->user['user_id'])
		{
			$this->errorOutput("用户没有登录");
		}
		return $this->user['user_id'];
	}
	//处理
	function getCreateMarkId()
	{
		$pIds = $pNmes = array();
		if(isset($this->input['name']))
		{
			$name = $this->marklib->getName();
			$names = explode(',', $name);
			foreach($names as $k=>$v)
			{
				$this->marklib->processDataMarkName($v);
			}
			$str = '';
			if(count($names) > 1)
			{
				$str = "'";
			}
			$name = $str . implode("','", $names) . $str;
			$result = $this->marklib->get('name', ' nid as mark_id,name as mark_name,state', array('name'=>$name,'action'=>0),  0, -1,  array());
			if($result)
			{
				foreach($result as $k=>$v)
				{
					if($v['state'] != 1)
					{
						$this->errorOutput("你的设置标签".$v['mark_name']."禁止使用");
					}
					$pNmes[$v['mark_id']] = $v['mark_name'];
					$pIds[$v['mark_id']] = $v['mark_id'];
				}
				//去除已经存在的标签
				$names = array_diff($names, $pNmes);
			}
			if($names)
			{
				foreach($names as $k=>$v)
				{
					$insert = array();
					$insert['name'] = $v;
					$insert['action'] = 0;//标签专用
					$insert['keywords_unicode'] = $this->marklib->str_utf8_unicode($v);
					$mark_id = $this->marklib->insert('name',$insert);
					$pIds[$mark_id] = $mark_id;
				}
			}
		}
		if(isset($this->input['mark_id']))
		{
			$name = trim($this->input['mark_id']);
			$names = explode(',', $name);
			foreach($names as $k=>$v)
			{
				if(!$v)
				{
					$this->errorOutput("你的设置的标签id参数错误");
				}
				$t[$v] = $v;
			}
			$names = $t;
			$name = implode(",", $names);
			$result = $this->marklib->get('name', ' nid as mark_id,name as mark_name,state', array('nid'=>$name,'action'=>0),  0, -1,  array());
			if($result)
			{
				foreach($result as $k=>$v)
				{
					if($v['state'] == 0)
					{
						$this->errorOutput("你的设置标签".$v['mark_name']."禁止使用");
					}
					$pIds[$v['mark_id']] = $v['mark_id'];
				}
				//去除已经存在的标签
				$names = array_diff($names, $pIds);
			}
			if($names)
			{
				$this->errorOutput("你的设置的标签id没有对应标签");
			}
		}
		return $pIds;
	}
	//获取对象参数
	public function getObject()
	{
		$data = array();
		if(isset($this->input['source']))
		{
			$data['source'] = trim(urldecode($this->input['source']));
		}
		if(isset($this->input['source_id']))
		{
			$data['source_id'] = trim(urldecode($this->input['source_id']));
		}
		if(isset($this->input['action']))
		{
			$data['action'] = trim(urldecode($this->input['action']));
		}
		$data['source'] = trim(urldecode($this->input['source']));
		if(isset($this->input['parent_id']))
		{
			$data['parent_id'] = trim(urldecode($this->input['parent_id']));
		}
		return $data;
	}
	//添加用户关系表
	function updateUserMarkSign($user_id, $data, $mark_id)
	{
		$data['mark_id'] = $mark_id;
		$data['user_id'] = $user_id;
		$result = $this->marklib->get('mark_sign', '*', $data, 0, -1, array(),array());
		if(!$result)
		{
			$data['create_time'] = TIMENOW;
			$data['state'] = 1;
			$this->marklib->insert('mark_sign', $data);
		}
		return true;
	}
	//创建标签
	public function create()
	{
		//获取对象参数
		$data = array();
		$data = $this->getObject();
		//处理参数
		$mark_ids = $this->getCreateMarkId();
		$mark_id = implode(',', $mark_ids);
		//获取用户
		$user_id = $this->checkUserExit();
		//获取对象标签表
		$pResult = array();
		$pResult = $this->marklib->get('mark_action', '*', $data, 0, -1, array(), array());
		$i = 0;
		if($pResult)
		{
			foreach($pResult as $k=>$v)
			{
				if(in_array($v['mark_id'], $mark_ids))
				{
					//删除已有数据
					unset($mark_ids[$v['mark_id']]);
					$i++;
				}
			}
		}
		if($i+count($mark_ids) > MARK_LIMIT_NUM)
		{
			$this->errorOutput("你设置的标签大于系统规定的最大数");
		}
		if($mark_ids)
		{
			$data['create_time'] = TIMENOW;
			foreach($mark_ids as $k=>$v)
			{
				$data['mark_id'] = $v;
				$result = $this->marklib->insert('mark_action', $data);
				if($result)
				{
					$this->updateUserMarkSign($user_id, $data, $v);
				}
			}
		}
		$this->setXmlNode('mark','create');
		$this->addItem_withkey('state', true);
		$this->output();
	}
	//更新标签
	public function update()
	{
		//获取对象参数
		$post = $data = array();
		$post = $data = $this->getObject();
		//处理参数
		$mark_ids = $this->getCreateMarkId();
		$mark_id = implode(',', $mark_ids);
		//获取用户
		$user_id = $this->checkUserExit();
		//获取对象标签表
		$pResult = array();
		$pResult = $this->marklib->get('mark_action', '*', $data, 0, -1, array(), array());
		$i = 0;
		if($pResult)
		{
			foreach($pResult as $k=>$v)
			{
				if(in_array($v['mark_id'], $mark_ids))
				{
					//删除已有数据
					unset($mark_ids[$v['mark_id']]);
					$i++;
				}
				else 
				{
					//补充去掉不存在数据
					$arr_id[] = $v['mark_id'];
				}			
			}
		}
		if($i+count($mark_ids) > MARK_LIMIT_NUM)
		{
			$this->errorOutput("你设置的标签大于系统规定的最大数");
		}
		if($mark_ids)
		{
			$data['create_time'] = TIMENOW;
			foreach($mark_ids as $k=>$v)
			{
				$data['mark_id'] = $v;
				$result = $this->marklib->insert('mark_action', $data);
				if($result)
				{
					$this->updateUserMarkSign($user_id, $data, $v);
				}
			}
		}
		if($arr_id)
		{
			$post['mark_id'] = implode(',', $arr_id);
			$this->marklib->delete('mark_action', $post);
			//删除用户纪录
			$this->deleteUserMark($user_id, $post);
		}
		$this->setXmlNode('mark','update');
		$this->addItem_withkey('state', true);
		$this->output();
	}
	//删除标签
	public function delete()
	{
		//获取对象参数
		$post = $data = array();
		if(isset($this->input['sid']))
		{
			$data['sid'] = trim($this->input['sid']);
		}
		else 
		{
			$post = $data = $this->getObject();
			//删除标签
			$mark_ids = array();
			$mark_ids = $this->getCreateMarkId();
			if($mark_ids)
			{
				$mark_id = implode(',', $mark_ids);
				$post['mark_id'] = $data['mark_id'] = $mark_id;
			}
		}
		//获取用户
		if(isset($this->input['user_id']))
		{
			$user_id = $this->input['user_id'];
		}
		else 
		{
			$user_id = $this->checkUserExit();
		}
		//
		
		$result = $this->marklib->get('mark_action', 'action,source,source_id,mark_id', $data, 0, -1, array());
		if($result)
		{
			if(empty($mark_id) ||  !isset($mark_id))
			{
				$mark_id = $sp = '';
				foreach($result as $k=>$v)
				{
					$mark_id .= $sp .$v['mark_id'];
					$sp = ',';
				}
				$post['mark_id'] = $mark_id;
			}
			$this->marklib->delete('mark_action', $post);
			//删除用户纪录
			$this->deleteUserMark($user_id, $post);
		}
		$this->setXmlNode('mark','delete');
		$this->addItem_withkey('state', true);
		$this->output();
	}
	//删除用户纪录
	function deleteUserMark($user_id, $pata)
	{
		$b = $this->marklib->get('mark_sign','id,user_id',$pata,0,-1,array());
		if($b)
		{
			foreach($b as $k=>$v)
			{
				if($v['user_id'] == $user_id)
				{
					$this->marklib->delete('mark_sign', array('id'=>$v['id']));
				}
				else 
				{
					$this->marklib->update('mark_sign', array('delete_id'=>$user_id,'up_time'=>TIMENOW),array('id'=>$v['id']),array());
				}
			}
		}
		return true;
	}
	
	function updateState()
	{		
		//获取对象参数
		$post = $pata = $data = array();
		if(isset($this->input['sid']))
		{
			$data['sid'] = trim($this->input['sid']);
		}
		else 
		{
			$post = $data = $this->getObject();
			$mark_ids = array();
			$mark_ids = $this->getCreateMarkId();
			if($mark_ids)
			{
				$mark_id = implode(',', $mark_ids);
				$post['mark_id'] = $data['mark_id'] = $mark_id;
			}
		}
		
		$pata['state'] = isset($this->input['state']) ? trim($this->input['state']) : 1;
		if($pata['state'] ==1)
		{
			$data['type_state'] = trim(urldecode($this->input['type']));
			$pata['type_state'] = '';
		}
		else 
		{
			$pata['type_state'] = trim(urldecode($this->input['type']));
			$data['state'] = 1;
		}
		$result = $this->marklib->get('mark_action',  'action,source,source_id,mark_id', $data, 0, -1, array());
		if($result)
		{
			if(empty($mark_id) ||  !isset($mark_id))
			{
				$mark_id = $sp = '';
				foreach($result as $k=>$v)
				{
					$mark_id .= $sp .$v['mark_id'];
					$sp = ',';
				}
				$post['mark_id'] = $mark_id;
			}
			$this->marklib->update('mark_action', $pata, $post, array());
			$this->marklib->update('mark_sign', $pata, $post, array());
		}
		$this->setXmlNode('mark','updateState');
		$this->addItem_withkey('state', true);
		
		$this->output();
	}
	
	function unkonw()
	{
		$this->errorOutput("你搜索得方法不存在");
	}
	function __destruct()
	{
		parent::__destruct();
		unset($this->marklib);
	}
	
}
/**
 *  程序入口
 */
$out = new markupdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'unknow';
}
$out->$action();
?>
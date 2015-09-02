<?php
include_once('./global.php');
define('MOD_UNIQUEID','cp_mark_m');//模块标识

class markAdminApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
			
		include_once './../lib/mark.class.php';
		$this->marklib = new markLib();
	}
	
	function __destruct()
	{
		parent::__destruct();
		unset($this->marklib);
	}
	function create()
	{
		$mark_name = trim(urldecode($this->input['mark_name']));
		$kind_name = trim(urldecode($this->input['kind_name']));
		$this->setXmlNode('kindAdmin', 'create');
		if(strlen($mark_name))
		{
			$names = explode(',', $mark_name);
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
					if($v['state'] ==0)
					{
						$this->errorOutput("你的设置里有参数已被删除");
					}
					if(in_array($v['mark_name'], $names))
					{
						$Pids[$v['mark_id']] = $v['mark_id'];
						$Pname[$v['mark_id']] = $v['mark_name'];
					}
				}
				$names = array_diff($names, $Pname);
			}
			if($names)
			{
				$insert['action'] = 0;//标签专用
				foreach($names as $k=>$v)
				{
					$insert['name'] = $v;
					$insert['keywords_unicode'] = $this->marklib->str_utf8_unicode($v);
					$mark_id = $this->marklib->insert('name',$insert);
					$Pids[$mark_id] = $mark_id;
				}
			}
			$mark_id = implode(',', $Pids);
			$kind_result = $this->marklib->get('name', ' nid as kind_id,name as kind_name,state', array('name'=>$kind_name,'action'=>1),  0, 1,  array());
			if(!$kind_result)
			{
				$insert = array();
				$insert['action'] = 0;//标签专用
				$insert['name'] = $kind_name;
				$insert['keywords_unicode'] = $this->marklib->str_utf8_unicode($kind_name);
				$kind_id = $this->marklib->insert('name',$insert);
			}
			else 
			{
				if($kind_result['state'] ==0)
				{
					$this->errorOutput("你的设置分类里有参数已被删除");
				}
				$kind_id = $kind_result['kind_id'];
			}
			$result = array();
			$result = $this->marklib->get('kind_action', '*',array('user_id'=>0, 'mark_id'=>$mark_id, 'kind_id'=>$kind_id),0 , -1, array());
			if($result)
			{
				foreach($result as $k=>$v)
				{
					if(in_array($v['mark_id'], $Pids) && $v['kind_id'] == $kind_id )
					{
						unset($Pids[$v['mark_id']]);
					}
				}
			}
			if($Pids)
			{
				$insert = array();
				$insert['kind_id'] = $kind_id;
				$insert['user_id'] = 0;
				$insert['create_time'] = TIMENOW;
				foreach($Pids as $k=>$v)
				{
					$insert['mark_id'] = $v;
					$this->marklib->insert('kind_action',$insert);
					$this->updateKindSign(array('user_id'=>0,'kind_id'=>$kind_id), 1, 1);
				}
			}
		}
		$this->addItem(true);
		$this->output();
	}
	
	function delete()
	{
		$id = trim($this->input['id']);
		$result = $this->marklib->get('kind_action', 'user_id,kind_id',array('id'=>$id),0 , -1, array());
		if($this->marklib->delete('kind_action', array('id'=>$id)))
		{
			if($result)
			{
				foreach($result as $k=>$v)
				{
					$this->updateKindSign($v, -1, 1);
				}
			}
		}
		$this->addItem($id);
		$this->output();
	}
	//
	function updateKindSign($data, $num, $type)
	{
		$res = 0;
		$ret = $this->marklib->get('kind_sign','count(*) as total', $data, 0, 1, array());
		if($ret)
		{
			$res = $this->marklib->update('kind_sign', array('num'=>$num), $data, array('num'=>$type));
		}
		else 
		{
			$data['num'] = $num;
			$res = $this->marklib->insert('kind_sign', $data);
		}
		return $res;
	}
	//更新该类型的总数
	public function updateMarkSigns($data, $num, $type)
	{
		$res = 0;
		if(!$data['source'] || !$data['source_id'] || !$data['action'])
		{
			$this->errorOutput("你的设置里有参数缺少来源参数");
		}
		$ret = $this->marklib->get('mark_sign','count(id) as total', $data, 0, 1, array());
		if($ret)
		{
			$res = $this->marklib->update('mark_sign', array('num'=>$num), $data, array('num'=>$type));
		}
		else 
		{
			$data['num'] = $num;
			$res = $this->marklib->insert('mark_sign', $data);
		}
		return $res;
	}
	//更新该分类的总数
	public function updateKindSigns($data, $num, $type)
	{
		$res = 0;
		if(!$data['user_id'] || !$data['kind_id'])
		{
			$this->errorOutput("你的设置里有参数缺少来源参数");
		}
		$ret = $this->marklib->get('kind_sign','count(id) as total', $data, 0, 1, array());
		if($ret)
		{
			$res = $this->marklib->update('kind_sign', array('num'=>$num), $data, array('num'=>$type));
		}
		else 
		{
			$data['num'] = $num;
			$res = $this->marklib->insert('kind_sign', $data);
		}
		return $res;
	}
	//
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 8;
		$result = array();
		$data['user_id'] = 0;
		$result = $this->marklib->getKindMarks('count(*) as total', $data, 0, 1, array());
		$this->addItem_withkey('total', $result[0]['total']);
		$arr = $this->marklib->getKindMarks('b.id, b.mark_id, b.mark_name, b.create_time, b.kind_id, b.user_id,c.name as kind_name',$v, $offset, $count, array('id'=>'desc'));
		$this->addItem_withkey('data', $arr);
		$this->output();
	} 
}

/**
 *  程序入口
 */
$out = new markAdminApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
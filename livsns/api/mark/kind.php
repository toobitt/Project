<?phpinclude_once('./global.php');define('MOD_UNIQUEID','cp_mark_m');//模块标识class kindApi extends BaseFrm{	function __construct()	{		parent::__construct();		include_once './lib/mark.class.php';		$this->marklib = new markLib();	}	//	public function getCreateMarkId()	{		if(!isset($this->input['kind_id']))		{			$name = '';			$name = $this->marklib->getName();			$names = explode(',', $name);			foreach($names as $k => $v)			{				$this->marklib->processDataMarkName($v);			}			$str = '';			if(count($names) > 1)			{				$str = "'";			}			$name = $str . implode("','", $names) . $str;			$result = $this->marklib->get('name', ' nid as kind_id,name,state', array('name'=>$name,'action'=>1),  0, count($names),  array());			$oNames = $oids = array();			if($result)			{				foreach ($result as $k => $v)				{					if($v['state'] == 0)					{						$this->errorOutput("你的设置里有参数已被禁止使用");					}					if(in_array($v['name'], $names))					{						$oNames[$v['kind_id']] = $v['name'];						$oids[$v['kind_id']] = $v['kind_id'];					}				}				$names = array_diff($names, $oNames);			}			if($names)			{				foreach($names as $k => $v)				{					$post = array();					$post['name'] = $v;					$post['action'] = 1;					$post['keywords_unicode'] = $this->marklib->str_utf8_unicode($v);					$mark_id = $this->marklib->insert('name',$post);					$oids[$kind_id] = $kind_id;					$oNames[$kind_id] = $v;				}			}		}		else 		{			$kind_id = trim($this->input['kind_id']);			$t = $marks = array();			$t = explode(',', $kind_id);			foreach($t as $k => $v)			{				if(empty($v))				{					$this->errorOutput("你的设置里的参数有问题");				}				$marks[$v] = $v;			}			$result = array();			$result = $this->marklib->get('name', 'nid as kind_id,name,state', array('nid'=>$kind_id,'action'=>1),  0, count($marks),  array());			if(count($result) != count($marks))			{				$this->errorOutput("你的设置里的参数有没有设置字段");			}			if($result)			{				$oNames = $oids = array();				foreach($result as $k => $v)				{					if($v['state'] == 0)					{						$this->errorOutput("你的设置里的参数有禁止字段");					}					$oNames[$v['kind_id']] = $v['name'];					$oids[$v['kind_id']] = $v['kind_id'];				}			}		}		return array('oids'=>$oids,'oNames'=>$oNames);	}	//增加分类信息	public function move()	{		$data = array();		$data['user_id'] = $this->marklib->checkUserExit();		//判断		$data['kind_id'] = trim($this->input['kind_id']);		$mark_id = trim($this->input['mark_id']);		$marks = explode(',', $mark_id);		$q = array();		foreach($marks as $k => $v)		{			if(empty($v))			{				$this->errorOutput("你的设置里的参数有空字段");			}			$q[$v] = $v;		}		$result = $this->marklib->get('kind_action', 'kind_id,user_id,mark_id', array('user_id'=>$data['user_id'],'kind_id'=>$data['kind_id']), 0, count($q), array());		$this->setXmlNode('kind', 'move');		$arr = array();		if($result)		{			foreach($result as $k => $v)			{				if(in_array($v['mark_id'], $q))				{					$this->addItem_withkey($v['mark_id'], true);					unset($q[$v['mark_id']]);				}			}		}		if($q)		{			foreach($q as $k => $v)			{				$try = false;				$data['mark_id'] = 0;				$data['mark_id'] = $v;				$data['create_time'] = TIMENOW;				if($this->marklib->insert('kind_action', $data))				{					$this->updateKindSigns(array('user_id'=>$data['user_id'],'kind_id'=>$data['kind_id']), 1, 1);					$try = true;				}				$this->addItem_withkey($v, $try);			}		}		$this->output();	}	//	public function updateKindSigns($data, $num, $type)	{		$res = 0;		if(!$data)		{			$this->errorOutput("你的设置里有参数缺少来源参数");		}		$ret = $this->marklib->get('kind_sign','count(id) as total', $data, 0, 1, array());		if($ret)		{			$res = $this->marklib->update('kind_sign', array('num'=>$num), $data, array('num'=>$type));		}		else 		{			$data['num'] = $num;			$res = $this->marklib->insert('kind_sign', $data);		}		return $res;	}		//创建用户分类	public function create()	{		$data = array();		$data['user_id'] = 0;				$name = $this->marklib->getName();		$names = explode(',', $name);		foreach($names as $k => $v)		{			$this->marklib->processDataMarkName($v);		}		if(count($names) > 1)		{			$str = "'";		}		$name = $str . implode("','", $names) . $str;		$result = array();		$result = $this->marklib->get('name', ' nid as kind_id,name,state', array('name'=>$name,'action'=>1),  0, count($names),  array());		$this->setXmlNode('kind', 'create');		if($result)		{			foreach($result as $k => $v)			{				if($v['state'] == 0)				{					$this->errorOutput("你的设置里有参数已被禁止使用");				}				$oName[$v['kind_id']] = $v['name'];				$oids[$v['kind_id']] = $v['kind_id'];			}			$names = array_diff($names, $oNames);		}		if($names)		{			foreach($names as $k => $v)			{				$post = array();				$post['name'] = $v;				$post['action'] = 1;				$post['keywords_unicode'] = $this->marklib->str_utf8_unicode($v);				$mark_id = $this->marklib->insert('name',$post);				$oids[$mark_id] = $mark_id;				$oNames[$mark_id] = $v;			}		}				$data['kind_id'] = implode(',', $oids);		$result = array();		$result = $this->marklib->get('kind_sign', 'user_id,kind_id', $data, 0, count($oids), array());		if($result)		{			foreach($result as $k => $v)			{				$this->addItem_withkey($oNames[$v['kind_id']], true);				unset($oids[$v['kind_id']]);			}		}		if($oids)		{			foreach($oids as $k => $v)			{				$data['kind_id'] = $v;				$this->marklib->insert('kind_sign', $data);				$this->addItem_withkey($oNames[$v], true);			}		}		$this->output();	}	public function update()	{		$data['user_id'] = isset($this->input['user_id']) ? trim($this->input['user_id']) : $this->marklib->checkUserExit();		$data['kind_id'] = tirm($this->input['kind_id']);		$mark_id = trim($this->input['mark_id']);		$marks = explode(',', $mark_id);		$q = array();		foreach($marks as $k => $v)		{			if(empty($v))			{				$this->errorOutput("你的设置里的参数有空字段");			}			$res = $this->marklib->get('name','nid as mark_id', array('nid'=>$v,'state'=>1,'action'=>1), 0, 1, array());			if(!$res)			{				$this->errorOutput("你的设置里的参数有禁止字段");			}			$q[$v] = $v;		}		$mark_id = implode(',', $q);		$new_kind_id = tirm($this->input['new_kind_id']);		$result = $this->marklib->get('kind_action', 'kind_id,user_id,mark_id', array('user_id'=>$data['user_id'],'kind_id'=>$data['kind_id'],'mark_id'=>$mark_id), 0, count($q), array());		$this->setXmlNode('kind', 'move');		$arr = array();		if($result)		{			foreach($result as $k => $v)			{				if(in_array($v['mark_id'], $q))				{					if($this->marklib->delete('kind_action', $v))					{						$this->updateKindSigns(array('user_id'=>$data['user_id'],'kind_id'=>$data['kind_id']), -1, 1);					}					$ret = $this->marklib->get('kind_action', 'kind_id,user_id,mark_id', array('user_id'=>$data['user_id'],'kind_id'=>$new_kind_id,'mark_id'=>$v['mark_id']), 0, 1, array());					if(!$ret)					{						if($this->marklib->insert('kind_action', array('user_id'=>$data['user_id'],'kind_id'=>$new_kind_id,'mark_id'=>$v['mark_id'],'create_time'=>TIMENOW)))						{							$this->updateKindSigns(array('user_id'=>$data['user_id'],'kind_id'=>$new_kind_id), 1, 1);						}					}					$this->addItem_withkey($v['mark_id'], true);					unset($q[$v['mark_id']]);				}			}		}		if($q)		{			$data['kind_id'] = $new_kind_id;			foreach($q as $k => $v)			{				$try = false;				$data['mark_id'] = $v;				$data['create_time'] = TIMENOW;				if($this->marklib->insert('kind_action', $data))				{					$this->updateKindSigns(array('user_id'=>$data['user_id'],'kind_id'=>$new_kind_id), 1, 1);					$try = true;				}				$this->addItem_withkey($v, $try);			}		}	}	//	public function delete()	{		$data = array();		$data['user_id'] = isset($this->input['user_id']) ? trim($this->input['user_id']) : $this->marklib->checkUserExit();		//判断		if(isset($this->input['id']))		{			$data['id'] =  trim($this->input['id']);		}		else 		{			if(isset($this->input['kind_id']))			{				$data['kind_id'] = trim($this->input['kind_id']);			}			if(isset($this->input['mark_id']))			{				$data['mark_id'] = trim($this->input['mark_id']);			}		}		$result = $this->marklib->get('kind_action', 'kind_id,user_id,mark_id', $data, 0, -1, array());		$this->setXmlNode('kind', 'delete');		$arr = array();		if($result)		{			foreach($result as $k => $v)			{				if($this->marklib->delete('kind_action', $v))				{					unset($v['mark_id']);					$this->updateKindSigns($v, -1, 1);				}			}		}		$this->setXmlNode('kind', 'delete');		$this->addItem_withkey('info', true);		$this->output();	}	public function show()	{		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;		$count = isset($this->input['count']) ? intval($this->input['count']) : 8;		$data['user_id'] = isset($this->input['user_id']) ? trim($this->input['user_id']) : $this->marklib->checkUserExit();		if(isset($this->input['kind_id']))		{			$data['kind_id'] = trim($this->input['kind_id']);		}		if(isset($this->input['kind_name']))		{			$kind_name = trim(urldecode($this->input['kind_name']));			$names = explode(',', $kind_name);			foreach($names as $k => $v)			{				$this->marklib->processDataMarkName($v);			}			$str = '';			if(count($names) > 1)			{				$str = "'";			}			$name = $str . implode("','", $names) . $str;			$result = array();			$result = $this->marklib->get('name', ' nid as kind_id', array('name'=>$name,'action'=>1),  0,-1,  array());						if(!$result)			{				$this->errorOutput("你的搜索分类不存在");			}			$data['kind_id'] =$sp ='';			foreach($result as $k=>$v)			{				$data['kind_id'] .= $sp. $v['kind_id'];				$sp = ',';			}		}		$this->setXmlNode('kind', 'show');		$total = $this->marklib->get('kind_action', 'count(id) as total', $data, 0, 1, array());		$this->addItem_withkey('total', $total);		if($total)		{			$result = $this->marklib->getKindMarks($data, $offset, $count, array('kind_id'=>'','create_time'=>'desc'));						if($result)			{				$arr = array();				foreach($result as $k=>$v)				{					$v['kind_name'] = $this->marklib->get('name','name as kind_name',array('nid'=>$v['kind_id'],'action'=>1,'state'=>1),0,1,array());					$arr[$k] = $v;				}				$this->addItem_withkey('data', $arr);			}		}		$this->output();	}		public function adminMove()	{		$data = array();		$data['user_id'] = isset($this->input['user_id']) ? trim($this->input['user_id']) : $this->marklib->checkUserExit();		$kind_names = $kind_ids = $kinds = array();		if(isset($this->input['kind_name']))		{			$kind_name = trim(urldecode($this->input['kind_name']));						$names = explode(',', $kind_name);			if(count($names) > 1)			{				$kind_name = "'" . implode("','", $names) . "'";			}			else 			{				$kind_name = implode("','", $names);			}			if($kind_name)			{				$kinds = $this->marklib->get('name','nid as kind_id,name as kind_name,state', array('name'=>$kind_name,'action'=>1),0 , -1, array());				if($kinds)				{					foreach($kinds as $k => $v)					{						if($v['state'] == 0)						{							$this->errorOutput("你设置分类名已被禁用");						}						if(in_array($v['kind_name'], $names))						{							$kind_ids[$v['kind_id']] = $v['kind_id'];							$kind_names[$v['kind_id']] = $v['kind_name'];						}					}				}				$names = array_diff($names, $kind_names);			}			if($names)			{				foreach($names as $k => $v)				{					$post = array();					$post['name'] = $v;					$post['action'] = 1;					$post['keywords_unicode'] = $this->marklib->str_utf8_unicode($v);					$kind_id = $this->marklib->insert('name',$post);					$kind_ids[$kind_id] = $kind_id;				}			}		}		if(isset($this->input['kind_id']))		{			$kind_name = trim(urldecode($this->input['kind_id']));			$names = explode(',', $kind_name);			if(count($names) > 1)			{				$kind_name = "'" . implode("','", $names) . "'";			}			else 			{				$kind_name = implode("','", $names);			}			$kinds = $this->marklib->get('name','nid as kind_id,name as kind_name,state', array('nid'=>$kind_name,'action'=>1),0 , -1, array());			if($kinds)			{				foreach($kinds as $k => $v)				{					if($v['state'] == 0)					{						$this->errorOutput("你设置分类名已被禁用");					}					if(in_array($v['kind_id'], $names))					{						$kind_ids[$v['kind_id']] = $v['kind_id'];					}				}				$names = array_diff($names, $kind_ids);			}			if($names)			{				$this->errorOutput("你设置的分类不存在");			}		}		//处理		if(!$kind_ids)		{			$this->errorOutput("你选择的分类不存在");		}		if(isset($this->input['mark_name']))		{			$mark_name = trim(urldecode($this->input['mark_name']));			$names = explode(',', $mark_name);			if(count($names) > 1)			{				$kind_name = "'" . implode("','", $names) . "'";			}			else 			{				$kind_name = implode("','", $names);			}						if($kind_name)			{				$marks = $this->marklib->get('name','nid as mark_id,name as mark_name,state', array('name'=>$kind_name,'action'=>0),0 , -1, array());								if($marks)				{					foreach($marks as $k => $v)					{						if($v['state'] == 0)						{							$this->errorOutput("你设置分类名已被禁用");						}						if(in_array($v['mark_name'], $names))						{							$mark_ids[$v['mark_id']] = $v['mark_id'];							$mark_names[$v['mark_id']] = $v['mark_name'];						}					}					$names = array_diff($names, $mark_names);				}								if($names)				{					foreach($names as $k => $v)					{						$post = array();						$post['name'] = $v;						$post['action'] = 0;						$post['keywords_unicode'] = $this->marklib->str_utf8_unicode($v);						$mark_id = $this->marklib->insert('name',$post);						$mark_ids[$mark_id] = $mark_id;					}				}			}		}		if(isset($this->input['mark_id']))		{			$mark_name = trim(urldecode($this->input['mark_id']));			$names = explode(',', $mark_name);			if(count($names) > 1)			{				$kind_name = "'" . implode("','", $names) . "'";			}			else 			{				$kind_name = implode("','", $names);			}						if($kind_name)			{				$marks = $this->marklib->get('name','nid as mark_id,name as mark_name,state', array('nid'=>$kind_name,'action'=>0),0 , -1, array());				if($marks)				{					foreach($marks as $k => $v)					{						if($v['state'] == 0)						{							$this->errorOutput("你设置分类名已被禁用");						}						if(in_array($v['mark_name'], $names))						{							$mark_ids[$v['mark_id']] = $v['mark_id'];						}					}					$names = array_diff($names, $mark_ids);				}				if($names)				{					$this->errorOutput("你设置的标签id不存在");				}			}		}		if(!$mark_ids)		{			$this->errorOutput("你选择的标签不存在");		}		$data['kind_id'] = implode(',', $kind_ids);		$data['mark_id'] = implode(',', $mark_ids);		foreach($kind_ids as $k =>$v)		{			$arr[$v] = $mark_ids;		}				$result = $this->marklib->get('kind_action', '*', $data, 0, -1, array('kind_id'=>''));		if(!$result)		{			foreach($kind_ids as $k => $v)			{				foreach($mark_ids as $m=>$n)				{					$this->marklib->insert('kind_action', array('user_id'=>$data['user_id'],'kind_id'=>$v,'mark_id'=>$n,'create_time'=>TIMENOW));					unset($arr[$v][$n]);					$this->updateKindSigns(array('user_id'=>$data['user_id'],'kind_id'=>$v), 1, 1);				}							}		}		else 		{			foreach($result as $k=>$v)			{				if(in_array($v['kind_id'], $kind_ids) && in_array($v['mark_id'],$mark_ids))				{					unset($arr[$v['kind_id']][$v['mark_id']]);				}			}		}		if($arr)		{			foreach($arr as $k=>$v)			{				foreach($v as $m=>$n)				{					$this->marklib->insert('kind_action', array('user_id'=>$data['user_id'],'kind_id'=>$k,'mark_id'=>$n,'create_time'=>TIMENOW));						$this->updateKindSigns(array('user_id'=>$data['user_id'],'kind_id'=>$k), 1, 1);						}							}		}		$this->setXmlNode('kind', 'adminMove');		$this->addItem_withkey('info', true);		$this->output();	}	function unkonw()	{		$this->errorOutput("你搜索得方法不存在");	}	function __destruct()	{		parent::__destruct();		unset($this->marklib);	}}/** *  程序入口 */$out = new kindApi();$action = $_INPUT['a'];if (!method_exists($out,$action)){	$action = 'unknow';}$out->$action();?>
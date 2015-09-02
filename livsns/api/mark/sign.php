<?phpinclude_once('./global.php');define('MOD_UNIQUEID','cp_mark_m');//模块标识class signApi extends BaseFrm{	function __construct()	{		parent::__construct();		include_once './lib/mark.class.php';		$this->marklib = new markLib();	}		//用户处理	public function checkUserExit()	{		$this->user = array('user_id'=>84);		if(!$this->user['user_id'])		{			$this->errorOutput("用户没有登录");		}		return $this->user['user_id'];	}		//更新类参数获取	public function getCreateMarkId()	{		if(!isset($this->input['mark_id']))		{			$name = '';			$name = $this->marklib->getName();			$names = explode(',', $name);			foreach($names as $k => $v)			{				$this->marklib->processDataMarkName($v);			}			if(count($names) > 1)			{				$str = "'";			}			$name = $str . implode("','", $names) . $str;			$result = $this->marklib->get('name', ' nid as mark_id,name,state', array('name'=>$name,'action'=>0),  0, -1,  array());			$num = count($names);			$oNames = $oids = array();			if($result)			{				foreach ($result as $k => $v)				{					if($v['state'] == 0)					{						$this->errorOutput("你的设置里有参数已被禁止使用");					}					if(in_array($v['name'], $names))					{						$oNames[$v['mark_id']] = $v['name'];						$oids[$v['mark_id']] = $v['mark_id'];					}				}				$names = array_diff($names, $oNames);			}			if($names)			{				foreach($names as $k => $v)				{					$post = array();					$post['name'] = $v;					$post['keywords_unicode'] = $this->marklib->str_utf8_unicode($v);					$mark_id = $this->marklib->insert('name',$post);					$oids[$mark_id] = $mark_id;					$oNames[$mark_id] = $v;				}			}		}		else 		{			$mark_id = trim($this->input['mark_id']);			$t = $marks = array();			$t = explode(',', $mark_id);			foreach($t as $k => $v)			{				if(empty($v))				{					$this->errorOutput("你的设置里的参数有问题");				}				$marks[$v] = $v;			}			$result = array();			$result = $this->marklib->get('name', 'nid as mark_id,name,state', array('nid'=>$mark_id,'action'=>0),  0, -1,  array());			$num = count($marks);			if($result)			{				$oNames = $oids = array();				foreach($result as $k => $v)				{					if($v['state'] == 0)					{						$this->errorOutput("你的设置里的参数有禁止字段");					}					if(in_array($v['mark_id'], $marks))					{						$oNames[$v['mark_id']] = $v['name'];						$oids[$v['mark_id']] = $v['mark_id'];						unset($marks[$v['mark_id']]);					}				}			}			if($marks)			{				$this->errorOutput("你的设置里有参数没有被使用");			}		}		return array('oids'=>$oids,'oNames'=>$oNames);	}	//插入标签关系	public function create()	{		$user_id = $this->checkUserExit();				if(isset($this->input['source']))		{			$data['source'] = trim(urldecode($this->input['source']));		}				if(isset($this->input['source_id']))		{			$data['source_id'] = trim($this->input['source_id']);		}				if(isset($this->input['action']))		{			$data['action'] = trim($this->input['action']);		}		if(!$data['action'] || !$data['source_id'] || !$data['source'])		{			$this->errorOutput("你的设置里缺少参数来源");		}		else 		{			$data['user_id'] = $user_id;		}		//判断		$oids = $oNames = $arr = array();		$arr = $this->getCreateMarkId();		$oids = $arr['oids'];		$oNames = $arr['oNames'];		//关系		if($oids)		{			$mark_id = implode(',', $oids);		}		$data['mark_id'] = $mark_id;		$result = array();		$result = $this->marklib->get('mark_action', 'mark_id,user_id,source,source_id', $data,  0, -1,  array());		$this->setXmlNode('mark', 'create');//print_r($result);exit;		if($result)		{			foreach($result as $k )			{				if(in_array($k['mark_id'], $oids))				{					$this->addItem_withkey($oNames[$k['mark_id']], true);					unset($oids[$k['mark_id']]);				}			}		}		if($oids)		{			foreach($oids as $k => $v)			{				unset($data['mark_id']);				$data['mark_id'] = $v;				$data['create_time'] = TIMENOW;				$id = $this->marklib->insert('mark_action',$data);				if($id)				{					$this->updateMarkSigns(array('source'=>$data['source'],'source_id'=>$data['source_id'],'action'=>$data['action']), 1, 1);					$this->updateMarkSign(array('source'=>$data['source'],'mark_id'=>$v,'action'=>$data['action']), 1, 1);					//$this->updateSign($v, 1);					$this->addItem_withkey($oNames[$v], true);				}				else 				{					$this->addItem_withkey($oNames[$v], false);				}			}		}		$this->output();	}	public function updateMarkSign($data, $num, $type)	{		$res = 0;		if(!$data['mark_id']  || !$data['source'] || !$data['action'])		{			$this->errorOutput("你的设置里有参数缺少来源参数");		}		$ret = $this->marklib->get('name_sign','count(id) as total', $data, 0, 1, array());		if($ret)		{			$res = $this->marklib->update('name_sign', array('num'=>$num), $data, array('num'=>$type));		}		else 		{			$data['num'] = $num;			$res = $this->marklib->insert('name_sign', $data);		}		return $res;	}	//更新该类型的总数	public function updateMarkSigns($data, $num, $type)	{		$res = 0;		if(!$data['source'] || !$data['source_id'] || !$data['action'])		{			$this->errorOutput("你的设置里有参数缺少来源参数");		}		$ret = $this->marklib->get('mark_sign','count(id) as total', $data, 0, 1, array());		if($ret)		{			$res = $this->marklib->update('mark_sign', array('num'=>$num), $data, array('num'=>$type));		}		else 		{			$data['num'] = $num;			$res = $this->marklib->insert('mark_sign', $data);		}		return $res;	}	//多个更新分类关系	public function update()	{		$user_id = $this->marklib->checkUserExit();		if(isset($this->input['source']))		{			$data['source'] = trim(urldecode($this->input['source']));		}				if(isset($this->input['source_id']))		{			$data['source_id'] = trim($this->input['source_id']);		}			if(isset($this->input['action']))		{			$data['action'] = trim($this->input['action']);		}		if(!$data['action'] || !$data['source_id'] || !$data['source'])		{			$this->errorOutput("你的设置里缺少参数来源");		}		else 		{			$data['user_id'] = $user_id;		}		//判断		$oids = $oNames = $arr = array();		$arr = $this->getCreateMarkId();		$oids = $arr['oids'];		$oNames = $arr['oNames'];				//		$result = array();		$result = $this->marklib->get('mark_action', 'mark_id,user_id,source,source_id,action', $data, 0, -1, array());				$this->setXmlNode('mark', 'update');		if($result)		{			foreach($result as $k => $v)			{				if(in_array($v['mark_id'], $oids))				{					$this->addItem_withkey($oNames[$v['mark_id']], true);					unset($oids[$v['mark_id']]);					unset($oNames[$v['mark_id']]);				}				else 				{					if($this->marklib->delete('mark_action', $v))					{						$this->updateMarkSigns(array('source'=>$data['source'],'source_id'=>$data['source_id'],'action'=>$data['action']), -1, 1);						//$this->updateSign($v['mark_id'], -1);						$this->updateMarkSign(array('source'=>$data['source'],'mark_id'=>$v['mark_id'],'action'=>$data['action']), -1, 1);					}				}			}		}		if($oids)		{			foreach($oids as $k => $v)			{				$data['mark_id'] = 0;				$data['mark_id'] = $v;				$data['create_time'] = TIMENOW;				if($this->marklib->insert('mark_action', $data))				{					$this->updateMarkSigns(array('source'=>$data['source'],'source_id'=>$data['source_id'],'action'=>$data['action']), 1, 1);					//$this->updateSign($v, 1);					$this->updateMarkSign(array('source'=>$data['source'],'mark_id'=>$v,'action'=>$data['action']), 1, 1);				}				$this->addItem_withkey($oNames[$v], true);			}		}		$this->output();	}	//获取删除类的参数	public function getDeleteMarkId()	{		if(isset($this->input['name']))		{			$name = '';			$name = $this->marklib->getName();			$names = explode(',', $name);			foreach($names as $k => $v)			{				$this->marklib->processDataMarkName($v);			}			if(count($names) > 1)			{				$str = "'";			}			$name = $str . implode("','", $names) . $str;			$result = $this->marklib->get('name', ' nid as mark_id,name,state', array('name'=>$name,'action'=>0),  0, -1,  array());						$num = count($names);			if($result)			{				$oNames = $oids = array();				foreach($result as $k => $v)				{					if($v['state'] == 0)					{						$this->errorOutput("你的设置里的参数有禁止字段");					}					if(in_array($v['name'], $names))					{						$oNames[$v['mark_id']] = $v['name'];						$oids[$v['mark_id']] = $v['mark_id'];					}				}			}			$names = array_diff($names, $oNames);			if($names)			{				$this->errorOutput("你的设置里有参数没有被使用");			}		}		if(isset($this->input['mark_id']))		{			$mark_id = trim($this->input['mark_id']);			$t = $marks = array();			$t = explode(',', $mark_id);			foreach($t as $k => $v)			{				if(empty($v))				{					$this->errorOutput("你的设置里的参数有问题");				}				$marks[$v] = $v;			}			$result = array();			$result = $this->marklib->get('name', 'nid as mark_id,name,state', array('nid'=>$mark_id,'action'=>0),  0, -1,  array());			$num = count($marks);					if($result)			{				$oNames = $oids = array();				foreach($result as $k => $v)				{					if($v['state'] == 0)					{						$this->errorOutput("你的设置里的参数有禁止字段");					}					if(in_array($v['name'], $names))					{						$oNames[$v['mark_id']] = $v['name'];						$oids[$v['mark_id']] = $v['mark_id'];						unset($marks[$v['mark_id']]);					}				}			}			if($marks)			{				$this->errorOutput("你的设置里有参数没有被使用");			}		}		return array('oids'=>$oids,'oNames'=>$oNames);	}	public function delete()	{		$user_id = $this->marklib->checkUserExit();				if(isset($this->input['source']))		{			$data['source'] = trim(urldecode($this->input['source']));		}				if(isset($this->input['source_id']))		{			$data['source_id'] = trim($this->input['source_id']);		}				if(isset($this->input['action']))		{			$data['action'] = trim($this->input['action']);		}		if(!$data['action'] || !$data['source_id'] || !$data['source'])		{			$this->errorOutput("你的设置里缺少参数来源");		}		else 		{			$data['user_id'] = $user_id;		}				//判断		$oids = $oNames = $arr = array();		$arr = $this->getDeleteMarkId();		$oids = $arr['oids'];		$oNames = $arr['oNames'];				if($oids)		{			$data['mark_id'] = implode(',', $oids);		}		$this->setXmlNode('mark','delete');		$result = array();		$result = $this->marklib->get('mark_action','mark_id,user_id,source,source_id,action', $data, 0, -1, array());		if($result)		{			foreach($result as $k => $v)			{				if($this->marklib->delete('mark_action', $v))				{					$this->updateMarkSigns(array('source'=>$data['source'],'source_id'=>$data['source_id'],'action'=>$data['action']), -1, 1);					//$this->updateSign($v['mark_id'], -1);					$this->updateMarkSign(array('source'=>$data['source'],'mark_id'=>$v['mark_id'],'action'=>$data['action']), -1, 1);				}				$this->addItem_withkey($oNames[$v['mark_id']], true);				unset($oNames[$v['mark_id']]);			}		}		if($oNames)		{			foreach($oNames as $k => $v)			{				$this->addItem_withkey($v, true);			}		}		$this->output();	}	//更新该标签使用次数	public function updateSign($mark_id, $num)	{		$res = false;		if($mark_id)		{			$res = $this->marklib->update('name', array('num'=>$num), array('nid'=>$mark_id,'state'=>1),array('num'=>1));		}		return $res;	}	/**	 * 获取某个来源的设置	 * source:来源	 * source_id:来源id	 * action:是否拥有	 * offset:起始	 * count:长度（0取全部）	 * sequence:array('属性名'＝>desc/asc)	 **/	public function show()	{		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;		$count = isset($this->input['count']) ? intval($this->input['count']) : 8;		if(isset($this->input['action']))		{			$data['action'] = trim(urldecode($this->input['action']));		}		$data['source']  = trim(urldecode($this->input['source']));		$data['source_id']  = trim(urldecode($this->input['source_id']));				if(!$data['source'] || !$data['source_id'] || !$data['action'] )		{			$this->errorOutput("你的参数设置不对");		}		$this->setXmlNode('mark','getSourceMarks');		$total = $this->marklib->get('mark_sign', '*', $data, 0, -1, array());		if($total)		{			$arr = array();			foreach($total as $k=> $v)			{				if(!strpos(',', $data['source_id']))				{					$arr[$v['source_id']]['total'] = $v['num'];					if($v['num'])					{						unset($v['num']);						unset($v['id']);						$result =  $this->marklib->getSourceMarks($v, $offset, $count, array());						$arr[$v['source_id']]['data'] = $result;					}				}				else if(!strpos(',', $data['action']))				{					$arr[$v['action']]['total'] = $v['num'];					if($v['num'])					{						unset($v['num']);						unset($v['id']);						$result =  $this->marklib->getSourceMarks($v, $offset, $count, array());						$arr[$v['action']]['data'] = $result;					}				}				else if(!strpos(',', $data['source']))				{					$arr[$v['source']]['total'] = $v['num'];					if($v['num'])					{						unset($v['num']);						unset($v['id']);						$result =  $this->marklib->getSourceMarks($v, $offset, $count, array());						$arr[$v['source']]['data'] = $result;					}				}				else 				{					$arr['total'] = $v['num'];					if($v['num'])					{						unset($v['num']);						unset($v['id']);						$result =  $this->marklib->getSourceMarks($v, $offset, $count, array());						$arr['data'] = $result;					}				}				$result = $arr;			}			$this->addItem_withkey('data',$result);		}		else 		{			$this->addItem_withkey('total',0);		}		$this->output();	}		//获取总数	public function count()	{		//获取选取条件		if(isset($this->input['action']))		{			$data['action'] = trim(urldecode($this->input['action']));		}		$data['source']  = trim(urldecode($this->input['source']));		$data['source_id']  = trim(urldecode($this->input['source_id']));				if(!$data['source'] || !$data['source_id'] || !$data['action'] )		{			$this->errorOutput("你的参数设置不对");		}		$this->setXmlNode('mark','count');		$total = $this->marklib->get('mark_sign', 'num', $data, 0, 1, array());				$this->addItem_withkey('total',$total);		$this->output();	}		public function getIdsByMark()	{		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;		$count = isset($this->input['count']) ? intval($this->input['count']) : 8;		if(isset($this->input['action']))		{			$data['action'] = trim(urldecode($this->input['action']));		}		if(isset($this->input['source']))		{			$data['source'] = trim(urldecode($this->input['source']));		}		if(isset($this->input['source_id']))		{			$data['source_id'] = trim(urldecode($this->input['source_id']));		}		$name = trim(urldecode($this->input['name']));		if(strpos($name, ','))		{			$name = "'" . str_replace(',', "','", $name) ."'";		}		$num = substr_count($name,',')+1;		$mark_id = $this->marklib->get('name', 'nid as mark_id', array('name'=>$name,'state'=>1,'action'=>0), 0, $num, array());		if(!$mark_id)		{			$this->addItem_withkey('data', array());		}		else 		{			if(is_array($mark_id))			{				$marks = $sp ='';				foreach($mark_id as $k)				{					$marks .= $sp . $k['mark_id'];					$sp = ',';				}			}			else 			{				$marks = $mark_id;							}			$data['mark_id'] = $marks;			$result = array();			$result = $this->marklib->get('mark_action', '*', $data, 0, -1,array('source'=>'desc','create_time'=>'desc'));						if($result)			{				$t = '';$arr =array();				foreach($result as $k =>$v)				{					if($t != $v['source'])					{						$t = $v['source'];						$sp = '';					}					$arr[$v['source']]['total'] ++;					$arr[$v['source']]['data'][$v['id']] = $v;					$arr[$v['source']][$v['source'].'_id'] .= $sp . $v['source_id'];					$sp = ',';				}			}			if($arr)			{				foreach($arr as $k => $v)				{					$this->addItem_withkey($k, $v);				}			}			else 			{				$this->addItem_withkey('data', array());			}		}		$this->output();	}	public function hot()	{		$data = array();		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;		$count = isset($this->input['count']) ? intval($this->input['count']) : 8;		if(isset($this->input['action']))		{			$data['action'] = trim(urldecode($this->input['action']));		}		if(isset($this->input['source']))		{			$data['source'] = trim(urldecode($this->input['source']));		}				if(!$data['source'] || !$data['action'])		{			$this->errorOutput("你搜索得参数缺少");		}				$result = array();				$sql = "SELECT t.name, g.num FROM " . DB_PREFIX ."name t,  " . DB_PREFIX ."name_sign g				WHERE g.source = '".$data['source']."' and g.action = '".$data['action']."'				AND t.nid = g.mark_id				AND t.nid IN (SELECT mark_id				FROM ` " . DB_PREFIX ."name_sign`				WHERE `source` = '".$data['source']."' 				and action = '".$data['action']."'				GROUP BY mark_id ORDER BY num DESC)				GROUP BY mark_id ORDER BY num DESC";		if($count)		{			$sql .= " limit ". $offset . "," . $count;		}			$this->setXmlNode('mark','hot');			$query = $this->db->query($sql);		while($row = $this->db->fetch_array($query))		{			$this->addItem_withkey($row['name'], $row['num']);		}		$this->output();	} 	function unkonw()	{		$this->errorOutput("你搜索得方法不存在");	}	function __destruct()	{		parent::__destruct();		unset($this->marklib);	}}/** *  程序入口 */$out = new signApi();$action = $_INPUT['a'];if (!method_exists($out, $action)){	$action = 'unknow';}$out->$action();?>
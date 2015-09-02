<?php
include_once('./global.php');
define('MOD_UNIQUEID','cp_mark_m');//模块标识

class markshowApi extends BaseFrm
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
		$this->user = array('user_id'=>84);
		if(!$this->user['user_id'])
		{
			$this->errorOutput("用户没有登录");
		}
		return $this->user['user_id'];
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
	//具体显示某个对象的对应属性
	public function detail()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : MARK_LIMIT_NUM;
		$data = array();
		$data = $this->getObject();
		$result = $this->marklib->getSourceMarks($data, 0, -1, array());
		$this->setXmlNode('mark','detail');
		$this->addItem_withkey('data', $result);
		$this->output();
	}
	//显示标签
	public function show()
	{
		$data = array();
		$data = $this->getCondition();
		
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : MARK_LIMIT_NUM;
		
		$result = $this->marklib->getSourceMarks($data, $offset, $count, array());
		$this->setXmlNode('mark','show');
		$this->addItem_withkey('data', $result);
		$this->output();
	}
	
	//
	function getCondition()
	{
		$data = array();
		if(isset($this->input['source']))
		{
			$data['source'] = trim(urldecode($this->input['source']));
			if(strpos($data['source'], ','))
			{
				$data['source'] = "'" . str_replace(",", "','", $data['source']) . "'"; 
			}
		}
		if(isset($this->input['source_id']))
		{
			$data['source_id'] = trim(urldecode($this->input['source_id']));
		}
		if(isset($this->input['action']))
		{
			$data['action'] = trim(urldecode($this->input['action']));
			if(strpos($data['action'], ','))
			{
				$data['action'] = "'" . str_replace(",", "','", $data['action']) . "'"; 
			}
		}
		if(isset($this->input['parent_id']))
		{
			$data['parent_id'] = trim(urldecode($this->input['parent_id']));
		}
		return $data;
	}
	//
	function search()
	{
		$data = array();
		$data = $this->getCondition();
		if(isset($this->input['mark_name']) && !empty($this->input['mark_name']))
		{
			$mark_name = trim(urldecode($this->input['mark_name']));
			if(strpos($mark_name, ','))
			{
				$data['name'] = "'" . str_replace(",", "','", $mark_name) . "'"; 
			}
			else 
			{
				$data['name'] = $mark_name ; 
			}
		}
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : MARK_LIMIT_NUM;
		
		$result = array();
		$result = $this->marklib->getSourceKindsMarks('g.sid,t.name as mark_name,g.mark_id,g.source,g.source_id,g.action,g.parent_id', $data, $offset, $count, array());
		$this->setXmlNode('mark','search');
		$this->addItem($result);
		$this->output();
	}
	//
	function count()
	{
		$data = array();
		$data = $this->getCondition();
		if(isset($this->input['mark_name']))
		{
			$mark_name = trim(urldecode($this->input['mark_name']));
			if(strpos($mark_name, ','))
			{
				$data['name'] = "'" . str_replace(",", "','", $mark_name) . "'"; 
			}
			else 
			{
				$data['name'] = $mark_name ; 
			}
		}
		$result = 0;
		$result = $this->marklib->getSourceKindsMarks('count(sid) as total',$data, 0, 1, array());
		$this->setXmlNode('mark','count');
		$this->addItem($result);
		$this->output();
	}
	//
	function getHotMark()
	{
		$data = array();
		$data = $this->getCondition();
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : MARK_LIMIT_NUM;
		
		$result = array();
		$result = $this->marklib->getSourceKindsMarks('t.name AS mark_name,g.sid, count(1) as num,g.parent_id,g.source_id',$data, $offset, $count,array('count( 1 )'=>'desc'), array('mark_name'=>''));
		$this->setXmlNode('mark','getHotMark');
		$this->addItem($result);
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
$out = new markshowApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'unknow';
}
$out->$action();
?>
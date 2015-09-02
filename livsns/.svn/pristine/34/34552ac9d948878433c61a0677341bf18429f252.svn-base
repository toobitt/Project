<?php
//print_r(1);exit;
require_once './global.php';

class correctDataApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		include_once './lib/mark.class.php';
		$this->marklib = new markLib();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	//插入标签
	function insertMark()
	{
		$name = trim(htmlspecialchars_decode(urldecode($this->input['name'])));
		$action = $this->input['action'] ? trim($this->input['action']) : 0;
		if(strpos($name, ','))
		{
			$names = explode(',', $name);
			$str = '';
			if(count($names) > 1)
			{
				$str = "'";
			}
			
		}
		$name = $str . implode("','", $names) . $str;
		$reuslt =  $this->marklib->get('name', 'nid as mark_id,name,state',array('name'=>$name), 0, -1, array());
		if($reuslt)
		{
			foreach($reuslt as $k=>$v)
			{
				if($v['state'] == 1)
				{
					$this->addItem_withkey($v['name'],$v['mark_id']);
					$arr[$v['name']] = $v['name'];
				}
				else 
				{
					$this->marklib->delete('name', $v);
				}
			}
			$names = array_diff($names, $arr);
		}
		if($names)
		{
			$insert = array();
			$insert['action'] = 0;//标签专用
			foreach($names as $k=>$v)
			{
				$insert['name'] = $v;
				$insert['keywords_unicode'] = $this->marklib->str_utf8_unicode($v);
				$mark_id = $this->marklib->insert('name',$insert);
				$this->addItem_withkey($v, $mark_id);
			}
		}
		$this->output();
	}

	function insertMarkAction()
	{
		
		$marks = trim(urldecode($this->input['marks_sss']));
		$pa = unserialize($marks);
		foreach($pa as $k=>$v)
		{
			$total = $this->marklib->get('mark_action', 'count(*) as total', $v,0,1,array());
			if(!$total)
			{
				$result = $this->marklib->insert('mark_action',$v);
				$this->addItem_withkey($k, $result);
			}
		}
		$this->output();
	}
	
	function insertNameAction()
	{
		
		$marks = trim(urldecode($this->input['marks_ttt']));
		$pa = unserialize($marks);
		foreach($pa as $k=>$v)
		{
			$total = $this->marklib->get('mark_sign', 'count(*) as total', $v,0,1,array());
			if(!$total)
			{
				$result = $this->marklib->insert('mark_sign',$v);
				$this->addItem_withkey($k, $result);
			}
		}
		$this->output();
	}
	
	function table()
	{
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "name`" );
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "mark_sign`" );
		$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "mark_sign`" );
	}
	
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
}

$out = new correctDataApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();
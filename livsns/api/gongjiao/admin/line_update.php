<?php
require('global.php');
define('MOD_UNIQUEID','gongjiao');//模块标识
class lineUpdateApi extends adminUpdateBase
{

	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/line.class.php');
		$this->obj = new line();	
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function update()
	{	
		$name = $this->input['name'];
		if(!$name)
		{
			$this->errorOutput("请填写线路名称");
		}

		$info = array(
			'id'			=> intval($this->input['id']),
			'name'			=> $name,
            'brief'			=> $this->input['brief'],
			'time'			=> $this->input['time'],
			'price'			=> $this->input['price'],
			'gjgs'			=> $this->input['gjgs'],
			'kind'			=> $this->input['kind'],
		);
		$ret = $this->obj->update($info);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 审核
	 * 
	 */
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = $this->input['id'];
		$audit = intval($this->input['audit']);
		$ret = $this->obj->audit($ids,$audit);
		$this->addItem($ret);
		$this->output();
	}
	
	function update_stand()
	{	
		//file_put_contents('002',var_export($this->input,1));exit;
		$stands['1'] = implode(',',$this->input['newsup']);
		$stands['2'] = implode(',',$this->input['newsdo']);
		$busstands['1'] = implode(',',$this->input['newbussup']);
		$busstands['2'] = implode(',',$this->input['newbusdo']);
		
		$info = array(
			'id'			=> intval($this->input['id']),
			'stands'		=> (addslashes(json_encode($stands))),
			'busstands'		=> (addslashes(json_encode($busstands))),
		);
		if($this->input['linfo'])
		{
			$linfo = unserialize($this->input['linfo']);
			$info = array_merge($info,$linfo);
		}
		$ret = $this->obj->update($info);
		
		foreach($this->input['newsup'] as $k =>$v)
		{
			if($v != $this->input['oldsup'][$k])
			{
				//." AND routeid = ".intval($this->input['id'])
				 $sql = "UPDATE " . DB_PREFIX ."stand SET name = " ."'".$v ."'"." WHERE name = "."'".$this->input['oldsup'][$k]."'";
				 $this->db->query($sql);		
			}
		}
		foreach($this->input['newsdo'] as $k =>$v)
		{
			if($v != $this->input['oldsdo'][$k])
			{
				 $sql = "UPDATE " . DB_PREFIX ."stand SET name = " ."'".$v ."'"." WHERE name = "."'".$this->input['oldsdo'][$k]."'";
				 $this->db->query($sql);		
			}
		}
		foreach($this->input['newbussup'] as $k =>$v)
		{
			if($v != $this->input['oldbusup'][$k])
			{
				 $sql = "UPDATE " . DB_PREFIX ."stand SET name = " ."'".$v ."'"." WHERE name = "."'".$this->input['oldbusup'][$k]."'";
				 $this->db->query($sql);		
			}
		}
		foreach($this->input['newbusdo'] as $k =>$v)
		{
			if($v != $this->input['oldbusdo'][$k])
			{
				 $sql = "UPDATE " . DB_PREFIX ."stand SET name = " ."'".$v ."'"." WHERE name = "."'".$this->input['oldbusdo'][$k]."'";
				 $this->db->query($sql);		
			}
		}
		
		$this->addItem($ret);
		$this->output();
	}
	function delete()
	{	
		$ids = $this->input['id'];
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的线路");
		}
		$ret = $this->obj->delete($ids);
		
		$this->addItem($ret);
		$this->output();
	}
	public function create()
	{
	}
	public function sort()
	{
	}
	public function publish()
	{
	}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new lineUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>
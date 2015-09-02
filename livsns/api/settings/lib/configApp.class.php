<?php
class configApp extends classCore
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create($data)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->create('appconfig',$data,true);
	}
	
	public function update($data,$param)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->update('appconfig',$data,$param);
	}
	public function delete($data)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->delete('appconfig', $data);
	}
	
	public function count($idsArr)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->count($idsArr, 'appconfig');
	}
	
	public function show($condition,$offset,$count,$field = '*',$key = '',$orderby = 'ORDER BY order_id DESC',$format = array('argument'=>array('type'=>'array','format'=>'unserialize')))
	{
		$sqlCore = new sqlCore();
		return $sqlCore->show($condition, 'appconfig', $offset, $count,$orderby,$field,$key,$format);
	}
	
	public function detail($id)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->detail($id, 'appconfig',array('argument'=>array('type'=>'array','format'=>'unserialize')));
	}
	
}

?>
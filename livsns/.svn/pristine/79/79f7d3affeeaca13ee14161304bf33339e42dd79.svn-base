<?php
class settingRelation extends classCore
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
		return $sqlCore->create('setting_relation',$data,true);
	}
	
	public function update($data,$param)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->update('setting_relation',$data,$param);
	}
	public function delete($data)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->delete('setting_relation', $data);
	}
	
	public function count($idsArr)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->count($idsArr, 'setting_relation');
	}
	
	public function show($condition,$offset,$count,$field = '*',$key = '',$type = 1,$otherKey = '',$join='')
	{
		$sqlCore = new sqlCore();
		return $sqlCore->show($condition, 'setting_relation', $offset, $count,'',$field,$key,array(),$type,$otherKey,$join);
	}
	
	public function detail($id)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->detail($id, 'setting_relation');
	}
	
}

?>
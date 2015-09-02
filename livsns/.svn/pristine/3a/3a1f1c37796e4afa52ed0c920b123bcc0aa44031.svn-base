<?php
class configSetSort extends classCore
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
		return $sqlCore->create('settinggroup',$data,true);
	}
	
	public function update($data,$param)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->update('settinggroup',$data,$param);
	}
	public function delete($data)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->delete('settinggroup', $data);
	}
	public function sortSetTotal($total,$idsArr)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->update('settinggroup', array('groupcount'=>$total),$idsArr);
	}
	
	public function count($idsArr)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->count($idsArr, 'settinggroup');
	}
	
	public function show($condition,$offset,$count,$field = '*',$key = '',$orderby = 'ORDER BY order_id DESC')
	{
		$sqlCore = new sqlCore();
		return $sqlCore->show($condition, 'settinggroup', $offset, $count,$orderby,$field,$key);
	}
	
	public function detail($id)
	{
		$sqlCore = new sqlCore();
		return $sqlCore->detail($id, 'settinggroup');
	}
	
}

?>
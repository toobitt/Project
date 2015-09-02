<?php
require('global.php');
class movie_person_update extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function person_create()
	{
		if ( empty($this->input['name']) or empty($this->input['type']))
		{
			$this->errorOutput("新增人物失败");
		}
		//$this->errorOutput("insert into " . DB_PREFIX . "movie_person values ( NULL , '" . $this->input['name'] . "' , '" . $this->input['type'] . "' )");
		$this->db->query( "insert into " . DB_PREFIX . "movie_person values ( NULL , '" . $this->input['name'] . "' , '" . $this->input['type'] . "' , '0' , 0 )" );
		echo "success";
	}
	
	public function person_update()
	{
		if ( empty($this->input['id']) )
		{
			$this->errorOutput("更新人物失败111111");
		}
		$this->db->query( "update " . DB_PREFIX . "movie_person set name = '". $this->input['name'] ."' , type = '" . $this->input['type'] . "' where id = ". $this->input['id'] );
		echo "success";
	}
	
	public function person_delete()
	{
		if ( empty($this->input['id']) )
		{
			$this->errorOutput("没有人物id");
		}
		$this->db->query( "delete from " . DB_PREFIX . "movie_person where id = ". $this->input['id'] );
		echo "success";
	}
	
	public function unknow()
	{
		$this->errorOutput("没有该方法");
	}
}

$out = new movie_person_update();
$action = $_INPUT['a'];
if (!method_exists( $out , $action))
{
	$action = 'unknow';
}
$out->$action();

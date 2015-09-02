<?php
require('global.php');
class movie_lang_update extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function lang_create()
	{
		if ( empty($this->input['name']) )
		{
			$this->errorOutput("新增语言失败");
		}
		$this->db->query( "insert into " . DB_PREFIX . "movie_lang values ( NULL , '" . $this->input['name'] . "' )" );
		echo "success";
	}
	
	public function lang_update()
	{
		if ( empty($this->input['id']) )
		{
			$this->errorOutput("更新语言失败");
		}
		$this->db->query( "update " . DB_PREFIX . "movie_lang set name = '". $this->input['name'] ."' where id = ". $this->input['id'] );
		echo "success";
	}
	
	public function lang_delete()
	{
		if ( empty($this->input['id']) )
		{
			$this->errorOutput("没有电影id");
		}
		$this->db->query( "delete from " . DB_PREFIX . "movie_lang where id = ". $this->input['id'] );
		echo "success";
	}
	
	public function unknow()
	{
		$this->errorOutput("没有该方法");
	}
}

$out = new movie_lang_update();
$action = $_INPUT['a'];
if (!method_exists( $out , $action))
{
	$action = 'unknow';
}
$out->$action();

?>

<?php
/**
 * 更改record表中column_id; 临时文件
 */
require('global.php');
define(SCRIPT_NAME,'UpdateRecord');
class UpdateRecord extends adminBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$tableName = urldecode($this->input['tablename']);
		if(!$tableName)
		{
			$this->errorOutput('请传入数据库名');
		}
		$sql = "SELECT id,cid FROM ".DB_PREFIX . $tableName ." WHERE 1 ";
		$q = $this->db->query($sql);
		include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->publishtcontent = new publishcontent();	
		while($row = $this->db->fetch_array($q))
		{
			$ret = $this->publishtcontent->get_content_by_cid($row['cid']);
			$ret = $ret[$row['cid']];
			$sql = "UPDATE " . DB_PREFIX . $tableName . " SET column_id = " . $ret['column_id']." WHERE id = " . $row['id'];
			$this->db->query($sql);	
			echo $row['id'] . "<br/>";
			flush();
   			ob_flush();
   			echo "<script>window.scrollTo(0,document.documentElement.scrollHeight)</script>";			
		}
		exit("更新完成");	
	}
}
require_once(ROOT_PATH . 'excute.php');
?>

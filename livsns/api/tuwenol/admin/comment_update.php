<?php
define('MOD_UNIQUEID','comment_update');
require('./global.php');
define('SCRIPT_NAME', 'comment_update');
class comment_update extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		
	}
	public function audit()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$sql = 'UPDATE ' . DB_PREFIX . 'comment set status = 1 where id in('.$id.')';
		$this->db->query($sql);
		$id = explode(',', $id);
		$this->addItem($id);
		$this->output();
	}
	public function delete()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$sql = 'SELECT tid,thid FROM ' . DB_PREFIX .'comment where id in('.$id.')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$this->db->query('UPDATE ' . DB_PREFIX . 'topic set comment=comment-1 where id='.$row['tid']);
			$this->db->query('UPDATE ' . DB_PREFIX . 'thread set comment=comment-1 where id='.$row['thid']);
		}
		$sql = 'DELETE FROM ' . DB_PREFIX . 'comment where id in('.$id.')';
		$this->db->query($sql);
		$id = explode(',', $id);
		$this->addItem($id);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
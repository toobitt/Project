<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: recache.class.php 1524 2011-01-04 09:46:16Z yuna $
***************************************************************************/

class lastSubmit
{
	private $db;
	private $user;
	private $input;
	
	function __construct()
	{
		global $_INPUT, $gUser;
		$this->user = $gUser;
		$this->input = $_INPUT;
	}
	
	function __destruct()
	{
	}
	
	public function fetch($op = 'create')
	{
		if (!$op)
		{
			return array();
		}
		$this->db = hg_checkDB();
		$sql = 'SELECT content FROM ' . DB_PREFIX . 'last_submit_data 
				WHERE admin_id=' . $this->user['id'] . ' 
					  AND op=\'' . $op . '\'
					  AND module_id=\'' . $this->input['mid'] . '\' 
				ORDER BY id DESC LIMIT 1';
		$data = $this->db->query_first($sql);
		$content = unserialize($data['content']);
		unset($content['a'], $content['mid'], $content['id']);
		return $content;
	}

	public function create()
	{
		if (!$this->input['reffer_a'])
		{
			return;
		}
		$this->db = hg_checkDB();
		$data = array(
			'content' => serialize($this->input), 	
			'module_id' => $this->input['mid'], 	
			'admin_id' => $this->user['id'], 	
			'op' => $this->input['reffer_a'], 	
			'ip' => hg_getip(), 	
			'create_time' => TIMENOW, 	
		);
		hg_fetch_query_sql($data, 'last_submit_data');
	}
}
?>
<?php
require('./global.php');
define('SCRIPT_NAME', 'logindb');
define('MOD_UNIQUEID', 'logindb');
class logindb extends adminBase
{
	function __construct()
	{
		parent::__construct();
		$this->verify_setting_prms();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$orders = array('id');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{

			$descasc = 'DESC';
		}
		if (in_array($this->input['hgorder'], $orders))
		{
			$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;
		}
		else 
		{
			$orderby = ' ORDER BY id DESC ';
		}
		$condition = $this->get_condition();
		$sql = 'SELECT * FROM '.DB_PREFIX.'login_server WHERE 1'.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		$this->setXmlNode('dbs','db');
		while($r = $this->db->fetch_array($q))
		{
			//$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$this->addItem($r);
		}
		$this->output();
	}
	public function detail()
	{
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'login_server WHERE id = '.$id;
		$data = $this->db->query_first($sql);
		$data['pass'] = hg_encript_str($data['pass'], false);
		$this->addItem($data);
		$this->output();
	}
	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'login_server '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	public function get_condition()
	{
		
	}
}
include ROOT_PATH . 'excute.php';
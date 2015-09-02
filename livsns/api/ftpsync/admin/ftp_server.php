<?php
require('./global.php');
define('SCRIPT_NAME', 'ftpserver');
define('MOD_UNIQUEID', 'ftpserver');
require_once(ROOT_PATH . 'lib/class/ftp.class.php');
class ftpserver extends adminBase
{
	protected $ftp;
	function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->ftp = new Ftp();
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
		$sql = 'SELECT * FROM '.DB_PREFIX.'ftpserver WHERE 1'.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		$this->setXmlNode('dbs','db');
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i:s',$r['update_time']);
			$r['status'] = 0;
			$config = array('hostname'=>$r['hostname'],'username'=>$r['user'],'password'=>hg_encript_str($r['pass'], false),'port'=>$r['port']);
			if($this->ftp->connect($config))
			{
				$r['status'] = 1;
				$this->ftp->close();
			}
			$this->addItem($r);
		}
		$this->output();
	}
	public function detail()
	{
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'ftpserver WHERE id = '.$id;
		$data = $this->db->query_first($sql);
		$data['pass'] = hg_encript_str($data['pass'], false);
		$this->addItem($data);
		$this->output();
	}
	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'ftpserver '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	public function get_condition()
	{
		
	}
}
include ROOT_PATH . 'excute.php';
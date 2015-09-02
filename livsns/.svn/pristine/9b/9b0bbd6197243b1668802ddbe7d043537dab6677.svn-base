<?php
require('./global.php');
define('SCRIPT_NAME', 'ftpsync');
define('MOD_UNIQUEID', 'ftpsync');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class ftpsync extends adminBase
{
	function __construct()
	{
		$this->mPrmsMethods = array(
		'manage'	=>'管理',
		);
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
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
		$sql = 'SELECT * FROM '.DB_PREFIX.'ftpsync WHERE 1'.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		$this->setXmlNode('dbs','db');
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i:s',$r['update_time']);
			$this->addItem($r);
		}
		$this->output();
	}
	public function detail()
	{
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'ftpsync WHERE id = '.$id;
		$data = $this->db->query_first($sql);
		$data['pass'] = hg_encript_str($data['pass'], false);
		$this->addItem($data);
		$this->output();
	}
	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'ftpsync '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	public function get_condition()
	{
		
	}
	public function get_ftp_servers()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'ftpserver';
		$query = $this->db->query($sql);
		$servers = array();
		while($server = $this->db->fetch_array($query))
		{
			$servers[$server['id']] = $server['hostname'];
		}
		$this->addItem($servers);
		$this->output();
	}
	public function get_apps()
	{
		$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		$apps = $curl->request('applications.php');
		$appdata = array();
		if($apps)
		{
			foreach($apps as $app)
			{
				$appdata[$app['bundle']] = $app['name'];
			}
		}
		$this->addItem($appdata);
		$this->output();
	}
}
include ROOT_PATH . 'excute.php';
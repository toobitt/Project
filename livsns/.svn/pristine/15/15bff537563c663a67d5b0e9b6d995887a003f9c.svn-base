<?php
/***************************************************************************
* $Id: member_extension_field.php 26794 2013-08-01 04:34:02Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member_staricon');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_staricon.class.php';
class memberstariconApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->staricon = new staricon();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{	
		$this->verify_setting_prms();
		$condition=$this->get_condition();		
		$sql='SELECT * FROM '.DB_PREFIX.'staricon WHERE 1'.$condition.' ORDER BY id DESC';
		$query=$this->db->query($sql);
		while ($ret=$this->db->fetch_array($query))
		{	
			$ret['star']=hg_fetchimgurl(unserialize($ret['star']));
			$ret['moon']=hg_fetchimgurl(unserialize($ret['moon']));
			$ret['sun']=hg_fetchimgurl(unserialize($ret['sun']));
			$this->addItem($ret);
			
		}	
		$this->output();
	}
	
	public function detail()
	{
		$this->verify_setting_prms();		
		$id = intval($this->input['id']);
		if(empty($id))
		{
			return false;
		}
		$star=$this->staricon->detail($id);	
		$this->addItem($star);
		$this->output();
	}
	//升级方式
	public function updatetype()
	{
		$updatetype=$this->settings['updatetype'];
		foreach ($updatetype as $updatetypes)
		{
			$this->addItem($updatetypes);
		}
		$this->output();
	}
	public function showgroup()
	{	
		$sql = "SELECT id,name FROM " . DB_PREFIX . "group ";
		$sql.= " WHERE isupdate=0";
		$info = $this->db->fetch_all($sql);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "staricon WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND starname LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		if (isset($this->input['opened']) && $this->input['opened'] != -1)
		{
			$condition .= " AND opened = " . intval($this->input['opened']);
		}
		
		return $condition;
	}

}

$out = new memberstariconApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
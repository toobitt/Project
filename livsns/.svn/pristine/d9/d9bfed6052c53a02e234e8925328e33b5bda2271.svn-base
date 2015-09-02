<?php
/***************************************************************************

*
* $Id: notify.php 2315 2011-02-27 13:33:03Z yuna $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class notifytotalApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function notify_total()
	{
		//数据声明
		
		//接受用户传过来的值
		$type = intval($this->input['type']);
		//get表示取总条数，add 表示加一 ， stuff 表示减一
		$ac = trim($this->input['ac']);	
		
		//测试预置数值
		//$type=1;
		//$ac ='get';
		
			
		//验证用户是否登录
		require_once(ROOT_DIR.'lib/user/user.class.php');
		$this->user = new user();
		$userinfo = $this->user->verify_credentials(); 
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		
		//数据库操作
		$sql = "SELECT * FROM ".DB_PREFIX."notify_total WHERE type IN (".$type.")" ;
		$query = $this->db->query_first($sql);	
		switch($ac)
		{
			case 'get':
			{
				$ret = $query;
			}
			case 'add':
			{
				if($query)
				{
					$sql = "UPDATE ".DB_PREFIX."notify_total SET 
							total  = total +1 
							WHERE type = ".$type;
					$query = $this->db->query($sql);
					$ret = array('info'=>'success');	
				}
				else 
				{
					$sql = "insert into " . DB_PREFIX . "notify_total(type,total) value
					('".$type."','1')";
					$query = $this->db->query($sql);
					$ret = array('info'=>'success');
					$ret = array('info'=>'success');	
				}
			}
			case 'stuff':
			{
				if($query)
				{
					$sql = "UPDATE ".DB_PREFIX."notify_total SET 
							total  = total - 1 
							WHERE type = ".$type;
					$query = $this->db->query($sql);
					$ret = array('info'=>'success');	
				}
				elseif($query['total']==0) 
				{
					$ret = $query;
				}
			}
			
		}
		
		//数据返回
		$this->setXmlNode('notify_total','notifys');
		$this->addItem($ret);
		$this->output();
		
	}
}
$out = new notifytotalApi();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'notify_total';
}
$out->$action();

?>
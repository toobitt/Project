<?php
/***************************************************************************

*
* $Id: notify.php 876 2010-12-18 09:32:47Z repheal $
***************************************************************************/
define('ROOT_DIR', '../');
require('./global.php');
class notifyajax extends uiBaseFrm
{
	var $notify;
	function __construct()
	{
		parent::__construct();
		$this->check_login(); 
		$this->load_lang('notify'); 
		include_once(ROOT_PATH . 'lib/class/notify.class.php');
		$this->notify = new notify();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	//获取该用户的未读通知 
	function getnotify()
	{
		if($this->user['id'] > 0)
		{
			 $notice = $this->notify->notify_get_unread($this->user['id'],-1,0,6); 
			 if(is_array($notice))
			 {    
				$this->tpl->addVar('notice', $notice);
			 	$html = $this->tpl->outTemplate('notice','hg_getnotify');
			 	echo 'var data=' . $html . ';';
			 } 
			 else
			{
				echo 'var data=0;';
			 }
		}
	}
	
	function send_read()
	{	
		if($this->user['id'] > 0)
		{
			//获取全部信息
			$type = intval($this->input['type']);
			$un_value = array();
			if($type=='-1')
			{
				$notifyunread = $this->notify->notify_get_unread($this->user['id'],-1);
			}
			else
			{
				$notifyunread = $this->notify->notify_get_unread($this->user['id'],$type);
			}
			
			foreach ($notifyunread as $key =>$value)
			{
				$notifyInfo = $this->notify->notify_send_read($value['id'],$this->user['id'],$value['type']);
			}
			echo 1;
		}	
	}
	
	//将某类新通知加入到已读的通知表中
	public function send_this_notify()
	{
		$userid = $this->user['id'];
		if(!$userid)
		{
			echo '未登录';
			exit;
		}
		$type = intval($this->input['type']);
		$notify_ids = $this->input['n_ids'];
		$notify_ids = explode(',',$notify_ids);
		$notify_ids = array_filter($notify_ids);
		$notify_ids = implode(',',$notify_ids);
		
		$notifyInfo = $this->notify->notify_send_read($notify_ids,$userid,$type);
		$notifyInfo = $notifyInfo[0];
		
		echo 'var reads=' . json_encode($notifyInfo) . ';';
	}
	 
}
$out = new notifyajax();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'getnotify';
}
$out->$action();

?>
<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: $
***************************************************************************/
define('ROOT_DIR', '../'); 
require('./global.php'); 

class message extends uiBaseFrm
{	
	var $mUser,$mMessages; 
	function __construct()
	{		
		
		parent::__construct();
		$this->check_login();
		$this->load_lang('followers');
		$this->load_lang('atme'); 
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		include_once(ROOT_PATH . 'lib/messages/messages.class.php');
		$this->mMessages = new messages();	
		$this->mUser = new user();		
	}

	function __destruct()
	{
		parent::__destruct();
	}

public function show()
	{
		$showtype  = intval($this->input['showtype']);
		$to_name = $this->input['to_name'];
		$to_id = $this->mUser->getUserByName($to_name);
		$to_id = $to_id[0]['id'];
		$msgul = ($this->input['ul_li']) ? $this->input['ul_li'] : '';
		$chk = $this->mMessages->check_member($this->user['id'],$to_id);
		if($chk[0])
		{
			$salt_str = $chk[0];
		}
		else
		{ 
			$salt_str = $this->input['salt_str'];
		}
		$user_info = $this->user;
		$this->tpl->addVar('to_name', $to_name);
		$this->tpl->addVar('msgul', $msgul);
		$this->tpl->addVar('salt_str', $salt_str);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->outTemplate('dialog','hg_html_dialog,'.$salt_str);
	}
	
	public function send_msg()
	{
		$sid = $this->input['sid'];
		$content = $this->input['content'];//解析表情$text = hg_verify($value['text']);
		$content = hg_verify($content);
		$to_name =$this->input['to_name'];
		$users = $this->mUser->getUserByName($to_name);
		$pid = intval($this->input['pid']);
		$id = array();
		
		if($users)
		{ 
			foreach($users as $key =>$user)
			{
				
				$id[$user['id']] = $user['id'];
			}
			 
			array_filter($id);
			$ids = implode(',',array_keys($id)); 
			
			$msg = $this->mMessages->send_message($sid,$ids,$content,$pid);
			//print_r($msg);
			$msg = $msg[0]; 
			echo json_encode($msg);
		}
		else
		{
			echo '您选择的用户不存在';
		} 
	}
	
	public function check_new()
	{
		
		$messages = $this->mMessages->get_new_message();	 
		if(!empty($messages))
		{
			$mm = array(); 
			
			foreach($messages as $session_id => $ss_info)
			{
				$uid = intval($ss_info['fromwhotitle']);
				$users = $this->mUser->getUserById($uid);
				$fromwho = $users[0]['username'];
				$mm[$session_id]=$ss_info;
				$mm[$session_id]['fromwhotitle'] = $fromwho;
				
			}
			if($mm)
			{   
				
				echo json_encode($mm); 
			}
			else
			{
				 
			}
		} 
			
		$user_info = $this->user;  
	}
	
	public function update_ltime()
	{
		$sid = $this->input['sid'];
		$ids = $this->input['ids'];
		$rtime = time();
		$messages = $this->mMessages->update_last_read($sid,$ids,$rtime); 
		echo json_encode($messages); 
	}
}

$out = new message();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
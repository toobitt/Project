<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: $
***************************************************************************/
class messages
{
	private $curl;
	function __construct()
	{
		global $gApiConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gApiConfig['host'], $gApiConfig['apidir'] . 'messages/');
	}

	function __destruct()
	{
	}
	
	/**
	 * 向某个对话中发送消息
	 * @param $sid：对话id(md5加密后的串)
	 * @param $uid：用户id（接受消息的用户），如果只传递一个id，认为是两人对话，若传递多个用户id字符串，则认为是群聊，多个用户id间以,相隔
	 * @param $content：消息内容
	 * @param $pid：上条消息的id（pid），如果不传递此参数，按照发起新对话处理，否则按照回复消息处理（即追加消息）
	 */
	public function send_message($sid,$uid,$content,$pid=0)
	{
		 
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('sid', $sid);
		$this->curl->addRequestData('uid', $uid);
		$this->curl->addRequestData('content', $content);
		$this->curl->addRequestData('pid', $pid);
		$ret = $this->curl->request('send_msg.php');
		return $ret;
	}
	
	/**
	 * 获取对话用户，注：两个参数皆为必需参数，按需传递，不需要传递的参数就传递空值
	 * @param $user_id，若传递此参数，就取于该用户有过对话的用户信息
	 * @param $sid，若传递此参数，则取某段对话的用户
	 */
	public function get_members($user_id,$sid='',$pp=0,$count=50)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('sid', $sid);
		$this->curl->addRequestData('user_id',$user_id);
		$this->curl->addRequestData('pp',$pp);
		$this->curl->addRequestData('count',$count);
		$ret = $this->curl->request('get_members.php');
		return $ret;
	}
	
	/**
	 * 获取某段对话的全部记录
	 * @param $sid : 对话id 
	 */
	public function get_one_msg($sid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('sid', $sid); 
		$ret = $this->curl->request('get_one.php');
		return $ret;
	}
	
	/**
	 * 获取当前登录用户所有的信息
	 */ 
	public function get_new_message($all=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('all', intval($all));
		$ret = $this->curl->request('get_my_msg.php');  
		return $ret[0];
	}
	
	/**
	 * 更新某个对话段的最后读取时间
	 * @param $sid
	 * @param unknown_type $ids
	 * @param unknown_type $rtime
	 */
	public function update_last_read($sid,$ids,$rtime)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('sid', $sid);
		$this->curl->addRequestData('ids', $ids);
		$this->curl->addRequestData('rtime', $rtime);
		$ret = $this->curl->request('update_last.php');
		return $ret;
	}
	
	/**
	 * 检测两个用户之间是否有对话历史
	 * @param unknown_type $user_id
	 * @param unknown_type $to_id
	 */
	public function check_member($user_id,$to_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', "check_them");
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('to_id', $to_id);
		$ret = $this->curl->request('get_members.php');
		return $ret;
	}
}
	
<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: message_send_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/messagesend.class.php';
define('MOD_UNIQUEID', 'message_send'); //模块标识

class messagesendUpdateApi extends adminUpdateBase
{
	private $messagesend;
	
	public function __construct()
	{
		parent::__construct();
		$this->messagesend = new messagesendClass();
		
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->messagesend);
	}
	

	/**
	** 信息更新操作
	**/
	public function update()
	{
		$id = intval($this->input['id']);
		if (empty($id)){
			$this->errorOutput(OBJECT_NULL);
		}
		else{
			$order_id = trim($this->input['order_id']);
			$received_phone = trim($this->input['received_phone']);
			$content = trim($this->input['content']);
			$backstatus = trim($this->input['backstatus']);
			$status = trim($this->input['status']);
			if ((empty($order_id)) || (empty($received_phone)) || (empty($content)) ){
			$this->errorOutput(OBJECT_NULL);
			}
			else{
			$received_phone=$received_phone?$received_phone:"";
			$content=$content?$content:"";
			$order_id=$order_id?(int)$order_id:9999;
			$backstatus=$backstatus?(int)$backstatus:1;
			$status=$status?(int)$status:0;
			$update_time = TIMENOW;
			if(!is_numeric($order_id)){
				$this->errorOutput("请正确输入数字");
			}
			$updateData = array();
			$updateData['content'] = $content;
			$updateData['order_id'] = $order_id;
			$updateData['backstatus'] = $backstatus;
			$updateData['status'] = $status;
			$updateData['update_time'] = $update_time;
			$updateData['received_phone'] = $received_phone;
			if(!is_numeric($updateData['received_phone'])){
				$this->errorOutput("电话号码必须为数字");
			}
			else{
				if(strlen($updateData['received_phone'])==11){
					if (!empty($updateData))
					{
						$result = $this->messagesend->update($updateData,$id);
					}
					else
					{
						$updateData = true;
					}
				}
				else{
					$this->errorOutput("电话号码位数不符合");
				}
			}
			$this->addItem($updateData);
			$this->output();
			}
		}
	}
	
	public function delete()
	{
		$ids = trim(urldecode($this->input['id']));
		if(empty($ids))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$result = $this->messagesend->delete($ids);
		$this->addItem($result);
		$this->output();
	}

	public function create()
	{
		$order_id = trim($this->input['order_id']);
		$received_phone = trim($this->input['received_phone']);
		$content = trim($this->input['content']);
		if ((empty($order_id)) || (empty($received_phone)) || (empty($content)) ){
			$this->errorOutput(OBJECT_NULL);
		}
		else{
			$received_phone=$received_phone?$received_phone:"";
			$content=$content?$content:"";
			$order_id=$order_id?(int)$order_id:9999;
			$status=0;
			$active = 1;
			$update_time = TIMENOW;
			$create_time = TIMENOW;
			$ip = hg_getip();
			$appid=$this->user['appid'];
			$appname=$this->user['display_name'];
			$user_id=$this->user['user_id'];
			$user_name=$this->user['user_name'];
			if(!is_numeric($order_id)){
				$this->errorOutput("请正确输入数字");
			}
			$updateData = array();
			$updateData['content'] = $content;
			$updateData['order_id'] = $order_id;
			$updateData['status'] = $status;
			$updateData['active'] = $active;
			$updateData['update_time'] = $update_time;
			$updateData['create_time'] = $create_time;
			$updateData['appid'] = $appid;
			$updateData['appname'] = $appname;
			$updateData['user_id'] = $user_id;
			$updateData['user_name'] = $user_name;
			$updateData['ip'] = $ip;
			
			$r_p_arr = array();
			$r_p_arr = explode(",",$received_phone);
			foreach($r_p_arr as $value){
				if(!empty($value)){
					if(!is_numeric($value)){
						$this->errorOutput("电话号码必须为数字");
					}
					else{
						if(strlen($value)==11){
							$updateData['received_phone'] = $value;
							if (!empty($updateData))
							{
								$result = $this->messagesend->create($updateData);
							}
							else
							{
								$updateData = true;
							}
						}
						else{
							$this->errorOutput("电话号码位数不符合");
						}
					}	
				}
			}
			
			$this->addItem($updateData);
			$this->output();
		}
		
	}
	
	//审核
	public function audit()
	{
		$ids = trim(urldecode($this->input['id'])); //条目的id
		$status = trim(urldecode($this->input['status'])); //状态值
		if(empty($ids) || empty($status))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$result = $this->messagesend->audit($ids,$status);
		$this->addItem($result);
		$this->output();
	}

	public function publish()
	{}

	public function sort()
	{}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
}
$out = new messagesendUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>
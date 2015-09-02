<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','clouduser_update');//模块标识
define('SCRIPT_NAME', 'clouduser_update');
class clouduser_update extends adminBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	//账号停权
	function stop()
	{
		$sql = 'UPDATE ' . DB_PREFIX . 'user_queue SET status = 2 WHERE user_id = ' .intval($this->input['id']);
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	function start()
	{
		$sql = 'UPDATE ' . DB_PREFIX . 'user_queue SET status = 1 WHERE user_id = ' .intval($this->input['id']);
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	//扩容接口
	function enlarge()
	{
		include(CUR_CONF_PATH . 'lib/UpYunApi.class.php');
		$this->upyun = new UpYunApi();
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'user_queue WHERE user_id = '.intval($this->input['id']);
		$bucket = $this->db->query_first($sql);
		if(!$bucket)
		{
			$this->errorOutput('获取空间信息错误');
		}
		$param = array(
		'bucket_name'=>$bucket['bucket_name'],
		'quota'=> intval($this->input['quota']) ? intval($this->input['quota']) : 800,
		);
		$result = $this->upyun->BucketQuota($param);
		$this->addItem($result);
		$this->output();
	}
	//更新用户组别
	function update_user_org()
	{
		//
		include_once CUR_CONF_PATH . 'lib/auth_app.php';
		$condition = array(
		'id'=>intval($this->input['id']),
		'org_id'=>intval($this->input['org_id']) ? intval($this->input['org_id']) : 5,
		);
		$this->auth = new authapp();
		$result = $this->auth->user_update_org($condition);
		$this->addItem($result);
		$this->output();
	}
	//充值接口
	function recharge()
	{
		$recharge = $this->input['recharge'] ? $this->input['recharge'] : 20;
		$sql = 'UPDATE ' . DB_PREFIX . 'user_queue SET balance = balance + '.$recharge.' WHERE user_id = ' .intval($this->input['id']);
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	//用户删除
	function delete()
	{
		//
		include_once CUR_CONF_PATH . 'lib/auth_app.php';
		$this->auth = new authapp();
		$sql = 'DELETE FROM ' . DB_PREFIX . 'user_queue WHERE user_id='.intval($this->input['id']);
		$this->db->query($sql);
		if($this->db->affected_rows())
		{
			$result = $this->auth->user_delete(array('id'=>intval($this->input['id'])));
		}
		$this->addItem($result);
		$this->output();
	}
	//扣费
	function pay()
	{
		$pay = $this->input['pay'] ? $this->input['pay'] : 50;
		$sql = 'UPDATE ' . DB_PREFIX . 'user_queue SET balance = balance - '.$pay.' WHERE user_id = ' .intval($this->input['id']);
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
}
include ROOT_PATH . 'excute.php';
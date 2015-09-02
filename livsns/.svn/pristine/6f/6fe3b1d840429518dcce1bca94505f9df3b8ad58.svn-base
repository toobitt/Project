<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','adv_customer');//模块标识
class adv_customer_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		$this->verify_setting_prms();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function delete()
	{
		
		//$this->errorOutput('此客户已经关联广告，无法删除');
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = 'SELECT COUNT(*) AS e FROM '.DB_PREFIX.'advcontent WHERE source IN('.urldecode($this->input['id']).')';
		//file_put_contents('1.txt', $sql);
		$exists = $this->db->query_first($sql);
		
		if($exists['e'])
		{
			$this->errorOutput('此客户已经关联广告，无法删除，请先删除相应的广告！');
		}
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "advcustomer WHERE id in (".urldecode($this->input['id']).")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['customer_name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['advcustomer'] = $row;
			
			//记录日志
			$this->addLogs("删除广告客户", $row, array(), $row['customer_name']);
			//记录日志结束	
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$sql = 'DELETE FROM '.DB_PREFIX.'advcustomer WHERE id in('.urldecode($this->input['id']).')';
			$this->db->query($sql);
			$this->addItem('success');
			$this->output();
		}
		
	}
	function audit()
	{
	}
	function update()
	{
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'advcustomer WHERE id = '.intval($this->input['id']);
		$c = $this->db->query_first($sql);
		if(!$c)
		{
			$this->errorOutput("不存在广告客户");
		}
		$data = array(
		'customer_name' => trim(urldecode($this->input['customer_name'])),
		'email'=>trim(urldecode($this->input['email'])),
		'address'=>trim(urldecode($this->input['address'])),
		'tel'=>trim(urldecode($this->input['tel'])),
		'mobile'=>trim(urldecode($this->input['mobile'])),
		'create_time'=>TIMENOW,
		);
		//$this->errorOutput(var_export($data,1));
		$sql = 'UPDATE '.DB_PREFIX.'advcustomer SET customer_name = "'.$data['customer_name'].
		'",email = "'.$data['email'].
		'",address = "'.$data['address'].
		'",tel = "'.$data['tel'].
		'",mobile = "'.$data['mobile'].
		'" WHERE id = '.intval($this->input['id']);
		$this->db->query($sql);
		if($this->db->affected_rows())
		{
			$this->db->query("UPDATE ".DB_PREFIX.'advcustomer set update_user_id='.$this->user['user_id'].', update_user_name="'.$this->user['user_name'].'" WHERE id = '.$c['id']);
		}
		//记录日志
		$this->addLogs("更新广告客户", $c, $data, $data['customer_name']);
		//记录日志结束
		$this->addItem('success');
		$this->output();
	}
	//广告管理那边快速创建客户
	function create_customer()
	{
		
		$data = array(
		'customer_name' => trim(urldecode($this->input['customer_name'])),
		'email'=>trim(urldecode($this->input['email'])),
		'address'=>trim(urldecode($this->input['address'])),
		'tel'=>trim(urldecode($this->input['tel'])),
		'mobile'=>trim(urldecode($this->input['mobile'])),
		'create_time'=>TIMENOW,
		'user_id'=>$this->user['user_id'],
		'user_name'=>$this->user['user_name'],
		);
		if(!$data['customer_name'])
		{
			$this->errorOutput(CUSTOMER_NAME_REQUIRED);
		}
		//$this->errorOutput(var_export($data,1));
		$sql = 'INSERT INTO '.DB_PREFIX.'advcustomer SET customer_name = "'.$data['customer_name'].
		'",email = "'.$data['email'].
		'",address = "'.$data['address'].
		'",tel = "'.$data['tel'].
		'",user_id = "'.$data['user_id'].
		'",user_name = "'.$data['user_name'].
		'",mobile = "'.$data['mobile'].
		'",create_time = "'.$data['create_time'].'"';
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		//记录日志
		$this->addLogs("创建广告用户", array(), $data, $data['customer_name']);
		//记录日志结束
		$this->addItem(array('id'=>$this->db->insert_id(),'customer_name'=>$data['customer_name']));
		$this->output();
	}
	function create()
	{
		
		$this->create_customer();
	}
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	function sort()
	{
		
	}
	function publish()
	{
		
	}
}
$ouput= new adv_customer_update();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
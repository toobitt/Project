<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','interview_content');//模块标识
class interview_content_update extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function update()
	{
		//参数接收
		$data = array(
			'id'=>urldecode($this->input['id']),
			'question'=>urldecode($this->input['reply']),
			'interview_id'=>urldecode($this->input['interview_id']),
		);
		$sql = 'UPDATE '.DB_PREFIX.'records SET question = "'.addslashes($data['question']).
		'"  WHERE id = '.$data['id'];
		$this->db->query($sql);
		$this->addItem($data);
		$this->output();
	}
	function delete()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'records WHERE id IN ('.urldecode($this->input['id']).')';
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	/**
	 * 
	 * 改变发布状态
	 */
	function changePub()
	{
		//参数接受
		$id = urldecode($this->input['id']);
		$state = urldecode($this->input['state']);
		//判断此时发布状态
		if ($state){
		
			$pub = 0;
		}else {
			$pub = 1;
		}
		$sql = 'UPDATE '.DB_PREFIX.'records  SET is_pub = '.$pub.' WHERE id='.$id;
		$this->db->query($sql);
		$this->addItem($pub);
		$this->output();
	}
	/**
	 * 批量发布
	 * 1为已发布  0为未发布
	 */
	public function pub()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$arr = explode(',', urldecode($this->input['id']));
		$sql = 'UPDATE '.DB_PREFIX.'records SET is_pub = 1
		WHERE id IN ('.urldecode($this->input['id']).')';
		$this->db->query($sql);
		$this->addItem($arr);
		$this->output();

	}
	/**
	 * 批量取消发布
	 * 1为已发布  0为未发布
	 */
	public function backpub()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$arr = explode(',', urldecode($this->input['id']));
		$sql = 'UPDATE '.DB_PREFIX.'records SET is_pub = 0
		WHERE id IN ('.urldecode($this->input['id']).')';
		$this->db->query($sql);
		$this->addItem($arr);
		$this->output();
	}
	public function create()
	{
	
	}
	
	public function audit()
	{
	
	}
	
	public function sort()
	{
	
	}
	
	public function publish()
	{
	
	}

}

$ouput= new interview_content_update();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();

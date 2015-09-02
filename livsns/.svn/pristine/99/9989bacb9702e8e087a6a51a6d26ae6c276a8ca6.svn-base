<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: weight_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/weight.class.php';

define('MOD_UNIQUEID', 'weightset'); //模块标识

class weightUpdateApi extends adminUpdateBase
{
	private $weight;
	
	public function __construct()
	{
		parent::__construct();
		$this->weight = new weightClass();
		
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->weight);
	}
	

	/**
	** 更新操作
	**/
	public function update()
	{
		$id = intval($this->input['id']);
		if (empty($id)){
			$this->errorOutput(OBJECT_NULL);
		}
		else{
			
			$title=$this->input['title']?$this->input['title']:"";
			$begin_w=$this->input['begin_w']?$this->input['begin_w']:"";
			$end_w=$this->input['end_w']?$this->input['end_w']:"";
			$order_id=$this->input['order_id']?$this->input['order_id']:"";
			$begin_w = (int)$begin_w;
			$end_w = (int)$end_w;
			$order_id = (int)$order_id;
			if($begin_w > $end_w)
			{
				$this->errorOutput(OBJECT_WRONG);
			}
			$updateData = array();
			$updateData['title'] = $title;
			$updateData['begin_w'] = $begin_w;
			$updateData['end_w'] = $end_w;
			$updateData['order_id'] = $order_id;
			if(is_array($updateData) &&!empty($updateData) && count($updateData)>0)
			{
				$result = $this->weight->update($updateData,$id);
			}
			else
			{
				$updateData = true;
			}
						
			$this->addItem($updateData);
			$this->output();
		}
	}
	
	public function delete()
	{
		$ids = trim(urldecode($this->input['id']));
		if(empty($ids))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$result = $this->weight->delete($ids);
		$this->addItem($result);
		$this->output();
	}

	public function create()
	{
		$title=$this->input['title']?$this->input['title']:"";
		$begin_w=$this->input['begin_w']?intval($this->input['begin_w']):"";
		$end_w=$this->input['end_w']?intval($this->input['end_w']):"";
		$order_id=$this->input['order_id']?intval($this->input['order_id']):"";		
		$begin_w = (int)$begin_w;
		$end_w = (int)$end_w;
		$order_id = (int)$order_id;
		if($begin_w > $end_w)
		{
			$this->errorOutput(OBJECT_WRONG);
		}
		$createData = array();
		$createData['title'] = $title;
		$createData['begin_w'] = $begin_w;
		$createData['end_w'] = $end_w;
		$createData['order_id'] = $order_id;
		if(is_array($createData) &&!empty($createData) && count($createData)>0)
		{
			$result = $this->weight->create($createData);
		}
		else
		{
			$createData = true;
		}
					
		$this->addItem($createData);
		$this->output();
	}
	
	public function audit()
	{}

	public function publish()
	{}

	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('weightset', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
}

$out = new weightUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>
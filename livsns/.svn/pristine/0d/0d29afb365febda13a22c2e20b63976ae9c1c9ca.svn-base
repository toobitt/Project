<?php
require('global.php');
define('MOD_UNIQUEID','subway_service');//模块标识
class subwayServiceUpdateApi extends adminUpdateBase
{

	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/news.class.php');
		$this->news = new news();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create() 
	{	
		/*$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput("请填写线路名称");
		}
		$data = $this->input;
		//$ret = $this->news->create($data);
		//$data['id'] = $ret;
		$this->addLogs('新增地铁服务' , '' , $data , $data['title']);
		$this->addItem($data);
		$this->output();*/
	}
	
	public function update()
	{	
		/*$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput("请填写线路名称");
		}
		$data = $this->input;
		$ret = $this->news->update($data);
		
		$this->addItem($ret);
		$this->output();*/
	}
	
	
	public function delete()
	{	
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的地铁服务");
		}
		
		$ret = $this->news->delete($ids);
		
		$this->addItem($ret);
		$this->output();
		
	}
	
	public function audit()
	{
		$id = urldecode($this->input['id']); 
		if(!$id)
		{
			$this->errorOutput("未传入地铁线路ID");
		}		
		$idArr = explode(',',$id);
		
		if(intval($this->input['audit']) == 1)
		{
			$ret = $this->news->audit($id,$this->input['audit']);
			$return = array('status' => 1,'id'=> $idArr);	
		}
		else if(intval($this->input['audit']) == 0)
		{
			$ret = $this->news->audit($id,$this->input['audit']);
			$return = array('status' =>2,'id' => $idArr);
		}
		
		//$this->addLogs($opration,'','',$opration . '+' . $id);	
		$this->addItem($return);
		$this->output();
	}
	
	public function publish()
	{
	 	$id = urldecode($this->input['id']);
	 	if(!$id)
	 	{
	 		$this->errorOutput('No Id');
	 	}
	 	$column_id = urldecode($this->input['column_id']);
	 	
	 	$ret = $this->news->publish($id,$column_id);
	 	
	 	$this->addItem('true');
	 	$this->output();
	}
	
	public function drag_order()
	{
		$ids       = $this->input['content_id'];
		$order_ids = $this->input['order_id'];
		
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		global $gGlobalConfig;
		$curl = new curl($gGlobalConfig['App_news']['host'],$gGlobalConfig['App_news']['dir'].'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
	    $curl->addRequestData('a','drag_order');
		$curl->addRequestData('content_id', $ids);
		$curl->addRequestData('order_id', $order_ids);
		$re = $curl->request('news_drag_order.php');
		
		$this->addItem(array('id' =>$ids));
		$this->output();
	}
	
	public function sort()
	{
	}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new subwayServiceUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>
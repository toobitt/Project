<?php
require('global.php');
define('MOD_UNIQUEID','ticket_column');//模块标识
define('SCRIPT_NAME', 'TicketColumnUpdate');
class TicketColumnUpdate extends adminUpdateBase
{

	public function __construct()
	{
		parent::__construct();
		//检测是否具有配置权限
        $this->verify_setting_prms();
        
		include(CUR_CONF_PATH . 'lib/venue.class.php');
		$this->obj = new Ticket();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create() 
	{	
		$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput("请填栏目名称");
		}
		
		if(!$this->settings['App_publishcontent'])
		{
			$this->errorOutput('请先安装发布库');
		}
		
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['App_publishcontent']['host'], $this->settings['App_publishcontent']['dir']. 'admin/');
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','operate');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('column_name',$title);
		$this->curl->addRequestData('site_id',1);
		$this->curl->addRequestData('column_fid',0);
		$ret = $this->curl->request('column.php');
		
		if(!$ret[0]['id'])
		{
			$this->errorOutput('栏目创建失败');
		}
		
		$data = array(
			'column_id'			=> $ret[0]['id'],
			'title'				=> $title,
			'sign'				=> trim($this->input['sign']),
			'brief'				=> $this->input['brief'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'user_id'	 		=> $this->user['user_id'],
			'user_name'	  		=> $this->user['user_name'],			 	
			'ip'          		=> $this->user['ip'],
		);
		
		$id = $this->obj->create($data,'column');
		
		if($id)
		{
			$this->obj->update("order_id = {$id}", 'column', "id={$id}");
			$this->addLogs('新增栏目' , '' , $data , $data['title']);
			
			$data['id'] = $id;
			$this->addItem($data);
		}
		
		$this->output();
	}
	
	public function update()
	{	
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('id不存在');
		}
		
		$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput("请填写栏目名称");
		}
		
		
		$s =  "SELECT * FROM " . DB_PREFIX . "column WHERE id = " . $this->input['id'];
		$pre_data = $this->db->query_first($s);
		
		$id = $pre_data['id'];
		
		$data = array(
			'title'				=> $title,
			'sign'				=> trim($this->input['sign']),
			'brief'				=> $this->input['brief'],
			'update_time'		=> TIMENOW,
			'user_id'	 		=> $this->user['user_id'],
			'user_name'	  		=> $this->user['user_name'],			 	
			'ip'          		=> $this->user['ip'],
		);
		
		$ret = $this->obj->update($data,'column',"id={$id}");	
	
		
		$sq =  "SELECT * FROM " . DB_PREFIX . "column WHERE id = " . $id;
		$up_data = $this->db->query_first($sq);
		
		$this->addLogs('更新栏目' , $pre_data , $up_data , $pre_data['title']);
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function delete()
	{	
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的栏目");
		}
		//查询栏目是否还有数据
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "publish_record WHERE cid IN (" . $ids . ")";
		$res = $this->db->query_first($sql);
		if ($res['total'])
		{
			$this->errorOutput('请先删除栏目下数据');
		}
		//查询被删除栏目在发布库栏目id
		$sql = "SELECT column_id FROM " . DB_PREFIX . "column WHERE id IN (" . $ids . ")";
		
		$column_arr = array();
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$column_arr[] = $r['column_id'];
		}
		
		if(!empty($column_arr))
		{
			$column_ids = implode(',', $column_arr);
		}
		//删除发布库对应栏目
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['App_publishcontent']['host'], $this->settings['App_publishcontent']['dir']. 'admin/');
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('id',$column_ids);
		$ret = $this->curl->request('column.php');
		//exit;
		
		$sql =  "SELECT * FROM " . DB_PREFIX . "column WHERE id IN (" . $ids . ")";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
		}
		
		$where = ' id IN ('.$ids.')';
		$ret = array();
		$ret = $this->obj->delete('column',$where);
		
		if($ret)
		{
			$this->addLogs('删除栏目' , $pre_data , '', '删除栏目'.$ids);
		}
		
		$this->addItem($ret);
		$this->output();
		
	}
	
	public function sort()
	{
		$ids       = explode(',',urldecode($this->input['content_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		
		if($ids && is_array($ids))
		{
			foreach($ids as $k => $v)
			{
				$sql = "UPDATE " .DB_PREFIX . "column SET order_id = '".$order_ids[$k]."'  WHERE id = '".$v."'";
				
				$this->db->query($sql);
			}
		}
		
		$this->addItem(array('id' =>$ids));
		$this->output();
	}
	
	public function audit()
	{
	}
	public function publish()
	{
	}
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}
include(ROOT_PATH . 'excute.php');
?>
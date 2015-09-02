<?php
require('global.php');
define('MOD_UNIQUEID','subway_service_sort');//模块标识
class subwayServiceSorUpdateApi extends adminUpdateBase
{

	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/subway_service_sort.class.php');
		$this->obj = new subwayServiceSort();
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
		
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
			$this->errorOutput("请填写服务类别名称");
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
		
		$data = array();
		$type = '';
		if($this->input['type'] && $this->input['type'] != '-1')
		{
			$type = $this->input['type'];
		}
		if($ret[0]['id'])
		{
			$data = array(
				'column_id'			=> $ret[0]['id'],
				'title'				=> $title,
				'sign'				=> $this->input['sign'],
				'type'				=> $type,
				'brief'				=> $this->input['brief'],
				'color'				=> $this->input['color'],
				'create_time'		=> TIMENOW,
				'update_time'		=> TIMENOW,
				'user_id'	 		=> $this->user['user_id'],
				'user_name'	  		=> $this->user['user_name'],			 	
				'ip'          		=> $this->user['ip'],
			);
			$ret = $this->obj->create($data);
			
			$this->obj->update_data(array('order_id' => $ret), 'subway_service_sort ', " id IN({$ret})");
			
			$materialIds = $this->input['indexpic'];	
			if (is_array($materialIds) && !empty($materialIds))
			{
				$mids = implode(',',$materialIds);
				//$this->obj->update_data(array('cid' => 0), 'subway_materials', " cid IN({$ret}) AND cid_type =5");
				$sql = 'UPDATE '.DB_PREFIX.'subway_materials SET cid = '.$ret .',flag = 0,cid_type =5 WHERE id IN ('.$mids.')';
				$this->db->query($sql);
			}	
			
			$data['id'] = $ret;
			$this->addLogs('新增地铁服务分类' , '' , $data , $data['title']);
		}
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{	
		$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput("请填写服务类别名称");
		}
		$s =  "SELECT * FROM " . DB_PREFIX . "subway_service_sort WHERE column_id = " . $this->input['id'];
		$pre_data = $this->db->query_first($s);
		
		$id = $pre_data['id'];
		$type = '';
		if($this->input['type'] && $this->input['type'] != '-1')
		{
			$type = $this->input['type'];
		}
		$data = array(
			'id'				=> $id,
			'title'				=> $title,
			'sign'				=> $this->input['sign'],
			'type'				=> $type,
			'brief'				=> $this->input['brief'],
			'color'				=> $this->input['color'],
			'update_time'		=> TIMENOW,
		);
		
		$ret = $this->obj->update($data);	
	
		$materialIds = $this->input['indexpic'];	
		if (is_array($materialIds) && !empty($materialIds))
		{
			$mids = implode(',',$materialIds);
			//$this->obj->update_data(array('cid' => 0), 'subway_materials', " cid IN({$ret}) AND cid_type =5");
			$sql = 'UPDATE '.DB_PREFIX.'subway_materials SET cid = '.$id .',flag = 0,cid_type =5 WHERE id IN ('.$mids.')';
			$this->db->query($sql);
		}	
		
		$sq =  "SELECT * FROM " . DB_PREFIX . "subway_service_sort WHERE id = " . $id;
		$up_data = $this->db->query_first($sq);
		
		$this->addLogs('更新地铁服务分类' , $pre_data , $up_data , $pre_data['title']);
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function delete()
	{	
		$column_ids = urldecode($this->input['id']);
		if(empty($column_ids))
		{
			$this->errorOutput("请选择需要删除的地铁服务分类");
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
		$this->curl->addRequestData('a','delete');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('id',$column_ids);
		$ret = $this->curl->request('column.php');
		//exit;
		$sqll =  "SELECT * FROM " . DB_PREFIX . "subway_service_sort WHERE column_id IN (" . $column_ids . ")";
		$sll = $this->db->query($sqll);
		$ret = array();
		while($rowl = $this->db->fetch_array($sll))
		{
			$id[] 		= $rowl['id'];
			$pre_data[] = $rowl;
		}
		$ids = implode(',',$id);
		$ret = $this->obj->delete($ids);
		if($ret)
		{
			$this->addLogs('删除地铁服务分类' , $pre_data , '', '删除地铁服务分类'.$ids);
		}
		
		$this->addItem($ret);
		$this->output();
		
	}
	
	
	public function upload()
	{
		//上传图片
		if($_FILES['Filedata'])
		{
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装！');
			}
			$material_pic = new material();
			$img_info = $this->material->addMaterial($_FILES,'','','-1');
			$img_data = array(
				'host' 			=> $img_info['host'],
				'dir' 			=> $img_info['dir'],
				'filepath' 		=> $img_info['filepath'],
				'filename' 		=> $img_info['filename'],
			);
			
			$data = $img_data;
			$data['cid'] 			= 0;//lbs的id,直接置零
			$data['original_id'] 	= $img_info['id'];
			$data['type'] 			= $img_info['type'];
			$data['mark'] 			= 'img';
			$data['imgwidth'] 		= $img_info['imgwidth'];
			$data['imgheight'] 		= $img_info['imgheight'];
			$data['flag']			= 1;
			$vid = $this->obj->insert_img($data);
			if($vid)
			{
				$data['id'] = $vid;
				$this->addItem($data);
				$this->output();
			}
		}
	}
	
	public function delete_img()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->obj->deleteMaterials($ids);
		$this->addItem($ret);
		$this->output();
	}
	
	public function drag_order()
	{
		$ids       = explode(',',urldecode($this->input['content_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		
		if($ids && is_array($ids))
		{
			foreach($ids as $k => $v)
			{
				$sql = "UPDATE " .DB_PREFIX . "subway_service_sort   SET order_id = '".$order_ids[$k]."'  WHERE column_id = '".$v."'";
				$this->db->query($sql);
			}
		}
		
		$this->addItem(array('id' =>$ids));
		$this->output();
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
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new subwayServiceSorUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>
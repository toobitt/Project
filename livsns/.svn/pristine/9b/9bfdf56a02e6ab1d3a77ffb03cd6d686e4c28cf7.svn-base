<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: photoedit_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/photoedit.class.php';
define('MOD_UNIQUEID', 'photoedit'); //模块标识

class photoeditUpdateApi extends adminUpdateBase
{
	private $photoedit;
	private $mMaterial;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->photoedit = new photoeditClass();
		
		require_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_material']['host'], $gGlobalConfig['App_material']['dir'] . 'admin/' );
		
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->photoedit);
		unset($this->mMaterial);
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
			$ip = hg_getip();
			$order_id=$this->input['order_id']?(int)$this->input['order_id']:9999;
			$imgurl=$this->input['imgurl'];
			$imgname="local_p_".rand(10,1000)."_".TIMENOW;
			$update_time = TIMENOW;
			if(!is_numeric($order_id)){
				$this->errorOutput("排序为数字！");
			}
			$updateData = array();
			$updateData['order_id'] = $order_id;
			$updateData['ip'] = $ip;
			$updateData['update_time'] = $update_time;
			
			//图片，附件的格式判断
			/*
			$material_type_files = ROOT_PATH . 'api/material/cache/material_type.cache.php';
			if(!file_exists($material_type_files))
			{
				$material_type = array(
				'img' => array("jpg","gif","png","bmp","jpeg","tif"),
				);
			}
			else
			{
				$material_type = file_get_contents($material_type_files);
				$material_type = unserialize($material_type);
			}
			*/
			//图片的处理
			if(is_array($_FILES['picture']) &&!empty($_FILES['picture']) && count($_FILES['picture'])>0)
			{
				$pictypetmp = explode('.',$_FILES['picture']['name']);
				$picfiletype = strtolower($pictypetmp[count($pictypetmp)-1]);
				$filename = $_FILES['picture']['tmp_name'];
				if(!is_readable($filename)==true)
				{
					$this->errorOutput("文件没有读取权限");
				}
				$fileresult = file_get_contents($filename);
				$fileresult = base64_encode($fileresult);
				
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a','replaceImg');
				$this->curl->addRequestData('imgdata',$fileresult);
				$this->curl->addRequestData('oldurl',$imgurl);
				$ret = $this->curl->request('material_update.php');
				if(!$ret)
				{
						$this->errorOutput("上传图片至图片服务器失败");
				}
				move_uploaded_file($_FILES['picture']['tmp_name'],"../data/" . $imgname.".".$picfiletype); 
			}
			
			//更新主表
			if ($updateData)
			{
				$result = $this->photoedit->update($updateData,$id);
			}
			else
			{
				$updateData = true;
			}
			
			//附表新增纪录
			$updateData_list = array();
			$updateData_list['fid'] = $id;
			$updateData_list['filename'] = $imgname.".".$picfiletype;
			$updateData_list['update_time'] = TIMENOW;
			$updateData_list['active'] = 1;
			
			if ($updateData_list)
			{
				$result = $this->photoedit->create_list($updateData_list);
			}
			else
			{
				$updateData_list = true;
			}
			
			$this->addItem($updateData);
			$this->output();
		}
	}
	
	
	/**
	** 图片主图更新
	**/
	public function updatepic()
	{
		$ismain = $this->input['ismain'];
		$fid = intval($this->input['fid']);
		if (empty($ismain) || empty($fid)){
			$this->errorOutput(OBJECT_NULL);
		}
		else{
			$imgurl = $this->photoedit->detail_img($fid);
			$updateData = array();
			$updateData['update_time'] = TIMENOW;
			if(is_readable("../data/".$ismain)==true){
				$fileresult = file_get_contents("../data/".$ismain);
				$fileresult = base64_encode($fileresult);
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a','replaceImg');
				$this->curl->addRequestData('imgdata',$fileresult);
				$this->curl->addRequestData('oldurl',$imgurl);
				
				$ret = $this->curl->request('material_update.php');
			}
			else{
				$this->errorOutput("文件没有读取权限");
			}
			
			if ($updateData)
			{
				$result = $this->photoedit->update($updateData,$fid);
				//更新附表
				$sql = "SELECT is_delete FROM " .DB_PREFIX. "photoedit_list WHERE active = 1 AND fid = " .$fid;
				$re_1 = $this->db->query_first($sql);
				$re_1['is_delete'] == 2 ? $delete = 2 : $delete = 1; //看被更新的是不是原图
				$sql_one = "UPDATE " .DB_PREFIX. "photoedit_list SET active = 0,is_delete = " .$delete. " WHERE fid = " .$fid. " AND active = 1";
				$this->db->query($sql_one);
				
				$sql = "SELECT is_delete FROM " .DB_PREFIX. "photoedit_list WHERE fid = " .$fid. " AND filename = '" .$ismain. "'";
				$re_2 = $this->db->query_first($sql);
				$re_2['is_delete'] == 2 ? $delete = 2 : $delete = 0; //看要更新的是不是原图
				$sql_two = "UPDATE " .DB_PREFIX. "photoedit_list SET active = 1,is_delete = " .$delete. " WHERE fid = " .$fid. " AND filename = '" .$ismain. "'";
				$this->db->query($sql_two);
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
		$result = $this->photoedit->delete($ids);
		$this->addItem($result);
		$this->output();
	}

	public function create()
	{
		if(is_array($_FILES['picture']) &&!empty($_FILES['picture']) && count($_FILES['picture'])>0)
		{
			$file_square['Filedata'] = $_FILES['picture'];
			$material_square = $this->mMaterial->addMaterial($file_square, $id);
			if(!$material_square)
			{
				$this->errorOutput("上传图片至图片服务器失败");
			}		
			$createData['host'] = $material_square['host'];
			$createData['dir'] = $material_square['dir'];
			$createData['filepath'] = $material_square['filepath'];
			$createData['filename'] = $material_square['filename'];
			$createData['order_id'] = 9999;
			$createData['active'] = 1;
			$createData['create_time'] = TIMENOW;
			$createData['update_time'] = TIMENOW;
			$createData['ip'] = hg_getip();
			$createData['appid']=$this->user['appid'];
			$createData['appname']=$this->user['display_name'];
			$createData['user_id']=$this->user['user_id'];
			$createData['user_name']=$this->user['user_name'];
			$result = $this->photoedit->create($createData);
			
			$pictypetmp = explode('.',$_FILES['picture']['name']);
			$picfiletype = strtolower($pictypetmp[count($pictypetmp)-1]);
			$imgname="local_p_".rand(10,1000)."_".TIMENOW;
		
			//本地化图片
		    move_uploaded_file($_FILES['picture']['tmp_name'], "../data/" . $imgname.".".$picfiletype);
		        
			//附表新增纪录
			$updateData_list = array();
			$updateData_list['fid'] = $result['id'];
			$updateData_list['filename'] = $imgname.".".$picfiletype;
			$updateData_list['update_time'] = TIMENOW;
			$updateData_list['active'] = 1;
			
			if ($updateData_list)
			{
				$result = $this->photoedit->create_list($updateData_list);
			}
			else
			{
				$updateData_list = true;
			}
			
			unset($material_square);
			unset($picture);
		}
			
		$this->addItem($createData);
		$this->output();
		
	}
	
	//设置文件/文件夹的写属性
	public function set_writeable($filename){
		
		if (is_dir($filename)==false)
		{
			if(@mkdir($filename, 0777))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			if(is_writable($filename))
			{
				return true;
			}
			else{
				if(@chmod($filename,0777))
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
	}
	
	//审核
	public function audit()
	{
	}

	public function publish()
	{}

	public function sort()
	{
		$this->verify_content_prms();
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('photoedit', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
}
$out = new photoeditUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>
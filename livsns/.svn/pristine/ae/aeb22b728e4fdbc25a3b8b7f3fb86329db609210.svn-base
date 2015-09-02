<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: payments_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/payments.class.php';
define('MOD_UNIQUEID', 'pay_list'); //模块标识

class paymentsUpdateApi extends adminUpdateBase
{
	private $payments;
	private $mMaterial;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->payments = new paymentsClass();
		
		require_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_material']['host'], $gGlobalConfig['App_material']['dir'] . 'admin/' );
		
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->payments);
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
			$order_id=$this->input['order_id']?(int)$this->input['order_id']:99;
			$pname = $this->input['pname'];
			$code = $this->input['code'];
			$desc = $this->input['miaoshu'];
			$is_on=$this->input['is_on'];
			$update_time = TIMENOW;
			if(!is_numeric($order_id)){
				$this->errorOutput("排序为数字！");
			}
			$updateData = array();
			$updateData['order_id'] = $order_id;
			$updateData['ip'] = $ip;
			$updateData['update_time'] = $update_time;
			$updateData['pname'] = $pname;
			$updateData['code'] = $code;
			$updateData['miaoshu'] = $desc;
			$updateData['is_on'] = $is_on;
			$imgurl = $this->input['imgurl'];
			//图片，附件的格式判断
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
			
			//图片的处理
			if(is_array($_FILES['picture']) &&!empty($_FILES['picture']) && count($_FILES['picture'])>0)
			{
				$pictypetmp = explode('.',$_FILES['picture']['name']);
				$picfiletype = strtolower($pictypetmp[count($pictypetmp)-1]);
				if(in_array($picfiletype,$material_type['img']))
				{
					    $filename = $_FILES['picture']['tmp_name'];
						$fileresult = file_get_contents($filename);
						$fileresult = base64_encode($fileresult);
						
						$this->curl->setSubmitType('post');
						$this->curl->setReturnFormat('json');
						$this->curl->initPostData();
						$this->curl->addRequestData('a','replaceImg');
						$this->curl->addRequestData('imgdata',$fileresult);
						$this->curl->addRequestData('oldurl',$imgurl);
						
						$ret = $this->curl->request('material_update.php');
				}
				else
				{
				  	$this->errorOutput("图片格式不在允许范围之内!");
				}
			}
			//更新主表
			if ($updateData)
			{
				$result = $this->payments->update($updateData,$id);
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
		$result = $this->payments->delete($ids);
		$this->addItem($result);
		$this->output();
	}

	public function create()
	{
		$createData = array();
		$createData['order_id'] = $this->input['order_id']?(int)$this->input['order_id']:99;
		$createData['pname'] = $this->input['pname'];
		$createData['code'] = $this->input['code'];
		$createData['is_on'] = $this->input['is_on']?(int)$this->input['is_on']:1;
		$createData['miaoshu'] = $this->input['miaoshu'];
		
		$createData['create_time'] = TIMENOW;
		$createData['update_time'] = TIMENOW;
		$createData['ip'] = hg_getip();
		$createData['appid']=$this->user['appid'];
		$createData['appname']=$this->user['display_name'];
		$createData['user_id']=$this->user['user_id'];
		$createData['user_name']=$this->user['user_name'];
		if(is_array($_FILES['picture']) &&!empty($_FILES['picture']) && count($_FILES['picture'])>0)
		{	
			//图片，附件的格式判断
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
			
			$pictypetmp = explode('.',$_FILES['picture']['name']);
			$picfiletype = strtolower($pictypetmp[count($pictypetmp)-1]);
			if(in_array($picfiletype,$material_type['img']))
			{
				$file_square['Filedata'] = $_FILES['picture'];
				$material_square = $this->mMaterial->addMaterial($file_square, $id);		
				$createData['logo'] = $material_square['host'].$material_square['dir'].$material_square['filepath'].$material_square['filename'];
			}
			else
			{
			  	$this->errorOutput("图片格式不在允许范围之内!");
			}
		}
		
		$result = $this->payments->create($createData);
		$this->addItem($createData);
		$this->output();
		
	}
	
	
	//审核
	public function audit()
	{
	}

	public function publish()
	{
		
	}

	public function sort()
	{
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
}
$out = new paymentsUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>
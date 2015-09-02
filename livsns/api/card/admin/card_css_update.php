<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: card_css_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH.'lib/cardcss.class.php';
define('MOD_UNIQUEID', 'card_css'); //模块标识

class cardcssUpdateApi extends adminUpdateBase
{
	private $cardcss;
	
	public function __construct()
	{
		parent::__construct();
		$this->cardcss = new cardcssClass();
		
		require_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_material']['host'], $gGlobalConfig['App_material']['dir'] . 'admin/' );
		
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->cardcss);
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
			$divcss=$this->input['divcss']?$this->input['divcss']:"";
			$order_id=$this->input['order_id']?$this->input['order_id']:"";

			$updateData = array();
			$updateData['title'] = $title;
			$updateData['divcss'] = $divcss;
			$updateData['order_id'] = $order_id;
			
			$imgurl = $this->input['imgurl'];
			
			//图片的处理
			if($_FILES['picture']['tmp_name'])
			{
			    $filename = $_FILES['picture']['tmp_name'];
				$fileresult = file_get_contents($filename);
				$fileresult = base64_encode($fileresult);
				
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a','imgdata2pic');
				$this->curl->addRequestData('imgdata',$fileresult);
				//$this->curl->addRequestData('oldurl',$imgurl);
				
				$ret = $this->curl->request('material_update.php');
				$material_square = $ret[0];
				if(!$material_square['host'])
				{
					$this->errorOutput("图片格式不在允许范围之内!");
				}
				$updateData['picture'] = $material_square['host'].$material_square['dir'].$material_square['filepath'].$material_square['filename'];
				//$this->errorOutput(var_export($material_square,1));
			}
			if(is_array($updateData) &&!empty($updateData) && count($updateData)>0)
			{
				$result = $this->cardcss->update($updateData,$id);
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
		$result = $this->cardcss->delete($ids);
		$this->addItem($result);
		$this->output();
	}

	public function create()
	{
		$title=$this->input['title']?$this->input['title']:"";
		$divcss=$this->input['divcss']?$this->input['divcss']:"";
		$order_id=$this->input['order_id'];		
		
		$createData = array();
		$createData['title'] = $title;
		$createData['divcss'] = $divcss;
		$createData['order_id'] = $order_id;
		
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
				$createData['picture'] = $material_square['host'].$material_square['dir'].$material_square['filepath'].$material_square['filename'];
			}
			else
			{
			  	$this->errorOutput("图片格式不在允许范围之内!");
			}
		}
		
		if(is_array($createData) &&!empty($createData) && count($createData)>0)
		{
			$result = $this->cardcss->create($createData);
		}
		else
		{
			$createData = true;
		}
					
		$this->addItem($createData);
		$this->output();
	}
	
	public function audit()
	{
		
	}

	public function publish()
	{
		
	}

	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('card_content_css', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
}

$out = new cardcssUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>
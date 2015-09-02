<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: message_received_update.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/messagereceived.class.php';
require_once ROOT_PATH . 'lib/class/curl.class.php';

define('MOD_UNIQUEID', 'message_received'); //模块标识

class messagereceviedUpdateApi extends adminUpdateBase
{
	private $messagereceived;
	private $mMaterial;
	
	public function __construct()
	{
		parent::__construct();
		$this->messagereceived = new messagereceivedClass();

		require_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->messagereceived);
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
			$title=$this->input['title']?$this->input['title']:"";
			$content=$this->input['content']?$this->input['content']:"";
			$send_name=$this->input['send_name']?$this->input['send_name']:"";
			$send_phone=$this->input['send_phone'];
			$receive_phone=$this->input['receive_phone'];		
			$status=$this->input['status']?$this->input['status']:0;
			$active = 1;
			$sort=$this->input['sort']?$this->input['sort']:9999;
			$update_time = time();

			$updateData = array();
			$updateData['title'] = $title;
			$updateData['content'] = $content;
			$updateData['send_phone'] = $send_phone;
			$updateData['send_name'] = $send_name;
			$updateData['receive_phone'] = $receive_phone;		
			
			$updateData['status'] = $status;
			$updateData['active'] = $active;
			$updateData['update_time'] = $update_time;
			$updateData['sort'] = $sort;
			if ($_FILES['picture']['name'])
			{
				$is_picture = 1;
			}
			else{
				$is_picture = $this->input['is_picture']?$this->input['is_picture']:0;
			}
			if ($_FILES['video']['name'])
			{
				$is_video = 1;
			}
			else{
				$is_video = $this->input['is_video']?$this->input['is_video']:0;
			}
			if ($_FILES['annex']['name'])
			{
				$is_annex = 1;
			}
			else{
				$is_annex = $this->input['is_annex']?$this->input['is_annex']:0;
			}

			if(($is_picture==1) || ($is_video==1) || ($is_annex==1))
			{
				$cateid = 2;
			}
			else
			{
				$cateid = 1;
			}

			$updateData['is_picture'] = $is_picture;
			$updateData['is_video'] = $is_video;
			$updateData['is_annex'] = $is_annex;
			$updateData['cateid'] = $cateid;

			
			if ($updateData)
			{
				$result = $this->messagereceived->update($updateData,$id);
			}
			else
			{
				$updateData = true;
			}
						
			
			//创建数组 插入附件表
			$update_data = array();
			$update_data['sid'] = $id;
			//图片，附件的格式判断
			$material_type_files = ROOT_PATH . 'api/material/cache/material_type.cache.php';
			if(!file_exists($material_type_files))
			{
				$material_type = array(
				'img' => array("jpg","gif","png","bmp","jpeg","tif"),
				'real' => array("swf"),
				'doc' => array("txt","zip","doc","docx"),
				);
			}
			else
			{
				$material_type = file_get_contents($material_type_files);
				$material_type = unserialize($material_type);
			}
			
			//图片的处理
			if (is_array($_FILES['picture']['name']))
			{
					$pic_date = array();
					foreach($_FILES['picture'] as $key=>$value)
					{
						$$key = $value;
						foreach($$key as $keyy=>$valuee)
						{
							$pic_date[$keyy][$key]=$valuee;
						}						
					}
					foreach($pic_date as $key=>$value)
					{
						$pictypetmp = explode('.',$value['name']);
						$picfiletype = strtolower($pictypetmp[count($pictypetmp)-1]);
						if(in_array($picfiletype,$material_type['img']))
						{
								$file_square['Filedata'] = $value;			
								$material_square = $this->mMaterial->addMaterial($file_square, $id);						
								$picture['host'] 	 = $material_square['host'];
								$picture['dir'] 	 = $material_square['dir'];
								$picture['filepath'] = $material_square['filepath'];
								$picture['filename'] = $material_square['filename'];
								$picture['typeid'] = 1;
								$update_data = array_merge($update_data,$picture);
								$resultfiles = $this->messagereceived->updatefiles($update_data);
								unset($material_square);
								unset($picture);
						}
						else 
						{
						  	$this->errorOutput(IMGUPLOAD_W);
						}
					}
			}
			
			
			//附件的处理
			if (is_array($_FILES['annex']['name']))
			{		
					$annex_date = array();
					foreach($_FILES['annex'] as $key=>$value)
					{
						$$key = $value;
						foreach($$key as $keyy=>$valuee)
						{
							$annex_date[$keyy][$key]=$valuee;
						}								
					}
					
					foreach($annex_date as $key=>$value)
					{
						$annextypetmp = explode('.',$value['name']);
						$annexfiletype = strtolower($annextypetmp[count($annextypetmp)-1]);
						if(in_array($annexfiletype,$material_type['doc']))
						{
							$file_square['Filedata'] = $value;				
							$material_square = $this->mMaterial->addMaterial($file_square, $id);						
							$annex['host'] 	 = $material_square['host'];
							$annex['dir'] 	 = $material_square['dir'];
							$annex['filepath'] = $material_square['filepath'];
							$annex['filename'] = $material_square['filename'];
							$annex['typeid'] = 3;
							$update_data = array_merge($update_data,$annex);
							$resultfiles = $this->messagereceived->updatefiles($update_data);
							unset($material_square);
							unset($annex);
						}
						else
						{
						  	$this->errorOutput(IMGUPLOAD_D);
						}
					}
					
				
			}
			
				
			//视频的处理
			if($_FILES['video']['name'])
			{
				$_FILES['videofile'] = array();
				$video_date = array();
				foreach($_FILES['video'] as $key=>$value)
				{
					$$key = $value;
					foreach($$key as $keyy=>$valuee)
					{
						$video_date[$keyy][$key]=$valuee;
					}
				}
				
				$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
				if(!$curl)
				{
					$this->errorOutput(MSERVER_LOST);
				}
				$curl->setSubmitType('post');
				$curl->setReturnFormat('json');
				$curl->initPostData();
				$curl->addRequestData('vod_leixing',$this->settings['m_r_leiixng']['vod_leixing']);
				foreach($video_date as $key=>$value)
				{
					$_FILES['videofile'] = $value;
					$curl->addFile($_FILES);
					$result = $curl->request('create.php');
					
					if (!$result)
					{
						$this->errorOutput('上传失败');
					}
					else
					{
							//基础字段
							$info = $result[0];
							$video = array(
							'host' => $info['protocol'],
							'dir' => $info['host'],
							'filepath' => $info['dir'],
							'filename' => $info['file_name'],
							'typeid' => 2,
							);
							//序列化字段
							$video_back = array(
							'vid' => $info['id'],
							'type' => $info['type'],
							'pic' => $info['img']['host'].$info['img']['dir'].$info['img']['filepath'].$info['img']['filename'],
							);
							
							//返回数据传回数组
							$update_data['backup'] = $video_back ? @serialize($video_back) : '';
							$update_data = array_merge($update_data,$video);
							$resultfiles = $this->messagereceived->updatefiles($update_data);
							unset($video_back);
							unset($video);							
					}
					unset($result);
				}		
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
		$result = $this->messagereceived->delete($ids);
		$this->addItem($result);
		$this->output();
	}

	public function create()
	{
	}
	
	//审核
	public function audit()
	{
		$ids = trim(urldecode($this->input['id'])); //条目的id
		$status = trim(urldecode($this->input['status'])); //状态值
		if(empty($ids) || empty($status))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$result = $this->messagereceived->audit($ids,$status);
		$this->addItem($result);
		$this->output();
	}

	public function publish()
	{}

	public function sort()
	{}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
}

$out = new messagereceviedUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>
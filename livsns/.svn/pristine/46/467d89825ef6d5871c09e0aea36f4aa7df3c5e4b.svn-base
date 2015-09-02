<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: message_received.php 17960 2013-03-21 14:28:00 jeffrey $
***************************************************************************/

header("Content-type:text/html;charset=utf-8");

require_once './global.php';
require_once CUR_CONF_PATH . 'lib/messagereceived.class.php';
require_once ROOT_PATH . 'lib/class/curl.class.php';
define('MOD_UNIQUEID', 'message_received'); //模块标识


class messagereceivedUpdateApi extends outerUpdateBase
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
	 * 增加短信信息
	 */
	public function create()
	{
		if (empty($this->input['send_phone']) || empty($this->input['send_time']) || empty($this->input['receive_phone'])){
			$this->errorOutput(OBJECT_NULL);
		}
		else{
			$title=$this->input['title']?$this->input['title']:"";
			$content=$this->input['content']?$this->input['content']:"";
			$send_name=$this->input['send_name']?$this->input['send_name']:"";
			$send_phone=$this->input['send_phone'];
			$receive_phone=$this->input['receive_phone'];
			$send_time=$this->input['send_time']?$this->input['send_time']:time();		
			$status=0;
			$active = 1;
			$sort=9999;
			$update_time = TIMENOW;
			$create_time = TIMENOW;
			$ip = hg_getip();
			$appid=$this->user['appid'];
			$appname=$this->user['display_name'];
			$user_id=$this->user['user_id'];
			$user_name=$this->user['user_name'];
			//数据入组
			$updateData = array();
			$updateData['title'] = $title;
			$updateData['content'] = $content;
			$updateData['send_phone'] = $send_phone;
			$updateData['send_name'] = $send_name;
			$updateData['receive_phone'] = $receive_phone;
			$updateData['send_time'] = (int)$send_time;				
			$updateData['status'] = $status;
			$updateData['active'] = $active;
			$updateData['update_time'] = $update_time;
			$updateData['sort'] = $sort;
			$updateData['create_time'] = $create_time;
			$updateData['appid'] = $appid;
			$updateData['appname'] = $appname;
			$updateData['user_id'] = $user_id;
			$updateData['user_name'] = $user_name;
			$updateData['ip'] = $ip;
			
			$is_picture = ($_FILES['picture']['name'])?1:0;
			$is_video = ($_FILES['video']['name'])?1:0;
			$is_annex = ($_FILES['annex']['name'])?1:0;			

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
			
			
			//数据存在
			if (!empty($updateData))
			{
			
			$result = $this->messagereceived->create($updateData);
			$id = $result['id'];				
				
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
				
				
			}
			else
			{
				$updateData = true;
			}						
			$this->addItem($updateData);
			$this->output();
		}
	}


	/**
	** 信息更新操作
	**/
	public function update()
	{
				
	}

	public function delete()
	{
			
	}

	public function none()
	{
		$this->errorOutput('调用方法出错!');
	}
	
}

$out = new messagereceivedUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'none';
}
$out->$action();

?>
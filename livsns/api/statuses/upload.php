<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: upload.php 17941 2013-02-26 02:20:49Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','weibo');
require(ROOT_DIR . 'global.php');
class uploadeApi extends appCommonFrm
{
	private $material;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_DIR . 'lib/class/material.class.php');
		$this->material = new material();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 上传图片
	*/
	public function uploadeImage()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$ret = $this->material->addMaterial($_FILES);
		
		if($ret['id'])
		{
			$info = array(
				'status_id' => 0,
				'material_id' => $ret['id'],
				'type' => 0,
				'source' => '',
				'host' => $ret['host'],
				'dir' => $ret['dir'],
				'filepath' => $ret['filepath'],
				'filename' => $ret['filename'],
				'filesize' => $ret['filesize'],
				'mark' => $ret['mark'],
				'ip' => $ret['ip'],
				'create_at' => $ret['create_time'],
			);
			$sql = "INSERT INTO " . DB_PREFIX . "media set ";
			$space = "";
			foreach($info as $k => $v)
			{
				$sql .= $space . $k . "='" . $v . "'";
				$space = ",";
			}
			//file_put_contents('./cache/11.php',$sql);
			$this->db->query($sql);
			$info['id'] = $this->db->insert_id();
		}		
		$this->setXmlNode('media','info');
		$this->addItem($info);
		$this->output();	
	}
	
	
	
	/**
	* 上传图片
	*/
	public function uploadeImageMore()
	{	
		/*
$files = urldecode($this->input['filenames']);
		if(!$files)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$files_arr = explode(",", $files);
		if(is_array($files_arr))
		{
			$this->setXmlNode('media','info');
			foreach($files_arr as $k => $v)
			{
				$info = $this->handle($v);
				if(is_file($v))
				{
					unlink($v);
				}
				$this->addItem($info);
			}
			$this->output();
		}
		else 
		{
			$this->errorOutput(OBJECT_NULL);
		}
*/
	}
	
	public function delete(){

		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		if($this->input['id'])
		{
			$id = $this->input['id'];
			$sql = "SELECT material_id FROM ".DB_PREFIX."media WHERE id = " . $id;
			
			$f = $this->db->query_first($sql);
			if($f['material_id'])
			{
				$ret = $this->material->delMaterialById($f['material_id'],2);
			}
			$sql = "DELETE FROM ".DB_PREFIX."media WHERE id = " . $id;
			$this->db->query($sql);	
			$this->setXmlNode('media','info');
			$this->addItem($id);
			return $this->output();	
		}
		else
		{
			$this->errorOutput('未传入素材ID');
		}
	}
	
	public function update()
	{
/*		$this->input['status_id'] =73187;
		$this->input['media_id'] = '172,171';
		$this->input['type'] = '0,';*/
		if($this->input['status_id'] && $this->input['media_id'])
		{
			
			$status_id = $this->input['status_id'];
			$media_id = urldecode($this->input['media_id']);
			
			str_replace(",","",$media_id, $count);
			if(!$count)
			{				
				$sql = "UPDATE ".DB_PREFIX."media SET status_id = ".$status_id." WHERE id = ".$media_id;
				$this->db->query($sql);
			}
			else 
			{
				$media_ids = explode(",",$media_id);
				$sql = "UPDATE ".DB_PREFIX."media SET status_id = ".$status_id." WHERE id = ";
				foreach ($media_ids as $key => $value)
				{
					if($value)
					{
						$sqls = $sql.$value;
					}
					$this->db->query($sqls);
				}
			}
			$sql = "SELECT * FROM ".DB_PREFIX."media where id IN(" . $media_id . ")";
			$mids = $space = "";
			$q = $this->db->query($sql);

			include_once(ROOT_DIR . 'lib/class/albums.class.php');
			$this->albums = new albums();
			while($row = $this->db->fetch_array($q))
			{
				$mids .= $space . $row['material_id'];
				$space = ",";
				$albums_info = array(
					'id' => $row['material_id'],
					'host' => $row['host'] . $row['dir'],
					'filepath' => $row['filepath'] . $row['filename'],
				);
				$tt = $this->albums->add_sys_albums(1, serialize($albums_info));
				//file_put_contents('./cache/11.php',$tt,FILE_APPEND);
			}
			$this->material->updateMaterial($mids,$status_id);
			
			$info = array(
				'status_id' => $status_id,
				'media_id' => $media_id,
			);
			$this->setXmlNode('media','info');
			$this->addItem($info);
			return $this->output();	
		}	
	}
	
	/**
	 * 针对于活动的发布微博的图片问题
	 */
	public function insert_update()
	{
		if($this->input['status_id'] && $this->input['media_id'])
		{
			$status_id = $this->input['status_id'];
			$media_id = $this->input['media_id'];
			$sql = "INSERT INTO ".DB_PREFIX."media(`type`,`source`,`dir`,`url`,`ip`,`create_at`) 
		SELECT `type`,`source`,`dir`,`url`,`ip`,`create_at` FROM ".DB_PREFIX."media WHERE id=".$media_id;
			$this->db->query($sql);
			$new_id = $this->db->insert_id();
			
			$sql = "UPDATE ".DB_PREFIX."media SET status_id = ".$status_id." WHERE id = ".$new_id;
			$this->db->query($sql);
			
			$info = array(
				'status_id' => $status_id,
				'media_id' => $new_id,
			);
			$this->setXmlNode('media','info');
			$this->addItem($info);
			return $this->output();	
		}
	}
	
	public function show()
	{
		$info = array();
		if($this->input['status_id'])
		{			
			$status_ids = urldecode($this->input['status_id']);
			$sql = "SELECT * FROM ".DB_PREFIX."media WHERE status_id IN (".$status_ids.")" ;
			$query = $this->db->query($sql);
			$this->setXmlNode('media','info');
			while ($array = $this->db->fetch_array($query))
			{
				$this->addItem($array);
			}			
			$this->output();
		}
	}
	
	
	/**
	 * 获得图片
	 */
	public function get_pic()
	{
	    $pic_id = $this->input['pic_id'];

		$sql = "SELECT * FROM ".DB_PREFIX."media WHERE id = " . $pic_id;

		$pic_info = $this->db->query_first($sql);
		//$pic_info['larger']=UPLOAD_URL.$pic_info['dir']."l_".$pic_info['url'];

		$this->setXmlNode('media','info');
		$this->addItem($pic_info['larger']);
		$this->output();
	}
	
	/**
	* 视频
	*/
	public function uploadVideo()
	{
		//$this->input['url'] = "http://www.tudou.com/playlist/playindex.do?lid=11088377&iid=68415328";
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		if(!$this->input['url'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$link = urldecode($this->input['url']);
//		$link = "http://127.0.0.1/livsns/vui/video_play.php?id=1";
		$infos = hg_get_video($link);
//		file_put_contents("D:texzt.php", $link);
		
		if(!$infos['img']&&!$infos['link']&&!$infos['title'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		include_once(ROOT_DIR . 'lib/class/shorturl.class.php');
		$short = new shorturl();
		$url = $short->shorturl($link);
		$ip = hg_getip();
		$create_at = time();
		$sql = "INSERT ".DB_PREFIX."media(status_id,type,title,link,img,url,ip,create_at) VALUES(0,1,'".str_replace("'", "’", $infos['title'])."','".$infos['link']."','".$infos['img']."','".$url."','".$ip."',".$create_at.")";
		$this->db->query($sql);	
		$id = $this->db->insert_id();
		$info['id'] = $id;
		$info['url'] = $url;
		$info['ip'] = $ip;
		$info['link'] = $infos['link'];
		$info['img'] = $infos['img'];
		$info['title'] = $infos['title'];
		$info['create_at'] = $create_at;
		$info['type'] = 1;	
		$this->setXmlNode('media','info');
		$this->addItem($info);
		return $this->output();	
	}
}
$out = new uploadeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();
?>
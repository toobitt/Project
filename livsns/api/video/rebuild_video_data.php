<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: rebuild_video_data.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
set_time_limit(0);

/**
 * 
 * 重建视频相关数据
 * @author chengqing
 *
 */
class rebuildVideoData extends adminBase
{
	private $count = 20;    //每次更新的数目

	private $tvie_video_api;  
	
	function __construct()
	{
		parent::__construct();
		global $config;
		include(ROOT_DIR . 'api/video/video_api.php');          //导入流媒体API
		$this->tvie_video_api=new TVie_video_api($config);
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 重建视频时长
	 */
	public function rebuild_video_toff()
	{
		$offset = $_REQUEST['offset'] ? intval($_REQUEST['offset']) : 0;
		$limit = $_REQUEST['limit'] ? intval($_REQUEST['limit']) : $this->count;
		
		$sql = "SELECT id , serve_id  FROM " . DB_PREFIX . "video WHERE state = 1 LIMIT " . $offset . "," . $limit;
		$q = $this->db->query($sql);
		$nums = $this->db->num_rows($q);
		
		if($nums > 0)
		{
			while($row = $this->db->fetch_array($q))
			{
				$serve_video_info = $this->tvie_video_api->find_video_by_id($row['serve_id']);
				$duration = ceil($serve_video_info->files[0]->duration);                        //视频时长(返回秒数)
				$sql = "UPDATE " . DB_PREFIX . "video SET toff = " . $duration . " WHERE id = " . $row['id'];
				$this->db->query($sql);
			}			
			$location = '?a=rebuild_video_toff&offset=' . intval($offset + $nums) . '&limit=' . $limit;			
			$this->page_redirect($location);			
		}
		else
		{
			echo '视频时长重建完成！';
		} 		
	}
	
	/**
	 * 重建视频缩略图
	 */
	public function rebuild_video_thumb()
	{
		$offset = $_REQUEST['offset'] ? intval($_REQUEST['offset']) : 0;
		$limit = $_REQUEST['limit'] ? intval($_REQUEST['limit']) : $this->count;
		
		$sql = "SELECT id , serve_id  FROM " . DB_PREFIX . "video WHERE state = 1 LIMIT " . $offset . "," . $limit;
		$q = $this->db->query($sql);
		$nums = $this->db->num_rows($q);
		
		if($nums > 0)
		{
			while($row = $this->db->fetch_array($q))
			{
				$serve_video_info = $this->tvie_video_api->find_video_by_id($row['serve_id']);
				$thumbnail_url = $serve_video_info->thumbnail_url;  		//视频缩略图 
				$bthumbnail_url = $serve_video_info->video_still_url;   	//视频大图 
				$sql = "UPDATE " . DB_PREFIX . "video SET schematic = '" . $thumbnail_url . "' , bschematic = '" . $bthumbnail_url . "' WHERE id = " . $row['id'];
				$this->db->query($sql);
			}			
			$location = '?a=rebuid_video_thumb&offset=' . intval($offset + $nums) . '&limit=' . $limit;			
			$this->page_redirect($location);			
		}
		else
		{
			echo '视频缩略图重建完成！';			
		} 		
	}
	
	/**
	 * 重建视频流地址
	 */
	public function rebuild_video_streaming()	
	{		
		$offset = $_REQUEST['offset'] ? intval($_REQUEST['offset']) : 0;
		$limit = $_REQUEST['limit'] ? intval($_REQUEST['limit']) : $this->count;
		
		$sql = "SELECT id , serve_id  FROM " . DB_PREFIX . "video WHERE state = 1 LIMIT " . $offset . "," . $limit;
		$q = $this->db->query($sql);
		$nums = $this->db->num_rows($q);

		if($nums > 0)
		{
			while($row = $this->db->fetch_array($q))
			{
				$serve_video_info = $this->tvie_video_api->find_video_by_id($row['serve_id']);
				$media_addr = $serve_video_info->files[0]->url;           			//流媒体地址
				$sql = "UPDATE " . DB_PREFIX . "video SET streaming_media = '" . $media_addr . "' WHERE id = " . $row['id'];
				$this->db->query($sql);
			}			
			$location = '?a=rebuild_video_streaming&offset=' . intval($offset + $nums) . '&limit=' . $limit;			
			$this->page_redirect($location);			
		}
		else
		{
			echo '视频流地址重建完成！';
		} 
	}
	
	/**
	 * 页面跳转
	 */
	public function page_redirect($location)
	{		
		$Js = '<script type="text/javascript">document.location.href="' . $location . '";</script>';		
		echo $Js;
	}	
}

$out = new rebuildVideoData();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'rebuild_video_toff';
}
$out->$action();

?>
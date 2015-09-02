<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
*@public function delete|update|create|detail|count
*@private function vodid_info
*
*  $Id: live_backup_update.php
***************************************************************************/
require_once('./global.php');
class live_backup extends BaseFrm
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include curl.class.php
	 */
	private $curl;
	private $mVod;
	function __construct()
	{
		parent::__construct();
		require_once ROOT_PATH.'lib/class/curl.class.php';
		$this->curl = new curl($this->settings['media_api']['host'],$this->settings['media_api']['dir']);
		
		$this->mVod = new curl($this->settings['livmedia_api']['host'],$this->settings['livmedia_api']['dir']);
	}
	function __destruct()
	{
		parent::__destruct();
	}
		
	/**
	 * 删除备播文件
	 * @name delete
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 备播文件ID
	 * @return $id int 被删除备播ID
	 */
	function delete()
	{
		$id = trim(urldecode($this->input['id']));
		if(!$id)
		{
			$this->errorOutput('该备播文件不存在或已被删除');
		}
		$sql = 'DELETE FROM ' . DB_PREFIX . 'backup WHERE id IN(' . $id . ')';
		$this->db->query($sql);
		$this->addItem($id);
		$this->output();
	}
		
	/**
	 * 获取视频ID
	 * @name vodid_info
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $vodid int 视频ID
	 * @return $return array 视频信息
	 */
	private function vodid_info($vodid)
	{
		$this->curl->setSubmitType('get');
		$this->curl->initPostData();
		$this->curl->addRequestData('auth',$this->settings['media_api']['token']);
		$this->curl->addRequestData('vodid',$vodid);
		$return = $this->curl->request('convert2flv.php');
		return $return;
	}

	/**
	 * 获取媒体库视频信息
	 * Enter description here ...
	 * @param unknown_type $id
	 */
	private function getVodInfoById($id)
	{
		$this->mVod->setSubmitType('post');
		$this->mVod->initPostData();
		$this->mVod->addRequestData('a','getVodInfoById');
		$this->mVod->addRequestData('id',$id);
		$return = $this->mVod->request('vod2backup.php');
		return $return[0];
	}
	/**
	 * 更新备播文件
	 * @name update
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 备播文件ID
	 * @param $title string 标题
	 * @param $vodinfo_id int 视频集合ID
	 * @param $brief string 描述
	 * @param $user_name string 用户名
	 * @param $update_time int 更新时间
	 * @param $ip string 创建者ID
	 * @param $filename string 源文件名
	 * @param $toff int 时长
	 * @param $img string 图片路径
	 * @param $filepath string 文件路径
	 * @param $newname string 上传后文件新名称
	 * @return $id int 备播文件ID
	 */
	function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('此备播文件不存在或已删除');
		}
		$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput('标题不能为空');
		}
		$vodinfo_id = intval($this->input['back_up_video_id']);
			
		$data = array(
		'title' => trim(urldecode($this->input['title'])),
		'brief' => trim(urldecode($this->input['brief'])),
		'user_name' => $this->user['user_name'],
		'update_time' => TIMENOW,
		'ip' => hg_getip(),
		);
		if($vodinfo_id)
		{
		//	$sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id=" .$vodinfo_id;
		//	$video_info = $this->db->query_first($sql);
		//	$vodid_info = $this->vodid_info($video_info['vodid']);
		
			$video_info = $this->getVodInfoById($vodinfo_id);
			
			if (empty($video_info))
			{
				return false;
			}
			
			$vodid_info = $this->vodid_info($video_info['vodid']);
			
			if (empty($vodid_info))
			{
				return false;
			}
			
			$data['filename'] = $video_info['title'];
			$data['toff'] = $video_info['duration'];
			$data['vodinfo_id'] = $vodinfo_id;
			$data['img'] = $video_info['img'];
			$data['filepath'] = $vodid_info['filepath'];
			$data['newname'] = $vodid_info['filename'];
		}
		else 
		{
			if($_FILES['backup_file']['error'] === 0)
			{
				$data['vodinfo_id'] = 0;
				$data['img'] = '';
				$data['toff'] = 0;
				$data['newname'] = microtime(true).'.'.UPLOAD_BACKUP_MMS_FILE_TYPE;
				$data['filename'] = $_FILES['backup_file']['name'];
				$dir = UPLOAD_DIR . 'backup_mms/' .hg_num2dir(intval($this->input['id']));
				if(!is_dir($dir))
				{
					hg_mkdir($dir);
				}
				$target_file = $dir.$data['newname'];
				if(!move_uploaded_file($_FILES['backup_file']['tmp_name'], $target_file))
				{
					$this->errorOutput(MOVEFILEFAILED);
				}
			}
		}
		
		$sql = 'UPDATE '.DB_PREFIX.'backup SET ';
		foreach ($data as $k=>$v)
		{
			$sql .= $k.'="'.$v.'",';
		}
		$sql = rtrim($sql, ',');
		$sql .= ' WHERE id='.intval($this->input['id']);
		$this->db->query($sql);
		$this->addItem(intval($this->input['id']));
		$this->output();
	}
		
	/**
	 * 创建备播文件
	 * @name create
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $vodinfo_id int 视屏集合ID
	 * @param $img string 图片路径
	 * @param $title string 标题
	 * @param $brief string 描述
	 * @param $toff int 时长
	 * @param $user_name string 用户名
	 * @param $create_time int 创建时间
	 * @param $update_time int 更新时间
	 * @param $ip string 创建者ID
	 * @param $filename string 源文件名
	 * @param $filepath string 文件路径
	 * @param $newname string 上传后文件新名称
	 * @return $ret int 备播文件ID
	 */
	function create()
	{
		$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput('标题不能为空');
		}
		$vodinfo_id = intval($this->input['back_up_video_id']);
		if($vodinfo_id)
		{
		//	$sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id=" .$vodinfo_id;
		//	$video_info = $this->db->query_first($sql);
			
			$video_info = $this->getVodInfoById($vodinfo_id);
			
			if (empty($video_info))
			{
				return false;
			}
			
			$vodid_info = $this->vodid_info($video_info['vodid']);
			
			if (empty($vodid_info))
			{
				return false;
			}
			
			$filename = $video_info['title'];
			$toff = $video_info['duration'];
			$filepath = $vodid_info['filepath'];
			$newname = $vodid_info['filename'];
		}
		else
		{
			if($_FILES['backup_file']['error'] !==0)
			{
				$this->errorOutput(NOUPLOADFILE);
			}
			$suffix = explode('.', $_FILES['backup_file']['name']);
			$suffix = $suffix[count($suffix)-1];
			if(strtolower($suffix) != UPLOAD_BACKUP_MMS_FILE_TYPE)
			{
				$this->errorOutput(FILE_TYPE_NOT_SURPPORT);
			}
			$filename = $_FILES['backup_file']['name'];
			$newname = microtime(true).'.'.UPLOAD_BACKUP_MMS_FILE_TYPE;
		}
		$data = array(
		'vodinfo_id' => $vodinfo_id,
		'img' => urldecode($video_info['img']),
		'title' => trim(urldecode($this->input['title'])),
		'brief' => trim(urldecode($this->input['brief'])),
		'toff' => $toff,
		'user_name' => $this->user['user_name'],
		'create_time' => TIMENOW,
		'update_time' => TIMENOW,
		'filename' => $filename,
		'newname' => $newname,
		'filepath' => $filepath,
		'ip' => hg_getip(),
		);
		$sql = 'INSERT INTO '.DB_PREFIX.'backup SET ';
		foreach ($data as $k=>$v)
		{
			$sql .= $k.'="'.$v.'",';
		}
		
		$sql = rtrim($sql, ',');
		$this->db->query($sql);
		$ret = $this->db->insert_id();
		if(!$vodinfo_id)
		{
			$dir = UPLOAD_DIR . 'backup_mms/'.hg_num2dir($ret);
			if(!is_dir($dir))
			{
				hg_mkdir($dir);
			}
			$target_file = $dir.$data['newname'];
			if(!move_uploaded_file($_FILES['backup_file']['tmp_name'], $target_file))
			{
				$this->errorOutput(MOVEFILEFAILED);
			}
		}
		$this->addItem($ret);
		$this->output();
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
$output= new live_backup();
if(!method_exists($output, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$output->$action();
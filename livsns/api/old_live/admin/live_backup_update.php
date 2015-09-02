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
define('MOD_UNIQUEID','live_backup');
require_once('./global.php');
class live_backup extends adminUpdateBase
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include backup.class.php
	 */
	private $mBackup;
	function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		require_once CUR_CONF_PATH . 'lib/backup.class.php';
		$this->mBackup = new backup();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	function audit()
	{
		
	}
	function publish()
	{
		
	}
	function sort()
	{
		
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
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}

		$title = trim($this->input['title']);
		if(!$title)
		{
			$this->errorOutput('标题不能为空');
		}
		
		$type = '';
		$file_info = $_FILES['backup_file'];
		if ($file_info['tmp_name'])
		{
			$type = 2;
			if ($file_info['type'] != 'video/mp4')
			{
				$this->errorOutput('目前只支持MP4格式的视频');
			}

			if ($file_info['size'] > $this->settings['backup_file_size'] * 1024 * 1024)
			{
				$this->errorOutput('最大可上传' . $this->settings['backup_file_size'] . 'M');
			}
			
			$path = CUR_CONF_PATH . BACKUP_PATH;
			if (!is_dir($path))
			{
				hg_mkdir($path);
			}
			
			$ret_file = move_uploaded_file($file_info['tmp_name'], $path . $file_info['name']);
			if (!$ret_file)
			{
				$this->errorOutput('您上传的视频失败');
			}
		}

		$vodinfo_id = intval($this->input['back_up_video_id']);
		if ($vodinfo_id)
		{
			$type = 1;
			//获取视频库数据
			$video_info = $this->mBackup->getVodInfoById($vodinfo_id);
		
			if (empty($video_info))
			{
				$this->errorOutput('您选择的视频信息不存在或已被删除');
			}
		}
		
		if (!$type)
		{
			$this->errorOutput('请选择一个视频，或者上传一个视频');
		}
		
		$brief = (trim($this->input['brief']) == '这里输入描述') ? '' : trim($this->input['brief']);
		
		$add_input = array(
			'title' 		=> $title,
			'brief' 		=> $brief,
			'server_id'		=> intval($this->input['server_id']),
			'type'			=> $type,
			'file_info' 	=> $file_info,
			'video_info'	=> $video_info,
		);
		
		$info = $this->mBackup->create($add_input, $this->user);
		
		if (!$info)
		{
			$this->errorOutput('添加失败');
		}
		else if ($info == -20)
		{
			$this->errorOutput('媒体服务器端添加失败');
		}
		
		$this->addItem($data['id']);
		$this->output();
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
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('此备播文件不存在或已删除');
		}
		
		$title = trim($this->input['title']);
		if(!$title)
		{
			$this->errorOutput('标题不能为空');
		}
		
		$type = intval($this->input['type']);
		
		$file_info = $_FILES['backup_file'];
		if ($file_info['tmp_name'])
		{
			$type = 2;
			if ($file_info['type'] != 'video/mp4')
			{
				$this->errorOutput('目前只支持MP4格式的视频');
			}
			
			if ($file_info['size'] > $this->settings['backup_file_size'] * 1024 * 1024)
			{
				$this->errorOutput('最大可上传' . $this->settings['backup_file_size'] . 'M');
			}
			//move_uploaded_file
			$path = CUR_CONF_PATH . BACKUP_PATH;
			if (!is_dir($path))
			{
				hg_mkdir($path);
			}
			
			$ret_file = move_uploaded_file($file_info['tmp_name'], $path . $file_info['name']);
			if (!$ret_file)
			{
				$this->errorOutput('您上传的视频失败');
			}
		}

		$vodinfo_id = intval($this->input['back_up_video_id']);
		if ($vodinfo_id)
		{
			$type = 1;
			//获取视频库数据
			$video_info = $this->mBackup->getVodInfoById($vodinfo_id);
		
			if (empty($video_info))
			{
				$this->errorOutput('您选择的视频信息不存在或已被删除');
			}
		}
		
		if (!$type)
		{
			$this->errorOutput('请选择一个视频，或者上传一个视频');
		}
		
		$brief = (trim($this->input['brief']) == '这里输入描述') ? '' : trim($this->input['brief']);
		
		$add_input = array(
			'title' 		=> $title,
			'brief' 		=> $brief,
			'server_id'		=> intval($this->input['server_id']),
			'type'			=> $type,
			'file_info' 	=> $file_info,
			'video_info'	=> $video_info,
		);
		
		$info = $this->mBackup->update($id, $add_input, $this->user);
		
		if (!$info)
		{
			$this->errorOutput('更新失败');
		}
		else if ($info == -20)
		{
			$this->errorOutput('媒体服务器端更新失败');
		}
		
		$this->addItem($data['id']);
		$this->output();
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
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = trim($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('该备播文件不存在或已被删除');
		}
	
		$info = $this->mBackup->delete($id);
		if (!$info)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 检测 该备播文件 是否 被 信号流、切播、串联单 所占用
	 * Enter description here ...
	 */
	public function check_backup()
	{
		$backupId = trim($this->input['id']);
		if (!$backupId)
		{
			$this->errorOutput('未传入备播文件ID');
		}
		$info = $this->mBackup->check_backup($backupId);
		$this->addItem($info);
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
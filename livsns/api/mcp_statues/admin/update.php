<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: update.php 12433 2012-10-11 09:59:16Z repheal $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/status.class.php';
include_once ROOT_PATH . 'lib/class/auth.class.php';
define('MOD_UNIQUEID', 'statusShow'); //模块标识

class updateApi extends adminUpdateBase
{
	private $status;
	
	public function __construct()
	{
		parent::__construct();
		$this->status = new status();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->status);
	}
	
	/**
	 * 发布微博信息
	 */
	public function create()
	{
		//先检测发布是否超过系统设置
		$check_result = $this->status->check_num($this->user['user_id'], TIME_SPACE, TIME_NUM);
		if ($check_result) $this->errorOutput(PUBLISH_QUICK);
		//获取处理后的数据
		$data = $this->filter_data();
		//验证发布的字数
		if (mb_strlen($data['text']) > WORDS_NUM)
		{
			$this->errorOutput(STRLEN_OVER);
		}
		//验证发布的内容是否有屏蔽字
		$ban = $this->check_banword($data['text']);
		//处理URL
		$ret = $this->check_url($data['text']);
		if ($ret) $data['text'] = $ret;
		//检测发布的内容是否重复
		$verify_result = $this->status->verify_repeat($this->user['user_id'], $data['text']);
		if ($verify_result) $this->errorOutput(REPEAT_ADD);
		//获取图片和视频的数量
		$img_num = $video_num = 0;
		if ($data['img_info'])
		{
			$img_num = count(explode(',', $data['img_info']));
		}
		if ($data['video_info'])
		{
			$video_num = count(explode(',', $data['video_info']));
		}
		//发布微博
		$updateData = array(
			'member_id' => $this->user['user_id'],
			'pic' => $img_num,
			'video' => $video_num,
			'text' => $data['text'],
			'reply_status_id' => 0,
			'reply_user_id' => 0,
			'app_id' => $this->user['appid'],
			'app_name' => $this->user['display_name'],
			'create_at' => TIMENOW,
			'ip' => hg_getip(),
			'bans' => $ban
		);
		$result = $this->status->create('status', $updateData);
		//微博扩展数据创建
		$this->status->create('status_extra', array('status_id' => $result['id']));
		//处理话题信息
		$this->status->check_topic($result['id'], $data['text']);
		//更新素材信息
		if ($img_num)
		{
			$material_data = array(
				'status_id' => $result['id'],
				'create_time' => $result['create_at'],
				'ip' => $result['ip']
			);
			$this->status->update('material', $material_data, array('id' => $data['img_info']));
		}
		if ($video_num)
		{
			$video_data = array(
				'status_id' => $result['id'],
				'create_time' => $result['create_at'],
				'ip' => $result['ip']
			);
			$this->status->update('video', $video_data, array('id' => $data['video_info']));
		}
		$result['avatar'] = $this->user['avatar'];
		$result['user_name'] = $this->user['user_name'];
		//获取图片和视频信息
		$media_info = $this->status->getMedia($result['id']);
		$result['media'] = $media_info[$result['id']];
		$this->addItem($result);
		$this->output();
	}
	
	//验证发布的内容是否有屏蔽字
	private function check_banword($text)
	{
		include_once ROOT_PATH . 'lib/class/banword.class.php';
		$banword = new banword();
		$banwords = $banword->exists($text);
		$ban = '';
		if ($banwords)
		{
			foreach ($banwords as $v)
			{
				$ban .= $v['banname'] . '|';
			}
			$ban = rtrim($ban, '|');
		}
		return $ban;
	}
	
	//处理URL
	private function check_url($text)
	{
		include_once ROOT_PATH . 'lib/class/shorturl.class.php';
		$shorturl = new shorturl();
		return $shorturl->shorturl($text);
	}
	
	/**
	 * 转发微博信息
	 */
	public function transmit()
	{
		$status_id = isset($this->input['status_id']) ? intval($this->input['status_id']) : '';
		if (empty($status_id)) $this->errorOutput(PARAM_WRONG);
		$info = $this->status->detail($status_id);
		if (!$info) $this->errorOutput(OBJECT_NULL);
		$content = isset($this->input['content']) ? trim(urldecode($this->input['content'])) : '';
		if (!empty($content))
		{
			//验证发布的字数
			if (mb_strlen($content) > WORDS_NUM) $this->errorOutput(STRLEN_OVER);
			//验证发布的内容是否有屏蔽字
			$ban = $this->check_banword($content);
			//处理URL
			$ret = $this->check_url($content);
			if ($ret) $content = $ret;
			//检测发布的内容是否重复
			$verify_result = $this->status->verify_repeat($this->user['user_id'], $content);
			if ($verify_result) $this->errorOutput(REPEAT_ADD);
		}
		$data = array(
			'member_id' => $this->user['user_id'],
			'text' => $content,
			'reply_status_id' => $info['reply_status_id'] ? $info['reply_status_id'] : $status_id,
			'reply_user_id' => $info['reply_user_id'] ? $info['reply_user_id'] : $info['member_id'],
			'app_id' => $this->user['appid'],
			'app_name' => $this->user['display_name'],
			'create_at' => TIMENOW,
			'ip' => hg_getip(),
			'bans' => $ban
		);
		//微博数据创建
		$status_info = $this->status->create('status', $data);
		//微博扩展数据创建
		$this->status->create('status_extra', array('status_id' => $status_info['id']));
		//处理话题信息
		$this->status->check_topic($status_info['id'], $data['text']);
		//更新统计数据
		$this->status->update(
			'status_extra',
			array('transmit_count' => 1),
			array('status_id' => $status_id),
			true
		);
		if ($info['reply_status_id'])
		{
			$this->status->update(
				'status_extra',
				array('transmit_count' => 1),
				array('status_id' => $info['reply_status_id']),
				true
			);
		}
		$status_info['avatar'] = $this->user['avatar'];
		$status_info['user_name'] = $this->user['user_name'];
		$retweeted_status = $this->status->show(0, -1, array('status_id' => $data['reply_status_id']));
		$status_info['retweeted_status'] = $retweeted_status[$data['reply_status_id']];
		$this->addItem($status_info);
		$this->output();
	}
	
	/**
	 * 删除微博信息
	 */
	public function delete()
	{
		$status_id = isset($this->input['sid']) ? trim(urldecode($this->input['sid'])) : '';
		if(empty($status_id)) $this->errorOutput(PARAM_WRONG);
		//获取有效数据
		$status_info = $this->status->show(0, -1, array('status_id' => $status_id));
		if (!$status_info) $this->errorOutput(PARAM_WRONG);
		$validate = $trans = array();
		foreach ($status_info as $v)
		{
			$validate[$v['id']] = $v['id'];
			if ($v['reply_status_id']) $trans[$v['id']] = $v['reply_status_id'];
		}
		$status_id = implode(',', $validate);
		if (!strpos($status_id, ',')) $status_id = intval($status_id);
		$flag = isset($this->input['flag']) ? intval($this->input['flag']) : 1; //1逻辑删除 2物理删除
		if ($flag === 1)
		{
			//删除评论、图片、视频
			$this->status->update('status_comments', array('flag' => 1), array('status_id' => $status_id));
			$this->status->update('status_extra', array('state' => 0), array('status_id' => $status_id));
			$this->status->update('material', array('isdel' => 0), array('status_id' => $status_id));
			$this->status->update('video', array('isdel' => 0), array('status_id' => $status_id));
			$result = $this->status->update('status', array('status' => 1), array('id' => $status_id));
			if ($trans)
			{
				//更新转发次数
				foreach ($trans as $v)
				{
					$this->status->update(
						'status_extra',
						array('transmit_count' => -1),
						array('status_id' => implode(',', $v))
					);
				}
			}
		}
		elseif ($flag === 2)
		{
			//删除对应的数据(评论、图片、视频)
			$this->status->delete('status_comments', array('status_id' => $status_id));
			$this->status->delete('status_extra', array('status_id' => $status_id));
			$this->status->delete('material', array('status_id' => $status_id));
			$this->status->delete('video', array('status_id' => $status_id));
			$result = $this->status->delete('status', array('id' => $status_id));
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 本地化图片
	 */
	public function addMaterial()
	{
		$material_id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		$name = isset($this->input['name']) ? trim(urldecode($this->input['name'])) : '';
		$host = isset($this->input['host']) ? trim(urldecode($this->input['host'])) : '';
		$dir = isset($this->input['dir']) ? trim(urldecode($this->input['dir'])) : '';
		$filepath = isset($this->input['filepath']) ? trim(urldecode($this->input['filepath'])) : '';
		$filename = isset($this->input['filename']) ? trim(urldecode($this->input['filename'])) : '';
		$type = isset($this->input['type']) ? trim(urldecode($this->input['type'])) : '';
		$filesize = isset($this->input['filesize']) ? intval($this->input['filesize']) : 0;
		$data = array(
			'status_id' => 0,
			'material_id' => $material_id,
			'user_id' => $this->user['user_id'],
			'name' => $name,
			'host' => $host,
			'dir' => $dir,
			'filepath' => $filepath,
			'filename' => $filename,
			'type' => $type,
			'filesize' => $filesize,
			'create_time' => TIMENOW,
			'ip' => hg_getip()
		);
		$result = $this->status->create('material', $data);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 本地化视频
	 */
	public function addVideo()
	{
		$video_id = isset($this->input['vid']) ? intval($this->input['vid']) : 0;
		$title = isset($this->input['title']) ? trim(urldecode($this->input['title'])) : '';
		$img = isset($this->input['img']) ? trim(urldecode($this->input['img'])) : '';
		$url = isset($this->input['url']) ? trim(urldecode($this->input['url'])) : '';
		$swf = isset($this->input['swf']) ? trim(urldecode($this->input['swf'])) : '';
		$object = isset($this->input['object']) ? trim(urldecode($this->input['object'])) : '';
		$type = isset($this->input['type']) ? intval($this->input['type']) : '';
		if (empty($type) || ($type != 1 && $type != 2)) $this->errorOutput(PARAM_WRONG);
		$data = array(
			'status_id' => 0,
			'video_id' => $video_id,
			'user_id' => $this->user['user_id'],
			'title' => $title,
			'img' => $img,
			'url' => $url,
			'swf' => $swf,
			'object' => $object,
			'type' => $type,
			'create_time' => TIMENOW,
			'ip' => hg_getip()
		);
		$result = $this->status->create('video', $data);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 删除图片素材信息
	 */
	public function dropMaterial()
	{
		$pid = isset($this->input['p_id']) ? intval($this->input['p_id']) : '';
		if (empty($pid)) $this->errorOutput(PARAM_WRONG);
		$info = $this->status->getMaterial($pid, $this->user['user_id']);
		if (!$info) $this->errorOutput(OBJECT_NULL);
		//删除本地图片信息
		$result = $this->status->delete('material', array('id' => $pid));
		//删除图片服务器上的信息
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$material = new material();
		$material->delMaterialById($info['material_id'], 2);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 删除视频信息
	 */
	public function dropVideo()
	{
		$vid = isset($this->input['v_id']) ? intval($this->input['v_id']) : '';
		if (empty($vid)) $this->errorOutput(PARAM_WRONG);
		$info = $this->status->getVideo($vid, $this->user['user_id']);
		if (!$info) $this->errorOutput(OBJECT_NULL);
		//删除本地视频信息
		$result = $this->status->delete('video', array('id' => $vid));
		$this->addItem($result);
		$this->output();
	}
	
	public function update() {}
	public function audit() {}
	public function publish() {}
	public function sort() {}
	
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
	
	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		$content = isset($this->input['content']) ? trim(urldecode($this->input['content'])) : '';
		$image = isset($this->input['images']) ? trim(urldecode($this->input['images'])) : '';
		$video = isset($this->input['videos']) ? trim(urldecode($this->input['videos'])) : '';
		if (empty($content)) $this->errorOutput(PARAM_WRONG);
		$data = array();
		$data['text'] = $content;
		if (!empty($image)) $data['img_info'] = $image;
		if (!empty($video)) $data['video_info'] = $video;
		return $data;
	}
}
$out = new updateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();
?>
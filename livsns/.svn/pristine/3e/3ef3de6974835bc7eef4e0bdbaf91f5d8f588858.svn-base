<?php
require_once './global.php';
define('MOD_UNIQUEID','memberUpdateApi');//模块标识
require_once CUR_CONF_PATH.'lib/member_mode.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(ROOT_PATH.'lib/class/members.class.php');
class memberUpdateApi extends outerReadBase
{
	private $seekhelp;
	private $member;
	private $members;
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		$this->member = new member_mode();
		$this->members = new members();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	public function detail(){}
	/**
	 * 设置个人主页背景图
	 * @param $memberId int
	 */
	public function updateBackground()
	{
		$memberId = intval($this->user['user_id']);
		if(!$memberId)
		{
			$this->errorOutput(NO_MEMBER_INFO);
		}
		$data['background'] = $this->uploadimg("background");
		if(!$data['background'])
		{
			$this->errorOutput(UPLOAD_IMG_FAIL);
		}
		$res = $this->member->detail($memberId);
		if(!$res && $memberId)
		{
			if ($this->settings['App_members'])
			{
				$memberInfo = $this->members->get_newUserInfo_by_ids($memberId);
			}
			$this->member->create(array(
					'member_id' => $memberId,
					'member_name' => $memberInfo[0]['member_name'],
					'background' => $data['background'],
			));
		}
		else
		{
			
			$this->member->update($res['id'],array(
					'background' => $data['background'],
			));
		}
		$result = array(
				'memberId' => $memberId,
				'background' => unserialize($data['background']),
		);
		
		$this->addItem($result);
		$this->output();
	}
	
	public function show(){}
	
	/**
	 * 创建会员缓存
	 */
	public function create()
	{
	}
	
	/**
	 * 更新会员缓存
	 */
	public function update()
	{
	}
	
	/**
	 * 删除会员缓存
	 */
	public function delete()
	{
	}
	
	private function get_condition()
	{
	}
	
	/**
	 * 上传图片
	 * @param unknown $var_name
	 * @return string
	 */
	private function uploadimg($var_name)
	{
		if($_FILES[$var_name])
		{
			//处理avatar图片
			if($_FILES[$var_name] && !$_FILES[$var_name]['error'])
			{
				$_FILES['Filedata'] = $_FILES[$var_name];
				$material = new material();
				$img_info = $material->addMaterial($_FILES);
				if($img_info)
				{
					$avatar = array(
							'host' 		=> $img_info['host'],
							'dir' 		=> $img_info['dir'],
							'filepath' 	=> $img_info['filepath'],
							'filename' 	=> $img_info['filename'],
							'width'		=> $img_info['imgwidth'],
							'height'	=> $img_info['imgheight'],
							'id'        => $img_info['id'],
					);
					$avatar = @serialize($avatar);
				}
			}
			return $avatar;
		}
	}
	
	public function count(){}
	
}
$ouput = new memberUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();

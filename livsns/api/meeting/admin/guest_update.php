<?php
define('MOD_UNIQUEID','guest');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/guest_mode.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class guest_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new guest_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$name 			= $this->input['name'];//用户名
		$company 		= $this->input['company'];//单位
		$job 			= $this->input['job'];//职务
		$telephone 		= $this->input['telephone'];//电话号码
		$email 			= $this->input['email'];//邮箱
		$url			= $this->input['url'];//外链
		$sort_id		= $this->input['sort_id'];//对应的分类id（该分类就是嘉宾的姓名）
			
		//判断有没有用户名
		if(!$name)
		{
			$this->errorOutput(NO_USERNAME);
		}
		
		//判断有没有单位
		if(!$company)
		{
			$this->errorOutput(NO_COMPANY);
		}
		
		//判断有没有职务
		if(!$job)
		{
			$this->errorOutput(NO_JOB);
		}
		
		//判断有没有手机号以及手机号的格式对不对
		/*
		if(!$telephone)
		{
			$this->errorOutput(NO_TELEPHONE);
		}
		elseif (!preg_match('/^1[3-8]\d{9}$/',$telephone))
		{
			$this->errorOutput(ERROR_FORMAT_TEL);
		}
		*/
		
		//判断有没有邮箱以及邮箱格式对不对
		if(!$email)
		{
			$this->errorOutput(NO_EMAIL);
		}
		elseif (!preg_match('/^[0-9a-zA-Z]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/i',$email))
		{
			$this->errorOutput(ERROR_FORMAT_EMAIL);
		}

		$data = array(
			'name' 			=> $name,
			'company' 		=> $company,
			'job' 			=> $job,
			'telephone' 	=> $telephone,
			'email' 		=> $email,
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
			'ip'			=> hg_getip(),
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'org_id'		=> $this->user['org_id'],
			'url'			=> $url,
			'sort_id'		=> $sort_id,
		);
		
		//处理avatar图片
		if($_FILES['avatar'])
		{
			$_FILES['Filedata'] = $_FILES['avatar'];
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			if($img_info)
			{
				$avatar = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
					'width'		=> $img_info['width'],
					'height'	=> $img_info['height'],
				);
				$data['avatar'] = @serialize($avatar);
			}
		}
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建大会嘉宾',$data,'','创建大会嘉宾' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$name 			= $this->input['name'];//用户名
		$company 		= $this->input['company'];//单位
		$job 			= $this->input['job'];//职务
		$telephone 		= $this->input['telephone'];//电话号码
		$email 			= $this->input['email'];//邮箱
		$url			= $this->input['url'];//外链
		$sort_id		= $this->input['sort_id'];//对应的分类id（该分类就是嘉宾的姓名）
		
		//判断有没有用户名
		if(!$name)
		{
			$this->errorOutput(NO_USERNAME);
		}
		
		//判断有没有单位
		if(!$company)
		{
			$this->errorOutput(NO_COMPANY);
		}
		
		//判断有没有职务
		if(!$job)
		{
			$this->errorOutput(NO_JOB);
		}
		
		//判断有没有手机号以及手机号的格式对不对
		/*
		if(!$telephone)
		{
			$this->errorOutput(NO_TELEPHONE);
		}
		elseif (!preg_match('/^1[3-8]\d{9}$/',$telephone))
		{
			$this->errorOutput(ERROR_FORMAT_TEL);
		}
		*/
		
		//判断有没有邮箱以及邮箱格式对不对
		if(!$email)
		{
			$this->errorOutput(NO_EMAIL);
		}
		elseif (!preg_match('/^[0-9a-zA-Z]+@(([0-9a-zA-Z]+)[.])+[a-z]{2,4}$/i',$email))
		{
			$this->errorOutput(ERROR_FORMAT_EMAIL);
		}
		
		$update_data = array(
			'name' 			=> $name,
			'company' 		=> $company,
			'job' 			=> $job,
			'telephone' 	=> $telephone,
			'email' 		=> $email,
			'update_time' 	=> TIMENOW,
			'url'			=> $url,
			'sort_id'		=> $sort_id,
		);
		
		//处理avatar图片
		if($_FILES['avatar'])
		{
			$_FILES['Filedata'] = $_FILES['avatar'];
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			if($img_info)
			{
				$avatar = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
					'width'		=> $img_info['width'],
					'height'	=> $img_info['height'],
				);
				$update_data['avatar'] = @serialize($avatar);
			}
		}
		
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新大会嘉宾',$ret,'','更新大会嘉宾' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除大会嘉宾',$ret,'','删除大会嘉宾' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

	//排序
	public function sort()
    {
        $this->drag_order('guest','order_id');
        $ids = explode(',', $this->input['content_id']);
        $this->addItem(array('id' => $ids));
        $this->output();
    }
    
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new guest_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>
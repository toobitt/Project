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
		$weibo          = $this->input['weibo'];//个人微博url
			
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
		    'weibo'			=> $weibo,
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
		$weibo          = $this->input['weibo'];//个人微博url
		
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
		    'weibo'			=> $weibo,
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
    
    //生成嘉宾的离线数据
    public function create_off_line()
    {
       $data = $this->getSaveFileData();//用于保存构建的关系数据
        //查询出所有大会嘉宾的数据
        $guest = $this->mode->show();
        $data[OFF_LINE_URL . 'meeting_guests.php?appkey=' . APPKEY . '&appid=' . APPID] = $guest;
        
        //取外链数据
        foreach ($guest AS $k => $v)
        {
            if(!$v['url'])
            {
                continue;
            }
            
            $_urlArr = explode('#',$v['url']);
            //根据url里面对应的栏目请求发布库数据
            $_url = OFF_LINE_URL . 'news_detail.php?appkey=' .APPKEY. '&appid=' .APPID. '&id=' . $_urlArr[1];
            $_guest_detail = $this->getUrlData($_url);
            if(!$_guest_detail)
            {
                continue;
            }
            $data[$_url] = $_guest_detail;
        }
        //保存到DATA目录
        file_put_contents(DATA_DIR . 'off_line_data.json', json_encode($data));
        $this->addItem('success');
        $this->output();
    }
    
    //生成参会指南离线数据
    public function create_guide_off_line()
    {
        $data = $this->getSaveFileData();
        //首先获取所有参会指南数据
        $guideUrl = OFF_LINE_URL . 'join_guide.php?appkey=' . APPKEY . '&appid=' . APPID;
        $guideData = $this->getUrlData($guideUrl);
        if($guideData)
        {
            //保存数据
            $data[$guideUrl] = $guideData;
            foreach ($guideData AS $k => $v)
            {
                $_url = OFF_LINE_URL . 'news_detail.php?appkey=' .APPKEY. '&appid=' .APPID. '&id=' . $v['id'];
                $_guide_detail = $this->getUrlData($_url);
                if(!$_guide_detail)
                {
                    continue;
                }
                $data[$_url] = $_guide_detail;
            }
        }
        
        //保存到DATA目录
        file_put_contents(DATA_DIR . 'off_line_data.json', json_encode($data));
        $this->addItem('success');
        $this->output();
    }
    
    private function getSaveFileData()
    {
        $data = array();
        if(file_exists(DATA_DIR . 'off_line_data.json'))
        {
            $data = file_get_contents(DATA_DIR . 'off_line_data.json');
            $data = json_decode($data,1);
        }
        else 
        {
            //创建文件
            file_put_contents(DATA_DIR . 'off_line_data.json',json_encode($data));
        }
        return $data;
    }
    
    private function getUrlData($url)
    {
        $data = file_get_contents($url);
        if($data)
        {
            return json_decode($data,1);
        }
        else 
        {
            return array();
        }
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
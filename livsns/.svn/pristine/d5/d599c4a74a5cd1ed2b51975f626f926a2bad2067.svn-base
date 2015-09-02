<?php
require_once './global.php';
define('MOD_UNIQUEID','service_bmfw_create');//模块标识
require_once CUR_CONF_PATH.'lib/service.class.php';
class serviceBMFWCreateApi extends outerUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->service = new ClassService();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 
	 * @Description 提交便民服务信息
	 * @author Kin
	 * @date 2013-10-26 上午10:01:15 
	 * @see outerUpdateBase::create()
	 */
	public function create()
	{
		$data = array(
				'title'				=> trim($this->input['title']),
				'appid'				=> $this->user['appid'],
				'appname'			=> $this->user['display_name'],
	 			'baidu_longitude'	=> trim($this->input['baidu_longitude']),
	 			'baidu_latitude'	=> trim($this->input['baidu_latitude']),
				'GPS_longitude'		=> trim($this->input['GPS_longitude']),
	 			'GPS_latitude'		=> trim($this->input['GPS_latitude']),
				'user_name'			=> trim($this->input['user_name']),
				'sex'				=> intval($this->input['sex']),
				'tel'				=> trim($this->input['tel']),
				'address'			=> trim($this->input['address']),
				'email'				=> trim($this->input['email']),
				'create_time'		=> TIMENOW,
				'password'			=> trim($this->input['password']),
				'type'				=> 1,
		);
		$content = trim($this->input['content']);

		//如果百度坐标存在的话，就转换为GPS坐标也存起来
		if($data['baidu_longitude'] && $data['baidu_latitude'] && !$data['GPS_longitude'] && !$data['GPS_latitude'])
		{
			$gps = $this->service->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
		}
		//如果GPS坐标存在的话，就转换为百度坐标也存起来
		if(!$data['baidu_longitude'] && !$data['baidu_latitude'] && $data['GPS_longitude'] && $data['GPS_latitude'])
		{
			$baidu = $this->service->FromGpsToBaiduXY($data['GPS_longitude'],$data['GPS_latitude']);
			$data['baidu_longitude'] = $baidu['x'];
			$data['baidu_latitude'] = $baidu['y'];
		}
		if (!$data['title'])
		{
			$this->errorOutput('请输入标题');
		}
		if (!$content)
		{
			$this->errorOutput('请输入内容');
		}
		//初始化的数据
		$is_reply 	= 0;
		$serviceInfor = $this->service->add_service($data);
		if (!$serviceInfor['id'])
		{			
			$this->errorOutput('数据库插入失败');
		}
		$id = $serviceInfor['id'];
		//添加描述	
		
		if ($content)
		{
			$contentInfor = $this->service->add_content($content, $id);
			if (!$contentInfor)
			{
				$this->errorOutput('数据库插入失败');
			}
			$data['content'] = $contentInfor;
		}
		$data['id'] = $id;
		if ($id)
		{
			$ret = $this->service->forward_people($data);
		}
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{
	
	}
	
	public function delete()
	{
	
	}
	
}
$ouput= new serviceBMFWCreateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'create';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
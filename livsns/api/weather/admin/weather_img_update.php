<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/weather_img.class.php';
define('MOD_UNIQUEID','weather');//模块标识
class forcastUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->img = new imgWeather();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function update()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		//查询已设置的天气图片
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_material WHERE id = '.$id;			
		$res = $this->db->query_first($sql);
		$app_user_image = unserialize($res['app_user_image'])?unserialize($res['app_user_image']):array();
		$app_bg_image = unserialize($res['app_bg_image'])?unserialize($res['app_bg_image']):array();
		$user_image = unserialize($res['user_img']) ? unserialize($res['user_img']) : array();
		$bg_image = unserialize($res['bg_image']) ? unserialize($res['bg_image']) : array();
		$update = $res['is_update'];
		
		
		$apps = $this->input['app'];
		$custom_name = $this->input['custom_name'];
		$delete_user_image = $this->input['delete_user_image'];
		$delete_bg_image = $this->input['delete_bg_image'];
		$delete_app_user_image = $this->input['delete_app_user_image'];
		$delete_app_bg_image = $this->input['delete_app_bg_image'];
		
		$data = array();
		
		//有删除的优先删除
		if ($delete_user_image)
		{
			$user_image = array();
			$update = 0;
		}
		if ($delete_bg_image)
		{
			$bg_image = array();
		}
		if (!empty($delete_app_user_image))
		{
			foreach ($app_user_image as $key=>$val)
			{
				if ($delete_app_user_image[$key])
				{
					unset($app_user_image[$key]);
				}
			}
		}
		if (!empty($delete_app_bg_image))
		{
			foreach ($app_bg_image as $key=>$val)
			{
				if ($delete_app_bg_image[$key])
				{
					unset($app_bg_image[$key]);
				}
			}
		}
		
		$add_user_image = array();
		$add_bg_image = array();
		$add_app_user_image = array();
		$add_app_bg_image = array();
		//上传
		if ($_FILES)
		{
			if ($_FILES['Filedata_user_image'])
			{
				$pic = array();
				foreach($_FILES['Filedata_user_image'] AS $k =>$v)
				{
					$pic['Filedata'][$k] = $_FILES['Filedata_user_image'][$k];
				}
				$ret = $this->img->uploadToPicServer($pic, $id);
				if (!empty($ret))
				{
					$add_user_image = array(
						'host'=>$ret['host'],
						'dir'=>$ret['dir'],
						'filepath'=>$ret['filepath'],
						'filename'=>$ret['filename'],
					);
					$update = 1;
				}else {
					$this->errorOutput("图片上传异常");
				}	
			}
			if ($_FILES['Filedata_bg_image'])
			{
				$pic = array();
				foreach($_FILES['Filedata_bg_image'] AS $k =>$v)
				{
					$pic['Filedata'][$k] = $_FILES['Filedata_bg_image'][$k];
				}
				$ret = $this->img->uploadToPicServer($pic, $id);
				if (!empty($ret))
				{
					$add_bg_image = array(
						'host'=>$ret['host'],
						'dir'=>$ret['dir'],
						'filepath'=>$ret['filepath'],
						'filename'=>$ret['filename'],
					);
				}else {
					$this->errorOutput("图片上传异常");
				}
			}
			
			if (is_array($apps) && !empty($apps))
			{
				foreach ($apps as $key=>$val)
				{
					
					//用户自定义图片
					if ($_FILES['Filedata_app_user_'.$val])
					{
						$temp = array();
						$pic= array();
						foreach($_FILES['Filedata_app_user_'.$val] AS $k =>$v)
						{
							$pic['Filedata'][$k] = $_FILES['Filedata_app_user_'.$val][$k];
						}
						$temp = $this->img->uploadToPicServer($pic,'');
						if (!empty($temp))
						{
							$add_app_user_image[$val] = array(
								'appid'=>$val,
								'custom_name'=>$custom_name[$key],
								'host'=>$temp['host'],
								'dir'=>$temp['dir'],
								'filepath'=>$temp['filepath'],
								'filename'=>$temp['filename'],
							);
						}else {
							$this->errorOutput("图片上传异常");
						}
					}
					//用户自定义背景图片
					if ($_FILES['Filedata_app_bg_'.$val])
					{
						$temp = array();
						$pic= array();
						foreach($_FILES['Filedata_app_bg_'.$val] AS $k =>$v)
						{
							$pic['Filedata'][$k] = $_FILES['Filedata_app_bg_'.$val][$k];
						}
						$temp = $this->img->uploadToPicServer($pic,'');
						if (!empty($temp))
						{
							$add_app_bg_image[$val] = array(
								'appid'=>$val,
								'custom_name'=>$custom_name[$key],
								'host'=>$temp['host'],
								'dir'=>$temp['dir'],
								'filepath'=>$temp['filepath'],
								'filename'=>$temp['filename'],
						);
						}else {
							$this->errorOutput("图片上传异常");
						}
					}
				}
			}
		}
		//数据整合
		if (!empty($add_user_image))
		{
			$user_image = $add_user_image;
		}
		if (!empty($add_bg_image))
		{
			$bg_image = $add_bg_image;
		}
		if (!empty($add_app_user_image))
		{
			foreach ($add_app_user_image as $key=>$val)
			{
				$app_user_image[$key]=$val;
			}
		}
		if (!empty($add_app_bg_image))
		{
			foreach ($add_app_bg_image as $key=>$val)
			{
				$app_bg_image[$key]=$val;
			}
		}
		//入库前数据处理
		$user_image = !empty($user_image) ? addslashes(serialize($user_image)) : '';
		$bg_image = !empty($bg_image) ? addslashes(serialize($bg_image)) : '';
		$app_user_image = !empty($app_user_image) ? addslashes(serialize($app_user_image)) : '';
		$app_bg_image = !empty($app_bg_image) ? addslashes(serialize($app_bg_image)) : '';

		$data = array(
			'user_img'=>$user_image,
			'bg_image'=>$bg_image,
			'app_user_image' => $app_user_image,
			'app_bg_image' => $app_bg_image,
			'is_update'=>$update,
			'update_time' => TIMENOW,
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'ip'=>$this->user['ip'],
		);
		$ret = $this->img->update($data,$id);
		$this->addItem('sucess');
		$this->output();
	}
	
	public function delete()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = $this->input['id'];
		$ret = $this->img->delete($id);
		$this->addItem($ret);
		$this->output();
	}
	public function create()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$title = $this->input['title'];
		$apps = $this->input['app'];
		$custom_name = $this->input['custom_name'];
		$update = 0;
		$user_image = array();
		$bg_image = array();
		$app_user_image = array();
		$app_bg_image = array();
		$data = array();
		$res = $this->img->check_title($title);
		if (!$res)
		{
			$this->errorOutput("图片名称已存在");
		}
		//图片上传，上传的系统图
		if ($_FILES)
		{
			if ($_FILES['Filedata_user_image'])
			{
				$pic = array();
				foreach($_FILES['Filedata_user_image'] AS $k =>$v)
				{
					$pic['Filedata'][$k] = $_FILES['Filedata_user_image'][$k];
				}
				$ret = $this->img->uploadToPicServer($pic,'');
				if (!empty($ret))
				{
					$user_image = array(
						'host'=>$ret['host'],
						'dir'=>$ret['dir'],
						'filepath'=>$ret['filepath'],
						'filename'=>$ret['filename'],
					);
					$update = 1;
				}else {
					$this->errorOutput("图片上传异常");
				}
			}
			
			if ($_FILES['Filedata_bg_image'])
			{
				$pic = array();
				foreach($_FILES['Filedata_bg_image'] AS $k =>$v)
				{
					$pic['Filedata'][$k] = $_FILES['Filedata_bg_image'][$k];
				}
				$ret = $this->img->uploadToPicServer($pic,'');
				if (!empty($ret))
				{
					$bg_image = array(
						'host'=>$ret['host'],
						'dir'=>$ret['dir'],
						'filepath'=>$ret['filepath'],
						'filename'=>$ret['filename'],
					);
				}else {
					
					$this->errorOutput("图片上传异常");
				}
			}
	
			if (is_array($apps) && !empty($apps))
			{
				foreach ($apps as $key=>$val)
				{
					if ($_FILES['Filedata_app_user_'.$val])
					{
						$temp = array();
						$pic = array();
						foreach($_FILES['Filedata_app_user_'.$val] AS $k =>$v)
						{
							$pic['Filedata'][$k] = $_FILES['Filedata_app_user_'.$val][$k];
						}
						$temp = $this->img->uploadToPicServer($pic,'');
						if (!empty($temp))
						{
							$app_user_image[$val] = array(
								'appid'=>$val,
								'custom_name'=>$custom_name[$key],
								'host'=>$temp['host'],
								'dir'=>$temp['dir'],
								'filepath'=>$temp['filepath'],
								'filename'=>$temp['filename'],
							);
						}else {
							$this->errorOutput("图片上传异常");
						}
					}
					if ($_FILES['Filedata_app_bg_'.$val])
					{
						$temp = array();
						$pic = array();
						foreach($_FILES['Filedata_app_bg_'.$val] AS $k =>$v)
						{
							$pic['Filedata'][$k] = $_FILES['Filedata_app_bg_'.$val][$k];
						}
						$temp = $this->img->uploadToPicServer($pic,'');
						if (!empty($temp))
						{
							$app_bg_image[$val] = array(
								'appid'=>$val,
								'custom_name'=>$custom_name[$key],
								'host'=>$temp['host'],
								'dir'=>$temp['dir'],
								'filepath'=>$temp['filepath'],
								'filename'=>$temp['filename'],
							);
						}else {
							$this->errorOutput("图片上传异常");
						}
					}
				}
			}
		}
		//入库前数据处理
		$user_image = !empty($user_image) ? addslashes(serialize($user_image)) : '';
		$bg_image = !empty($bg_image) ? addslashes(serialize($bg_image)) : '';
		$app_user_image = !empty($app_user_image) ? addslashes(serialize($app_user_image)) : '';
		$app_bg_image = !empty($app_bg_image) ? addslashes(serialize($app_bg_image)) : '';
		
		//入库
		$data =array(
			'title'=>addslashes($title),
			'user_img'=>$user_image,
			'bg_image'=>$bg_image,
			'app_user_image'=>$app_user_image,
			'app_bg_image'=>$app_bg_image,
			'is_update'=>$update,
			'create_time'=>TIMENOW,
			'update_time'=>TIMENOW,
			'user_id'=>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'ip'=>$this->user['ip'],
		);
		$id = $this->img->create($data);
		$this->addItem($id);
		$this->output();
	}
	public function del_img()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id =$this->input['id'];
		$type = $this->input['type'];
		$this->img->del_img($id, $type);
		$arr = array(
			'id'=>explode(',', $id),
			'type'=>$type
		);
		$this->addItem($arr);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
}

$ouput = new forcastUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();

?>


			
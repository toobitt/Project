<?php
define('MOD_UNIQUEID','water_config');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/water_config_mode.php');
class water_config_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new water_config_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if(!$_FILES['water_pic'])
		{
			$this->errorOutput('没有图片文件');
		}

		if (!hg_mkdir(TARGET_DIR . 'water/') || !is_writeable(TARGET_DIR . 'water/'))
		{
			$this->errorOutput(NOWRITE);
		}
		
		$original 	= $_FILES['water_pic']['name'];
		$filetype 	= strtolower(strrchr($original, '.'));
		//随机产生一个文件名
		$filename = TIMENOW . hg_rand_num(6) . $filetype;
		if (!@move_uploaded_file($_FILES['water_pic']['tmp_name'], TARGET_DIR . 'water/' . $filename))
		{
			$this->errorOutput(FAILMOVE);
		}
		
		//图片文件传上去之后，记录数据库
		$data = array(
			'name' 			=> $this->input['name'],
			'hostwork' 		=> (defined('TARGET_VIDEO_DOMAIN') && TARGET_VIDEO_DOMAIN)?ltrim(TARGET_VIDEO_DOMAIN,'http://'):$this->settings['videouploads']['host'],
			'base_path' 	=> TARGET_DIR . 'water/',
			'img_path' 		=> $filename,
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'org_id'		=> $this->user['org_id'],
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建水印',$data,'','创建水印' . $vid);
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
		
		$update_data = array(
			'name' 			=> $this->input['name'],
			'update_time' 	=> TIMENOW,
		);
		
		//如果传了图片
		if($_FILES['water_pic'])
		{
			$original 	= $_FILES['water_pic']['name'];
			$filetype 	= strtolower(strrchr($original, '.'));
			//随机产生一个文件名
			$filename = TIMENOW . hg_rand_num(6) . $filetype;
			if (!@move_uploaded_file($_FILES['water_pic']['tmp_name'], TARGET_DIR . 'water/' . $filename))
			{
				$this->errorOutput(FAILMOVE);
			}
			
			$add_data = array(
				'hostwork' 		=> (defined('TARGET_VIDEO_DOMAIN') && TARGET_VIDEO_DOMAIN)?ltrim(TARGET_VIDEO_DOMAIN,'http://'):$this->settings['videouploads']['host'],
				'base_path' 	=> TARGET_DIR . 'water/',
				'img_path' 		=> $filename,
			);
			
			//还要将原来图片文件删除掉
			$oldWter = $this->mode->detail($this->input['id']);
			if($oldWter)
			{
				unlink($oldWter['base_path'] . $oldWter['img_path']);
			}
		}
		
		if($add_data)
		{
			$update_data = array_merge($update_data,$add_data);
		}
		
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{	
			$this->addLogs('更新水印',$ret,'','更新水印' . $this->input['id']);
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
			$this->addLogs('删除水印',$ret,'','删除水印' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new water_config_update();
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
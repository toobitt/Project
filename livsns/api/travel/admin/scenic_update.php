<?php
require('global.php');
define('MOD_UNIQUEID','travel');//模块标识
class scenicUpdateApi extends adminUpdateBase
{

	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/scenic.class.php');
		$this->obj = new scenic();	
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{	
		$name = $this->input['name'];
		if(!$name)
		{
			$this->errorOutput("请填写景区名称");
		}
		if(!($this->input['sort_id']))
		{
			$this->errorOutput("请选择景区分类");
		}
		$info = array();
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
		if($_FILES['Filedata'])
		{
			$file_name= $_FILES['Filedata']['name'];
			$file_type = strtolower(strrchr($file_name,"."));
			$ftypes = $this->settings['pic_types'];
			if(!in_array($file_type,$ftypes))
			{
				$this->errorOutput("示意图类型错误，请重新上传");
			}
			$fileinfo = $this->material->addMaterial($_FILES); //插入图片服务器
			
		}	
		if($_FILES['Filedaolan'])
		{
			$guide_name= $_FILES['Filedaolan']['name'];
			$guide_type = strtolower(strrchr($guide_name,"."));
			$ftypes = $this->settings['pic_types'];
			if(!in_array($guide_type,$ftypes))
			{
				$this->errorOutput("导览图类型错误，请重新上传");
			}
			$_FILES['Filedata']  = $_FILES['Filedaolan'];
			$guideinfo = $this->material->addMaterial($_FILES); //插入图片服务器
		}
			
		$info = array(
			'name'			=> $name,
            'sort_id'		=> intval($this->input['sort_id']),
			'appid'			=> intval($this->input['appid']),
            'brief'			=> $this->input['brief'],
			'country'		=> '1',
			'address'		=> $this->input['address'],
			'grade'			=> $this->input['grade'],
			'keywords'		=> $this->input['keywords'],
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'ip'			=> $this->user['ip'],
			'create_time'	=> TIMENOW,
		);
		if($this->input['longitude'])
		{
			$info['longitude'] = $this->input['longitude'];
		}
		else
		{
			$info['longitude'] = 0;
		}
		if($this->input['latitude'])
		{
			$info['latitude'] = $this->input['latitude'];
		}
		else
		{
			$info['latitude'] = 0;
		}
		if($this->input['province'])
		{
			$info['province'] = $this->input['province'];
		}
		else
		{
			$info['province'] = 0;
		}
		if($this->input['city'])
		{
			$info['city'] = $this->input['city'];
		}
		else
		{
			$info['city'] = 0;
		}
		if($this->input['area'])
		{
			$info['area'] = $this->input['area'];
		}
		else
		{
			$info['area'] = 0;
		}
		if($fileinfo)
		{
			$arr = array(
				'host'			=>	$fileinfo['host'],
				'dir'			=>	$fileinfo['dir'],
				'filepath'		=>	$fileinfo['filepath'],
				'filename'		=>	$fileinfo['filename'],
			);
			$info['indexpic'] =	serialize($arr);
		}
		if($guideinfo)
		{
			$arr = array(
				'host'			=>	$guideinfo['host'],
				'dir'			=>	$guideinfo['dir'],
				'filepath'		=>	$guideinfo['filepath'],
				'filename'		=>	$guideinfo['filename'],
			);
			$info['guidepic'] =	serialize($arr);
		}
		if(intval($this->input['fid']))
		{
			$info['fid'] = intval($this->input['fid']);
		}
		else 
		{
			$info['fid'] = 0;
		}
		$ret = $this->obj->create($info);
		$this->addItem($ret);
		$this->output();
	}
	
	function update()
	{	
		$name = $this->input['name'];
		if(!$name)
		{
			$this->errorOutput("请填写景区名称");
		}
		if(!($this->input['fid']))
		{
			if(!($this->input['sort_id']))
			{
				$this->errorOutput("请选择景区分类");
			}
		}
		
		$info = array();
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
		if($_FILES['Filedata'])
		{
			$file_name= $_FILES['Filedata']['name'];
			$file_type = strtolower(strrchr($file_name,"."));
			$ftypes = $this->settings['pic_types'];
			if(!in_array($file_type,$ftypes))
			{
				$this->errorOutput("示意图类型错误，请重新上传");
			}
			$fileinfo = $this->material->addMaterial($_FILES); //插入图片服务器
			
		}	
		if($_FILES['Filedaolan'])
		{
			$guide_name= $_FILES['Filedaolan']['name'];
			$guide_type = strtolower(strrchr($guide_name,"."));
			$ftypes = $this->settings['pic_types'];
			if(!in_array($guide_type,$ftypes))
			{
				$this->errorOutput("导览图类型错误，请重新上传");
			}
			$_FILES['Filedata']  = $_FILES['Filedaolan'];
			$guideinfo = $this->material->addMaterial($_FILES); //插入图片服务器
		}
		$info = array(
			'id'			=> intval($this->input['id']),
			'name'			=> $name,
            'sort_id'		=> intval($this->input['_sort_id']),
			'appid'			=> intval($this->input['appid']),
            'brief'			=> $this->input['brief'],
			'country'		=> '1',
			'address'		=> $this->input['address'],
			'grade'			=> $this->input['grade'],
			'keywords'		=> $this->input['keywords'],
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'ip'			=> $this->user['ip'],
			'create_time'	=> TIMENOW,
		);
		if($this->input['longitude'])
		{
			$info['longitude'] = $this->input['longitude'];
		}
		else
		{
			$info['longitude'] = 0;
		}
		if($this->input['latitude'])
		{
			$info['latitude'] = $this->input['latitude'];
		}
		else
		{
			$info['latitude'] = 0;
		}
		if($this->input['province'])
		{
			$info['province'] = $this->input['province'];
		}
		else
		{
			$info['province'] = 0;
		}
		if($this->input['city'])
		{
			$info['city'] = $this->input['city'];
		}
		else
		{
			$info['city'] = 0;
		}
		if($this->input['area'])
		{
			$info['area'] = $this->input['area'];
		}
		else
		{
			$info['area'] = 0;
		}
		if($fileinfo)
		{
			$arr = array(
				'host'			=>	$fileinfo['host'],
				'dir'			=>	$fileinfo['dir'],
				'filepath'		=>	$fileinfo['filepath'],
				'filename'		=>	$fileinfo['filename'],
			);
			$info['indexpic'] =	serialize($arr);
		}
		if($guideinfo)
		{
			$arr = array(
				'host'			=>	$guideinfo['host'],
				'dir'			=>	$guideinfo['dir'],
				'filepath'		=>	$guideinfo['filepath'],
				'filename'		=>	$guideinfo['filename'],
			);
			$info['guidepic'] =	serialize($arr);
		}
		$ret = $this->obj->update($info);
		$this->addItem($ret);
		$this->output();
	}
	
	
	/*参数:video_id(路况的id可以多个),order_id(圈子的排序id),table_name(需要排序的表名)
	 *功能:对圈子列表进行排序操作
	 *返回值:将圈子id以逗号隔开，字符串的形式返回
	 * */
	public function drag_order()
	{
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}
		$ids       = explode(',',urldecode($this->input['video_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " .DB_PREFIX. "scenic  SET orderid = ".$order_ids[$k]."  WHERE id = ".$v;
			$this->db->query($sql);
		}
		$this->addItem($ids);
		$this->output();
	}
	function delete()
	{			
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的景区");
		}
		$ret = $this->obj->delete($ids);
		$this->addItem('sucess');
		$this->output();
		
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
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new scenicUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>
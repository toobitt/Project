<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function create|update|delete|audit|unknow
*
* $Id: water_update.php 6406 2012-04-12 09:47:23Z wangleyuan $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','water_conf');
class waterUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/water.class.php');
		$this->obj = new water();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(empty($this->input['config_name']))
		{
			$this->errorOutput('配置名称不能为空');
		}
		if(intval($this->input['water_type'])==1 && empty($this->input['water_filename']))
		{
			$this->errorOutput('水印图片不能为空');
		}

		if(intval($this->input['water_type'])==0 && empty($this->input['water_text']))
		{
			$this->errorOutput('水印文字不能为空');
		}
		$config_name = urldecode($this->input['config_name']);	
		switch(intval($this->input['water_type']))
		{
			case 0://文字水印
				if(empty($this->input['water_text']))
				{
					$this->errorOutput('水印文字不能为空');
				}
				$water = array(
					'config_name'=> $config_name,
					'type' =>0,
					'position' => intval($this->input['get_photo_waterpos']),
					'filename' =>'',
					'margin_x'=> intval($this->input['margin_x']),
					'margin_y'=> intval($this->input['margin_y']),
					'condition_x' => intval($this->input['condition_x']),
					'condition_y' => intval($this->input['condition_y']),
					'water_text' => urldecode($this->input['water_text']),
					'water_angle' => intval($this->input['water_angle']) ? intval($this->input['water_angle']) : 1,
					'water_font' => urldecode($this->input['water_font']),
					'font_size' => intval($this->input['font_size']),
					'opacity' => urldecode($this->input['opacity']),
					'water_color' => urldecode($this->input['water_color']),
					'create_time' => TIMENOW,
					'update_time' => TIMENOW,
					'ip' => hg_getip(),
					'user_name' => trim(urldecode($this->user['user_name'])),
					'global_default' => $this->input['default'] ? 1 : '',
				);
				$this->obj->create($water);
				$insert_id = $this->db->insert_id();
				if($water['global_default'])
				{
					$this->obj->update_water_nodefault($insert_id);			
				}
			break;
		    case 1:  //图片水印
				if(empty($this->input['water_filename']))
				{
					$this->errorOutput('水印图片不能为空');
				}
				$water_name = urldecode($this->input['water_filename']);
				$water = array(
					'config_name'=>$config_name,
					'type' => 1,
					'position' => intval($this->input['get_photo_waterpos']),
					'filename' => $water_name,
					'margin_x'=> intval($this->input['margin_x']),
					'margin_y'=> intval($this->input['margin_y']),
					'condition_x' => intval($this->input['condition_x']),
					'condition_y' => intval($this->input['condition_y']),
					'water_text' =>'',
					'water_angle' =>'',
					'water_font' =>'',
					'font_size' => '',
					'opacity' =>urldecode($this->input['opacity']),
					'water_color' =>'',
					'create_time' => TIMENOW,
					'update_time' => TIMENOW,
					'ip' => hg_getip(),
					'user_name' => trim(urldecode($this->user['user_name'])),
					'global_default' => $this->input['default'] ? 1 : '',
				);
				$this->obj->create($water);
				$insert_id = $this->db->insert_id();
				if($water['global_default'])
				{
					$this->obj->update_water_nodefault($insert_id);
				}
				//移动水印图片
				$temp_file = hg_getimg_default_dir() . MATERIAL_TMP_PATH . $water_name;
				$path = hg_getimg_default_dir() . WATER_PATH;
				if(!hg_mkdir($path))
				{
					return false;
				}
				if(file_exists($temp_file))
				{
					copy($temp_file,$path . $water_name);
				}
				break;
			default:
				break;
		}
		hg_unlink_file(CACHE_DIR,'.water.cache.php');  //删除水印缓存文件	
		$water['id'] = $insert_id;
		$this->addLogs('添加水印配置','',$water,$water['config_name']);	
		$this->addItem($water);
		$this->output();
	}


	public function update()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(empty($this->input['id']))
		{
			$this->errorOutput('水印名称不能为空');
		}
		if(empty($this->input['config_name']))
		{
			$this->errorOutput('配置名称不能为空');
		}

		if(intval($this->input['water_type'])==1 && empty($this->input['water_filename']))
		{
			$this->errorOutput('水印图片不能为空');
		}

		if(intval($this->input['water_type'])==0 && empty($this->input['water_text']))
		{
			$this->errorOutput('水印文字不能为空');
		}
		$id = intval($this->input['id']);
		$config_name = urldecode($this->input['config_name']);	
		switch(intval($this->input['water_type']))
		{
			case 0://文字水印
				if(empty($this->input['water_text']))
				{
					$this->errorOutput('水印文字不能为空');
				}
				$water = array(
					'config_name'=> $config_name,
					'type' =>0,
					'position' => intval($this->input['get_photo_waterpos']),
					'filename' =>'',
					'margin_x'=> intval($this->input['margin_x']),
					'margin_y'=> intval($this->input['margin_y']),
					'condition_x' => intval($this->input['condition_x']),
					'condition_y' => intval($this->input['condition_y']),
					'water_text' => urldecode($this->input['water_text']),
					'water_angle' => intval($this->input['water_angle']) ? intval($this->input['water_angle']) : 1,
					'water_font' => urldecode($this->input['water_font']),
					'font_size' => intval($this->input['font_size']),
					'opacity' => urldecode($this->input['opacity']),
					'water_color' => urldecode($this->input['water_color']),
					'global_default' => $this->input['default'] ? 1 : '',
				);
				$ret = $this->obj->update($water,$id);
				if($water['global_default'])
				{
					$this->obj->update_water_nodefault($id);
				}
			break;
		    case 1:  //图片水印
				if(empty($this->input['water_filename']))
				{
					$this->errorOutput('水印图片不能为空');
				}
				$water_name = urldecode($this->input['water_filename']);
				$water = array(
					'config_name'=>$config_name,
					'type' => 1,
					'position' => intval($this->input['get_photo_waterpos']),
					'filename' => $water_name,
					'margin_x'=> intval($this->input['margin_x']),
					'margin_y'=> intval($this->input['margin_y']),
					'condition_x' => intval($this->input['condition_x']),
					'condition_y' => intval($this->input['condition_y']),
					'water_text' =>'',
					'water_angle' =>'',
					'water_font' =>'',
					'font_size' => '',
					'opacity' => urldecode($this->input['opacity']),
					'water_color' =>'',
					'global_default' => $this->input['default'] ? 1 : '',
				);
				$ret = $this->obj->update($water,$id);
				if($water['global_default'])
				{
					$this->obj->update_water_nodefault($id);
				}
				//移动水印图片
				$temp_file = hg_getimg_default_dir() . MATERIAL_TMP_PATH . $water_name;
				$path = hg_getimg_default_dir() . WATER_PATH;
				if(!hg_mkdir($path))
				{
					return false;
				}
				if(file_exists($temp_file))
				{
					@copy($temp_file,$path . $water_name);
				}
				break;
			default:
				break;
		}
		hg_unlink_file(CACHE_DIR,'.water.cache.php');  //删除水印缓存文件
		if($ret)   //修改成功则记录日志
		{ 
			$data = array(
				'update_time' => TIMENOW,
			);
			$this->obj->update($data,$id);
			$this->addLogs('修改水印配置','',$water,$water['config_name']);
		}		
		$this->addItem('success');
		$this->output();		
	}


	public function delete()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if(!$this->input['id'])
		{
			$this->errorOutput('ID不能为空');
		}
		$ret = $this->obj->delete($this->input['id']);
		$this->addLogs('删除水印配置','','','删除水印配置+' . $ret);
		$this->addItem($ret);
		$this->output();
	}
	public function water_upload()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		if($_FILES['Filedata'])
		{
			if(!$_FILES['Filedata']['error'])
			{
				$return = $this->obj->water_upload();
				if(!$return)
				{
					$this->errorOutput('上传失败');
				}
				else
				{
					$this->addItem($return);
					$this->output();
				}
			}
			else
			{
				$this->errorOutput('上传失败');
			}
		}
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

	public function unknow()
	{
		
		$this->errorOutput("此方法不存在！");
	}

}

$out = new waterUpdateApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			
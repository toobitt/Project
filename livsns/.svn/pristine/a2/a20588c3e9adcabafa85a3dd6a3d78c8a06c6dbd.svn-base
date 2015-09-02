<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
*
* $Id: material_update.php 6577 2012-04-26 07:04:14Z wangleyuan $
***************************************************************************/
define('MOD_UNIQUEID','material');
require_once('global.php');
class materialUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/material.class.php');
		$this->obj = new material();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}	
	public function publish(){}
	
	//nousing
	function delMaterialByCid()
	{
		$id = urldecode($this->input['id']) ;
		$app_bundle = urldecode($this->input['app_bundle']);
		$module_bundle = urldecode($this->input['module_bundle']);
		if(empty($id))
		{
			$this->errorOutput("未传入内容ID");
		}
		$ret = $this->obj->delMaterialByCid($id,$app_bundle);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput("删除附件失败");
		}
	}
//using
	function delMaterialByMid()
	{
		$id = urldecode($this->input['id']);
		$app_bundle = urldecode($this->input['app_bundle']);
		$module_bundle = urldecode($this->input['module_bundle']);
		if(empty($id))
		{
			$this->errorOutput("未传入素材ID");
		}
		$ret = $this->obj->delMaterialByMid($id,$app_bundle);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput("删除附件失败");
		}
	}
//using
	function delMaterialByUrl()
	{
		$url = urldecode($this->input['id']);
		if(!$url)
		{
			$this->errorOutput('NOURL');
		}
		$ret = $this->obj->delMaterialByUrl();
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('删除失败');
		}
		
	}
//using
	function deleteMaterialState()
	{
		$id = urldecode($this->input['material_id']);
		$app_bundle = urldecode($this->input['app_bundle']);
		if(empty($id))
		{
			$this->errorOutput("未传入素材ID状态");
		}
		$ret = $this->obj->deleteMaterialState($id,$app_bundle);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput("删除附件状态失败");
		}
	}
//using
	function recoverMaterialState()
	{
		$id = urldecode($this->input['material_id']);
		$app_bundle = urldecode($this->input['app_bundle']);
		if(empty($id))
		{
			$this->errorOutput("未传入素材ID状态");
		}
		$ret = $this->obj->recoverMaterialState($id,$app_bundle);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput("恢复附件状态失败");
		}
	}
//using
	function localMaterial()
	{
		if(empty($this->input['url']))
		{
			$this->errorOutput("请传入url");
		}
		$info = $this->obj->localMaterial(urldecode($this->input['url']),intval($this->input['cid']),intval($this->input['catid']),urldecode($this->input['app_bundle']),urldecode($this->input['module_bundle']),'',$this->input['speial_type']);
		if(empty($info))
		{
			$this->errorOutput("附件插入失败");
		}
		else
		{	
			$this->addItem($info);
			$this->output();
		}
	}
	
//using 
	function addMaterial()
	{
		if($_FILES['Filedata'])
		{
			if(!$_FILES['Filedata']['error'])
			{
				if(!$this->input['imgzip'])
				{
					$return = $this->obj->addMaterial();
					if(empty($return))
					{
						$this->errorOutput('上传失败！');
					}
					else
					{
						$this->addItem($return);
						$this->output();
					}
				}
				else
				{
					$this->unzip_img();//处理图片要缩包的上传
				}
			}
			else 
			{
				$this->errorOutput('上传失败！');
			}
		}
	}
	
	//解压图片压缩包
	public function unzip_img()
	{
		//首先验证要缩包是不是zip
		$file = $_FILES['Filedata'];
		$typetmp = explode('.',$file['name']);
		$filetype = strtolower($typetmp[count($typetmp)-1]);
		if($filetype != 'zip')
		{
			$this->errorOutput('请上传zip格式的压缩包');
		}
		//创建临时目录存放解压文件
		$tmp_dir = hg_getimg_dir() . 'ziptmp/';
		if (!hg_mkdir($tmp_dir) || !is_writeable($tmp_dir))
		{
			$this->errorOutput($tmp_dir . '目录不可写');
		}
		$filepath = $tmp_dir . 'zipimg_' . TIMENOW . '.' . $filetype;
		if(!move_uploaded_file($file['tmp_name'], $filepath))
		{
			$this->errorOutput('zip包移动失败');
		}
		
		//开始解压
		$uzip_dir = $tmp_dir . TIMENOW . '/';//解压后存放文件的目录
		if (!hg_mkdir($uzip_dir) || !is_writeable($uzip_dir))
		{
			$this->errorOutput($uzip_dir . '目录不可写');
		}
		$unzip_cmd = ' unzip ' . $filepath . ' -d ' . realpath($uzip_dir);
		exec($unzip_cmd);
		
		//解压后遍历读取文件,将文件路径存放倒数组中
		$img_arr = array();
		$img_info = array();//存放图片信息的数组
		$this->read_file(realpath($uzip_dir),$img_arr);
		if($img_arr && !empty($img_arr))
		{
			@unlink($filepath);//删除zip文件
			$url = implode(',',$img_arr);
			$info = $this->obj->localMaterial($url,intval($this->input['cid']),intval($this->input['catid']),urldecode($this->input['app_bundle']),urldecode($this->input['module_bundle']),true);
			$this->rm_file(realpath($uzip_dir));//删除对应的目录
			if($info)
			{
				foreach($info as $k => $v)
				{
					if($v)
					{
						foreach ($v as $kk => $vv)
						{
							if(in_array($kk, $img_arr))
							{
								unset($info[$k][$kk]);
							}
						}
					}
				}
				$this->addItem($info);
				$this->output();
			}
		}
	}
	
	//递归读取目录里面的所有文件
	private function read_file($path,&$img_arr)
	{
		if ($handle = opendir($path))//打开路径成功  
        {
            while ($file = readdir($handle))//循环读取目录中的文件名并赋值给$file  
            {
                if ($file != '.' && $file != '..')//排除当前路径和前一路径  
                {
                    if (is_dir($path."/".$file))  
                    {
                        $this->read_file($path . '/' . $file,$img_arr);
                    }  
                    else  
                    {
                    	if($this->check_type($file) && $file[0] != '.')//只取出图片类型的图片,并且屏蔽隐藏文件
                    	{
	                    	 $img_arr[] = realpath($path . '/' . $file);
                    	}
                    }  
                }  
            }
            closedir($handle);
        }
	}
	
	private function check_type($path)
	{
		$type_config = array('jpg','png','gif','jpeg','bmp');
		$typetmp = explode('.',$path);
		$filetype = strtolower($typetmp[count($typetmp)-1]);
		return in_array($filetype,$type_config)?1:0;
	}
	
	//递归删除文件以及目录
	private function rm_file($path)
	{
		if ($handle = opendir($path))//打开路径成功  
        {
            while ($file = readdir($handle))//循环读取目录中的文件名并赋值给$file  
            {
                if ($file != '.' && $file != '..')//排除当前路径和前一路径  
                {
                    if (is_dir($path."/".$file))  
                    {
                        $this->rm_file($path . '/' . $file);
                    }  
                    else  
                    {
	                    @unlink($path . '/' . $file);
                    }  
                }  
            }
            @rmdir($path);
            closedir($handle);
        }
        
	} 

//using
	function updateMaterial()
	{
		if(!$this->input['material_id'])
		{
			$this->errorOutput("素材ID不存在");
		}
		if(!$this->input['cid'])
		{
			$this->errorOutput("内容ID不存在");
		}
		if(!$this->input['app_bundle'])
		{
			$this->errorOutput("应用标识不存在");
		}
		if(!$this->input['module_bundle'])
		{
			$this->errorOutput("模块标识不存在");
		}
		$material_id = urldecode($this->input['material_id']);
		$cid = intval($this->input['cid']);
		$catid = intval($this->input['catid']);
		$app_bundle = urldecode($this->input['app_bundle']);
		$module_bundle = urldecode($this->input['module_bundle']);
		$ret = $this->obj->updateMaterial($material_id, $cid, $app_bundle, $module_bundle);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput("创建文章时修改附件表失败");
		}
	}


	//using 
	function insertMaterialWater()
	{
		if(empty($this->input['app_bundle']))
		{
			$this->errorOutput('应用标识不存在');
		}
//		if(empty($this->input['module_bundle']))
//		{
//			$this->errorOutput('模块标识不存在');
//		}
		$ret = $this->obj->insertMaterialWater(urldecode($this->input['app_bundle']),urldecode($this->input['module_bundle']), urldecode($this->input['catid']), intval($this->input['cid']), $this->input['water_id']);
		$this->addItem($ret);
		$this->output();
	}

    /**
     * 获取水印设置
     *
     * @param app_uniqueid 应用标识
     * @param mod_uniqueid 模块标识
     * @param catid  分类id
     * @param cid    内容id
     */
    public function fetchWatermarkSet()
    {
        if (!$this->input['app_uniqueid'])
        {
            $this->errorOutput('NO_APP_UNIQUEID');
        }
        $app_uniqueid = $this->input['app_uniqueid'];
        $mod_uniqueid = $this->input['mod_uniqueid'];
        $catid = $this->input['catid'];
        $cid = intval($this->input['cid']);
        $sql = "SELECT * FROM " . DB_PREFIX ."water_material WHERE bundle_id='".$app_uniqueid."' AND mid='".$mod_uniqueid."' AND  catid='".$catid."' AND cid=".$cid;
        $info = $this->db->query_first($sql);
        $ret = array();
        $ret['watermark_id'] =  $info['water_id'];
        include_once (CUR_CONF_PATH . 'lib/water.class.php');
        $this->water = new water();
        $ret['watermark_list'] = $this->water->show();
        $this->addItem($ret);
        $this->output();
    }

	//using
	function revolveImg()
	{
//		if(empty($this->input['app_bundle']))
//		{
//			$this->errorOutput('应用标示不能为空');
//		}
		if(empty($this->input['material_id']))
		{
			$this->errorOutput('附件ID不能为空');
		}
		if(empty($this->input['direction']))
		{
			$this->errorOutput('旋转方向不能为空');
		}
		$ret = $this->obj->revolveImg(urldecode($this->input['app_bundle']),intval($this->input['material_id']),intval($this->input['direction']));
		$this->addItem($ret);
		$this->output();
	}
	
//using
	function addMaterialNodb()
	{
		if(empty($this->input['type']))
		{
			$this->errorOutput('类型不能为空');
		}
		if(empty($this->input['dir']))
		{
			$this->errorOutput('路径不能为空');
		}
		if(intval($this->input['type']) == 1)
		{
			if(empty($this->input['url']))
			{
				$this->errorOutput('原图地址不能为空');
			}
		}
		$return = $this->obj->addMaterialNodb();
		$this->addItem($return);
		$this->output();
	}

	//using
	/**
	 * 根据路径、文件名删除图片
	 * Enter description here ...
	 */
	function delMaterialNodb()
	{
		if(empty($this->input['path']))
		{
			$this->errorOutput(NOPATH);
		}
		if(empty($this->input['filename']))
		{
			$this->errorOutput(NONAME);
		}
		$path = $this->input['path'];
		$filename = $this->input['filename'];
		hg_editTrue_material(hg_getimg_default_dir() . $path, $filename);
		hg_delete_material(hg_getimg_default_dir() . $path , $filename);
		$this->addItem('true');
		$this->output();
	}

	//using
	/**
	 * 替换图片
	 */
	function replaceImg()
	{
		if(empty($this->input['imgdata']) && !$this->input['url'])
		{
			$this->errorOutput(NODATE);
		}
		if(empty($this->input['oldurl']))
		{
			$this->errorOutput(NOURL);
		}
		$return = $this->obj->replaceImg($this->input['imgdata'],$this->input['oldurl'], $this->input['url']);
		$this->addItem($return);
		$this->output();
	}
	
	//using
	/**
	 * 判断图片是否编辑过
	 */
	function editedImg()
	{
		if(empty($this->input['url']))
		{
			$this->errorOutput(NOURL);
		}
		$return = $this->obj->editedImg($this->input['url']);
		$this->addItem($return);
		$this->output();
	} 
	
	//using
	/**
	 * 对编辑过的图片进行还原 
	 */
	function recoverImg()
	{
		if(empty($this->input['url']))
		{
			$this->errorOutput(NOURL);
		}
		$return = $this->obj->recoverImg($this->input['url']);
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 图片二进制数据转换为图片
	 */
	function imgdata2pic()
	{
		if(empty($this->input['imgdata']))
		{
			$this->errorOutput(NODATE);
		}
		$return = $this->obj->imgdata2pic($this->input['imgdata'],$this->input['app_bundle'],$this->input['type']);
		$this->addItem($return);
		$this->output();
	}
	
	function update_material_num()
	{
		if(!$this->input['material_id'])
		{
			$this->errorOutput('material_id不能为空');
		}
		$material_id = urldecode($this->input['material_id']);
		$sql = "UPDATE ".DB_PREFIX."materail SET nums = nums+1 WHERE id IN(".$material_id.")";
		$this->db->query($sql);
		$this->addItem(true);
		$this->output();
	}
    
    
	function change_material_water() {
	   if (!$this->input['material_id'])  {
	       $this->errorOutput('material_id不能为空');
	   }
       
       $material_id = $this->input['material_id'];
       $water_id = intval($this->input['water_id']);
       
       $material_id = explode(',', $material_id);
       $material_id = implode("','", $material_id);
       $sql = "UPDATE " . DB_PREFIX . "material SET water_id=" . $water_id . " WHERE id IN ('" . $material_id . "')";
       $this->db->query($sql);     
       
       $sql = "SELECT * FROM ".DB_PREFIX."material WHERE id IN('".$material_id."')";
       $q = $this->db->query($sql);
       while ($row = $this->db->fetch_array($q)) {
           $this->obj->deleteMaterialThumb($row['bundle_id'], $row['filepath'], $row['filename'], $row["bs"]);
           $this->obj->createFile($row['water_id'], $row['bundle_id'], $row['filepath'], $row['filename'], $row["bs"]);
       }
       
       $this->addItem(true);
       $this->output();
	}
    
    /**
     * 将附件打包提供下载
     * 
     * @param material_path string 图片上传返回的路径信息(取出host)
     * @return string 包得地址 
     */
    function zip_material() {
        $material_path = $this->input['material_path'];
        if(empty($material_path)) {
            $this->errorOutput(NO_MATERIAL);
        }
        if (!is_array($material_path)) {
            $material_path = explode(',', $material_path);
        }
        
        foreach((array) $material_path as $k => $v) {
            //$v = preg_replace('/(.*?)(\?.*)?/i', '\\1', $v);
            $pos = strpos($v, '?');
            if ($pos !== false) {
                $v = substr($v, 0, $pos);
            }
            if($domain == $v)
            {
	            $domain = '';
            }
            $material_path[$k] = hg_getimg_dir($domain, "host"). str_replace($domain, '', $v);
        }
        $material_path = implode(' ', $material_path);
        $path = 'ziptmp/' . date('Ym', TIMENOW) . '/';
        $zip_path = hg_getimg_dir() . $path;
        if (!hg_mkdir($zip_path) || !is_writeable($zip_path))
        {
            $this->errorOutput('压缩文件存放临时目录不可写');
        }
        $zip_name = TIMENOW . hg_generate_user_salt(). '.zip';        
        $zipcmd = ' zip -j ' . $zip_path . $zip_name . ' ' . $material_path;
        exec($zipcmd);
        
        $url = hg_getimg_host() . $path . $zip_name;
        $this->addItem($url);
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

	protected function verifyToken()
	{
	}
}

$out = new materialUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>
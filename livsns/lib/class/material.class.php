<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: material.class.php 43182 2014-12-26 09:05:00Z tandx $
***************************************************************************/
class material
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_material']['host'], $gGlobalConfig['App_material']['dir'] . 'admin/' );
	}
	function __destruct()
	{
	}
    /**
     * 删除素材
     * @param int $id 根据素材id删除素材
     * 
     */
	public function delMaterialById($id,$type = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delMaterialByMid');
		$this->curl->addRequestData('id',$id);
		$this->curl->addRequestData('app_bundle',APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle',MOD_UNIQUEID);
		$this->curl->request('material_update.php');
	}
	
	/**
	 * 根据素材ur删除图片
	 * @param string $url 素材地址
	 * 
	 */
	public function delMaterialByUrl($url)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('url',$url);
		$this->curl->addRequestData('a','delMaterialByUrl');
		$this->curl->request('material_update.php');		
	}
	
	/**
	 * 根据文件路径、文件名删除图片
	 * @param $path 文件路径
	 * @param $filename 文件名
	 * @return 
	 */
	public function delMaterialNodb($path, $filename)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delMaterialNodb');
		$this->curl->addRequestData('path', $path);
		$this->curl->addRequestData('filename', $filename);
		$ret = $this->curl->request('material_update.php');
		return $ret;		
	}		
	
	/**
	 * 素材软删除
	 * @param int $material_id 素材id
	 */
	public function deleteMaterialState($material_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','deleteMaterialState');
		$this->curl->addRequestData('material_id',$material_id);
		$this->curl->addRequestData('app_bundle',APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle',MOD_UNIQUEID);
		return $this->curl->request('material_update.php');
	}

	/**
	 * 还原素材
	 * @param int $material_id 素材id
	 */
	public function recoverMaterialState($material_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','recoverMaterialState');
		$this->curl->addRequestData('material_id',$material_id);
		$this->curl->addRequestData('app_bundle',APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle',MOD_UNIQUEID);
		return $this->curl->request('material_update.php');
	}
	/**
	 * 远程附件本地化接口
	 * @param string $url 附件url
	 * @param int $cid 所属内容id,默认为0
	 * @param int $catid 所属分类id,默认为0
	 * @param int $water_id 使用的水印id,默认继承水印,-1不使用水印
	 * @return array 本地化后附件信息
	 * 
	 */
	public function localMaterial($url, $cid='', $catid='', $water_id='',$speial_type = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','localMaterial');
		$this->curl->addRequestData('url', $url);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('catid', $catid);
		$this->curl->addRequestData('speial_type', $speial_type);
		$this->curl->addRequestData('app_bundle',APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle',MOD_UNIQUEID);
		$this->curl->addRequestData('client_id',intval($this->user['appid']));
		$this->curl->addRequestData('client_name',urldecode($this->user['display_name']));
		$this->curl->addRequestData('water_id', $water_id);
		$ret = $this->curl->request('material_update.php');
		if(count($ret) == 1)
		{
			return $ret[0];
		}
		return $ret;
	}
	/**
	 * 文件上传接口
	 * @param array $file $_FILES数组
	 * @param int $cid 文件所属内容id
	 * @param int $catid 文件所属分类id
	 * @param int $water_id 水印id
	 * @param boolean $imgzip 是否需要解压
	 * @return array 上传后的文件信息
	 */
	public function addMaterial($file, $cid='', $catid='', $water_id='',$imgzip = '', $trans_format = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','addMaterial');
		$this->curl->addRequestData('cid',$cid);
		$this->curl->addRequestData('catid',$catid);
		$this->curl->addRequestData('app_bundle',APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle',MOD_UNIQUEID);
		$this->curl->addRequestData('water_id',$water_id);
		$this->curl->addRequestData('imgzip',$imgzip);
        $this->curl->addRequestData('trans_format',$trans_format);
		$this->curl->addFile($file);
		$ret = $this->curl->request('material_update.php');
		return $ret[0];
	}
	
	/**
	 * 指定路径上传文件，不存入数据库
	 * @param  	   $data 值和type有关 1->文件url, 2->$_FILES数组
	 * @param  int $type 上传类型  1 -> url , 2-> file上传
	 * @param  string $dir 文件存放的路径
	 * @param  string $newname 上传后使用的文件名
	 * @return array 文件信息
	 */
	public function addMaterialNodb($data, $type, $dir, $newname = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'addMaterialNodb');
		$this->curl->addRequestData('dir', $dir);
		$this->curl->addRequestData('type', $type);
		if($type == 1)
		{
			$this->curl->addRequestData('url', $data);
		}
		else if($type == 2)
		{
			$this->curl->addFile($data);
		}
		$this->curl->addRequestData('name', $newname);
		$ret = $this->curl->request('material_update.php');
		return $ret[0];
	}
	/**
	 * 更新素材的内容id
	 * @param int $material_id 素材id
	 * @param int $cid 内容id
	 * @param int $catid 分类id
	 */
	public function updateMaterial($material_id,$cid,$catid='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','updateMaterial');
		$this->curl->addRequestData('material_id',$material_id);
		$this->curl->addRequestData('cid',$cid);
		$this->curl->addRequestData('catid',$catid);
		$this->curl->addRequestData('app_bundle',APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle',MOD_UNIQUEID);
		$this->curl->request('material_update.php');
	}
	
	/**
	 * 更新素材的使用次数nums
	 * @param int $material_id 素材id
	 */
	public function updateMaterialNum($material_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update_material_num');
		$this->curl->addRequestData('material_id',$material_id);
		$this->curl->addRequestData('app_bundle',APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle',MOD_UNIQUEID);
		$this->curl->request('material_update.php');
	}	
	/**
	 * 图片水印列表
	 */
	public function waterSystem()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','waterSystem');
		$ret=$this->curl->request('water.php');
		return $ret;
	}
	/**
	 * 入水印关系表
	 */
	public function insertMaterialWater($catid='',$cid='',$water_id='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('catid', $catid);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('water_id', $water_id);
		$this->curl->addRequestData('app_bundle',APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle',MOD_UNIQUEID);
		$this->curl->addRequestData('a','insertMaterialWater');
		$ret = $this->curl->request('material_update.php');
		return $ret;
	}

    /**
     * 设置水印关系
     */
    public function setMaterialWater($water_id, $app_uniqueid = APP_UNIQUEID, $mod_uniqueid = '', $catid='',$cid='')
    {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('catid', $catid);
        $this->curl->addRequestData('cid', $cid);
        $this->curl->addRequestData('water_id', $water_id);
        $this->curl->addRequestData('app_bundle',$app_uniqueid);
        $this->curl->addRequestData('module_bundle',$mod_uniqueid);
        $this->curl->addRequestData('a','insertMaterialWater');
        $ret = $this->curl->request('material_update.php');
        return $ret;
    }

	/**
	 * 上传水印图片
	 * @param  array $file $_FILES数组
	 */
	public function upload_water($file)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'water_upload');
		$this->curl->addFile($file);
		$ret = $this->curl->request('water_update.php');
		if(count($ret) == 1)
		{
			return $ret[0];
		}
		return $ret;
	}
	/**
	 * 添加水印配置
	 * @param array $water_info 水印信息
	 */
	public function create_water_config($water_info)
	{
		if(!is_array($water_info))
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		foreach ($water_info as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$ret = $this->curl->request('water_update.php');
		if(count($ret) == 1)
		{
			return $ret[0];
		}
		return $ret;
	}
	/**
	 * 获取水印配置列表
	 */
	public function water_config_list()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$ret = $this->curl->request('water.php');
		return $ret;
	}
	/**
	 * 旋转图片 ...
	 * @param int $material_id  素材
	 * @param int $direction	方向 1左旋转  2右旋转
	 */
	public function revolveImg($material_id,$direction)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','revolveImg');
		$this->curl->addRequestData('material_id',$material_id);
		$this->curl->addRequestData('direction',$direction);
		$this->curl->addRequestData('app_bundle',APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle',MOD_UNIQUEID);
		$ret = $this->curl->request('material_update.php');
		return $ret[0];
	}
	/**
	 * 获取准许上传的附件类型
	 * @return array 准许上传的附件类型
	 */
	public function get_allow_type()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','check_cache');
		$ret = $this->curl->request('cache.php');
		return	$ret[0];
	}	
	/**
	 * 获取引用素材缩略图
	 * @param string $scrPath 缩略图路径
	 * @param string $newName 生成缩略图名称
	 * @param string or array $title 标题或投票选项
	 * @param string $type  引用素材类型
	 */
	public function create_sketch_map($srcPath,$newName,$title,$type)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create_map_'.$type);
		$this->curl->addRequestData('srcFile', $srcPath);
		$this->curl->addRequestData('name', $newName);
		$this->curl->addRequestData('title', $title);
		$this->curl->addRequestData('app_bundle', APP_UNIQUEID);
		$this->curl->addRequestData('module_bundle', MOD_UNIQUEID);
		$ret = $this->curl->request('sketch_map.php');
		return is_array($ret) ? $ret[0] : ''; 
	}	
	/**
	 * 根据图片二进制数据生成图片
	 * @param $imgdata 图片二进制数据
	 * @return 
	 */
	public function imgdata2pic($imgdata,$type = 'png')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'imgdata2pic');
		$this->curl->addRequestData('imgdata', $imgdata);
		$this->curl->addRequestData('app_bundle', APP_UNIQUEID);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('material_update.php');
		return $ret;
	}

    public function get_material_by_ids($material_ids)
    {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_material_by_ids');
        $this->curl->addRequestData('material_ids', $material_ids);
        $ret = $this->curl->request('material.php');
        return $ret[0];
    }

    public function change_material_water($material_ids, $water_id)
    {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'change_material_water');
        $this->curl->addRequestData('material_id', $material_ids);
        $this->curl->addRequestData('water_id', $water_id);
        $this->curl->addRequestData('app_bundle', APP_UNIQUEID);
        $this->curl->addRequestData('module_bundle', MOD_UNIQUEID);
        $ret = $this->curl->request('material_update.php');
        return $ret;
    }
    
    /**
     * 将附件打zip包 
     * 
     * @param string 附件上传返回地址(去掉host)  多个用半角逗号隔开
     * 
     * @return string 包地址
     */
    public function zip_material($material_path) {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'zip_material');
        $this->curl->addRequestData('material_path', $material_path);
        $this->curl->addRequestData('app_bundle', APP_UNIQUEID);
        $this->curl->addRequestData('module_bundle', MOD_UNIQUEID);
        $ret = $this->curl->request('material_update.php');
        return $ret;        
    }

    /**
     * 替换图片
     *
     * @param string oriurl 需要替换图片地址
     * @param string $url  图片地址
     * @param string $base64_data  图片base64加密数据
     * @return string
     */
    public function replaceImg($oriurl, $url = '', $base64_data = '') {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'replaceImg');
        $this->curl->addRequestData('oldurl', $oriurl);
        $this->curl->addRequestData('url', $url);
        $this->curl->addRequestData('imgdata', $base64_data);
        $this->curl->addRequestData('app_bundle', APP_UNIQUEID);
        $this->curl->addRequestData('module_bundle', MOD_UNIQUEID);
        $ret = $this->curl->request('material_update.php');
        return $ret;
    }
    
}

?>
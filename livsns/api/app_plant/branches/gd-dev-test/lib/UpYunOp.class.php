<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description UPYUN 操作类（主要是对upyun.class.php的二次封装方便操作）
 **************************************************************************/
require_once(dirname(__FILE__) . '/upyun/UpYun.class.php');

class UpYunOp extends InitFrm
{
	private $_upyun;
	public function __construct()
	{
		parent::__construct();
		$this->_upyun = new UpYun($this->settings['upyun']['bucket'],$this->settings['upyun']['username'],$this->settings['upyun']['password']);
	}
	
	/**
     * 根据id获取图片信息
     *
     * @access public
     * @param  id 多个用逗号分隔
     *         
     * @return ARRAY | BOOL
     */
	public function getPicInfoById($ids = '',$cond = '')
	{
	    if( !$ids && !$cond)
	    {
	        return FALSE;
	    }
	    
	    //根据id查询图片
	    if($ids)
	    {
	        $sql = " SELECT * FROM " .DB_PREFIX. "app_material WHERE id IN (" .$ids. ")";
	    }
	    else 
	    {
	        $sql = " SELECT * FROM " .DB_PREFIX. "app_material WHERE 1 " . $cond;
	    }
	    $q   = $this->db->query($sql);
	    $imgInfo  = array();
	    while ($r = $this->db->fetch_array($q))
	    {
	        $imgInfo[] = $r;
	    }
	    return $imgInfo;
	}
	
	/**
     * 上传图片到空间(如果第二个参数存在就覆盖这张图)
     *
     * @access public
     * @param  $files 图片文件
     *         $originalPath:原始路径（这个路径是标是已经上传到upyun上的图片路径，主要用户更新upyun上面的图片，如果不存在就是在upyun上新创建）
     * @return success:array | fail:FALSE
     */
	public function uploadToBucket($files,$originalPath = '',$user_id = '')
	{
		if($files && !$files['error'])
		{
			//移动到临时目录
			$dirArr = Common::buildDirStruct($user_id);
			$base_dir =  CUR_CONF_PATH . 'cache/upyun_tmp/';
			$pic_type =  strtolower(strrchr($files['name'], '.'));
			$filePath = $base_dir . $dirArr[1] . $dirArr[0] . $pic_type;
			if (!hg_mkdir($base_dir . $dirArr[1]) || !is_writeable($base_dir . $dirArr[1]))
			{
				return FALSE;
			}
			
			if(move_uploaded_file($files['tmp_name'],$filePath))
			{
				if($originalPath)
				{
					$_pathinfo = pathinfo($originalPath);
					$dirArr[0] = $_pathinfo['filename'];
					$dirArr[1] = $_pathinfo['dirname'] . '/';
					$pic_type  = '.'.$_pathinfo['extension'];
				}
				
				//上传到upyun
				$fh = fopen($filePath, 'rb');
			    $rsp = $this->_upyun->writeFile('/' . $dirArr[1] . $dirArr[0] . $pic_type, $fh, True);
			    fclose($fh);
			    if($rsp)
			    {
			        //获取图片的尺寸
			        $imageSize = getimagesize($filePath);
			    	$img_info = array(
			    	    'name'	       => $files['name'],//原名称
			    	    'host'	       => $this->settings['upyun']['host'],
			    	    'dir'	       => '',
			    	    'filepath'     => $dirArr[1],
			    	    'filename'     => $dirArr[0] . $pic_type,//新的名称
			    	    'mark'	       => 'img',
			    	    'imgwidth'     => $imageSize[0],//图片宽度
			    	    'imgheight'    => $imageSize[1],//图片高度
			    	    'filesize'     => $files['size'],//图片文件大小
			    	    'type'	       => ltrim($pic_type,'.'),
			    	    'ip'	       => hg_getip(),
			    	    'create_time'  => TIMENOW,
			    	    'url'	       => $this->settings['upyun']['host'] . $dirArr[1] . $dirArr[0] . $pic_type,
			    	);
			    	//将本地的图片删除
			    	@unlink($filePath);
			    	return $img_info;
			    }
			    return FALSE;
			}
			return FALSE;
		}
	}
	
	/**
     * 从upyun删除某张图片
     *
     * @access public
     * @param  $filePath UPYUN上的文件路径
     *
     * @return BOOL
     */
	public function deletePicFromUpYun($filePath = '')
	{
		if($this->_upyun->delete($filePath))
		{
			return TRUE;
		}
		return FALSE;
	}
	
	/**
     * 通过zip打包上传
     *
     * @access public
     * @param  $filePath UPYUN上的文件路径
     *
     * @return BOOL | ARRAY 
     */
	public function uploadPicWithZip($file,$user_id = '')
	{
	    //首先验证压缩包是不是zip
		$typetmp  = explode('.',$file['name']);
		$filetype = strtolower($typetmp[count($typetmp)-1]);
		if($filetype != 'zip')
		{
			return FALSE;
		}
		
		//创建临时目录存放解压文件
		$tmpDir = CACHE_DIR . 'ziptmp/';
		if (!hg_mkdir($tmpDir) || !is_writeable($tmpDir))
		{
			return FALSE;
		}
		
		$filepath = $tmpDir . 'zipimg_' . TIMENOW . '.' . $filetype;
		if(!move_uploaded_file($file['tmp_name'], $filepath))
		{
			return FALSE;
		}
		
		//开始解压
		$uzipDir = $tmpDir . TIMENOW . '/';//解压后存放文件的目录
		if (!hg_mkdir($uzipDir) || !is_writeable($uzipDir))
		{
			return FALSE;
		}
		
		$unzipCmd = ' unzip ' . $filepath . ' -d ' . realpath($uzipDir);
		exec($unzipCmd);
		
		//解压后遍历读取文件,将文件路径存放倒数组中
		$imgArr = array();
		$imgInfo = array();//存放图片信息的数组
		$this->_readFiles(realpath($uzipDir),$imgArr);
		if($imgArr && !empty($imgArr))
		{
			@unlink($filepath);//删除zip文件
			//循环遍历图片路径，一次提交到upyun
			foreach ($imgArr AS $k => $v)
			{
			    //产生一个目录结构
			    $dirArr  = Common::buildDirStruct($user_id);
    			$picType = pathinfo($v, PATHINFO_EXTENSION);
			    //上传到upyun
				$fh = fopen($v, 'rb');
			    $rsp = $this->_upyun->writeFile('/' . $dirArr[1] . $dirArr[0] . $picType, $fh, True);
			    fclose($fh);
			    if($rsp)
			    {
			        //获取图片的尺寸
			        $imageSize = getimagesize($v);
			    	$img = array(
			    	    'name'	       => pathinfo($v, PATHINFO_BASENAME),//原名称
			    	    'host'	       => $this->settings['upyun']['host'],
			    	    'dir'	       => '',
			    	    'filepath'     => $dirArr[1],
			    	    'filename'     => $dirArr[0] . $picType,//新的名称
			    	    'mark'	  	   => 'img',
			    	    'imgwidth'     => $imageSize[0],//图片宽度
			    	    'imgheight'    => $imageSize[1],//图片高度
			    	    'filesize'     => filesize($v),//图片文件大小
			    	    'type'	       => ltrim($picType,'.'),
			    	    'ip'	       => hg_getip(),
			    	    'create_time'  => TIMENOW,
			    	    'url'	       => $this->settings['upyun']['host'] . $dirArr[1] . $dirArr[0] . $picType,
			    	);
			    	//将本地的图片删除
			    	@unlink($v);
			    	$imgInfo[] = $img;
			    }
			}
			return $imgInfo;
		}
		return FALSE;	
	}
	
	/**
     * 通过图片url上传到upyun
     *
     * @access private
     * @param  $url 图片链接
     *
     * @return array | BOOL
     */
	public function uploadToBucketByUrl($url = '',$user_id = '',$selfDirArr = array())
	{
	    //首先检测url的合法性
	    if(!$url || !$url = $this->_isAvailablePic($url))
	    {
	        return FALSE;
	    }
	    
	    //产生一个目录结构
	    if($selfDirArr)//如果是自定义的目录
	    {
	        $dirArr[1] = $selfDirArr['filepath'];
	        $dirArr[0] = $selfDirArr['filename'];
	    }
	    else 
	    {
	        $dirArr  = Common::buildDirStruct($user_id);
	    }
		$picType = '.' . pathinfo($url, PATHINFO_EXTENSION);
	    //上传到upyun
		$picData = file_get_contents($url);
	    $rsp = $this->_upyun->writeFile('/' . $dirArr[1] . $dirArr[0] . $picType, $picData, True);
	    if($rsp)
	    {
	        //获取图片的尺寸
	        $imageSize = getimagesize($url);
	        //获取图片的基本信息
	        $picInfo   = $this->_upyun->getFileInfo('/' . $dirArr[1] . $dirArr[0] . $picType);
	    	$img = array(
	    	    'name'	       => pathinfo($url, PATHINFO_BASENAME),//原名称
	    	    'host'	       => $this->settings['upyun']['host'],
	    	    'dir'	       => '',
	    	    'filepath'     => $dirArr[1],
	    	    'filename'     => $dirArr[0] . $picType,//新的名称
	    	    'mark'	  	   => 'img',
	    	    'imgwidth'     => $imageSize[0],//图片宽度
	    	    'imgheight'    => $imageSize[1],//图片高度
	    	    'filesize'     => $picInfo['x-upyun-file-size']?$picInfo['x-upyun-file-size']:0,
	    	    'type'	       => ltrim($picType,'.'),
	    	    'ip'	       => hg_getip(),
	    	    'create_time'  => TIMENOW,
	    	    'url'	       => $this->settings['upyun']['host'] . $dirArr[1] . $dirArr[0] . $picType,
	    	);
	    	return $img;
	    }
	    return FALSE;
	}

	/**
     * 判断一个图片是否可用
     *
     * @access private
     * @param  $imagePath 图片链接
     *
     * @return BOOL | STRING 
     */
	private function _isAvailablePic($imagePath)
	{
		//首先查看所给的图片链接里面有没有?有的话就去除掉
		if(strpos($imagePath,'?'))
		{
		    $image = explode('?',$imagePath);
		    $imagePath = $image[0];
		}
		
		$imageArr = explode('.',$imagePath);
		$imageSuffix = end($imageArr);//取到最后的值;
		$availableImages = array('jpg','png','gif','jpeg');//允许的图片类型
		if(in_array($imageSuffix,$availableImages))
		{
			//测试该图片是否可用
			if(@fopen($imagePath,'r'))
			{
				return $imagePath;
			}
		}
		return FALSE;
	}
	
    /**
     * 递归读取目录里面的所有文件
     *
     * @access private
     * @param  $path 目录路径
     *         &$array 引用保存文件路径
     *
     * @return &array
     */
	private function _readFiles($path,&$array)
	{
		if ( $handle = opendir($path))//打开路径成功  
        {
            while ( $file = readdir($handle))//循环读取目录中的文件名并赋值给$file  
            {
                if ( $file != '.' && $file != '..')//排除当前路径和前一路径  
                {
                    if ( is_dir($path . '/' . $file))
                    {
                        $this->_readFiles($path . '/' . $file,$array);
                    }
                    else
                    {
                    	if ( $this->_checkType($file) && $file[0] != '.')//只取出图片类型的图片,并且屏蔽隐藏文件
                    	{
	                    	 $array[] = realpath($path . '/' . $file);
                    	}
                    }
                }
            }
            closedir($handle);
        }
	}
	
	/**
     * 检测图片类型是不是允许的类型
     *
     * @access private
     * @param  $path 目录路径
     *
     * @return BOOL
     */
	private function _checkType($path)
	{
		$typeConfig = array('jpg','png','gif','jpeg','bmp');
		$typetmp    = explode('.',$path);
		$filetype   = strtolower($typetmp[count($typetmp)-1]);
		return in_array($filetype,$typeConfig)?TRUE:FALSE;
	}
}
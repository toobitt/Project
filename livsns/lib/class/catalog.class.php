<?php
/*
 *	编目curl
 *
 **/
class catalog
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_catalog'])
		{
			$this->curl = new curl($gGlobalConfig['App_catalog']['host'],$gGlobalConfig['App_catalog']['dir']);
		}
	}
	function __destruct()
	{
	}

	public function stofield($content_id='',$catalog_sort)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		if($content_id)
		{
			$this->curl->addRequestData('content_id',$content_id);
		}
		$this->curl->addRequestData('catalog_sort',$catalog_sort);
		$this->curl->addRequestData('a','stofield');
		$ret = $this->curl->request('catalog.php');
		return $ret[0];
	}
	public function ftofield($content_id='',$catalog_sort)
	{
	}

	public function show($content_id='')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		if($content_id)
		{
			$this->curl->addRequestData('content_id',$content_id);
		}
		$ret = $this->curl->request('catalog.php');
		return $ret[0];
	}

	public function sort($content_id='')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		if($content_id)
		{
			$this->curl->addRequestData('content_id',$content_id);
		}
		$this->curl->addRequestData('a','sort');
		$ret = $this->curl->request('catalog.php');
		return $ret[0];
	}
	public function field($content_id='')
	{
	}
	public function detail($content_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		if (!$content_id) return false;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		$this->curl->addRequestData('content_id',$content_id);
		$this->curl->addRequestData('a','detail');
		$ret = $this->curl->request('catalog.php');
		return $ret[0];
	}
	/**
	 * 备用
	 public function no_catalog($content_id='')//检测编目是否被使用完毕或者该应用不存在编目
	 {
		if (!$this->curl)
		{
		return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		$this->curl->addRequestData('content_id',$content_id);
		$this->curl->addRequestData('a','no');
		$ret = $this->curl->request('catalog.php');
		return $ret[0];
		}
		public function yes_catalog($content_id='')//检测编目是否被使用
		{
		if (!$this->curl)
		{
		return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		$this->curl->addRequestData('content_id',$content_id);
		$this->curl->addRequestData('a','yes');
		$ret = $this->curl->request('catalog.php');
		return $ret[0];
		}
		*/
	public function  create($content_id,$input='',$files='')
	{
		if (!$this->curl)
		{
			return array();
		}
		if (!$content_id) return false;
		if (empty($input) && empty($files)) return false;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if(is_array($input) && count($input) > 0)
		{
			foreach($input as $k => $v)
			{
				if(is_array($v))
				{
					$this->array_to_add($k,$v);
				}
				else
				{
					$this->curl->addRequestData($k,$v);
				}
			}
		}
		if(is_array($files) && count($files) > 0)
		{
			$this->curl->addFile($files);
		}
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		$this->curl->addRequestData('content_id', $content_id);
		$this->curl->addRequestData('a','create');
		$ret = $this->curl->request('catalog_update.php');		
		return $ret;

	}
	public function update($content_id,$input='',$files='')
	{
		if (!$this->curl)
		{
			return array();
		}
		if (!$content_id) return false;
		if (empty($input) && empty($files)) return false;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if(is_array($input) && count($input) > 0)
		{
			foreach($input as $k => $v)
			{
				if(is_array($v))
				{
					$this->array_to_add($k,$v);
				}
				else
				{
					$this->curl->addRequestData($k,$v);
				}
			}
		}
		if(is_array($files) && count($files) > 0)
		{
			$this->curl->addFile($files);
		}
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		$this->curl->addRequestData('content_id', $content_id);
		$this->curl->addRequestData('a','update');
		$ret = $this->curl->request('catalog_update.php');
		return $ret;
	}
	/**
	 * file数组// 备用
	 *

	 public function upload($files='')// 备用,暂时此函数无效
	 {
		if (!$this->curl)
		{
		return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addFile($file);
		$this->curl->addRequestData('a', 'upload_tuji_imgs');
		$ret = $this->curl->request('catalog_update.php');
		return $ret[0];
		}
		*/
	public function delete($content_id,$catalog_field='')
	{
		if (!$this->curl)
		{
			return array();
		}
		if (!$content_id) return false;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$this->curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		$this->curl->addRequestData('content_id',$content_id);
		if($catalog_field)
		{
			$this->curl->addRequestData('catalog_field',$catalog_field);
		}
		$this->curl->addRequestData('a','delete');
		$ret = $this->curl->request('catalog_update.php');
		return $ret;
	}
        
    public function get_catalog($bundle_id, $module_id, $content_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$data[$content_id] = array(
		    'app_uniqueid'=>$bundle_id,
		    'mod_uniqueid'=>$module_id,
		    'content_id'=>$content_id,
		);
		$this->array_to_add('data', $data);
		$this->curl->addRequestData('a','getAllcontent');
		$ret = $this->curl->request('catalog.php');
		return $ret[$content_id];
	}
        
     public function getAllcontent($data)
	 {
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->array_to_add('data',$data);
		$this->curl->addRequestData('a','getAllcontent');
		$ret = $this->curl->request('catalog.php');
		return $ret;
      }
	
	public function array_to_add($str, $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
	}
}
?>
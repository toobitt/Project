<?php
class textsearch
{
	function __construct()
	{
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		global $gGlobalConfig;
		if(!$gGlobalConfig['App_textsearch'])
		{
			return false;
		}
		$this->curl = new curl($gGlobalConfig['App_textsearch']['host'], $gGlobalConfig['App_textsearch']['dir'] );
	}

	function __destruct()
	{
	}
	
	//整体导入配置
	public function index($data,$type='add')
	{
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','xs_index');
		$this->curl->addRequestData('html',true);
                if(is_array($data))
                {
                    $this->array_to_add('data' , $data);
                }
                else
                {
                    $this->curl->addRequestData('data' , $data);
                }
		$this->curl->addRequestData('type' , $type);
		$this->curl->addRequestData('bundle_id' , APP_UNIQUEID);
		$this->curl->addRequestData('module_id' , MOD_UNIQUEID);
		$ret = $this->curl->request('textsearch.php');
		return $ret[0];
	}
	
	public function search($data,$array_field,$highlight_field)
	{
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','xs_search');
		$this->curl->addRequestData('html',true);
		$this->array_to_add('searchdata' , $data);
		$this->array_to_add('array_field' , $array_field);
		$this->array_to_add('highlight_field' , $highlight_field);
		$this->curl->addRequestData('bundle_id' , APP_UNIQUEID);
		$this->curl->addRequestData('module_id' , MOD_UNIQUEID);
		$ret = $this->curl->request('textsearch.php');
		return $ret[0];
	}
	
	public function get_keyword($text,$limit,$xattr)
	{
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','xs_get_keyword');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('text' , $text);
		$this->curl->addRequestData('limit' , $limit);
		$this->curl->addRequestData('xattr' , $xattr);
		$this->curl->addRequestData('bundle_id' , APP_UNIQUEID);
		$this->curl->addRequestData('module_id' , MOD_UNIQUEID);
		$ret = $this->curl->request('textsearch.php');
		return $ret[0];
	}
	
	public function get_Result($text)
	{
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','xs_getResult');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('text' , $text);
		$this->curl->addRequestData('bundle_id' , APP_UNIQUEID);
		$this->curl->addRequestData('module_id' , MOD_UNIQUEID);
		$ret = $this->curl->request('textsearch.php');
		return $ret[0];
	}
        
        public function xs_get_hotquery($count)
	{
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','xs_get_hotquery');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('count' , $count);
		$this->curl->addRequestData('bundle_id' , APP_UNIQUEID);
		$this->curl->addRequestData('module_id' , MOD_UNIQUEID);
		$ret = $this->curl->request('textsearch.php');
		return $ret[0];
	}
	
	public function array_to_add($str , $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if(is_array($vv))
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
<?php
class page_manage
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_publishsys']['host'], $gGlobalConfig['App_publishsys']['dir']);
	}

	function __destruct()
	{
	}
	
	public function get_page_manage($site_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_page_manage');
		$this->curl->addRequestData('site_id',$site_id);
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/page_manage.php');
		return $ret[0];
	}
	
	public function get_page_data($page_id, $offset='', $count='', $fid=0, $pinfo = array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_page_data');
		$this->curl->addRequestData('page_id',$page_id);
		$this->curl->addRequestData('offset',$offset);
		$this->curl->addRequestData('count',$count);
		$this->curl->addRequestData('fid',$fid);
		$this->curl->addRequestData('pinfo',$pinfo);
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/page_manage.php');
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
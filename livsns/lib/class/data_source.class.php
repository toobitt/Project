<?php
class dataSource
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
	
	public function showDataSource()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','showDataSource');
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/data_source.php');
		return $ret[0];
	}
	
	public function get_datasource_info($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_datasource_info');
		$this->curl->addRequestData('id',$id);
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/data_source.php');
		return $ret[0];
	}
	
	public function get_content_by_datasource($id,$data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_content_by_datasource');
		$this->curl->addRequestData('id',$id);
		$this->array_to_add('data',$data);
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/data_source.php');
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
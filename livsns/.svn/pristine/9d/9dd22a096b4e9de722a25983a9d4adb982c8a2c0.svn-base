<?php
class publishcontent
{
	private $curl;
	private $client;
	function __construct()
	{
		global $gGlobalConfig;
		$this->site = $gGlobalConfig['v_site'];
		$this->client = $gGlobalConfig['v_client'];
		$this->curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
	}

	function __destruct()
	{
	}
	
	//根据内容ID取内容
	public function get_content($cmid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_content');
		$this->curl->addRequestData('id', $cmid);
		$ret = $this->curl->request('content.php');
		return $ret[0];
	}


	
	//根据条件取内容
	public function get_content_condition($column_id = "",$weight = "",$offset = 0,$count=20,$data=array())        
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_content');
		$this->curl->addRequestData('site_id', $this->site['id']);//默认取当前站点的内容
		$this->curl->addRequestData('column_id', $column_id);
		$this->curl->addRequestData('weight', $weight);
		$this->curl->addRequestData('client_type', $this->client['id']);//只取发布到网站的数据
		$this->curl->addRequestData('offset', $offset);
		$this->curl->addRequestData('count', $count);
		if(!empty($data))
		{
			foreach($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$ret = $this->curl->request('content.php');
		return $ret;
	}
	

	
	public function get_content_around($cid,$data = array())
	{
		global  $siteinfo;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_content_around');
		$this->curl->addRequestData('content_id',$cid);			
		$this->curl->addRequestData('site_id', $siteinfo['siteid']);//默认取当前站点的内容
		$this->curl->addRequestData('client_type', 2);//只取发布到网站的
		if(!empty($data))
		{
			foreach($data as $k => $v)
			{
				$this->curl->addRequestData($k, $v);
			}
		}
		$ret = $this->curl->request('content.php');
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
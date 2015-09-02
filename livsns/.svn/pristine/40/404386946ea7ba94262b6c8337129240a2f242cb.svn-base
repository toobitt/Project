<?php
class logs
{
	function __construct()
	{
		global $gGlobalConfig;
		if($gGlobalConfig['App_logs'])
		{
			include_once (ROOT_PATH . 'lib/class/curl.class.php');
			$this->curl = new curl($gGlobalConfig['App_logs']['host'], $gGlobalConfig['App_logs']['dir'] . 'admin/');
		}
		
	}

	function __destruct()
	{
	}
	
	//添加日志
	public function addLogs($operation,$pre_data,$up_data,$title,$content_id,$sort_id,$action)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','create');
		$this->curl->addRequestData('bundle_id',APP_UNIQUEID);
		$this->curl->addRequestData('moudle_id',MOD_UNIQUEID);
		$this->curl->addRequestData('operation',$operation);
		$this->curl->addRequestData('title',$title);
		$this->curl->addRequestData('action',$action);
		$this->array_to_add('pre_data' , $pre_data);
		$this->array_to_add('up_data' , $up_data);
		if($content_id)
		{
			$this->curl->addRequestData('content_id',$content_id);
		}
		if($sort_id)
		{
			$this->curl->addRequestData('sort_id',$sort_id);
		}
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('logs_update.php');
		return $ret[0];
	}
	
	public function updateLogs($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update');
		$this->curl->addRequestData('bundle_id',APP_UNIQUEID);
		$this->curl->addRequestData('moudle_id',MOD_UNIQUEID);
		
		foreach($data as $k => $v)
		{
			if($k == 'pre_data' || $k == 'up_data')
			{
				$this->array_to_add($k , $v);
			}
			else
			{
				$this->curl->addRequestData($k , $v);				
			}
		}
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('logs_update.php');
		return $ret[0];
	}
	
	//删除日志
	public function deleteLogs($ids)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete');
		$this->curl->addRequestData('id',$ids);
		$ret = $this->curl->request('logs_update.php');
		return $ret[0];
	}
	
	//删除日志
	public function deleteLogsByContent($id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','deleteContent');
		$this->curl->addRequestData('bundle_id',APP_UNIQUEID);
		$this->curl->addRequestData('moudle_id',MOD_UNIQUEID);
		$this->curl->addRequestData('content_id',$id);
		$ret = $this->curl->request('logs_update.php');
		return $ret[0];
	}
	
	//获取日志
	public function queryLogs($data)
	{	
		if (!$this->curl)
		{
			return array();
		}
		/*$data = array(
			'content_id' =>  $content_id,	//内容id
			'sort_id' 	 =>  $sort_id,		//分类id
			'create_time' 	 =>  $pub_time,		//分类id
			'operation'  =>  $operation,	//操作类型
			'source' 	 =>  $source,		//来源
			'user_id' 	 =>  $user_id,		//操作人id
			'user_name'  =>  $user_name,	//操作人姓名
			'orderby'	 =>  $orderby,		//排序  $orderby = 'ORDER BY a.id DESC';
			'offset'	 =>  $offset,		//偏移量
			'count'		 =>  $count,		//数量
			);*/
		$data['bundle_id'] = APP_UNIQUEID;
		$data['moudle_id'] = MOD_UNIQUEID;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','query');
		foreach($data as $k => $v)
		{
			$this->curl->addRequestData($k,$v);
		}
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('logs.php');
		return $ret[0];
	}
	
	public function getLogsById($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getLogsById');
		$this->curl->addRequestData('id',$id);
		$ret = $this->curl->request('logs.php');
		return $ret[0];
	}	
	
	//获取日志数
	public function showCount($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','showCount');
		$data['bundle_id'] = APP_UNIQUEID;
		$data['moudle_id'] = MOD_UNIQUEID;
		foreach($data as $k => $v)
		{
			$this->curl->addRequestData($k,$v);
		}
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('logs.php');
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
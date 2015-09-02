<?php
class statistic
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if ($gGlobalConfig['App_statistics'])
		{
			$this->curl = new curl($gGlobalConfig['App_statistics']['host'], $gGlobalConfig['App_statistics']['dir'] . 'admin/');
		}
	}

	function __destruct()
	{
	}

	public function insert_record($statistics_data)
	{
		if (!$this->curl)
		{
			return;
		}
		$statistics_data['app_uniqueid'] = APP_UNIQUEID;
		$statistics_data['module_uniqueid'] = MOD_UNIQUEID;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','insert_record');
		$this->array_to_add('statistics_data',$statistics_data);
		return $ret = $this->curl->request('stat_update.php');
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

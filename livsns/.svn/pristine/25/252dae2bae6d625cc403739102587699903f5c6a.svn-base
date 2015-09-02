<?php
/*
*	回收站curl
*
**/
class recycle
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_recycle']['host'],$gGlobalConfig['App_recycle']['dir'] . 'admin/');
	}
	function __destruct()
	{
	}
    /**
	*   放入回收站
	*	$data=array(
	*		'title' =>  标题
	*		'app_mark' => 应用标识
	*		'module_mark' => 模块标识
	*		'delete_people' => 删除人
	*       'time'    =>   删除时间
	*		'ip' => ip
	*		'content' =>  内容
	*		'cid' => 内容ID
	*		);
	*/
	public function add_recycle($title,$delete_people,$cid,$content,$catid='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','add_recycle');
		$this->curl->addRequestData('title',$title);
		$this->curl->addRequestData('app_mark',APP_UNIQUEID);
		$this->curl->addRequestData('module_mark',MOD_UNIQUEID);
		$this->curl->addRequestData('delete_people',$delete_people);
		$this->curl->addRequestData('time',TIMENOW);
		$this->curl->addRequestData('ip',hg_getip());
		$this->curl->addRequestData('cid',$cid);
		$this->curl->addRequestData('catid',$catid);
		$this->curl->addRequestData('html',true);
		$this->array_to_add('content', $content);
		$this->curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
		$ret = $this->curl->request('recycle_update.php');
		return $ret[0];
	}
	public function array_to_add($str , $data)
	{
		$str = $str ? $str : 'data';
		if(is_array($data))
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
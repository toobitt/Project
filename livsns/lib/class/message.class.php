<?php
/**
 * 
 * 评论接口类
 * @author Kin
 *
 */
class message
{
	private $curl;
	
	public function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_message']['host'], $gGlobalConfig['App_message']['dir']);
	}

	public function __destruct()
	{
	    unset($this->curl);
	}
	
	/**
	 * 
	 * @Description: 根据发布库ID或者应用标识，模块标识，内容ID删除评论
	 * @author Kin   
	 * @date 2014-7-9 下午03:29:23
	 */
	public function deleteComment($cmid = '', $app_uniqueid = '', $mod_uniqueid = '', $cid = '')
	{
	    if (!$this->curl)
	    {
	        return array();
	    }
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete_content_comment');
		$this->curl->addRequestData('cmid', $cmid);
		$this->curl->addRequestData('app_uniqueid', $app_uniqueid);
		$this->curl->addRequestData('mod_uniqueid', $mod_uniqueid);
		$this->curl->addRequestData('content_id', $cid);
		$result = $this->curl->request('admin/message_update.php');
		return $result;
	}
	
	private function array_to_add($str, $data)
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
<?php
/**
 *
 * @author Ayou
 *
 */
class email
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		if($gGlobalConfig['App_email'])
		{
			$this->curl = new curl($gGlobalConfig['App_email']['host'],$gGlobalConfig['App_email']['dir']);
		}
	}
	function __destruct()
	{
	}
	
	public function addEmailQueue($params)
	{
		$paramsConfig = array(
			'appuniqueid'=>array(),
			'to'=>array(),
			'subject'=>array(),
			'body'=>array(),
			'tspace'=>array(),
			'bspace'=>array()
		);
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if(is_array($params))
		{
			foreach ($params as $k => $v)
			{
				if(array_key_exists($k, $paramsConfig))
				{
					$this->addRequest($k, $v);
				}
			}
		}
		$this->curl->addRequestData('a','addEmailQueue');
		return $this->curl->request('email_queue.php');
		
	}

    public function sendCloudMail($data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        if ($data && is_array($data))
        {
            foreach ($data as $k => $v)
            {
                if (is_array($v))
                {
                    $this->array_to_add($k, $v);
                }
                else
                {
                    $this->curl->addRequestData($k, $v);
                }
            }
        }
        $this->curl->addRequestData('a', 'send');
        $result = $this->curl->request('sendCloudMail.php');
        return $result;
    }

    private function array_to_add($str, $data)
    {
        $str = $str ? $str : 'data';
        if (is_array($data)) {
            foreach ($data AS $kk => $vv) {
                if (is_array($vv)) {
                    $this->array_to_add($str . "[$kk]", $vv);
                } else {
                    $this->curl->addRequestData($str . "[$kk]", $vv);
                }
            }
        }
    }

	public function addRequest($str, $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if (is_array($vv))
				{
					$this->addRequest($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
		else
		{
			$this->curl->addRequestData($str, $data);
		}
	}
}
?>
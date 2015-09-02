<?php
class feedback
{

    public function __construct()
    {
        global $gGlobalConfig;
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        if($gGlobalConfig['App_verifycode'])
		{
			$this->curl = new curl($gGlobalConfig['App_feedback']['host'], $gGlobalConfig['App_feedback']['dir']);
			$this->curls = new curl($gGlobalConfig['App_feedback']['host'], $gGlobalConfig['App_feedback']['dir'] . 'admin/');
		}
	}

    public function __destruct()
    {
        unset($this->curl);
    }

    public function show($offset = 0, $count = 10 ,$sort_id = '')
    {
    	if (!$this->curls)
		{
			return array();
		}
		$this->curls->setSubmitType('post');
		$this->curls->setReturnFormat('json');
		$this->curls->initPostData();
		$this->curls->addRequestData('a','show');
		$this->curls->addRequestData('state',1);
		$this->curls->addRequestData('_id',$sort_id);
		$this->curls->addRequestData('offset',$offset);
		$this->curls->addRequestData('count',$count);
		$ret = $this->curls->request('feedback.php');
		return $ret;
    }
    
    public function detail($fid)
    {
    	if (!$this->curls)
		{
			return array();
		}
		$this->curls->setSubmitType('post');
		$this->curls->setReturnFormat('json');
		$this->curls->initPostData();
		$this->curls->addRequestData('a','detail');
		$this->curls->addRequestData('id',$fid);
		$ret = $this->curls->request('feedback.php');
		$ret = $ret[0];
		return $ret;
    }
    
    public function count($sort_id = '')
    {
    	if (!$this->curls)
		{
			return array();
		}
		$this->curls->setSubmitType('post');
		$this->curls->setReturnFormat('json');
		$this->curls->initPostData();
		$this->curls->addRequestData('a','count');
		$this->curls->addRequestData('state',1);
		$this->curls->addRequestData('_id',$sort_id);
		$ret = $this->curls->request('feedback.php');
		return $ret;
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
                    $this->array_to_add($str . "[$kk]", $vv);
                }
                else
                {
                    $this->curl->addRequestData($str . "[$kk]", $vv);
                }
            }
        }
    }
    
    public function get_feed_members($id = '',$process = -1)
    {
    	if (!$this->curls)
		{
			return array();
		}
		if(!$id)
		{
			return false;
		}
		$this->curls->setSubmitType('post');
		$this->curls->setReturnFormat('json');
		$this->curls->initPostData();
		$this->curls->addRequestData('a','get_feed_members');
		$this->curls->addRequestData('process',$process);//-1为全部，0为未审核,1为通过,2未通过;多个状态以英文逗号分割，例如0,1
		$this->curls->addRequestData('id',$id);
		$ret = $this->curls->request('feedback_result.php');
		$ret = $ret[0];
		return $ret;
    }
}

?>
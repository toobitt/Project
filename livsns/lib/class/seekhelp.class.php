<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-8-2
 * Time: 下午5:43
 */
class seekhelp
{
    function __construct()
    {
        global $gGlobalConfig;
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        if($gGlobalConfig['App_im'])
        {
            $this->curl = new curl($gGlobalConfig['App_seekhelp']['host'], $gGlobalConfig['App_seekhelp']['dir']);
        }
    }

    function __destruct()
    {

    }

	/**
     * 获取消息列表
     */
    public function show()
    {
    		
    }


    /**
     * 检查黑名单
     */
    public function check_black($app_id)
    {
    	if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('app_id', $app_id);
        $this->curl->addRequestData('a','check_blackByappId');
        $ret = $this->curl->request('seekhelp_blacklist.php');
       	return $ret[0];
    }
    
    /**
     * 更新sort app_name
     */
    public function updateSort_AppName($app_id,$app_name)
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('post');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('app_id', $app_id);
    	$this->curl->addRequestData('app_name', $app_name);
    	$this->curl->addRequestData('a','updateByAppid');
    	$ret = $this->curl->request('admin/sort_update.php');
    	return $ret[0];
    }
    
     /**
     * 获取个人主页会员资料
     */
    public function getMemberInfo($memberId)
    {
    	if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('member_id', $memberId);
        $this->curl->addRequestData('a','detail');
        $ret = $this->curl->request('seekhelp_member.php');
       	return $ret;
    }
    
    /**
     * 获取我的帖子 评论 赞的 总数
     * @param unknown $str
     * @param unknown $data
     */
    public function getMycount($memberId)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('member_id', $memberId);
        $this->curl->addRequestData('a','getMyCount');
        $ret = $this->curl->request('community.php');
        return $ret[0];
    }

    /**
     * 创建时间线
     * @param $memberId
     * @return array
     */
    public function setTimeline($data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        if(is_array($data) && count($data) > 0)
        {
            foreach($data as $k => $v)
            {
                if(is_array($v))
                {
                    $this->array_to_add($k,$v);
                }
                else
                {
                    $this->curl->addRequestData($k,$v);
                }
            }
        }
        $this->curl->addRequestData('a','create');
        $ret = $this->curl->request('timeline.php');
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
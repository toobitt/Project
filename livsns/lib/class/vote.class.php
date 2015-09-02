<?php

class vote
{

    public function __construct()
    {
        global $gGlobalConfig;
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        $this->curl = new curl($gGlobalConfig['App_vote']['host'], $gGlobalConfig['App_vote']['dir']);
    }

    public function __destruct()
    {
        unset($this->curl);
    }

  	/**
  	 * 
  	 * @Description 获取单个投票内容
  	 * @author Kin
  	 * @date 2014-2-26 上午11:24:15
  	 */
    public function detail($id)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $id);
        //$this->curl->addRequestData('a', 'detail');   //切换投票接口
        $this->curl->addRequestData('a', 'getQestionOption');
        $ret = $this->curl->request('admin/vote_question.php');
        return $ret[0];
    }
    
    public function getTotal($id)
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('get');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('id', $id);
    	$this->curl->addRequestData('a', 'getTotal');
    	$ret = $this->curl->request('vote.php');
    	return $ret[0];
    }
    
    /**
     * 创建一个投票
     * @param $data
     */
    public function create($data,$file)
    {
		if (!$this->curl)
		{
			return array();
		}
		if (empty($data)) return false;
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
		if ($file && is_array($file))
		{
			$this->curl->addFile($file);
		}
		$this->curl->addRequestData('a', 'create');
		$ret = $this->curl->request('admin/vote_question_update.php');
		return $ret[0];
    }
    
    
    /**
     * 更新一个投票
     * @param $data
     */
    
	public function update($data,$id,$file)
	{
		if (!$this->curl)
		{
			return array();
		}
		if (empty($data) || empty($id)) return false;
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
		if ($file && is_array($file))
		{
			$this->curl->addFile($file);
		}
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('a', 'update');
		$ret = $this->curl->request('admin/vote_question_update.php');
		return $ret[0];
	}
	
	/**
	 * 删除接口，支持多个id
	 * Enter description here ...
	 * @param unknown_type $ids
	 */
	public function delete($ids)
	{
		if (!$this->curl)
		{
			return array();
		}
		if (!$ids) return false;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete');
		$this->curl->addRequestData('id', $ids);
		$ret = $this->curl->request('admin/vote_question_update.php');
		return $ret[0];
	}

	/**
	 * 审核打回方法
	 * 
	 * @param string $id  内容id
	 * @param int $audit  操作类型  audit=1 打回   audit=0，2审核
	 */
	public function audit($id, $audit)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'audit');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('audit', $audit);
		$ret = $this->curl->request('admin/vote_question_update.php');
		return $ret[0];		
	}

	/**
	 * 开启关闭(单个投票)
	 * @param string $id  内容id
	 */
	public function open($id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'open');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('admin/vote_question_update.php');
		return $ret[0];		
	}
	
	/**
	 * 投票上传图片upload_image
	 */
	public function upload_image($file)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'upload_image');
		$this->curl->addFile($file);
		$ret = $this->curl->request('admin/vote_question_update.php');
		return $ret[0];		
	}
	
	/**
	 * 
	 * @Description 更新权重
	 * @author Kin
	 * @date 2014-2-20 下午07:51:11
	 */
	public function updateWeight($data = array(), $id)
	{
		if (!$this->curl)
		{
			return array();
		}
		if (empty($data) || empty($id))
		{
			 return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_weight');
		$this->curl->addRequestData('app', APP_UNIQUEID);
		$this->curl->addRequestData('module', MOD_UNIQUEID);
		$this->curl->addRequestData('html', true);
		if (is_array($data) && count($data) > 0)
		{
			foreach ($data as $k => $v)
			{
				if (is_array($v))
				{
					$this->array_to_add($k,$v);
				}
				else
				{
					$this->curl->addRequestData($k,$v);
				}
			}
		}
		$this->curl->addRequestData('id',$id);
		$ret = $this->curl->request('admin/vote_question_update.php');
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
	/**
	 * 
	 * @Description: 更新单个内容的栏目
	 * @author Kin   
	 * @date 2014-6-12 下午05:24:06
	 */
	public function update_column($id, $column_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'publish');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('column_id', $column_id);
		$this->curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
		$ret = $this->curl->request('admin/vote_update.php');
		return $ret[0];
	}
	
	/**
	 * 内容管理下更换栏目
	 * @author jitao
	 */
	public function editColumnsById($id = 0 , $column_id = 0 , $column_path = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'editColumnsById');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('column_id', $column_id);
		$this->curl->addRequestData('column_path', $column_path);
		$this->curl->addRequestData('token', '8sdhu9a7sdASDSiSUDs9SwiU7sGF');
		$ret = $this->curl->request('admin/vote_question_update.php');
		return $ret[0];
	}
	
	/**
	 * 赞或者取消赞成功后更新发布库对应内容的赞的count
	 * @param string $operate add或cancel
	 * @param number $content_id 内容ID
	 * @param number $num 次数
	 * @return multitype:|Ambigous <>
	 */
	public function update_praise_count($operate = '' , $content_id = 0 , $num = 0)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_praise_count');
		$this->curl->addRequestData('content_id',$content_id);
		$this->curl->addRequestData('operate',$operate);
		$this->curl->addRequestData('num',$num);
		$this->curl->addRequestData('token','8sdhu9a7sdASDSiSUDs9SwiU7sGF');
		$ret = $this->curl->request('admin/vote_question_update.php');
		return $ret[0];
	}
	
	/**
	 * 移动到垃圾箱
	 */
	public function moveToTrash($id,$vote_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'moveToTrash');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('vote_id', $vote_id);
		$this->curl->addRequestData('token','8sdhu9a7sdASDSiSUDs9SwiU7sGF');
		$ret = $this->curl->request('admin/vote_question_update.php');
		return $ret[0];
	}
}

?>
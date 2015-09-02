<?php

class special
{

    public function __construct()
    {
        global $gGlobalConfig;
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        $this->curl = new curl($gGlobalConfig['App_special']['host'], $gGlobalConfig['App_special']['dir']);
    }

    public function __destruct()
    {
        unset($this->curl);
    }
    
    public function get_special_by_ids($ids)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $ids);
        $this->curl->addRequestData('a', 'show');
        $ret = $this->curl->request('special.php');
        return $ret;
    }

    /**
     * 获取单个专题的信息
     * @param Int $id
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
        $this->curl->addRequestData('a', 'detail');
        $ret = $this->curl->request('admin/special.php');
        return $ret[0];
    }

    public function special_column_info($special_column_id)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $special_column_id);
        $this->curl->addRequestData('a', 'get_spe_column_info');
        $ret = $this->curl->request('admin/special_content.php');
        return $ret[0];
    }

    public function get_special_by_id($id = '', $special_column_id = '')
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('special_column_id', $special_column_id);
        $this->curl->addRequestData('a', 'get_special_by_id');
        $ret = $this->curl->request('special.php');
        return $ret[0];
    }

    public function get_mkspecial($id = '', $special_column_id = '')
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('special_column_id', $special_column_id);
        $this->curl->addRequestData('a', 'get_mkspecial');
        $ret = $this->curl->request('special.php');
        return $ret[0];
    }

    /**
     * 创建专题
     * @param Array $data
     * @param Array $files
     */
    public function create($data, $files)
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
        if ($files && is_array($files))
        {
            $this->curl->addFile($files);
        }
        $this->curl->addRequestData('a', 'create');
        $ret = $this->curl->request('admin/special_update.php');
        return $ret[0];
    }

    /**
     * 编辑专题
     * @param Array $data
     * @param Int $id
     * @param Array $files
     */
    public function update($data, $id, $files)
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
        if ($files && is_array($files))
        {
            $this->curl->addFile($files);
        }
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('a', 'update');
        $ret = $this->curl->request('admin/special_update.php');
        return $ret[0];
    }

    /**
     * 删除专题
     * @param unknown_type $ids
     */
    public function delete($ids)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $ids);
        $this->curl->addRequestData('a', 'delete');
        $ret = $this->curl->request('admin/special_update.php');
        return $ret[0];
    }

    /**
     * 专题审核与打回操作
     * @param Int $id
     * @param Int $op 1审核 0打回
     */
    public function audit($id, $op)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('audit', $op);
        $this->curl->addRequestData('a', 'audit');
        $ret = $this->curl->request('admin/special_update.php');
        return $ret[0];
    }

    public function get_special($sort_id)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_special');
        $this->curl->addRequestData('sort_id', $sort_id);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/special.php');
        return $ret[0];
    }

    //发布专题
    public function insert_special_content($data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'insert_special_con');
        $this->array_to_add('data', $data);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/special_content_update.php');
        return $ret[0];
    }

    //更新发布专题
    public function update_special_content($data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'update_special_con');
        $this->array_to_add('data', $data);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/special_content_update.php');
        return $ret[0];
    }

    public function delete_special_content($data,$content_id,$content_data=array())
    {
        if (!$this->curl)
        {
            return array();
        }

        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'delete_special_cont');
        $this->curl->addRequestData('data', $data);
        $this->curl->addRequestData('content_id', $content_id);
        $this->array_to_add('content_data', $content_data);
        $this->curl->addRequestData('html', true);
        $ret = $this->curl->request('admin/special_content_update.php');
        return $ret[0];
    }
    
    public function get_special_col_url($column_id) {
        if (!$this->curl) {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData(); 
        $this->curl->addRequestData('a', 'columnUrl'); 
        $this->curl->addRequestData('column_id', $column_id); 
        $ret = $this->curl->request('column.php');
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
		$ret = $this->curl->request('admin/special_update.php');
		return $ret[0];
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

}

?>
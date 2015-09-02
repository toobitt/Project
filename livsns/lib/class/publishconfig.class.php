<?php
class publishconfig
{
	function __construct()
	{
		global $gGlobalConfig;
		if (!$gGlobalConfig['App_publishcontent'])
		{
			return false;
		}
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir'] . 'admin/');
	}

	function __destruct()
	{
	}
    
    function seturl()
    {
        global $gGlobalConfig;
        $this->curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
    }   
	
	//获取站点
	//id  site_name
	public function get_site($field = ' * ', $offset = '', $count = '', $site_id='', $key = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_pub_site');
        $this->curl->addRequestData('offset', $offset);
        $this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('field',$field);
        $this->curl->addRequestData('id', $site_id);
        $this->curl->addRequestData('keyword', $key);
		$ret = $this->curl->request('site.php');
		return $ret[0];
	}

    //获取站点总数
    public function get_site_count($key = '')
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','count');
        $this->curl->addRequestData('keyword',$key);
        $ret = $this->curl->request('site.php');
        return $ret;
    }
	public function get_sites()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_sites');
		$ret = $this->curl->request('site.php');
		return $ret[0];
	}
	public function get_site_first($field = ' * ',$site_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_pub_site_first');
		$this->curl->addRequestData('field',$field);
		$this->curl->addRequestData('site_id',$site_id);
		$ret = $this->curl->request('site.php');
		return $ret[0];
	}
	
	public function get_site_by_ids($field = ' * ',$site_ids)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_site_by_ids');
		$this->curl->addRequestData('field',$field);
		$this->curl->addRequestData('site_ids',$site_ids);
		$ret = $this->curl->request('site.php');
		return $ret[0];
	}
	
	/**
	 * 返回数据格式
	 * $ret['site'] = array('id'=>……);
	 * $ret['client'] = {客户端id}=>array('id'=>,'name'=>)
	 * */
	public function get_site_client($site_id,$field = ' id,site_name,support_client,tem_style ')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_site_client');
		$this->curl->addRequestData('field',$field);
		$this->curl->addRequestData('site_id',$site_id);
		$ret = $this->curl->request('site.php');
		return $ret[0];
	}
	
	public function get_column($field = ' * ',$condition = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_column');
		$this->curl->addRequestData('field',$field);
		$this->curl->addRequestData('condition',$condition);//site_id  fid 
		$this->curl->addRequestData('html',true); 
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	
	public function get_column_first($field = ' * ',$column_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_column_first');
		$this->curl->addRequestData('field',$field);
		$this->curl->addRequestData('column_id',$column_id);
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	/**
	 * 根据publishids得到点击次数与comment——num集合
	 * @return multitype:|Ambigous <>
	 * $author cesc
	 */
	public function getClickCountByPublishids($publishIds){
		$publishIdsToString = implode(',', $publishIds);
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','getClicknumsByPublishids');
		$this->curl->addRequestData('publishids',$publishIdsToString);
		$ret = $this->curl->request('content.php');
		return $ret[0];
	}
	
	public function get_columnname_by_ids($field = ' * ',$column_ids)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_columnname_by_ids');
		$this->curl->addRequestData('field',$field);
		$this->curl->addRequestData('column_ids',$column_ids);
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	
	public function get_column_by_ids($field = ' * ',$column_ids)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_column_by_ids');
		$this->curl->addRequestData('field',$field);
		$this->curl->addRequestData('column_ids',$column_ids);
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
    
    public function get_column_info_by_ids($column_ids, $offset = 0, $count = 200)
    {
        $this->seturl();
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','show');
        $this->curl->addRequestData('id',$column_ids);
        $this->curl->addRequestData('offset',$offset);
        $this->curl->addRequestData('count',$count);
        $ret = $this->curl->request('column.php');
        return $ret;
    }    
	
	public function get_column_site_by_ids($field = ' * ',$column_ids)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_column_site_by_ids');
		$this->curl->addRequestData('field',$field);
		$this->curl->addRequestData('column_ids',$column_ids);
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	
	public function get_col_parents($col_ids)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_col_parents');
		$this->curl->addRequestData('col_ids',$col_ids);
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	
	//获取客户端
	//id name
	public function get_client($condition = '')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_pub_client');
		$this->curl->addRequestData('condition',$condition);
		$ret = $this->curl->request('client.php');
		return $ret[0];
	}
	
	//获取客户端
	//id name
	public function get_client_first($client_type)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_client_first');
		$this->curl->addRequestData('client_id',$client_type);
		$ret = $this->curl->request('client.php');
		return $ret[0];
	}
	
	public function get_column_node($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		$this->array_to_add('pub_input',$data);
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	
	public function get_column_count($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','pub_count');
		$this->array_to_add('pub_input',$data);
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	
	public function column_support_content($column_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','column_support_content');
		$this->array_to_add('column_id',$column_id);
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	
	public function insert_cms_column($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		foreach ($data as $k=>$v)
		{
			$this->curl->addRequestData($k, $v);
		}
		$this->curl->addRequestData('channel_id', intval($this->input['channelid']));
		$this->curl->addRequestData('cms_fid', $fid);
		$this->curl->addRequestData('cms_siteid', $this->getCMSsiteid($data['siteid']));
		$data['cms_columnid'] = intval($this->curl->request('sync_cms_column.php'));
	}
	
	/**
	 * 更新加site_id
	 * 添加：site_name 站点名称
	 * 		site_keywords  站点关键字
	 * 		content  站点描述
	 * */
	public function edit_site($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','operate');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k,$v);
		}
		$ret = $this->curl->request('site.php');
		return $ret[0];
	}
	
	public function delete_site($site_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete');
		$this->curl->addRequestData('site_id',$site_id);
		$ret = $this->curl->request('site.php');
		return $ret[0];
	}
	
	public function edit_column($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','operate');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k,$v);
		}
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	
	public function delete_column($column_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete');
		$this->curl->addRequestData('id',$column_id);
		$ret = $this->curl->request('column.php');
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
	//权限用
	public function get_column_site($data = array())
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_column_site');
		foreach($data as $k=>$v)
		{
			$this->curl->addRequestData($k,$v);
		}
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	
	/**
	 * 
	 * @Description 栏目排序
	 * @author Kin
	 * @date 2014-2-24 下午04:25:25
	 */
	public function column_sort($data = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','column_sort');
		$this->curl->addRequestData('sort',$data);
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}
	
	/**
	 * 子栏目排序
	 */
	public function childColumnsSort($newColumnIds = '' , $parentId = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','childColumnsSort');
		$this->curl->addRequestData('newColumnIds',$newColumnIds);
		$this->curl->addRequestData('parentId',$parentId);
		$ret = $this->curl->request('column.php');
		return $ret[0];
	}

    /**
     *
     * @Description: 根据站点取出所有栏目
     * @author Kin
     * @date 2014-6-5 下午04:57:18
     */
    public function get_column_by_site($sid,$fields = '*')
    {
        global $gGlobalConfig;
        $this->curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir'] . '/');
        $this->curl->setSubmitType('get');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'get_column_by_site');
        $this->curl->addRequestData('site_id', $sid);
        $this->curl->addRequestData('fields', $fields);
        $result = $this->curl->request('column.php');
        return $result;
    }
}
?>
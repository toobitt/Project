<?php
class publishcontent
{
	function __construct()
	{
		global $gGlobalConfig;
		if ($gGlobalConfig['App_publishcontent'])
		{
			include_once (ROOT_PATH . 'lib/class/curl.class.php');
			$this->curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir'] . 'admin/');
		}
	}

	function __destruct()
	{
	}
	
	function seturl()
	{
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['App_publishcontent']['host'], $gGlobalConfig['App_publishcontent']['dir']);
	}
	
	//建表
	public function create_table($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','create_table');
		$this->curl->addRequestData('html',true);
		$this->array_to_add('data' , $data);
		return $ret = $this->curl->request('content_set.php');
	}
	
	//插入内容
	public function insert_content($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','insert_content');
		$this->curl->addRequestData('html',true);
		$this->array_to_add('data' , $data);
		$ret = $this->curl->request('content_set.php');
		return $ret[0];
	}
	
	//删除内容
	public function delete_content($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete');
		$this->curl->addRequestData('html',true);
		$this->array_to_add('data' , $data);
		$ret = $this->curl->request('content.php');
		return $ret[0];
	}
	
	//删除内容
	public function update_content_by_rid($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update_content');
		$this->curl->addRequestData('html',true);
		if(is_array($data))
		{
			foreach($data as $k=>$v)
			{
				$this->curl->addRequestData($k,$v);
			}
		}
		$ret = $this->curl->request('content.php');
		return $ret[0];
	}
    
    public function update_content_by_cid($content_id, $data)
    {
        if (!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','update_content_by_cid');
        $this->curl->addRequestData('content_id',$content_id);
        $this->array_to_add('data', $data);
        $ret = $this->curl->request('content.php');
        return $ret[0];
    }   

    public function updateContentbyrid($rid, $data)
    {
    	if (!$this->curl)
    	{
    		return array();
    	}
    	$this->curl->setSubmitType('post');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
    	$this->curl->addRequestData('a','update_content_by_rid');
    	$this->curl->addRequestData('rid',$rid);
    	$this->array_to_add('data', $data);
    	$ret = $this->curl->request('content.php');
    	return $ret[0];
    }
	
	//单独删除子级内容
	public function delete_child_content($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete_child');
		$this->curl->addRequestData('html',true);
		$this->array_to_add('data' , $data);
		$ret = $this->curl->request('content.php');
		return $ret[0];
	}
	
	//更新内容
	public function update_content($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update');
		$this->curl->addRequestData('html',true);
		$this->array_to_add('data' , $data);
		return $ret = $this->curl->request('content.php');
	}
	
	//更新内容的发布状态，表示已全部发布
	public function update_is_complete($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update_is_complete');
		$this->curl->addRequestData('html',true);
		$this->array_to_add('data' , $data);
		return $ret = $this->curl->request('content.php');
	}
	
	public function insert_childs_to_content($bundle_id,$module_id,$struct_id,$struct_ast_id,$content_rid,$from_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','insert_childs_to_content');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('bundle_id',$bundle_id);
		$this->curl->addRequestData('module_id',$module_id);
		$this->curl->addRequestData('struct_id',$struct_id);
		$this->curl->addRequestData('struct_ast_id',$struct_ast_id);
		$this->curl->addRequestData('content_rid',$content_rid);
		$this->curl->addRequestData('from_id',$from_id);
		return $ret = $this->curl->request('content_set.php');
	}
	
	//根据栏目id获取内容类型
	public function get_content_type_by_colid($column_id,$expand_module='',$is_site='')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_content_type_by_colid');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('column_id',$column_id);
		$this->curl->addRequestData('is_site',$is_site);
		$this->curl->addRequestData('expand_module',$expand_module);
		$ret = $this->curl->request('content_set.php');
		return $ret[0];
	}
	
	//获取内容类型
	public function get_all_content_type()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_all_content_type');
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('content_set.php');
		return $ret[0];
	}
	
	/**获取内容
	site_id  column_id  bundle_id  module_id client_type  weight  
	is_have_indexpic  is_have_video  
	*/
	public function get_content($data)
	{	
		$this->seturl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_content');
		$this->curl->addRequestData('html',true);
		if (is_array($data))
		{
			foreach($data as $k=>$v)
			{
				$this->curl->addRequestData($k,$v);
			}
		}
		$ret = $this->curl->request('content.php');
		return $ret;
	}
	
	/**
	 * 根据content_id获取内容信息
	 * $cotent_id    :    1,2,3
	 * $column       :    array(1=>'1,2,3',2=>'')
	 * */
	public function get_content_by_cid($content_id,$column ='')
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->seturl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_content_by_cid');
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('content_id',$content_id);
		$this->array_to_add('column',$column);
		$ret = $this->curl->request('content.php');
		return $ret[0];
	}
	
	//获取区块
	public function get_block()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_block');
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('block.php');
		return $ret[0];
	}
	
	//获取区块内容
	/*$data = array(
		'block_id' => $block_id, //区块id
		'line_num' => $line_num, //取的行数，不用填写
	);
	*/
	public function get_block_content($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_block_content');
		$this->curl->addRequestData('html',true);
		foreach($data as $k => $v)
		{
			$this->curl->addRequestData($k,$v);
		}
		$ret = $this->curl->request('block.php');
		return $ret[0];
	}
	
	//获取区块内容html
	/*$data = array(
		'block_id' => $block_id, //区块id
		'line_num' => $line_num, //取的行数，不用填写
		'pic_width' => $pic_width,//图片宽度  像素
		'pic_height' => $pic_height,//图片高度
		'title_num' => $title_num,//标题长度
		'brief_num' => $brief_num,//描述长度
	);
	*/
	public function get_block_content_html($data)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_block_content_html');
		$this->curl->addRequestData('html',true);
		foreach($data as $k => $v)
		{
			$this->curl->addRequestData($k,$v);
		}
		$ret = $this->curl->request('block.php');
		return $ret[0];
	}
	
	public function get_app()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_all_app');
		$ret = $this->curl->request('content_set.php');
		return $ret;
	}
	
	public function get_app_module()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_app_module');
		$ret = $this->curl->request('content_set.php');
		return $ret[0];
	}
	
	public function content_field_by_ids($id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','content_field_by_ids');
		$this->curl->addRequestData('id',$id);
		$ret = $this->curl->request('content_set.php');
		return $ret[0];
	}
	
	public function video_record($site)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','video_record');
		$this->array_to_add('site',$site);
		$ret = $this->curl->request('content_set.php');
		return $ret[0];
	}
	
	public function get_pub_content_type()
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_pub_content_type');
		$ret = $this->curl->request('content.php');
		return $ret[0];
	}
	
	public function get_content_type_by_app($bundle_id,$module_id)
	{
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_content_type_by_app');
		$this->curl->addRequestData('bundle_id',$bundle_id);
		$this->curl->addRequestData('module_id',$module_id);
		$ret = $this->curl->request('content_set.php');
		return $ret[0];
	}
	
	public function get_content_by_rid($rid)
	{
		$this->seturl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_content_by_rid');
		$this->curl->addRequestData('id',$rid);
		$ret = $this->curl->request('content.php');
		return $ret[0];
	}
	
	public function get_content_by_rids($rids)
	{
		$this->seturl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_content_by_rids');
		$this->curl->addRequestData('id',$rids);
		$ret = $this->curl->request('content.php');
		return $ret;
	}
        
    public function mk_content_by_rid($rid)
	{
		$this->seturl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','mk_content_by_rid');
		$this->curl->addRequestData('rid',$rid);
		$ret = $this->curl->request('cron/content_set.php');
		return $ret;
	}
	
	public function get_content_by_other($content_fromid,$bundle_id,$module_id)
	{
		$this->seturl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_content_by_other');
		$this->curl->addRequestData('content_fromid',$content_fromid);
		$this->curl->addRequestData('bundle_id',$bundle_id);
		$this->curl->addRequestData('module_id',$module_id);
		$ret = $this->curl->request('content.php');
		return $ret;
	}
    
    public function videoop_content_data($site_id, $videoop_count)
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
        $this->curl->addRequestData('site_id',$site_id);     
        $this->curl->addRequestData('video_record_count',$videoop_count);
        $ret = $this->curl->request('site.php');
        return $ret;     
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
	/**
	 * 
	 * @Description 更新权重
	 * @author Kin
	 * @date 2014-2-20 下午07:21:10
	 */
	public function update_weight($data =array())
	{
		if (is_array($data) && !empty($data))
		{
			if (!$this->curl)
			{
				return array();
			}
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a','update_weight');
			$this->curl->addRequestData('data',json_encode($data));
			$ret = $this->curl->request('content.php');
			return $ret;
		}
		else
		{
			return false;
		}
	}
	
	/**获取内容的点击数 分享数 */
	public function get_clicknum($data)
	{	
		$this->seturl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_clicknums');
		$this->curl->addRequestData('html',true);
		if (is_array($data))
		{
			foreach($data as $k=>$v)
			{
				$this->curl->addRequestData($k,$v);
			}
		}
		$ret = $this->curl->request('content.php');
		return $ret;
	}

	/**获取内容
	 * 栏目合并
	*/
	public function get_content_list($data)
	{	
		$this->seturl();
		if (!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_content_list');
		$this->curl->addRequestData('html',true);
		if (is_array($data))
		{
			foreach($data as $k=>$v)
			{
				$this->curl->addRequestData($k,$v);
			}
		}
		$ret = $this->curl->request('content.php');
		return $ret;
	}
	
}
?>
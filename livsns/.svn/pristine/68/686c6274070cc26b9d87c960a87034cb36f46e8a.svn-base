<?php
class block
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_block']['host'], $gGlobalConfig['App_block']['dir']);
	}

	function __destruct()
	{
	}
	
	public function insert_block($data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','create');
		if(is_array($data))
		{
			foreach($data as $k=>$v)
			{
			    if (is_array($v))
                {
                   $this->array_to_add($k, $v);
                }
                else
                {
				    $this->curl->addRequestData($k,$v);
                }
			}
		}
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/block_update.php');
		return $ret[0];
	}
    
    public function update_block($data, $block_id)
    {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','update');
        if(is_array($data))
        {
            foreach($data as $k=>$v)
            {
                if (is_array($v))
                {
                   $this->array_to_add($k, $v);
                }
                else
                {
                    $this->curl->addRequestData($k,$v);
                }
            }
        }
        $this->curl->addRequestData('id', $block_id);
        $this->curl->addRequestData('html',true);
        $ret = $this->curl->request('admin/block_update.php');
        return $ret[0];        
    }
    
    public function updateBlockAndData($data) {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','update_block');
        $this->array_to_add('data', $data);   
        $ret = $this->curl->request('admin/browse.php');
        return $ret[0];    
    } 
    
    public function getBlockData($intBlockId) {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','get_block_data_and_line_info');
         $this->curl->addRequestData('block_id',$intBlockId);   
        $ret = $this->curl->request('admin/block.php');
        return $ret[0];          
    }
    
    public function delete_block($block_id)
    {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a','delete');
        $this->curl->addRequestData('id', $block_id);
        $ret = $this->curl->request('admin/block_update.php');
        return $ret[0];        
    }    
	
	public function get_block_list()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_block_list');
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/block.php');
		return $ret;
	}
	
	public function get_block_info($block_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_block_info');
		$this->curl->addRequestData('block_id',$block_id);
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/block.php');
		return $ret[0];
	}
	
	public function insert_block_content($data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','insert_block_content');
		$this->array_to_add('data',$data);
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/block.php');
		return $ret;
	}
	
	public function update_block_content($data)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update_block_content');
		$this->array_to_add('data',$data);
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/block.php');
		return $ret;
	}
	
	public function delete_block_content($block_id,$content_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','delete_block_content');
		$this->curl->addRequestData('block_id',$block_id);
		$this->curl->addRequestData('content_id',$content_id);
		$this->curl->addRequestData('html',true);
		$ret = $this->curl->request('admin/block.php');
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
}
?>
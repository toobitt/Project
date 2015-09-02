<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
//在没有出入任何可选数据时 控件默认调用的类
class special
{	
	private $site;
	private $db;
	private $input;
	function __construct()
	{
		global $_INPUT;
		global $gGlobalConfig;
		$this->db = hg_checkDB();
		$this->input = $_INPUT;
		if(!$gGlobalConfig['App_special'])
		{
			return false;
		}
		$this->curl = new curl($gGlobalConfig['App_special']['host'],$gGlobalConfig['App_special']['dir']);
	}
	function __destruct()
	{
		
	}

	//模板中获取已选中的专题栏目
	public function get_selected_col($selected_id)
	{
		if(!$this->curl)
		{
			return false;
		}
		$result = array();
		if(!$selected_id)
		{
			return;
		}
		if(is_array($selected_id))
		{
			$selected_id = implode(',',$selected_id);
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $selected_id);
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('a', 'get_pub_special_by_id');
		$columns = $this->curl->request('admin/special.php');
		return $columns;
	}
	//注意此处没有做limit限制 也就是在子栏目很多的情况下可能会影响加载速度
	public function get_special_sort()
	{
		if(!$this->curl)
		{
			return false;
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('a', 'get_sort');
		$sorts = $this->curl->request('admin/special.php');
		$sorts  = $sorts ? $sorts : array();
		return $sorts;
	}
	
	public function get_special_column($special_id)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('html',true);
		$this->curl->addRequestData('a', 'get_special_column');
		$this->curl->addRequestData('special_id', $special_id);
		$columns = $this->curl->request('admin/special.php');
		$columns  = $columns ? $columns : array();	
		return $columns;			
	}

    public function get_special_column_new($special_id)
    {
        if(!$this->curl)
        {
            return array();
        }
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('html',true);
        $this->curl->addRequestData('a', 'get_column');
        $this->curl->addRequestData('special_id', $special_id);
        $columns = $this->curl->request('column.php');
        $columns  = $columns ? $columns : array();  
        return $columns;            
    }

	
	public function get_special_column_byid($column_ids)
	{
		if (!$this->curl) {
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('html', true);
		$this->curl->addRequestData('a', 'get_special_column_byid');
		$this->curl->addRequestData('column_ids', $column_ids);
		$columns = $this->curl->request('admin/special.php');
		$columns = $columns ? $columns[0] : array();
		return $columns;
	}
	
}
?>
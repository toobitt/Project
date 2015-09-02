<?php

define('MOD_UNIQUEID','catalog_sort');//模块标识
require('global.php');
include_once CUR_CONF_PATH . 'core/catalog.core.php';
require_once CUR_CONF_PATH . 'lib/catalog_sort.class.php';
class catalogsortUpdateApi extends adminUpdateBase
{
	private $catalogsort;
	public function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
	    	if(stripos($this->user['prms']['app_prms'][APP_UNIQUEID]['setting'],MOD_UNIQUEID)===false)
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->catalogsort = new catalogsort();
		$this->catalogcore = new catalogcore();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{   
		$catalog_sort 	  = trim($this->input['catalog_sort']);
		$catalog_sort_name 	  = trim($this->input['catalog_sort_name']);
			
		if (!$catalog_sort_name)
		{
			$this->errorOutput(CATALOG_SORT_NAME_NOT_NULL);
		}
		if (!$catalog_sort)
		{
			$this->errorOutput(CATALOG_SORT_FIELD_NOT_NULL);
		}
		//  验证分类字段是否为数字和字母组合
		//if(!ctype_alnum($catalog_sort)){
		//	$this->errorOutput('您输入的不是字母和数字');
		//	}
		//验证 $catalog_sort 是否存在
		$this->field_verify($catalog_sort_name,'分类名称',0,0,1);//验证字符合法性
		$this->field_verify($catalog_sort,'标识');//验证字符合法性
		$condition = " AND catalog_sort = '" . $catalog_sort . "'";
		$member_catalog_sort = $this->catalogsort->get_member_catalog_sort($condition);
		if (!empty($member_catalog_sort))
		{
			$this->errorOutput(CATALOG_SORT_FIELD_EXIST);
		}

		//验证 $catalog_sort_name 是否存在
		$condition = " AND catalog_sort_name = '" . $catalog_sort_name . "'";
		$member_catalog_sort_name = $this->catalogsort->get_member_catalog_sort($condition);
		if (!empty($member_catalog_sort_name))
		{
			$this->errorOutput(CATALOG_SORT_NAME_EXIST);
		}


		$data = array(
			'catalog_sort'		=> $catalog_sort,
			'catalog_sort_name'		=> $catalog_sort_name,
			'user_id'			=>$this->user['user_id'],
			'user_name'			=>$this->user['user_name'],
			'create_time'		=> TIMENOW,
			'update_time' 		=> TIMENOW,
		);

		$ret = $this->catalogsort->create($data);

		if (!$ret)
		{
			$this->errorOutput(ADD_FAILED);
		}
		$this->catalogcore->cache();//更新缓存

		$this->addItem($data);
		$this->output();
	}
	private function field_verify($verify,$verify_name,$is_num = 1,$is_china = 1,$is_null = 1)
	{
		if(is_array($verify))
		{
			foreach ($verify as $v)
			$this->field_verify($v,$is_num,$is_china,$is_null);
		}
		elseif($is_null&&empty($verify))
		{
			$this->errorOutput($verify_name.'禁止为空字符串');
		}
		elseif($is_num&&is_numeric($verify))
		{
			$this->errorOutput($verify_name.'禁止全数字');
		}
		elseif ($is_china&&preg_match("/([\x81-\xfe][\x40-\xfe])/", $verify, $match))
		{
			$this->errorOutput($verify_name.'禁止使用或者含有汉字');
		}
		elseif(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$verify))
		{
			$this->errorOutput($verify_name.'禁止使用特殊字符,请使用字母、字母和数字组合(可用下划线连接)!');
		}
	}
	public function update()
	{   
		$catalog_sort	  = trim($this->input['catalog_sort']);
		$catalog_sort_name	  = trim($this->input['catalog_sort_name']);
		if(!$this->input['id'])
		{
			$this->errorOutput(NO_DATA_ID);
		}

		if (!$catalog_sort_name)
		{
			$this->errorOutput(CATALOG_SORT_NAME_NOT_NULL);
		}
		if (!$catalog_sort)
		{
			$this->errorOutput(CATALOG_SORT_FIELD_NOT_NULL);
		}

		$this->field_verify($catalog_sort_name,'分类名称',0,0,1);//验证字符合法性
		$this->field_verify($catalog_sort,'标识');//验证字符合法性
		
		//不允许修改catalog_sort
		$condition = " AND catalog_sort = '" . $catalog_sort . "' AND id IN ('" . $this->input['id'] . "')";
		$member_catalog_sort = $this->catalogsort->get_member_catalog_sort($condition,'catalog_sort');
		if ($member_catalog_sort['catalog_sort']!=$catalog_sort)
		{
			$this->errorOutput(FORBID_UPDATE);
		}

		//验证 $catalog_sort_name 是否存在
		$condition = " AND catalog_sort_name = '" . $catalog_sort_name . "' AND id NOT IN ('" . $this->input['id'] . "')";
		$member_catalog_sort = $this->catalogsort->get_member_catalog_sort($condition);
		if (!empty($member_catalog_sort))
		{
			$this->errorOutput(CATALOG_SORT_NAME_EXIST);
		}

		$data = array(
		'catalog_sort'		=> $catalog_sort,
		'catalog_sort_name'	=>	$catalog_sort_name,
		'update_time'		=>		TIMENOW,
		);

		$ret = $this->catalogsort->update($this->input['id'],$data);

		if (!$ret)
		{
			$this->errorOutput(UPDATE_FAILED);
		}
		$this->catalogcore->cache();//更新缓存
		$this->addItem($data);
		$this->output();
	}
	/*
	public function display()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$switch = intval($this->input['is_on']);
		$switch = ($switch ==1) ? $switch : 0;
		$data = $this->catalogcore->display($ids,$switch,'field_sort');
		$this->catalogcore->cache();//更新缓存
		$this->addItem($data);
		$this->output();
	}
	*/

	public function delete()
	{
		$catalog_sort_ids = trim($this->input['id']);
		if (!$catalog_sort_ids)
		{
			$this->errorOutput(NO_CATALOG_SORT_ID);
		}
		$condition = " AND sort.id IN (".$catalog_sort_ids.")";
		$sql = "SELECT distinct sort.catalog_sort_name FROM " . DB_PREFIX . "field AS field LEFT JOIN ". DB_PREFIX . "field_sort AS sort ON field.catalog_sort_id =sort.id";
		$sql.= " WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$tmp[] = $row['catalog_sort_name'];
		}
		$catalog_sort_data=implode(' AND ', $tmp);
		if (!empty($catalog_sort_data))
		{
			$this->errorOutput($catalog_sort_data.',已被使用,禁止删除');
		}

		$ret = $this->catalogsort->delete($catalog_sort_ids);

		if (!$ret)
		{
			$this->errorOutput(DELETE_FAILED);
		}
		$this->catalogcore->cache();//更新缓存
		$this->addItem($catalog_sort_id);
		$this->output();
	}

	public function audit(){}
	
	public function sort()
	{
		$this->addLogs('更改编目分类排序', '', '', '更改编目分类排序');
		$content_ids = explode(',', $this->input['content_id']);
        $order_ids   = explode(',', $this->input['order_id']);
        foreach ($content_ids as $k => $v)
        {
            $sql = "UPDATE " . DB_PREFIX . "field_sort  SET order_id = '" . $order_ids[$k] . "'  WHERE id = '" . $v . "'";
            $this->db->query($sql);
        }
        $this->catalogcore->cache();//更新缓存
        $this->addItem('success');
        $this->output();
		//$this->drag_order('field_sort','order_id');
	}
	public function publish(){}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
}

$out = new catalogsortUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
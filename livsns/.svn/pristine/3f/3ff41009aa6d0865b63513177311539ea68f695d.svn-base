<?php
/**
 **附加信息管理*
 */
require('./global.php');
define('MOD_UNIQUEID','lbs_field');
include_once (CUR_CONF_PATH . 'lib/field.class.php');

class lbsfield extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->field = new field();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index()
	{
		//
	}

	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$sql = "SELECT f.*,s.zh_name AS stylename,s.zh_name AS stylename,s.datatype AS styletype
               FROM " . DB_PREFIX . "field AS f ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "style AS s ON f.form_style = s.id WHERE 1";
		$condition=$this->get_condition();
		if($condition)
		{
			$sql.=$condition;
		}
		$sql .= " ORDER BY f.order_id DESC ";
		if($offset || $count)
		{
			$sql .= " LIMIT " . $offset . " , " . $count ;  //分页
		}
		 
		$q = $this->db->query($sql);

		while($data = $this->db->fetch_array($q))
		{
			$data['create_time'] = date('Y-m-d H:i:s',$data['create_time']);
			$data['update_time'] = date('Y-m-d H:i:s',$data['update_time']);
			$this->addItem($data);
		}
		$this->output();
	}


	public function count()
	{
		$condition = $this->get_condition();
		$sql = 'SELECT COUNT(*) AS total FROM ' .DB_PREFIX. 'field f WHERE 1';
		if ($condition) $sql .= $condition;
		exit(json_encode($this->db->query_first($sql)));
	}

	//获取某个的配置
	public function detail()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$sql = "SELECT f.*,s.zh_name as form_style_name,bind.sort_id
               FROM " . DB_PREFIX . "field f ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "style s ON f.form_style = s.id ";
		$sql .= " LEFT JOIN " . DB_PREFIX . "fieldbind AS bind ON bind.field_id = f.id ";
		$sql .= "WHERE f.id=" . $id;
		$q = $this->db->query($sql);
		while($ret = $this->db->fetch_array($q))
		{
			$sort_id[]=$ret['sort_id'];
			$data=$ret;
			$data['field_default']=explode(',', $ret['field_default']);
			$data['create_time'] = date('Y-m-d H:i:s',$data['create_time']);
			$data['update_time'] = date('Y-m-d H:i:s',$data['update_time']);
				
		}
		$data['sort_id']=$sort_id;
		$this->addItem($data);
		$this->output();
	}

	/**
	 * 获取分类信息
	 */
	function get_sort()
	{
		$condition='';
		if($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
		{
			$authnode_str = '';
			$authnode_str = $authnode ? implode(',', $authnode) : '';
			if($authnode_str)
			{
				$condition = ' AND id IN(' . $authnode_str . ')';
				$authnode_str=$this->field->get_sort($condition,'GROUP_CONCAT( childs ) AS childs',false);
				$condition = ' AND id IN(' . $authnode_str['childs'] . ')';
			}
		}
		$sort_info=$this->field->get_sort($condition);
		if($sort_info&&is_array($sort_info))
		{
			foreach ($sort_info as $val)
			{
				$this->addItem($val);
			}
		}
		else $this->addItem($sort_info);
		$this->output();
	}
	/**
	 * 获取所有类型信息
	 */
	public function get_styles()
	{
		$styles=$this->field->get_styles();
		while (list($key,$style) = each($styles))
		{
			$this->addItem($style);
		}
		$this->output();
	}


	function get_condition()
	{
		$conditon = '';

		//附加信息类型
		$form_style = isset($this->input['form_style_id']) ? intval($this->input['form_style_id']) : '';
		if($form_style && $form_style > 0)
		{
			$condition .= " AND f.form_style = " . $form_style ;
		}
	  
		//样式名字
		$zh_name = isset($this->input['zh_name']) ? trim(urldecode($this->input['zh_name'])): '';
		if($zh_name)
		{
			$condition .= " AND f.zh_name like '%" . $zh_name ."%' ";
		}

		//附加信息状态
		if (isset($this->input['switch']) && $this->input['switch'] != -1)
		{
			$condition .= ' AND f.switch = '.intval($this->input['switch']);
		}

		return $condition;

	}


}

$out=new lbsfield();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>
<?php
require("./global.php");
require(ROOT_PATH . 'frm/node_frm.php');
define('MOD_UNIQUEID','article_sort_m');//模块标识
class ArticleSort extends nodeFrm
{
   public function __construct()
	{
	   parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
   
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):100;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$sql = "SELECT id,name FROM ".DB_PREFIX."catalog  WHERE 1 ".$condition.$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$arr[] = $r;
		}
		
		//查询用户记录
		if($this->user['user_id'])
		{
			$sql = "SELECT sort_id FROM ".DB_PREFIX."user_log WHERE user_id = ".$this->user['user_id'];
			$res = $this->db->query_first($sql);
		}
		
		$arr['sort_id'] = $res['sort_id'];
		$this->addItem($arr);
		$this->output();
	}  

	public function detail()
	{
		if(!$this->input['id']){
			$this->errorOutput('请传入ID');
		}
		$this->show();
	}

   public function count()
   {
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'catalog WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
   }

   public function get_condition()
   {
	   $condition=' ';
	   //查询ID
	   if($this->input['id'])
	   {
		   $condition .= " AND id IN(".$this->input['id'].")";
	   }
	   
	   if($this->input['issue_id'])
	   {
	   		$condition .= " AND issue_id = ".intval($this->input['issue_id']);
	   }
	   
	   //查询关键字
	   if($this->input['k'])
	   {
			$condition .= ' AND name LIKE  \'%' . trim(urldecode($this->input['k'])) . '%\'';
	   }
	   //查询起始时间
       if($this->input['start_time'])
	   {
		   $condition .=" AND create_time >" .strtotime($this->input['start_time']);
	   }

	   //查询结束时间
	   if($this->input['end_time'])
	   {
		   $condition .=" AND create_time <" .strtotime($this->input['end_time']);
	   }

	   //查询排序字段(默认为创建时间)
	   $order=$this->input['order_field']?urldecode($this->input['order_field']):'create_time';
	   switch($order)
	   {
		   case 'create_time':
			   $condition .= " ORDER BY  create_time ";
		        break;
		   default:
			   $condition .=" ORDER BY ".$order ;
		       break;
	   }

	   //查询排序方式(默认为降序)
	   $condition .=$this->input['descasc'] ? $this->input['descasc'] : ' DESC';

	   //返回
	   return $condition;
	}
}

$out=new ArticleSort();
$action=$_INPUT['a'];
if(!method_exists($out,$action))
{
	$action='show';
}
$out->$action();
?>
<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: line_channel_user.php 4086 2011-06-17 09:27:44Z zhuld $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

/**
 * 
 * 频道用户信息获取API
 * 
 * 提供的方法：
 * 1)获取所有用户的信息
 * 2)获取单个用户的信息
 * 
 * @author chengqing
 *
 */
class lineChannelUserShowApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 频道用户数据获取默认方法
	 */
	public function show()
	{
		//分页参数设置
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;	
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;	
						
		$sql = "SELECT id, username , avatar , register_time , email , ip , collect_count , comment_count FROM " . DB_PREFIX . "user WHERE 1 ";
		
		//获取查询条件
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $data_limit;		
		$q = $this->db->query($sql);
		
		$this->setXmlNode('user_info' , 'user');
		while($row = $this->db->fetch_array($q))
		{
			$row['register_time'] = date('Y-m-d H:i:s' , $row['create_time']);			
			$row['larger_avatar'] = hg_avatar($row['id'],"larger",$row['avatar']);
			$row['middle_avatar'] = hg_avatar($row['id'],"middle",$row['avatar']);
			$row['small_avatar'] = hg_avatar($row['id'],"small",$row['avatar']); 
			$this->addItem($row);	
		}		
		$this->output();		
	}
	
	/**
	 * 获取频道用户总数
	 * 默认为全部频道用户的总数
	 */
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "user WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);	
		
		echo json_encode($r);
		
		/*$total_nums = $r['total_nums'];		
		$this->setXmlNode('user_info' , 'user_count');
		$this->addItem($total_nums);	
		$this->output();*/
	}
	
	/**
	 * 获取单条频道用户数据
	 */
	public function detail()
	{
		$this->input['id'] = urldecode($this->input['id']);
		if(!$this->input['id'])
		{
			return;
		}
		if($this->input['id'] == 'lastest')
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id in(' . $this->input['id'] .')';
		}
			
		$sql = "SELECT id, username , avatar , register_time , email , ip , collect_count , comment_count FROM " . DB_PREFIX . "user  " . $condition;		
		$r = $this->db->query_first($sql);
		$this->setXmlNode('user_info' , 'user');
		
		if(is_array($r) && $r)
		{
			$r['register_time'] = date('Y-m-d H:i:s' , $r['create_time']);			
			$r['larger_avatar'] = hg_avatar($r['id'],"larger",$r['avatar']);
			$r['middle_avatar'] = hg_avatar($r['id'],"middle",$r['avatar']);
			$r['small_avatar'] = hg_avatar($r['id'],"small",$r['avatar']); 
			 
			$this->addItem($r);
			$this->output();
		}
		else
		{
			$this->errorOutput('用户不存在');	
		} 					
		
	}
	
	/**
	 * 获取查询条件
	 */
	public function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['k'])
		{
			$condition .= " AND username LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
		
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND register_time > " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND register_time < " . strtotime($this->input['end_time']);	
		}
		
		//查询email
		if($this->input['email'])
		{
			$condition .= " AND email LIKE '%" . trim($this->input['email']) . "%' ";	
		}
		
		//查询排序类型(字段，默认为创建时间)
		$order = $this->input['order_field'] ? $this->input['order_field'] : 'register_time'; 
		switch($order)
		{
			case 'create_time' : $condition .= " ORDER BY " . $order;break;
			default:$condition .= " ORDER BY " . $order;	
		}
		
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $this->input['order_type'] ? $this->input['order_type'] : ' DESC ';
		
		return $condition;	
	}
}

/**
 *  程序入口
 */
$out = new lineChannelUserShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

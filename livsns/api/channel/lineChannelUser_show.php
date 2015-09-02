<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: lineChannelUser_show.php 4016 2011-05-30 06:32:19Z repheal $
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
		$page = $this->input['page'] ? $this->input['page'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		
		$offset = $page * $count;
						
		$sql = "SELECT id, username , avatar , register_time , email , ip , collect_count , comment_count FROM " . DB_PREFIX . "user WHERE 1 ";
		
		//获取查询条件
		$condition = $this->get_condition();		
		$sql = $sql . $condition;		
		$q = $this->db->query($sql);
		
		$this->setXmlNode('user_info' , 'user');
		while($row = $this->db->fetch_array($q))
		{
			$row['register_time'] = date('Y-m-d H:i:d' , $row['create_time']);		
				
			if(strlen($row['avatar']) > 32)//qq同步的用户头像
			{
				$row['large_avatar']= hg_avatar($row['id'],"100",$row['avatar'],0);
				$row['middle_avatar']= hg_avatar($row['id'],"50",$row['avatar'],0);
				$row['small_avatar'] = hg_avatar($row['id'],"10",$row['avatar'],0);
			}
			else 
			{
				$row['larger_avatar']= hg_avatar($row['id'],"larger",$row['avatar']);
				$row['middle_avatar']= hg_avatar($row['id'],"middle",$row['avatar']);
				$row['small_avatar'] = hg_avatar($row['id'],"small",$row['avatar']);
			}			
			
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
		$total_nums = $r['total_nums'];		
		$this->setXmlNode('user_info' , 'user_count');
		$this->addItem($total_nums);	
		$this->output();
	}
	
	/**
	 * 获取单条频道用户数据
	 */
	public function detail()
	{
		//用户ID
		$id = $this->input['id'] ? intval($this->input['id']) : -1;
		if($id > 0)
		{			
			$sql = "SELECT id, username , avatar , register_time , email , ip , collect_count , comment_count FROM " . DB_PREFIX . "user WHERE id = " . $id;		
			$r = $this->db->query_first($sql);
			$this->setXmlNode('user_info' , 'user');
			
			if(is_array($r) && $r)
			{
				$r['register_time'] = date('Y-m-d H:i:d' , $r['create_time']);		
				
				if(strlen($r['avatar']) > 32)//qq同步的用户头像
				{
					$r['large_avatar']= hg_avatar($r['id'],"100",$r['avatar'],0);
					$r['middle_avatar']= hg_avatar($r['id'],"50",$r['avatar'],0);
					$r['small_avatar'] = hg_avatar($r['id'],"10",$r['avatar'],0);
				}
				else 
				{
					$r['larger_avatar']= hg_avatar($r['id'],"larger",$r['avatar']);
					$r['middle_avatar']= hg_avatar($r['id'],"middle",$r['avatar']);
					$r['small_avatar'] = hg_avatar($r['id'],"small",$r['avatar']);
				}	
				 
				$this->addItem($r);
				$this->output();
			}
			else
			{
				$this->errorOutput('用户不存在');	
			} 					
		}
		else
		{
			$this->errorOutput('未传入查询ID');		
		} 		
	}
	
	/**
	 * 获取查询条件
	 */
	public function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['username'])
		{
			$condition .= " AND username LIKE '%" . trim($this->input['keywords']) . "%' ";
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
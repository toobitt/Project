<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: pictures.php 22925 2013-05-29 07:30:03Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','pictures');//模块标识
require('global.php');
/**
 * 
 * 图片数据获取API
 * 
 * 提供的方法：
 * 1) 获取所有图片数据
 * 2) 获取单条图片数据
 * 3) 获取图片的总数
 * 
 * @author chengqing
 *
 */

class picturesShowApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	/**
	 * 获取所有图片
	 */
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		

		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = "SELECT p.*,a.albums_name FROM " . DB_PREFIX . "pictures p 
			LEFT JOIN " . DB_PREFIX . "albums a
				ON p.albums_id=a.albums_id
		WHERE 1 ";
			
		$sql = $sql . $condition  . $data_limit;
		$q = $this->db->query($sql);

		$this->setXmlNode('pictures_info' , 'picture');
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$row['pub_time'] = date('Y-m-d H:i:s' , $row['pub_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['file_path'] = PHOTO_URL . PHOTO_SIZE3 . '/' . $row['file_path'] . $row['file_name'];
			//$row['file_path'] = ALBUMS_COVER_URL . PHOTO_SIZE3 . '/001/12950691784045.jpg';
			
			$row['name'] = $row['name'] ? $row['name'] : $row['file_name']; //图片不存在时，将文件名赋给name
			$row['state'] = $this->settings['state'][$row['state']];
			if($this->settings['rewrite'])
			{
				$row['link'] = PHOTO_DOMAIN."picture-".$row['material_id'].".html";	
			}
			else 
			{
				$row['link'] = PHOTO_DOMAIN."?m=picture&picture_id=".$row['material_id'];
			}
			
			if($row['description'])
			{
				$row['description'] = '还未描述';
			}

			//hg_pre($row);
			$this->addItem($row);
		}
		
		$this->output();
	}
	
	/**
	 * 获取相册总数
	 * 默认为全部相册的总数
	 */
	public function count()
	{	
		$sql = "SELECT  COUNT(*) AS total  FROM " . DB_PREFIX . "pictures p 
			LEFT JOIN " . DB_PREFIX . "albums a
				ON p.albums_id=a.albums_id
		WHERE 1 ";
		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		
		echo json_encode($r);
	}
	
	/**
	 * 获取单条图片信息
	 */
	public function detail()
	{
		$this->input['material_id'] = urldecode($this->input['material_id']);
		if(!$this->input['material_id'])
		{
			$condition = ' ORDER BY material_id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE material_id in(' . $this->input['material_id'] .')';
		}		
		$sql = "SELECT * FROM " . DB_PREFIX . "pictures" . $condition;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('pictures_info' , 'pictures');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);	
			if($this->settings['rewrite'])
			{
				$row['link'] = PHOTO_DOMAIN."picture-".$row['material_id'].".html";	
			}
			else 
			{
				$row['link'] = PHOTO_DOMAIN."?m=picture&picture_id=".$row['material_id'];	
			}
			$row['file_path'] = PHOTO_URL . PHOTO_SIZE5 . '/' . $row['file_path'] . $row['file_name'];		
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('图片不存在');	
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
			$condition .= " AND p.name LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
		
		//查询某用户的相册
		if($this->input['user_name'])
		{
			$condition .= " AND p.user_name = '" . trim($this->input['user_name']) . "' ";	
		}
		
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND p.pub_time >= " . strtotime($this->input['pub_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND p.pub_time <= " . strtotime($this->input['end_time']);	
		}
		
		//查询图片的类型
		if(isset($this->input['state']))
		{
			$condition .= " AND p.state = " . intval($this->input['state']);	
		}
		
		//查询某相册下的图片
		if($this->input['_id'])
		{
			$condition .= " AND p.albums_id = " . intval($this->input['_id']);	
		}
					
		$orders = array('pub_time', 'thread_count', 'post_count', 'total_visit', 'group_id', 'today_visit', 'last_update', 'update_time');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (!in_array($this->input['hgorder'], $orders))
		{
			$this->input['hgorder'] = 'pub_time';
		}
		
		$orderby = ' ORDER BY p.' . $this->input['hgorder']  . ' ' . $descasc ;

		
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $orderby;
		
		return $condition;	
	}
}

$out = new picturesShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: albums.php 7916 2012-07-14 02:59:44Z hanwenbin $
***************************************************************************/

define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','cp_albums_m');//模块标识
require(ROOT_DIR . 'global.php');

/**
 * 
 * 相册数据获取API
 * 
 * 提供的方法：
 * 1) 获取所有相册数据
 * 2) 获取单条相册数据
 * 3) 获取指定相册的总数
 * 
 * @author chengqing
 *
 */
class albumsShowApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 获取所有相册
	 */
	public function show()
	{
		//分页参数设置
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		

		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;

		$sql = "SELECT * FROM " . DB_PREFIX . "albums WHERE 1 ";
		
		//获取查询条件
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $data_limit;
		
		$q = $this->db->query($sql);

		$this->setXmlNode('albums_info' , 'albums');
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['state'] = $this->settings['state'][$row['state']];
			$row['cover_file_name'] = ALBUMS_COVER_URL . PHOTO_SIZE3 . '/' . $row['cover_file_name'];
			//$row['cover_file_name'] = ALBUMS_COVER_URL . PHOTO_SIZE3 . '/001/12950683477266.jpg';
			if($this->settings['rewrite'])
			{
				$row['link'] = PHOTO_DOMAIN."albums-show-".$row['albums_id'].".html";	
			}
			else 
			{
				$row['link'] = PHOTO_DOMAIN."?m=albums&albums_id=".$row['albums_id']."&a=albums_view";	
			}
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
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "albums WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		
		echo json_encode($r);
	}
	
	/**
	 * 获取单条相册信息
	 */
	public function detail()
	{
		$this->input['albums_id'] = urldecode($this->input['albums_id']);
		if(!$this->input['albums_id'])
		{
			$condition = ' ORDER BY albums_id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE albums_id in(' . $this->input['albums_id'] .')';
		}			
		$sql = "SELECT * FROM " . DB_PREFIX . "albums " . $condition;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('thread_info' , 'thread');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);		
			if($this->settings['rewrite'])
			{
				$row['link'] = PHOTO_DOMAIN."albums-show-".$row['albums_id'].".html";	
			}
			else 
			{
				$row['link'] = PHOTO_DOMAIN."?m=albums&albums_id=".$row['albums_id']."&a=albums_view";
			}
			$row['cover_file_name'] = ALBUMS_COVER_URL . PHOTO_SIZE5 . '/' . $row['cover_file_name'];	
			$this->addItem($row);
			$this->output();
		}
		else
		{
			$this->errorOutput('相册不存在');	
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
			$condition .= " AND albums_name LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
		//状态查询
		if($this->input['albums_state'] && intval($this->input['albums_state']) != -1)
		{
			if(intval($this->input['albums_state']) == 1)
			{
				$condition .= " AND state = 1 ";	
			}
			else 
			{
				$condition .= " AND state = 0 ";
			}
		}
		
		//查询某用户的相册
		if($this->input['user_name'])
		{
			$condition .= " AND user_name = '" . trim($this->input['user_name']) . "' ";	
		}
		
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND create_time > " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND create_time < " . strtotime($this->input['end_time']);	
		}

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		//查询相册的类型
		if(isset($this->input['state']))
		{
			$condition .= " AND state = " . intval($this->input['state']);	
		}
				
		$orders = array('create_time', 'update_time', 'picture_count', 'comment_count', 'albums_id');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (!in_array($this->input['hgorder'], $orders))
		{
			$this->input['hgorder'] = 'create_time';
		}
		
		$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;

		
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $orderby;
		
		return $condition;	
	}
	
	/*获取相册以及相册里面的所有图片*/
	function look_alubms_pic()
	{
		if(!$this->input['albums_id'])
		{
			$this->errorOutput('未传相册id');
		}
		
		$ret = array();
		/*查询出该相册的信息*/
		$sql = "SELECT a.*,c.name FROM ".DB_PREFIX."albums AS a LEFT JOIN ".DB_PREFIX."albums_category AS c ON a.albums_category_id = c.albums_category_id WHERE a.albums_id = '".intval($this->input['albums_id'])."'";
		$arr = $this->db->query_first($sql);
		$arr['create_time'] = date('Y-m-d',$arr['create_time']);
		$arr['update_time'] = date('Y-m-d',$arr['update_time']);
		$ret['albums'] = $arr;
		
		$userinfo = $this->mUser->getUserById($arr['user_id']);

		/*查询出该相册下面的图片*/
		$sql = "SELECT * FROM ".DB_PREFIX."pictures WHERE albums_id = '".intval($this->input['albums_id'])."'";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$r['file_path'] = ALBUMS_COVER_URL . PHOTO_SIZE3 . '/' . $r['file_path'] . $r['file_name'];
			$ret['picture'][] = $r;
		}
		if(!empty($userinfo))
		{
			$ret['avatar'] = $userinfo[0]['small_avatar'];
		}
		$this->addItem($ret);
		$this->output();
	}
}

$out = new albumsShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
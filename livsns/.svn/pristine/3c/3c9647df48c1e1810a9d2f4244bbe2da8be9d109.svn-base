<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require_once './lib/epaper_mode.php';
define('MOD_UNIQUEID','epaper');//模块标识
class EpaperApi extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new epaper_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	function detail()
	{
		
	}
	public function show()
	{
		$epaper_id = intval($this->input['epaper_id']);	//报刊id
		
		$period_id = intval($this->input['period_id']);//期id
		$stack_id = intval($this->input['stack_id']);//叠id
		
		
		if(!$epaper_id && !$period_id && !$stack_id)
		{
			return false;
		}
		
		//期id
		if($epaper_id && !$period_id && !$stack_id)
		{
		
			$sql = "SELECT id,period_date FROM " . DB_PREFIX . "period WHERE 1 AND status = 1 AND epaper_id = " . $epaper_id;
			
			//根据日期检索某一期
			if($this->input['period_date'])
			{
				$period_date = strtotime($this->input['period_date']);
				$sql .= " AND period_date = " . $period_date . ' LIMIT 0,1';
			}
			else 
			{
				$orderby = '  ORDER BY period_date DESC LIMIT 0,1';
				$sql .= $orderby;
			}
			
			$query = $this->db->query_first($sql);
			$period_id = $query['id'];
			//$period_pub_date = date('Y-m-d',$query['period_date']);
			
			//$info['period_pub_date'] = $period_pub_date;
			if(!$period_id)
			{
				return FALSE;
			}
		}
		
		//叠id
		if($period_id && !$stack_id)
		{
			//查询当前期上下期id
			$next_pre = $this->check_next_pre($period_id, $epaper_id, 1);
			
			$info['period_next_id'] 	= $next_pre['next_id']; 
			$info['period_pre_id'] 		= $next_pre['pre_id']; 
			
			$info['period_pub_date'] 	= $next_pre['period_date'];
			
			
			//查询期下面叠
			$sql = "SELECT id,name,zm FROM " . DB_PREFIX . "stack WHERE period_id = " . $period_id . " ORDER BY id ASC";
			$q = $this->db->query($sql);
			
			while($r = $this->db->fetch_array($q))
			{
				if(!$stack_id)
				{
					$stack_id = $r['id'];
				}
				$stack[$r['id']] = $r['name'];
			}
			
			//没有传递stack_id,查询当前期的叠信息
			$info['stack_info'] 	= $stack;
		}
		
		
		//叠id不存在，返回false
		if(!$stack_id)
		{
			return false;
		}
		
		
		//查询叠下面版面和新闻
		$sql = "SELECT id,title FROM " . DB_PREFIX . "page WHERE stack_id = " . $stack_id . " ORDER BY order_id ASC";
		$query = $this->db->query($sql);
		$first_page_id = '';
		while ($r = $this->db->fetch_array($query))
		{
			if(!$first_page_id)
			{
				$first_page_id 	= $r['id'];
			}
			/*if($r['hot_area'])
			{
				$r['hot_area'] = unserialize($r['hot_area']);
			}*/
			$page[$r['id']] = $r;
		}
		
		
		//查询叠下面文章
		$sql = "SELECT id, title, page_id FROM " . DB_PREFIX . "article WHERE stack_id = " . $stack_id . " ORDER BY order_id ASC";
		$query = $this->db->query($sql);
		while ($r = $this->db->fetch_array($query))
		{
			$article[$r['page_id']][] = $r; 
		}
		
		if($page)
		{
			foreach ($page as $page_id => $val)
			{
				$page_info[$page_id] = $val;
				if($article[$page_id])
				{
					$page_info[$page_id]['article_info'] = $article[$page_id];
				}
			}
			
			//页信息
			$info['page_info'] = $page_info;
		}
		
		//查询叠下头版
		if($first_page_id)
		{
			/*$sql = 'SELECT host,dir,filepath,filename,mark FROM '.DB_PREFIX."material WHERE page_id = ".$first_page_id;
			$query = $this->db->query($sql);
			while ($r = $this->db->fetch_array($query))
			{
				if($r['mark'] == 'img')
				{
					$mater['img'] = $r; 
				}
				else 
				{
					$mater['pdf'] = $r;
				}
			}
			
			//查询头版的下一版id
			$page_next_pre = $this->check_next_pre($first_page_id, $stack_id);
			$mater['page_pre_id'] 	= $page_next_pre['pre_id'];
			$mater['page_next_id'] 	= $page_next_pre['next_id'];
			
			//头版信息
			$info['mater']			= $mater;*/
			$mater = $this->get_mater_by_page_id($first_page_id);
			
			if(!empty($mater))
			{
				if($mater['img'])
				{
					$info['img'] = $mater['img'];
					$info['img']['title'] = $mater['title'];
				}
				
				if($mater['pdf'])
				{
					$info['pdf'] = $mater['pdf'];
					$info['pdf']['title'] = $mater['title'];
				}
				
				if($mater['hot_area'])
				{
					$info['hot_area'] = $mater['hot_area'];
				}
				
				$info['page_pre_id'] 	= $mater['page_pre_id'];
				$info['page_next_id']	= $mater['page_next_id'];
			}
		}
		//hg_pre($info,0);
		
		$this->addItem($info);
		$this->output();
	}
	private function get_condition()
	{
		return $condition;
	}
	
	
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'article m '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	
	/**
	 * 点击某一页操作
	 * Enter description here ...
	 */
	public function get_page()
	{
		$page_id = intval($this->input['page_id']);
		if(!$page_id)
		{
			return FALSE;
		}
		$data = $this->get_mater_by_page_id($page_id);
		
		//hg_pre($data,0);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 根据页id取图片和pdf
	 * Enter description here ...
	 */
	public function get_mater_by_page_id($page_id)
	{
		//$page_id = intval($this->input['page_id']);
		if(!$page_id)
		{
			return false;
		}
		
		//根据页id查询页所有的叠和热区信息
		$sql = "SELECT title,stack_id,hot_area FROM " . DB_PREFIX . "page WHERE id = ".$page_id;
		$res = $this->db->query_first($sql);
		if(!$res)
		{
			return false;
		}
		if($res['hot_area'])
		{
			$mater['hot_area'] = unserialize($res['hot_area']);
		}
		
		$mater['title'] = $res['title'];
		
		$stack_id = $res['stack_id'];
		
		
		//查询页下的素材
		$sql = 'SELECT host,dir,filepath,filename,mark FROM '.DB_PREFIX."material WHERE page_id = " . $page_id;
		$query = $this->db->query($sql);
		while ($r = $this->db->fetch_array($query))
		{
			if($r['mark'] == 'img')
			{
				$mater['img'] = $r; 
			}
			else 
			{
				$mater['pdf'] = $r;
			}
		}
		
		$page_next_pre = $this->check_next_pre($page_id, $stack_id);
		$mater['page_pre_id'] 	= $page_next_pre['pre_id'];
		$mater['page_next_id'] 	= $page_next_pre['next_id'];
		
		return $mater;
	}
	
	
	//检查上下篇
	private function check_next_pre($id,$oid,$type='')
	{
		$field_s = 'id';
		//上下期
		if($type == 1)
		{
			$table_name = 'period';
			$field = 'epaper_id';
			$order = 'period_date DESC';
			$field_s .= ',period_date';
		}
		else if($type == 2)//上下篇文章
		{
			$table_name = 'article';
			$field = 'page_id';
			$order = ' order_id ASC';
		}
		else //上一版下一版
		{
			$table_name = 'page';
			$field = 'stack_id';
			$order = 'order_id ASC';
		}
		
		$sql = 'SELECT ' . $field_s . ' FROM '.DB_PREFIX . $table_name . ' WHERE '.$field.' = '.$oid.' ORDER BY '.$order;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			if($r['period_date'] && $r['id'] == $id)
			{
				$period_date = date('Y-m-d',$r['period_date']);
			}
			$ids[] = $r['id'];
		}
		if(!empty($ids))
		{
			$k = array_search($id,$ids);
			$data['pre_id'] = $ids[$k-1];//上一篇文章id
			$data['next_id'] = $ids[$k+1];//下篇文章id
		}
		
		if(!$data['pre_id'])
		{
			$data['pre_id'] = 0;
		}

		if(!$data['next_id'])
		{
			$data['next_id'] = -1;
		}
		//返回期时间
		$data['period_date'] = $period_date;
		return $data;
	}
	
	/**
	 * 查询报刊下的所有期刊
	 * Enter description here ...
	 */
	public function get_period()
	{
		$epaper_id = intval($this->input['epaper_id']);
		if(!$epaper_id)
		{
			return FALSE;
		}
		include_once(CUR_CONF_PATH . 'lib/period_mode.php');
		$obj = new period_mode();
		$condition = " AND p.status=1 AND p.epaper_id = " . $epaper_id;
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):20;
		$order_by = ' ORDER BY p.period_date DESC,p.period_num DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$data = $obj->show($condition,$order_by,$limit);
		if(!empty($data))
		{
			if($this->input['need_count'])
			{
							
				$totalcount = $this->return_count();
				$this->addItem_withkey('total',$totalcount['total'] );
				$this->addItem_withkey('data',$data );
			}
			else
			{
				foreach ($data as $k=>$v)
				{
					$this->addItem($v);	
				}
			}
			$this->output();
		}
	}
	
	//往期分页
	function return_count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'period  WHERE epaper_id = '. intval($this->input['epaper_id']);
		$res = $this->db->query_first($sql);
		return $res;
	}
	
	/**
	 * 查询文章信息
	 * Enter description here ...
	 * @param $type 传递type额外查询新闻所在的页信息 
	 */
	public function get_article()
	{
		$id = intval($this->input['article_id']);
		if(!$id)
		{
			return false;
		}
		include_once './lib/article_mode.php';
		$obj = new article_mode();
		$info = $obj->detail($id);
		if($info)
		{
			$info['content'] = htmlspecialchars_decode($info['content']);
			if ($this->input['need_process'])
			{
				$info['content'] = strip_tags($info['content'], '<p><br><a><img><div>');
				$info['content'] = preg_replace('#<p[^>]*>#i','<p>',$info['content']);
			}
			
			
			$page_id = $info['page_id'];
			if($page_id)
			{
				$next_pre = $this->check_next_pre($id,$page_id,2);
				
				$info['pre_id'] = $next_pre['pre_id'];
				$info['next_id'] = $next_pre['next_id'];
				
			}
			
			//额外查询新闻所在的页信息
			if($this->input['type'] && $info['page_id'])
			{
				$page_id = $info['page_id'];
				if(!$page_id)
				{
					return false;
				}
				$mater = $this->get_mater_by_page_id($page_id);
				
				if(!empty($mater))
				{
					if($mater['img'])
					{
						$info['img'] = $mater['img'];
					}
					
					if($mater['pdf'])
					{
						$info['pdf'] = $mater['pdf'];
					}
					
					if($mater['hot_area'])
					{
						$info['hot_area'] = $mater['hot_area'];
					}
					
					$info['page_pre_id'] 	= $mater['page_pre_id'];
					$info['page_next_id']	= $mater['page_next_id'];
				}
				
			}
			//hg_pre($info,0);
			
			$this->addItem($info);
			$this->output();
		}
	}
	
	//版面预览，获取某一期的所有版面图片
	public function get_all_mater()
	{
		$period_id = intval($this->input['period_id']);
		if(!$period_id)
		{
			return FALSE;
		}
		$order_by = " ORDER BY p.stack_id ASC,p.order_id ASC ";
		$sql = "SELECT p.id,p.title,p.order_id,m.host,m.dir,m.filepath,m.filename FROM " . DB_PREFIX . "page p 
					LEFT JOIN " . DB_PREFIX . "material m 
				ON p.jpg_id = m.id
				WHERE p.period_id = " . $period_id . $order_by;
		
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$data[] = $r;
		}
		//hg_pre($data,0);
		$this->addItem($data);
		$this->output();
	}
}

$ouput= new EpaperApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>
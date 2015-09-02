<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
require_once '../lib/article.class.php';
define('MOD_UNIQUEID','maga_article');//模块标识
class Article extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->article = new ArticleClass();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
	}
	function show()
	{
		if(!$this->input['issue_id'])
		{
			return false;
		}
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_issue'));
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		
		$orderby = ' ORDER BY a.order_id  DESC';
		$res = $this->article->show($this->get_condition(),$orderby,$offset,$count);
		
		$issue_id = intval($this->input['issue_id']);
		if($issue_id)
        {

        	$sql = "SELECT * FROM ".DB_PREFIX."issue WHERE id = ".$issue_id;
			$issue_info = $this->db->query_first($sql);
			if($issue_info)
			{
				$maga_name = urldecode($this->input['maga_name']);
				if(!$maga_name && $this->input['maga_id'])
				{
					$sql = "SELECT name FROM ".DB_PREFIX."magazine WHERE id = ".intval($this->input['maga_id']);
					$maga_res = $this->db->query_first($sql);
					
					$maga_name = $maga_res['name'];
				}
				$issue_info['maga_name'] = $maga_name;
				
				$issue_info['pub_date'] = date('Y-m-d',$issue_info['pub_date']);
			}
        	
        	
        	if(!$this->input['get_more_article'])
        	{
	        	$sql = "SELECT id,name,article_num,cur_article_num FROM ".DB_PREFIX."catalog WHERE issue_id = ".$issue_id;
	        	
	        	$q = $this->db->query($sql);
	        	
	        	while ($r = $this->db->fetch_array($q))
	        	{
	        		$sort_arr[] = $r;  
	        	}
	        	if($sort_arr)
	        	{
	        		$issue_info['sort_info'] = $sort_arr;
	        	}
	        	if(!$res)
	        	{
	        		$res[] = $issue_info;
	        	}
	        	else 
	        	{
	        		array_unshift($res,$issue_info);
	        	}
        	}
        }
		if($res)
		{
			foreach ($res as $k=>$v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'article a WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	private function get_condition()
	{
		$condition = '';
		//权限判断
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND a.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND a.org_id IN (' . $this->user['slave_org'] . ')';
			}
		}
		
		if ($this->input['issue_id'])
		{
			$condition .= ' AND a.issue_id = '.intval($this->input['issue_id']);
		}
		if($this->input['id'])
		{
			$condition .= ' AND a.id='.intval($this->input['id']);
		}
		
		if($this->input['k'])
		{
			$condition .= ' AND a.title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		
		//创建者
		if($this->input['user_name'])
		{
			$condition .= " AND a.user_name LIKE '%".trim($this->input['user_name'])."%'";
		}
		
		//作者
		if($this->input['author'])
		{
			$condition .= " AND a.article_author LIKE '%".trim($this->input['author'])."%'";
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND a.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND a.create_time <= ".$end_time;
		}
		if($this->input['maga_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['maga_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  a.create_time > ".$yesterday." AND a.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  a.create_time > ".$today." AND a.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  a.create_time > ".$last_threeday." AND a. create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND a.create_time > ".$last_sevenday." AND a.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	function detail()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_issue'));
		
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('没有id');
		}
		$res = $this->article->detail($id);
		$this->addItem($res);
		$this->output();		
	}
	
	function get_keywords()
	{
		if($content = htmlspecialchars_decode($this->input['content']))
		{
			$num = intval($this->input['num']);
			$result = $this->xs_get_keyword($content,empty($num)?'':$num);
			$this->addItem($result);
			$this->output();
		}
		else
		{
			$this->addItem(array());
			$this->output();
		}
	}
	
	public function get_article()
	{
		$this->verify_content_prms(array('_action'=>'manage_issue'));
		
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$offset = intval(($pp - 1)*$count);	
		
		$orderby = ' ORDER BY a.order_id  DESC';
		
		$res = $this->article->show($this->get_condition(),$orderby,$offset,$count);
		
		$issue_id = intval($this->input['issue_id']);
		
		if(!$issue_id)
		{
			return false;
		}
		
		$sql = "SELECT id,name,article_num,cur_article_num FROM ".DB_PREFIX."catalog WHERE issue_id = ".$issue_id;
        $q = $this->db->query($sql);
        	
        while ($r = $this->db->fetch_array($q))
        {
        	$sort_arr[] = $r;  
        }
        if($sort_arr)
        {
        	$data['sort_info'] = $sort_arr;
        }
        
        //分页信息
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'article a WHERE 1'.$this->get_condition();
		$re = $this->db->query_first($sql);
        $total_num = $re['total'];//总的记录数
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$return['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$return['total_page']    = intval($total_num/$count) + 1;
		}
		$return['total_num'] = $total_num;//总的记录数
		$return['page_num'] = $count;//每页显示的个数
		$return['current_page']  = $pp;//当前页码
		
		$data['info'] = $res;
		$data['page_info'] = $return;
		
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 加载更多文章，暂时不用
	 * Enter description here ...
	 */
	public function get_more_article()
	{
		$this->input['get_more_article'] = 1;
		$this->show();
	}
}

$ouput= new Article();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
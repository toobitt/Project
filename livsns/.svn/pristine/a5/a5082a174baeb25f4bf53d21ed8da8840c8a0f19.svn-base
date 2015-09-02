<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require_once './lib/article.class.php';
define('MOD_UNIQUEID','maga_article');//模块标识
require(CUR_CONF_PATH."lib/functions.php");

class ArticleApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->obj = new ArticleClass();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		if(!$this->input['issue_id'] || $this->input['issue_id'] == -1)
		{
			return false;
		}
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):50;
		
		$orderby = ' ORDER BY a.order_id  DESC';
		$condition = $this->get_condition();
		$res = $this->obj->show($this->get_condition(),$orderby,$offset,$count);
		if($res)
		{
			if($this->input['show_type'])
			{
				foreach($res as $k => $v)
				{
					if($v['sort_name'])
					{
						$data[$v['sort_name']][] = $v;
					}
					else
					{
						$data['未分类'][] = $v;
					}
					
				}
				$this->addItem($data);
			}
			else
			{
				
				foreach ($res as $k=>$v)
				{
					$this->addItem($v);
				}
			}
		}
		//hg_pre($data,1);
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		if ($this->input['issue_id'])
		{
			$condition .= ' AND a.issue_id = '.intval($this->input['issue_id']);
		}
		$condition .= " AND a.state = 1 ";
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'article a '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	//查询文章信息
	function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('没有文章id');
		}
		$data = $this->obj->detail($id);
		
		//过滤
		if($this->input['need_process'])
		{
			$content = $data['content'];
			$need_process = $this->input['need_process'];
			$data['content'] = $need_process?strip_tags(htmlspecialchars_decode($content), '<p><br><a>'):$content;
			$data['content'] = preg_replace('#<p[^>]*>#i','<p>',$data['content']);
		}
		
		//图文分离
		if($this->input['need_manage'])
		{
			$need_pages = intval($this->input['need_page']);
			$after_process = $this->obj->content_manage($url, $dir, $data['content'], $need_pages, 0,true);
			$data['content'] 		= $after_process['content'];
			$data['content_pics'] 	= $after_process['content_pics'];
		}
		
		$sql = 'SELECT issue_id FROM '.DB_PREFIX.'article WHERE id='.$id;
		$res = $this->db->query_first($sql);
		$issue_id = $res['issue_id'];
		
		if(!$issue_id)
		{
			return false;
		}
		
		$sql = 'SELECT id,title FROM '.DB_PREFIX.'article WHERE issue_id = '.$issue_id.' ORDER BY order_id DESC';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$ids[] = $r['id'];
			$title[$r['id']] = $r['title'];
		}
		$k = array_search($id,$ids);
		$data['pre_id'] = $ids[$k-1];//上一篇文章id
		$data['pre_title'] = $title[$ids[$k-1]];//上一篇文章标题
		if(!$data['pre_id'])
		{
			$data['pre_id'] = 0;
			$data['pre_title'] = '这已经是第一篇了';
		}
		$data['next_id'] = $ids[$k+1];//下篇文章id
		$data['next_title'] = $title[$ids[$k+1]];

		if(!$data['next_id'])
		{
			$data['next_id'] = -1;
			$data['next_title'] = '这已经是最后一篇';
		}
		$this->addItem($data);
		$this->output();
	}
}
$ouput= new ArticleApi();
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
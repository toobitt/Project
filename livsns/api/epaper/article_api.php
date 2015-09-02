<?php
define(ROOT_DIR, '../../');
require(ROOT_DIR . 'global.php');
require_once './lib/article_mode.php';
require_once('./lib/functions.php');

define('MOD_UNIQUEID','epaper');//模块标识
class ArticleApi extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new article_mode();
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
		$id = intval($this->input['article_id']);
		if(!$id)
		{
			return false;
		}
		$info = $this->mode->detail($id);
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
				$next_pre = $this->check_next_pre($id, $page_id);
				
				$info['pre_id'] = $next_pre['pre_id'];
				$info['next_id'] = $next_pre['next_id'];
				
			}
			//hg_pre($info,0);
			
			$this->addItem($info);
			$this->output();
		}
	}
	private function get_condition()
	{
		if($this->input['k'])
		{
			$condition .= ' AND title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
	
		if ($this->input['article_id'])
		{
			$condition .= ' AND id IN ('.$this->input['article_id'].')'; 
		}
		
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'article '.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	function return_count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'article '.$this->get_condition();
		$res = $this->db->query_first($sql);
		return $res;
	}
	
	//检查上下篇
	private function check_next_pre($id,$oid)
	{
		$table_name = 'article';
		$field = 'page_id';
		$order = ' order_id ASC';
		
		$sql = 'SELECT id FROM '.DB_PREFIX . $table_name . ' WHERE '.$field.' = '.$oid.' ORDER BY '.$order;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$ids[] = $r['id'];
		}
		$k = array_search($id,$ids);
		$data['pre_id'] = $ids[$k-1];//上一篇文章id
		if(!$data['pre_id'])
		{
			$data['pre_id'] = 0;
		}
		
		$data['next_id'] = $ids[$k+1];//下篇文章id

		if(!$data['next_id'])
		{
			$data['next_id'] = -1;
		}
		
		return $data;
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
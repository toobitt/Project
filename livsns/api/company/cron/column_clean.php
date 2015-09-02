<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
define('MOD_UNIQUEID','column_clean');//模块标识
include_once CUR_CONF_PATH . 'lib/content.class.php';
class columnCleanApi extends cronBase
{
	private $api;
	public function __construct()
	{
		$this->api = new content();
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '删除内容栏目任务',	 
			'brief' => '删除栏目的同时删除内容上的栏目',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'column_cid_buffer WHERE  1 ORDER BY id ASC LIMIT 0,10';
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[] = $row; 
		}
		
		if (!empty($arr))
		{
			foreach ($arr as $val)
			{
				
				$content_info = array();
				$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$val['cid'];
				$res = $this->db->query_first($sql);
				$column_id		= $res['column_id'] ? (@unserialize($res['column_id']) ? unserialize($res['column_id']) : '') : '';
				if (is_array($column_id) && $column_id[$val['column_id']])
				{
					unset($column_id[$val['column_id']]);
				}
				$column_path	= $res['column_path'] ? (@unserialize($res['column_path']) ? unserialize($res['column_path']) : '') : '';
				if (is_array($column_path) && $column_path[$val['column_id']])
				{
					unset($column_path[$val['column_id']]);
				}
				if ($column_id && is_array($column_id) && !empty($column_id))
				{
					$new_column_id = $column_id;
					$column_id = array_keys($column_id);
					$column_id = implode(',', $column_id);
				}
				else
				{
					$column_id = '';
				}
				if ($column_path && is_array($column_path) && !empty($column_path))
				{
					$column_path = serialize($column_path);
				}
				else
				{
					$column_path = '';
				}
				
				if ($res['id'])
				{					
					switch ($res['source'])
					{
						case 'news':
						$content_info = $this->news_column($res['source_id'], $column_id);
						break;
						case 'photo':
							$content_info = $this->photo_column($res['source_id'], $column_id);
						break;
						case 'video':
							$content_info = $this->video_column($res['source_id'], $column_id);
						break;
						case 'vote':
							$content_info = $this->vote_column($res['source_id'], $column_id);
						break;
					}					
					if ($new_column_id && is_array($new_column_id) && !empty($new_column_id))
					{
						$new_column_id = serialize($new_column_id);
					}
					else
					{
						$new_column_id = '';
					}
					$sql = 'UPDATE '.DB_PREFIX.'content SET column_id = "'.addslashes($new_column_id).'", 
							column_path = "'.addslashes($column_path).'" WHERE id = '.$res['id'];
					$this->db->query($sql);
					$sql = 'DELETE FROM '.DB_PREFIX.'column_cid_buffer WHERE cid  = '.$val['cid'];
					$this->db->query($sql);
					
				}
				else 
				{
					$sql = 'DELETE FROM '.DB_PREFIX.'column_cid_buffer WHERE cid  = '.$val['cid'];
					$this->db->query($sql);
				}
				
			}
			exit('执行完成');
		}
		else 
		{
			exit('无执行数据');
		}
	}
	
	//文稿签发
	private function news_column($id, $column_id)
	{		
		include_once ROOT_PATH . 'lib/class/news.class.php';
		$newsApi = new news();
		return $newsApi->update_column($id, $column_id);
	}
	//视频签发
	private function photo_column($id, $column_id)
	{
		include_once ROOT_PATH . 'lib/class/tuji.class.php';
		$picApi = new tuji();
		return  $picApi->update_column($id, $column_id);
	}
	//图集签发
	private function video_column($id, $column_id)
	{
		include_once ROOT_PATH . 'lib/class/livmedia.class.php';
		$videoApi = new livmedia();
		return $videoApi->update_column($id, $column_id);
	}
	//投票签发
	private function vote_column($id, $column_id)
	{
		include_once ROOT_PATH . 'lib/class/vote.class.php';
		$voteApi = new vote();
		return $voteApi->update_column($id, $column_id);
	}
}

$out = new columnCleanApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
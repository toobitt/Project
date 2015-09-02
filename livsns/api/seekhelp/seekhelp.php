<?php
require_once './global.php';
define('MOD_UNIQUEID','seekhelp');//模块标识
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
require_once CUR_CONF_PATH.'lib/seekhelp_blacklist_mode.php';
class seekhelpApi extends outerReadBase
{
	private $blacklist;
	public function __construct()
	{
		parent::__construct();
		$this->sh = new ClassSeekhelp();
		$this->blacklist = new seekhelp_blacklist_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{		
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;
		$orderby = ' ORDER BY is_top DESC,order_id  DESC';
		$condition = $this->get_condition();
		//dingdone判断sort_id=0获取数据错误
		if(!$this->input['sort_id'])
		{
			$this->errorOutput(NO_SORT_ID);
		}
		
		//检查社区黑名单
		$blackInfo = $this->blacklist->is_black($this->input['sort_id']);
		if($blackInfo)
		{
			$this->errorOutput(SEEKHELP_IS_BLACK);
		}
		
		$user_id = ($this->user && $this->user['user_id']) ? $this->user['user_id'] : 0;
		$res = $this->sh->show($condition,$orderby,$offset,$count, $user_id);
		
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				//替换图片节点
				$val['content'] = html_entity_decode($val['content']);
				for ($i = 0;i<100;$i++)
				{
					if (strstr($val['content'],"pic_".$i.""))
					{
							$search = '<div m2o_mark="pic_'.$i.'" style="display:none"></div>';
							$replace = '[图片'.($i+1).']';
							$val['content'] = str_replace($search, $replace, $val['content']);
							
							continue;
					}
					else
					{
						break;
					}
				}
				$this->addItem($val);
			}
		}
		
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		if ($this->input['id'])
		{
			$condition .= ' AND sh.id = '.intval($this->input['id']);
		}
		if ($this->input['sort_id'])
		{
			$sql = " SELECT childs,fid FROM ".DB_PREFIX."sort WHERE  id IN (".$this->input['sort_id'].")";
			$q = $this->db->query($sql);
			
			$childs = '';
			while ($r = $this->db->fetch_array($q))
			{
				if($childs)
				{
					$childs .=  ',' . $r['childs'];
				}
				else 
				{
					$childs = $r['childs'];
				}
				
			}
			if($childs)
			{
				$condition .= ' AND sh.sort_id IN (' . $childs . ')';
			}
			else
			{
                $this->errorOutput(THIS_SORT_NOT_EXISTS);			    
			}
		}
		if (isset($this->input['is_push']))
		{
			$condition .= ' AND sh.is_push IN (' . $this->input['is_push'] . ')';
		}
		if ($this->input['member_id'])
		{
			$condition .= ' AND sh.member_id = '.intval($this->input['member_id']);
		}
		if ($this->input['status'])
		{
			$condition .= ' AND sh.status IN ('.$this->input['status'] . ')';
		}
		if ($this->input['joint'])
		{
			$sql = 'SELECT cid FROM '.DB_PREFIX.'joint WHERE member_id = '.$this->user['user_id'];
			$query = $this->db->query($sql);
			$cids = array();
			while ($row = $this->db->fetch_array($query))
			{
				$cids[] = $row['cid'];
			}
			if (!empty($cids))
			{
				$condition .= ' AND sh.id IN ('.implode(',', $cids).')';
			}else {
				$this->addItem(array());
				$this->output();
			}
		}
		if ($this->input['attention'])
		{
			$sql = 'SELECT cid FROM '.DB_PREFIX.'attention WHERE member_id = '.$this->user['user_id'];
			$query = $this->db->query($sql);
			$cids = array();
			while ($row = $this->db->fetch_array($query))
			{
				$cids[] = $row['cid'];
			}
			if (!empty($cids))
			{
				$condition .= ' AND sh.id IN ('.implode(',', $cids).')';
			}else {
				$this->addItem(array());
				$this->output();
			}
		}
		return $condition;
	}
	
	public function count()
	{
		$ret = $this->sh->count($this->get_condition());
		echo json_encode($ret);
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		$member_id = intval($this->user['user_id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->sh->detail($id, $member_id);
		$this->addItem($data);
		$this->output();
	}
	
	public function sort()
	{
		$id = $this->input['id'];
		
		$id = $id ? $id : 0;
		$need_all = $this->input['need_all'];
		
		$exclude_id = intval($this->input['exclude_id']);
		
		$main_sort = intval($this->input['main_sort']);
		$data = $this->sh->sort($id, $exclude_id,$main_sort);
		
		
		if($need_all)
		{
			$arr = array(
				'id'	=> 0,
				'name'	=> '全部',
			);
			array_unshift($data,$arr);
		}
		if (!empty($data))
		{
			foreach ($data as $k=>$v)
			{	
				$this->addItem($v);
			}
		}
		$this->output();
	}	
}
$ouput = new seekhelpApi();
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
<?php
define('MOD_UNIQUEID','mood');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/mood_style_mode.php');
require_once(CUR_CONF_PATH . 'lib/mood_mode.php');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
class mood_style extends outerReadBase
{
	private $Mstyle;
	private $Mood;
	private $publishcontent;
    public function __construct()
	{
		parent::__construct();
		$this->Mstyle = new mood_style_mode();
		$this->Mood = new mood_mode();
		$this->publishcontent = new publishcontent();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();  //显示状态已审核的所有心情样式
		$orderby = '  ORDER BY order_id DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->Mstyle->show($condition,$orderby,$limit);

		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function count()
	{
		$condition = '';
		$info = $this->Mstyle->count($condition);
		echo json_encode($info);
	}
	
	public function detail()
	{
		$rid = intval($this->input['rid']);
		$cid = intval($this->input['cid']);
		$app_uniqueid = trim($this->input['app_uniqueid']);
		if(!$rid)
		{
			if(!$cid || !$app_uniqueid)
			{
				$error = '数据错误！';
			}
		}
		if($rid)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "mood WHERE rid = " . $rid ;
			$ret = $this->db->query_first($sql);
			$list_id = $ret['id'];
		}
		elseif($cid && $app_uniqueid)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "mood WHERE cid = " . $cid ." AND app_uniqueid like '" .$app_uniqueid ."'";
			$ret = $this->db->query_first($sql);
			$list_id = $ret['id'];
		}
		
		if($list_id)
		{
			$condition = " AND list_id = " . $list_id;
			//获取心情结果
			$mood_result = $this->Mood->get_mood_result($condition,$ret['mood_style']);
			if($mood_result)
			{
				$ret['total_count'] = $mood_result['total_count'];
				$ret['result'] = $mood_result['result'];  //点击量详情
			}
			
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	/**
	 * 顶踩排行
	 * Enter description here ...
	 */
	public function rank()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$mood = $this->input['mood'];
		//$artical_id = $this->input['rid'];
		if(!$mood)
		{
			$this->errorOutput('请输入顶踩选项id');
		}
		$condition = $this->rank_condition();
		$condition .= ' AND  r.mood_id = ' . $mood ;
		$condition .= ' AND m.rid >0 ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = 'SELECT count(*) as total , r.list_id as ct , m.* FROM  '. DB_PREFIX . 'mood_record r LEFT JOIN '. DB_PREFIX . 'mood m ON m.id = r.list_id  WHERE 1 '.$condition.' GROUP BY r.list_id ORDER BY total DESC '.$limit;
 		$q = $this->db->query($sql);
 		while($r = $this->db->fetch_array($q)) 
 		{
 			//$rank[] = $r;
 			$rid[] = $r['rid'];
 			$rid_t[] = array(
 			    'rid'  => $r['rid'],
 			    'total' => $r['total'],
 			);
  		}
 		if(!$rid || count($rid)<1)
 		{
 			$this->errorOutput(NOCONTENTS);
 		}
 		$rids = implode(',',$rid);
 		if($rids)
 		{
 			$content = $this->Mood->get_publishcontent($rids);
 		}
 		foreach ($rid_t as $k=>$v)
 		{
 			if($content[$v['rid']]['title'])
 			{
 				$cc[] = array(
 			        'rid'    => $v['rid'],
 			        'title'  => $content[$v['rid']]['title'],
 			        'content_url' => $content[$v['rid']]['content_url'],
 			        'indexpic' => $content[$v['rid']]['indexpic'],
 			        'total'   => $v['total'],
 					'is_indexpic' => $content[$v['rid']]['is_indexpic'],
				    'subtitle'    => $content[$v['rid']]['subtitle'],
				    'brief'       => $content[$v['rid']]['brief'],
				    'keywords'    => $content[$v['rid']]['keywords'],
				    'publish_time'=> $content[$v['rid']]['publish_time'],
				    'create_time' => $content[$v['rid']]['create_time'],
				    'author'      => $content[$v['rid']]['author'],
				    'tcolor'      => $content[$v['rid']]['tcolor'],
				    'isbold'      => $content[$v['rid']]['isbold'],
				    'isitalic'    => $content[$v['rid']]['isitalic'],
 			); 
 			}
 		}
 		if($cc && count($cc)>0)
 		{
 			foreach ($cc as $v)
 			{
 				$this->addItem($v);
 			}
 		}
 		$this->output();
	}
	
	public function get_condition()
	{
		$condition = ' AND status = 1 ';
		if(intval($this->input['id']))
		{			
			$condition .= ' AND id ='.intval($this->input['id']);
		}
		return $condition;
	}
	
	public function rank_condition()
	{
		$condition = '';
		if(trim($this->input['rid']))
		{			
			$condition .= ' AND m.rid in ('.trim($this->input['rid']) .')';
		}
		
		if(trim($this->input['column_id']))
		{			
			$condition .= ' AND m.column_id NOT IN ('.trim($this->input['column_id']) .')';
		}
			
		if(intval($this->input['is_indexpic']))
		{			
			$condition .= ' AND m.is_indexpic ='.intval($this->input['is_indexpic']) ;
		}
				
		if(trim($this->input['app_uniqueid']))
		{			
			$condition .= ' AND m.app_uniqueid like "%'.trim($this->input['app_uniqueid']) .'%"';
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
					$condition .= " AND  r.create_time > '".$yesterday."' AND r.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  r.create_time > '".$today."' AND r.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  r.create_time > '".$last_threeday."' AND r.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  r.create_time > '".$last_sevenday."' AND r.create_time < '".$tomorrow."'";
					break;
				case 6://最近30天
					$last_month = strtotime(date('y-m-d',TIMENOW-31*24*3600));
					$condition .= " AND  r.create_time > '".$last_month."' AND r.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
}

$out = new mood_style();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>
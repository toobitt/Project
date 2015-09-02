<?php
require_once('./global.php');
require_once(CUR_CONF_PATH . 'core/reply.dat.php');
define('MOD_UNIQUEID','reply_manage');
class ReplyManage extends adminReadBase
{
	function __construct()
	{
		$this->resetPrmsMethods();
		$this->mModPrmsMethods['manage'] = array(
			'name' => '管理',
		);
		parent::__construct();
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
		$data['_action'] = 'manage';
		$this->verify_content_prms($data);
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		
		$orders = array('id');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (in_array($this->input['hgorder'], $orders))
		{
			$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;
		}
		else
		{
			$orderby = ' ORDER BY r.id desc ';
		}
	
		$condition = $this->get_condition();

		$obj = new Reply();
		$res = $obj->show($condition,$orderby,$limit);
		if(count($res))
		{
			foreach ($res as $k => $v)
			{
				if($v['state'] == '1')
				{
					$v['state'] = '已审核';
				}
				else if($v['state'] == '2')
				{
					$v['state'] = '打回';
				} 
				else 
				{
					$v['state'] = '待审核';
				}
				if(!$v['answerer'])
				{
					$v['answerer'] = $v['ip'];
				}
				$this->addItem($v);
			}
		}
		$this->output();
	}
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND r.content_reply LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND r.id = '.intval($this->input['id']);
		}
		if ($this->input['contentid'])
		{
			$condition .= ' AND r.contentid = '.intval($this->input['contentid']);
		}
		
		if($this->input['message_status'] == 1)
		{
			$condition .= ' AND r.state = 0';	
		}
		else if($this->input['message_status'] == 2)
		{
			$condition .= ' AND r.state = 1';	
		}
		else if($this->input['message_status'] == 3)
		{
			$condition .= ' AND r.state = 2';
		}
		//开始结束时间相同，默认检索当天的
		if($this->input['start_time'] && $this->input['end_time'] && $this->input['start_time'] == $this->input['end_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$end_time = $start_time + 24*3600;
			$condition .= " AND  r.reply_time >= '".$start_time."' AND r.reply_time < '".$end_time."'";
		}
		else 
		{
			if($this->input['start_time'])
			{
				$start_time = strtotime(trim(urldecode($this->input['start_time'])));
				$condition .= " AND r.reply_time >= '".$start_time."'";
			}
			
			if($this->input['end_time'])
			{
				$end_time = strtotime(trim(urldecode($this->input['end_time'])));
				$condition .= " AND r.reply_time <= '".$end_time."'";
			}
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
					$condition .= " AND  r.reply_time > '".$yesterday."' AND r.reply_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  r.reply_time > '".$today."' AND r.reply_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  r.reply_time > '".$last_threeday."' AND r.reply_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  r.reply_time > '".$last_sevenday."' AND r.reply_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	function count()
	{
			$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'message_reply r WHERE 1'.$this->get_condition();
			echo json_encode($this->db->query_first($sql));
		
	}
	function detail()
	{	
		$data['_action'] = 'manage';
		$this->verify_content_prms($data);
		
		if(!$this->input['id'])
		{
			$this->errorOutput("没有发现回复留言id");
		}
		else
		{
			$condition = $this->get_condition();
		}
		$obj = new Reply();
		$res = $obj->detail($condition);
		$this->addItem($res);
		$this->output();
	}
	//添加回复
	function reply_add()
	{
		$data['_action'] = 'manage';
		$this->verify_content_prms($data);
		
		if(!$this->input['contentid'])
		{
			$this->errorOutput("没发现留言id");
		}
		else
		{
			$condition .= ' AND m.id = '.intval($this->input['contentid']);
		}
		
		$sql = "SELECT m.content,m.contentid,g.groupname FROM ".DB_PREFIX."message m 	
		LEFT JOIN ".DB_PREFIX."message_group g ON m.groupid = g.groupid 
		WHERE 1 " .$condition;
		$r = $this->db->query($sql);
		while($info = $this->db->fetch_array($r))
		{	
			$return['info'][] = $info;
		}
		$this->addItem($return);
		$this->output();
	}
}
$output = new ReplyManage();
if(!method_exists($output,$_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$output->$action();
?>
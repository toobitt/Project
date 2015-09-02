<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','interview_content');//模块标识
class interview_content extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function index()
	{
	
	}
	public  function show()
	{
		$interviewid = $this->input['interview_id'];

		if(empty($interviewid))
		{
			$this->errorOutput('无效参数');
		}
		$qqbiaoqing_type=".gif";
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count} ";
		$orderby = ' ORDER BY order_id  ASC ';
		$condition = $this->get_condition();
		$sql = 'SELECT * FROM '.DB_PREFIX.'records WHERE interview_id= '.$interviewid.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		while ($r =$this->db->fetch_array($q))	
		{
			$r['interview_id'] = $interviewid;
			$r['question'] = str_ireplace('[QUOTE]','<span style="display:block;padding:5px 15px;border:1px dashed #77a9f0;">引用:',$r['question']);
			$r['question'] = str_ireplace('[/QUOTE]','</span>',$r['question']);
			$r['question'] = preg_replace("/\[:(\d+)\]/",'<img src="'.QQBIAOQING_DIR."\\1".$qqbiaoqing_type.'" />',$r['question']);
			$r['question'] = preg_replace("/\[IMG=(\S+)\]\[\/IMG\]/U",'<a href="${1}"><img src="${1}" onclick="show_pic('.$r['id'].');"  style="width:50px;height:50px"/></a>',$r['question']);
			$r['time'] = $this->hg_get_date($r['create_time']);
			$this->addItem($r);
		}
		$this->output();
	}
	public function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND question LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if(isset($this->input['pub_state']) && $this->input['pub_state']!=-1 && $this->input['interview_id'])
		{
			$condition.= ' AND is_pub ='.intval($this->input['pub_state']);
		}
		return $condition;
	}
	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'records  WHERE interview_id ='.urldecode($this->input['interview_id']);
		echo json_encode($this->db->query_first($sql));
	}
	
	/**
	 * 格式化时间输出
	 * @param $date unix时间戳
	 */
	function hg_get_date($date = 0)
	{
		if (! $date)
		{
			return '';
		}
		$seconds = TIMENOW - $date;
		$minutes = $seconds / 60;
		
		if ($minutes < 60)
		{
			if ($minutes < 1)
			{
				if ($seconds <= 10)
				{
					$showtime = '刚刚';
				} else
					$showtime = $seconds . '秒前';
			} else
			{
				$showtime = intval ( $minutes ) . '分钟前';
			}
		} elseif ($minutes < 1440)
		{
			$showtime = intval ( $minutes / 60 ) . '小时前';
		} elseif ($minutes < 14400)
		{
			$showtime = intval ( $minutes / 1440 ) . '天前';
		} else
		{
			$showtime =  date('Y-m-d H:i:s',$date);
		}
		
		return $showtime;
	}
	
	function detail(){
		if (!$this->input['id']){
			$this->errorOutput(NOID);
		}
		$sql = 'SELECT question FROM '.DB_PREFIX.'records 	WHERE  id = '.urldecode($this->input['id']);
		$res = $this->db->query_first($sql);
		$this->addItem($res);
		$this->output();
	}
	
	
}
$ouput= new interview_content();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();

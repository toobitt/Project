<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/pic.class.php');
define('MOD_UNIQUEID','interview_pic');//模块标识
class interview_pic extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->obj = new pic();
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
			//$this->errorOutput('无效参数');
		}
		//首页图片
		$res = $this->db->query_first ('SELECT cover_pic FROM ' . DB_PREFIX . 'interview where id='.$interviewid);
		$cover_pic = $res['cover_pic'];
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$orderby = ' ORDER BY order_id  DESC';
		$condition = $this->get_condition();
		$sql = 'SELECT * FROM '.DB_PREFIX.'files WHERE interview_id='.$interviewid.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		while ($r =$this->db->fetch_array($q))
		{
			$r['interview_id'] = $interviewid;
			$r['cover_pic'] = $cover_pic;
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			$r['file_size'] = round($r['file_size']/1024/8,2) . '&nbsp;KB';
			switch ($r['show_pos'])
			{
				case 0:$r['show_pos'] = '头部';break;
				case 1:$r['show_pos'] = '背景';break;
				case 2:$r['show_pos'] = '其它';break;
			}
			$r['ori_pic'] = hg_material_link(IMG_URL,app_to_dir(APP_UNIQUEID), $r['file_path'], $r['file_name']);
			//$r['ori_pic'] = $this->obj->pic_path($r['server_mark'], $r['file_path'], $r['file_name'], '');
			//$r['pic'] = $this->obj->pic_path($r['server_mark'], $r['file_path'], $r['file_name'], '50x50');
			$r['pic'] = hg_material_link(IMG_URL,app_to_dir(APP_UNIQUEID), $r['file_path'], $r['file_name'], '40x30/');
			$this->addItem($r);
		}
		$this->output();
	}

	public function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'files WHERE interview_id ='.urldecode($this->input['interview_id']).$this->get_condition();	
		
		echo json_encode($this->db->query_first($sql));
	}

	public function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['interview_pic'] && $this->input['interview_pic']!=-1)
		{
			$type = $this->settings['file_type'][$this->input['interview_pic']];
			$condition.= ' AND file_type = "'.$type.'" ';
		}
		return $condition;
	}
	public function detail()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
        if (!$this->input['kid']){
        	
        	$this->errorOutput(NOID);
        }
		$sql = 'SELECT * FROM '.DB_PREFIX.'files WHERE id = '.urldecode($this->input['id']);
		$res = $this->db->query_first($sql);
		$res['kid'] = $this->input['kid'];
		$this->addItem($res);
		$this->output();
	}
}
$ouput= new interview_pic();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();

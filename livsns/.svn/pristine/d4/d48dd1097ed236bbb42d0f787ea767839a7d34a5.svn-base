<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :userlogs.php
 * package  :package_name
 * Created  :2013-7-4,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'gamescore'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/gamescore.class.php');
class scoreApi extends  adminReadBase 
{
	private $obj=null;
	public function __construct() 
	{
		parent::__construct();
		$this->obj = new gamescore();
	}
	public function detail()
	{
		$id = intval($this->input['id']);
		if($id)
		{
			$data_limit = 'where id='.$id;
		}
		else
		{
			$data_limit = ' LIMIT 1';
		}
		$info = $this->obj->detail('gamescore',$data_limit);
		if(!$info)
		{
			$this->errorOutput(DATA_NOT_EXIST);
		}
		$this->addItem($info);
		$this->output();
	}
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;					
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		$datas = $this->obj->show('gamescore',$data_limit,$fields='*');
		
		if(empty($datas))
		{
			$this->errorOutput(DATA_NOT_EXIST);
		}
		$gameids = array();
		foreach($datas as $key=>$v)
		{
			$gameids[$v['game_id']] = $v['game_id']; 
		}
		$games = $this->obj->show('game'," where id in(".implode(',', array_keys($gameids)) .") ");	
		foreach($datas as $k => $v)
		{
			$v['game_name'] = $games[$v['game_id']]['name'];
			$this->addItem($v);
		}	
			
		$this->output();		
	}
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count('gamescore',$condition);		
		echo json_encode($info);
	}
	public function index()
	{
		
	}
	private function get_condition()
	{
		$cond = " where 1 ";
		//某一个游戏的 score
		if(isset($this->input['game_id']))
		{
			$cond .= " and game_id=".$this->input['game_id'];
		}
		//某人游戏 score
		if(isset($this->input['user_id']))
		{
			$cond .= " and user_id=".$this->input['user_id'];
		}
		return $cond;
	}
}

$out = new scoreApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out-> $action ();
?>

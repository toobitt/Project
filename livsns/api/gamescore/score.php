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
require_once './global.php';
require_once CUR_CONF_PATH.'lib/gamescore.class.php';
define('MOD_UNIQUEID','gamescore');//模块标识
class score_topApi extends  outerReadBase 
{
	private $obj=null;
	public function __construct() 
	{
		parent::__construct();
		$this->obj = new gamescore();
	}
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 10;					
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		$datas = $this->obj->show('scoretop',$condition.$data_limit,$fields='*');
		
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
	}
	public function detail()
	{
		
	}
	public function index()
	{
		
	}
	private function get_condition()
	{
		$cond = " where 1 ";
		//某一个游戏的top score
		if($this->input['game_id'])
		{
			$cond .= " and game_id=".intval($this->input['game_id']);
		}
		//某人游戏top score
		if($this->input['user_id'])
		{
			$cond .= " and user_id=".intval($this->input['user_id']);
		}
		return $cond." order by score desc ";
	}
}

$out = new score_topApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out-> $action ();
?>

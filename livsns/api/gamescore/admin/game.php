<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :game.php
 * package  :package_name
 * Created  :2013-7-16,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'gamescore'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/gamescore.class.php');
class gameApi extends  adminReadBase 
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
			$data_limit = 'where id='.$id;
		else
			$data_limit = ' LIMIT 1';
		$info = $this->obj->detail('game',$data_limit);
		if(!$info)
			$this->errorOutput(NO_DATA_EXIST);
		foreach($info as $key=>$val)
		{
			$this->addItem($val);
			$this->output();
		}

	}
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;					
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		$datas = $this->obj->show('game',$data_limit,$fields='*');
		if($datas && is_array($datas))
		{
			foreach($datas as $k => $v)
			{
				$this->addItem($v);
			}			
		}
		$this->output();		
	}
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count('game',$condition);		
		echo json_encode($info);
	}
	public function index()
	{
		
	}
	private function get_condition()
	{
		$cond = " where 1 ";
		return $cond;
	}
}

$out = new gameApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out-> $action ();
?>


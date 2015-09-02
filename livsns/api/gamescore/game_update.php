<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :game_update.php
 * package  :package_name
 * Created  :2013-7-19,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH.'lib/gamescore.class.php';
define('MOD_UNIQUEID','gamescore');//模块标识
class game_updateApi extends  outerUpdateBase 
 {
 	public function __construct()
 	{
 		parent::__construct();	
		$this->obj = new gamescore();
 	}
 	public function __destruct()
 	{
 		parent::__destruct();
 	}
 	public function create()
 	{
		$this->input['name'] = trim($this->input['name']);
 		if(!$this->input['name'])
 		{
 			$this->errorOutput(NO_GAME_NAME);
 		}
		if($this->game_exist(trim($this->input['name'])))
 		{
 			$this->errorOutput(GAME_NAME_EXISTED);
 		}
		$params = array();
 		$params['name'] = htmlspecialchars($this->input['name']);
 		$params['info'] = htmlspecialchars($this->input['info']);
 		$params['user_id'] = $this->user['user_id'];
 		$params['user_name'] = $this->user['user_name'];
 		$params['appid'] = $this->user['appid'];
 		$params['appname'] = trim(($this->user['display_name']));
 		$params['create_time'] = TIMENOW;
 		$params['update_time'] = TIMENOW;
		$params['id'] = $this->obj->insert('game',$params);
		$this->addItem($params);
		$this->output();	
 	}
	public function audit()
	{
		
	}
	public function delete()
	{
		
	}
	public function update()
	{
		
	}
	public function sort()
	{
	}
	public function publish()
	{
		
	}
	private function game_exist($game_name)
	{
		$cond = " where 1  AND name='".$game_name."'";
 		$datas = $this->obj->count('game',$cond);
 		if(isset($datas['total']))
 			if($datas['total']>0)
 				return true;//存在返回true
 		return false;//不存在
	}
 }
$out = new game_updateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>
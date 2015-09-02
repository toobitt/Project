<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 游戏管理
 * filename :game_update.php
 * package  :package_name
 * Created  :2013-7-16,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
 define('MOD_UNIQUEID', 'gamescore');
 require_once('global.php');
 require_once(ROOT_PATH.'lib/class/curl.class.php');
	include(CUR_CONF_PATH . 'lib/gamescore.class.php');
 class  gameUpdateApi extends adminUpdateBase
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
 		if(!$this->input['name'])
 		{
 			$this->errorOutput(NO_GAME_NAME);
 		}
 		if($this->game_exist(trim($this->input['name'])))
 		{
 			$this->errorOutput(GAME_NAME_EXISTED);
 		}
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
	public function update()
	{
		if(!$this->input['id'])
 		{
 			$this->errorOutput(NO_DATA_ID);
 		}
 		if($this->input['name'])
 		{
 			$params['name'] = htmlspecialchars($this->input['name']);
 		}
  		if($this->input['info'])
 		{
 			$params['info'] = htmlspecialchars($this->input['info']);
 		}
 		$datas = $this->obj->update('game',$params,' where id='.intval($this->input['id']));
 		$this->addItem($datas);
 		$this->output();
	}
	public function delete()
	{
		$params = $this->get_condition();
 		if(empty($params))
 		{
 			$this->errorOutput(NO_DATA_ID);
 		}
 		$datas = $this->obj->delete('game',$params);
 		//与游戏相关的数据清除 暂时注释
 		/*
 		$datas = $this->obj->delete('scoretop'," where gid=".intval($this->input['id']));
 		$datas = $this->obj->delete('gamescore'," where gid=".intval($this->input['id']));
 		*/
 		//与游戏相关的数据清除 end
 		$this->addItem($datas);
 		$this->output();
 		//return true;
 		
	}
	public function audit()
	{
		
	}
	public function sort()
	{}
	public function publish()
	{
		
	}
	private function get_condition()
	{

		$cond = " where 1 ";
		if(isset($this->input['id']))
			$cond .= " AND id=".intval($this->input['id']);
		return $cond;
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
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
	
 }
$out = new gameUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>

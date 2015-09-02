<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 得分等级处理类
 * filename :userlogs_update.php
 * package  :package_name
 * Created  :2013-7-5,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
 define('MOD_UNIQUEID', 'gamescore');
 require_once('global.php');
 require_once(ROOT_PATH.'lib/class/curl.class.php');
 include(CUR_CONF_PATH . 'lib/gamescore.class.php');
 class  gamescoreUpdateApi extends adminUpdateBase
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
		$this->input['game_id'] = intval($this->input['game_id']);
		$this->input['score'] = intval($this->input['score']);
		$this->input['level'] = intval($this->input['level']);
 		if(!$this->input['game_id'])
 		{
 			$this->errorOutput(NOGAME);
 		}
 		if(!$this->input['score'])
 		{
 			$this->errorOutput(NOGAMESCORE);
 		}
 		if(!$this->input['level'])
 		{
 			$this->errorOutput(NOGAMELEVEL);
 		}
 		$params['game_id'] = $this->input['game_id'];
 		$params['score'] = $this->input['score'];
 		$params['level'] = $this->input['level'];
 		$params['user_id'] = $this->user['user_id'];
 		$params['user_name'] = $this->user['user_name'];
 		$params['appid'] = $this->user['appid'];
 		$params['appname'] = trim(($this->user['display_name']));
 		$params['create_time'] = TIMENOW;
 		$params['update_time'] = TIMENOW;
 		$return = $this->obj->insert('gamescore',$params);
		$params['id'] = $return;
 		if(!$this->exist_scoretop())
 		{
 			$idatas['user_id'] = $this->user['user_id'];
 			$idatas['game_id'] = $this->input['game_id'];
 			$idatas['score'] = $this->input['score'];
 			$idatas['level'] = $this->input['level'];
 			$idatas['user_name'] = $this->user['user_name'];
 			$idatas['create_time'] = TIMENOW;
 			$idatas['update_time'] = TIMENOW;
 			$ireturn = $this->obj->insert('scoretop',$idatas);
 		}
		else
		{
 			if($this->is_score_top())
 			{
 				$udatas['score'] = $params['score'];
 				$udatas['level'] = $params['level'];
 				$udatas['update_time'] = TIMENOW;
	 			$cond = " where user_id=".$this->user['user_id']." and game_id=".$this->input['game_id'];
	 			$ureturn = $this->obj->update('scoretop',$udatas,$cond);
 			}
 		}
 		
 		
  		if(!$this->exist_leveltop())
 		{
 			$idatas['user_id'] = $this->user['user_id'];
 			$idatas['game_id'] = $this->input['game_id'];
 			$idatas['score'] = $this->input['score'];
 			$idatas['level'] = $this->input['level'];
 			$idatas['user_name'] = $this->user['user_name'];
 			$idatas['create_time'] = TIMENOW;
 			$idatas['update_time'] = TIMENOW;
 			$ireturn = $this->obj->insert('leveltop',$idatas);
 		}
		else
		{
 			if($this->is_level_top())
 			{
 				$udatas['score'] = $params['score'];
 				$udatas['level'] = $params['level'];
 				$udatas['update_time'] = TIMENOW;
	 			$cond = " where user_id=".$this->user['user_id']." and game_id=".$this->input['game_id'];
	 			$ureturn = $this->obj->update('leveltop',$udatas,$cond);
 			}
 		}		
 		
 		
 		$this->addItem($params);
 		$this->output();	
 		
 	}
	public function update()
	{
		
	}
	public function delete()
	{
		if(!isset($this->input['tbname']))
			return false;
		
		$params = $this->get_condition();
 		if(empty($params))
 		{
 			$this->errorOutput(NO_DATA_ID_AND_SCORE);
 		}
 		$datas = $this->obj->delete($this->input['tbname'],$params);
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
	//检查用户的某游戏是否存在,存在返回true否则false
	public function exist_scoretop()
	{
 		if(!isset($this->input['game_id']))
 		{
 			$this->errorOutput(NO_GAME_ID);
 		}
 		$cond = 'where 1  AND game_id='.$this->input['game_id'].' AND user_id='.$this->user['user_id'];
 		$datas = $this->obj->count('scoretop',$cond);
 		if(isset($datas['total']))
 			if($datas['total']>0)
 				return true;
 		return false; 		
	}
	//检查分数是否是最高,如果不是则返回false，否则返回true
	public function is_score_top()
	{
 		if(!$this->input['game_id'])
 		{
 			$this->errorOutput(NO_GAME_ID);
 		}
 		if(!$this->input['score'])
 		{
 			$this->errorOutput(NO_GAME_SCORE);
 		}
 		$cond = ' where 1 ';
 		$cond .= ' AND game_id='.$this->input['game_id'].' AND user_id='.$this->user['user_id'].' AND score>='.$this->input['score'];
 		$datas = $this->obj->count('scoretop',$cond);
 		if(isset($datas['total']))
 			if($datas['total']>0)
 				return false;
 		return true;
	}
	//检查用户的某游戏等级是否存在,存在返回true否则false
	public function exist_leveltop()
	{
 		if(!isset($this->input['game_id']))
 		{
 			$this->errorOutput(NO_GAME_ID);
 		}
 		$cond = ' where 1  AND game_id='.$this->input['game_id'].' AND user_id='.$this->user['user_id'];
 		$datas = $this->obj->count('leveltop',$cond);
 		//var_dump($datas);
 		if(isset($datas['total']))
 			if($datas['total']>0)
 				return true;
 		return false; 		
	}
	//检查分数是否是最高,如果不是则返回false，否则返回true
	public function is_level_top()
	{
 		if(!$this->input['game_id'])
 		{
 			$this->errorOutput(NO_GAME_ID);
 		}
 		if(!$this->input['level'])
 		{
 			$this->errorOutput(NO_GAME_LEVEL);
 		}
 		$cond = ' where 1 ';
 		$cond .= ' AND game_id='.$this->input['game_id'].' AND user_id='.$this->user['user_id'].' AND level>='.$this->input['level'];
 		$datas = $this->obj->count('leveltop',$cond);
 		if(isset($datas['total']))
 			if($datas['total']>0)
 				return false;
 		return true;
	}
	private function get_condition()
	{

		$cond = " where 1 ";
		if(isset($this->input['id']))
			$cond .= " AND id=".intval($this->input['id']);
			
		if(isset($this->input['gid']))
			$cond .= " AND gid=".intval($this->input['gid']);

		return $cond;
	}
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
	
 }
$out = new gamescoreUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>

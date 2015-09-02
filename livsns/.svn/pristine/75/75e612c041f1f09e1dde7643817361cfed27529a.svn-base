<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :Update_cate.php
 * package  :package_name
 * Created  :2013-7-23,Writen by scala yuanzhigang@scalachina.com
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
 define('MOD_UNIQUEID', 'publishdefaultdata_cate');
 require_once('global.php');
 include(CUR_CONF_PATH . 'lib/Core.class.php');
 class  CateUpdateApi extends adminUpdateBase
 {
 	public function __construct()
 	{
 		parent::__construct();	
	    $this->obj = new Core();
 	}
 	public function __destruct()
 	{
 		parent::__destruct();
 	}
 	//new tbname 
 	public function create()
 	{
 		if(!$this->input['name'])
 		{
 			$this->errorOutput(NO_NAME);
 		}
 		if(trim($this->input['name'])=='cate')
 		{
 			$this->errorOutput(TB_NAME_CANBE_CATE);
 		}
 		
 		$params['name'] = htmlspecialchars($this->input['name']);
 		
 		$params['desc'] = htmlspecialchars($this->input['desc']);
 		
 		//db
 		$params['tbname'] = htmlspecialchars($this->input['tbname']);//need varify 	
 		if($this->check_exist_cate($params['tbname']))
 			$this->errorOutput(TB_EXIST);
 			
 		/*
 		$params['engine'] = htmlspecialchars($this->input['engine']);//need varify 		
 		$params['charset'] = htmlspecialchars($this->input['charset']);//need varify 
 		$params['collate'] = htmlspecialchars($this->input['collate']);//need varify 	
 		$params['comment'] = htmlspecialchars($this->input['comment']);//need varify 	
 		*/
 		$params['engine'] = 'myisam';//need varify 		
 		$params['charset'] = 'utf8';//need varify 
 		$params['collate'] = 'utf8_unicode_ci';//need varify 	
 		$params['comment'] = '';//need varify 	
 		
 		//db
 			
 		$params['user_id'] = $this->user['user_id'];
 		$params['user_name'] = $this->user['user_name'];
 		$params['appid'] = $this->user['appid'];
 		$params['appname'] = trim(($this->user['display_name']));
 		$params['create_time'] = TIMENOW;
		$params['id'] = $this->obj->insert('cate',$params);
		
		$query = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX.$this->input['tbname']."`( " .
 				"`id` int(10) NOT NULL AUTO_INCREMENT,primary key (`id`))" .
 				" ENGINE=myisam".
				" DEFAULT CHARSET=utf8".
				" COLLATE =utf8_unicode_ci";	
		$this->db->query($query);
 		$this->addItem($params);
 		$this->output();	
 		
 	}
	public function update()
	{
		//tbname 不提供更新
		if(!$this->input['id'])
 		{
 			$this->errorOutput(NO_DATA_ID);
 		}
 		if($this->input['desc'])
 		{
 			$params['desc'] = htmlspecialchars($this->input['desc']);
 		}
 		if($this->input['name'])
 		{
 			$params['name'] = htmlspecialchars($this->input['name']);
 		}
 		$datas = $this->obj->update('cate',$params,' where id='.intval($this->input['id']));
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
 		$info = $this->obj->detail('cate','where id='.intval($this->input['id']));
 		$datas = $this->obj->delete('cate',$params);
 		
 		//drop the table
 		$query = "drop table ".DB_PREFIX.$info['tbname'];
		$this->db->query($query);		
 		//drop the table
 		
 		$this->addItem($datas);
 		$this->output();
 		
	}
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
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
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
	
	
	//opcate
	public function alter()
	{
		if(!$this->input['tbname'])
		{
			$this->errorOutput(NO_TB_CHOOSE);
		}
		if(!$this->input['length'])
		{
			$this->errorOutput(NO_FIELD_LENGTH);
		}
		if(!$this->input['Field'])
		{
			$this->errorOutput(NO_FIELD);
		}
		/*
		$query = "ALTER TABLE ".DB_PREFIX.$this->input['tbname'].
				" ADD `.$this->input['Field'].` ".$this->input['Type']."(".$this->input['length'].")," .
				" ADD `.$this->input['Field'].` ".$this->input['Type']."(".$this->input['length'].")" ;
		*/
		$query = "ALTER TABLE ".DB_PREFIX.$this->input['tbname'].
				" ADD `".$this->input['Field']."` ".$this->input['Type']."(".$this->input['length'].") COMMENT '".$this->input['Comment']."'"; 
		
		if(!$this->db->query($query))
			$this->errorOutput(SOME_ERROR);
		$this->addItem("success");
 		$this->output();
	}
	public function alter_drop_field()
	{
		if(!$this->input['tbname'])
		{
			$this->errorOutput(NO_TB_CHOOSE);
		}
		if(!$this->input['Field'])
		{
			$this->errorOutput(NO_FIELD);
		}
		$query = "ALTER TABLE ".DB_PREFIX.$this->input['tbname'].
				 " DROP `".$this->input['Field']."` ";
		if(!$this->db->query($query))
			$this->errorOutput(SOME_ERROR);
		$this->addItem("success");
 		$this->output();
	}
	
	//opcate end
	private function check_exist_cate($tbname)
	{
 		$cond = " where 1  AND name='".$tbname."'";
 		$datas = $this->obj->count('cate',$cond);
 		if(isset($datas['total']))
 			if($datas['total']>0)
 				return true;//存在返回true
 		return false;//不存在		
	}
	
 }
$out = new CateUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>


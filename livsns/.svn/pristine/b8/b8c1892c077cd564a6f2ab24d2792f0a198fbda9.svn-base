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
 include(CUR_CONF_PATH . 'lib/template_default_data.class.php');
 class  DataUpdateApi extends adminUpdateBase
 {
 	public function __construct()
 	{
 		parent::__construct();	
	    $this->obj = new template_default_data();
	}
 	
 	public function __destruct()
 	{
 		parent::__destruct();
 	}
 	//new tbname 
 	public function create()
 	{
 		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('publishdefaultdata_cate_data',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
 		$params['cate_id'] = $this->input['cate_id'];
 		$params['data'] = serialize($this->input['data']);
 		
 		$params['user_id'] = $this->user['user_id'];
 		$params['user_name'] = $this->user['user_name'];
 		$params['appid'] = $this->user['appid'];
 		$params['appname'] = trim(($this->user['display_name']));
 		$params['create_time'] = TIMENOW;
		$params['id'] = $this->obj->insert('data_cate_datas',$params);
		
 		$this->addItem($params);
 		$this->output();	
 		
 	}
	public function update()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('publishdefaultdata_cate_data',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		//tbname 不提供更新
		if(!$this->input['id'])
 		{
 			$this->errorOutput(NO_DATA_ID);
 		}
 		if($this->input['desc'])
 		{
 			$params['desc'] = htmlspecialchars($this->input['desc']);
 		}
 		$params['data'] = serialize($this->input['data']);
 		$datas = $this->obj->update('data_cate_datas',$params,' where id='.intval($this->input['id']));
 		$this->addItem($datas);
 		$this->output();
	}
	public function delete()
	{
		/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$action = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if(!in_array('publishdefaultdata_cate_data',$action))
			{
				$this->errorOutput("NO_PRIVILEGE");
			}
		}*/
		
		$params = $this->get_condition();
 		if(empty($params))
 		{
 			$this->errorOutput(NO_DATA_ID);
 		}
 		$datas = $this->obj->delete('data_cate_datas',$params);
 		
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
	
	
 }
$out = new DataUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :update_dafault.php
 * package  :package_name
 * Created  :2013-7-23,Writen by scala yuanzhigang@scalachina.com
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
 define('MOD_UNIQUEID', 'publishdefaultdata');
 require_once('global.php');
 include(CUR_CONF_PATH . 'lib/Core.class.php');
 class  DefaultDataUpateApi extends adminUpdateBase
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
 	public function create()
 	{
 		
 		
 		foreach($this->input['formdata'] as $key=>$val)
 		{
 			$params[$key] = $val[0];
 		}
		$params['id'] = $this->obj->insert($this->input['tbname'],$params);
 		$this->addItem($params);
 		$this->output();	
 		
 	}
	public function update()
	{
	}
	public function delete()
	{
		$params = $this->get_condition();
 		if(empty($params))
 		{
 			$this->errorOutput(NO_DATA_ID);
 		}
 		$datas = $this->obj->delete($this->input['tbname'],$params);
 		$this->addItem($datas);
 		$this->output();
 		
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
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
	
 }
$out = new DefaultDataUpateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>

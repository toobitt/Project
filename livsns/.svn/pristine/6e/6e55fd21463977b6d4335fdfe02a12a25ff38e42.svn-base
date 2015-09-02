<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :Cate.php
 * package  :package_name
 * Created  :2013-7-23,Writen by scala yuanzhigang@scalachina.com
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'publishdefaultdata_cate_data'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/template_default_data.class.php');
class DataAPi extends  adminReadBase 
{
	private $obj=null;
	public function __construct() 
	{
		parent::__construct();
		$this->obj = new template_default_data();
	}
	public function detail()
	{
		$id = intval($this->input['id']);
		if($id)
			$data_limit = 'where id='.$id;
// 		else
// 			$data_limit = ' LIMIT 1';
		
		$info = $this->obj->detail('data_cate_datas',$data_limit);
		$info['data'] = unserialize($info['data']);
		
		//file_put_contents('1111.txt',var_export($info,1));
		if(!$info)
			$this->errorOutput(NO_DATA_EXIST);
		
		$this->addItem($info);
		$this->output();

	}
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;					
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		
		$datas = $this->obj->show('data_cate_datas',$condition.$data_limit,$fields='*');
		foreach($datas as $k=>$v)
		{
			$datas[$k]['data'] = unserialize($v['data']);
		}
		$this->addItem($datas);
		$this->output();	
		
	
	}
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count('data_cate_datas',$condition);		
		echo json_encode($info);
	}
	public function index()
	{
		
	}
	private function get_condition()
	{
		$cond = " where 1 ";
		//某类别数据
		if($this->input['cate_id'])
		{
			$cond .= " AND cate_id=".$this->input['cate_id'];
		}
		return $cond;
	}
	
}

$out = new DataAPi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out-> $action ();
 

 
 
?>
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
define('MOD_UNIQUEID', 'publishdefaultdata_cate'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class CateAPi extends  adminReadBase 
{
	private $obj=null;
	public function __construct() 
	{
		parent::__construct();
		$this->obj = new Core();
	}
	public function detail()
	{
		$id = intval($this->input['id']);
		if($id)
			$data_limit = 'where id='.$id;
		else
			$data_limit = ' LIMIT 1';
		
		$info = $this->obj->detail('cate',$data_limit);
		
		
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
		$datas = $this->obj->show('cate',$data_limit,$fields='*');
		if(is_array($datas))
		{
			foreach($datas as $k=>$v)
			{
				$this->addItem($v);
			}
			$this->output();	
		}
	}
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count('cate',$condition);		
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
	//opcate
	public function tb_detail()
	{
		
		$tbname = trim($this->input['tbname']);
		if(!$tbname)
			$this->errorOutput(NO_TB_CHOOSE);
		$info = $this->obj->desc_tb($tbname);
		if(!is_array($info))
			$this->errorOutput(NO_DATA_EXIST);
		$this->addItem($info);
		$this->output();		
	}
	
	public function filed_detail()
	{
		$this->addItem(' ');
		$this->output();
	}	
	//opcate end
}
$out = new CateAPi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out-> $action ();
 
 
?>
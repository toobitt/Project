<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :Default.php
 * package  :package_name
 * Created  :2013-7-23,Writen by scala yuanzhigang@scalachina.com
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
 define('MOD_UNIQUEID', 'publishdefaultdata'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class DefaultAPi extends  adminReadBase 
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
		
		$info = $this->obj->detail('data',$data_limit);
		if(!$info)
			$this->errorOutput(NO_DATA_EXIST);
			
		
		$cate_id = $info['cate_id'];
		$cate_info = $this->obj->detail('cate',' where id='.$cate_id);
			
		$info['cate_name'] = $cate_info['name'];
		$this->addItem($info);
		$this->output();

	}
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;					
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		$datas = $this->obj->show('data',$data_limit,$fields='*');
		if(!$datas||!is_array($datas))
		{
			$this->errorOutput(NO_DATA_EXIST);
		}
		$cateids_in_datas = array();
		foreach($datas as $k=>$v)
		{
			$cateids_in_datas[$v['cate_id']] = $v['cate_id'];
		}	
		
		$cates = $this->obj->show('cate',' where id in ('.implode(',', array_keys($cateids_in_datas)).')',$fields='*');
		foreach($datas as $k=>$v)
		{
			$v['cate_name'] = $cates[$v['cate_id']]['name'];
			$this->addItem($v);
		}
		$this->output();		
	}
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count('data',$condition);		
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

$out = new DefaultAPi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out-> $action ();
 
 
?>

<?php
/*******************************************************************
 * filename :CDN.php
 * Created  :2013年8月9日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
require('./global.php');
define('MOD_UNIQUEID','aboke');
require_once CUR_CONF_PATH . 'lib/Core.class.php';
class CategoryAPI extends  outerReadBase
{
	private $obj=null;
    private $tbname = 'cate';
	public function __construct()
	{
		parent::__construct();
		$this->obj = new Core();
	}
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		
		$cond = " where 1 and id = $id and type=2 ";
		!$this->input['otheruser'] && $cond .= " AND user_id = ".(int)($this->input['user_id'] ? (int)$this->input['user_id'] : $this->user['user_id']);
		$info = $this->obj->detail($this->tbname,$cond);
		
		if(!$info)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
        $info['img'] = $this->getvideoimg($info['id']);
		$this->addItem($info);
		$this->output();
	}
    
    
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;					
		$data_limit = $condition.' order by id desc LIMIT ' . $offset . ' , ' . $count;		
		$datas = $this->obj->show($this->tbname,$data_limit,$fields='*');

		foreach($datas as $k=>$v)
		{
			$v['img'] = $this->getvideoimg($v['id']);
			$this->addItem($v);
		
		}
		$this->output();
	}
	
	public function getvideoimg($cate_id)
	{
		$sql = 'select img from '.DB_PREFIX.'video where state = 1 AND cate_id = '.$cate_id.' ORDER BY id DESC';
		$data = $this->db->query_first($sql);
		return $data['img'];
	}
    
    
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($this->tbname,$condition);
		echo json_encode($info);
	}
    
    
	public function index()
	{

	}
	
	
	private function get_condition()
	{
		//只显示用户自定义的分类    
		$cond = " where 1 and type=2 and user_id=".(int)($this->input['user_id'] ? (int)$this->input['user_id'] : $this->user['user_id']);
		
		if(isset($this->input['state']))
		{
			$cond .= ' and state='.intval($this->input['state']);
		}
		
		
		return $cond;
	}
    
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
}

$out = new CategoryAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'unknow';
}
$out-> $action();
?>

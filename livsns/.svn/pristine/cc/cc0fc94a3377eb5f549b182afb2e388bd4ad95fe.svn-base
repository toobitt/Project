<?php
/*******************************************************************
 * filename :CDN.php
 * Created  :2013年8月9日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'aboke'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class Category extends  adminReadBase
{
	private $obj=null;
    private $tbname = 'cate';
	public function __construct()
	{
		parent::__construct();
		$this->obj = new Core();
	}
    
    /*
     * @describe:       获取分类详细
     * @function:       detail
     * @param:          id
     * @return:         array
     */
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
        {
            $id = 0;
            //$this->errorOutput(NO_ID);
        }

		$data_limit = ' where id='.$id;
		
		$info = $this->obj->detail($this->tbname,$data_limit);
        
        $cate_mark = $this->obj->show('cate_mark');
        
        $info['cate_mark'] = $cate_mark;

		if(!$info)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
		$this->addItem($info);
		$this->output();
	}
    
    
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
        
        $data_limit = $condition.' order by sort_id desc,id desc LIMIT ' . $offset . ' , ' . $count; 

        $query = "
                SELECT ".DB_PREFIX."$this->tbname.*, ".DB_PREFIX."cate_mark.name AS cate_mark_name,".DB_PREFIX."cate_mark.mark_type AS cate_mark_type
                FROM ".DB_PREFIX."$this->tbname
                LEFT JOIN `".DB_PREFIX."cate_mark` 
                ON ".DB_PREFIX."$this->tbname.cate_mark_id=".DB_PREFIX."cate_mark.id
                   ";
        $datas = $this->obj->query($query.$data_limit);
        
        //$strategies = $this->obj->query("SELECT * FROM `".DB_PREFIX."strategy` ");
        
		//$datas = $this->obj->show($this->tbname,$data_limit,$fields='*');
		
		foreach($datas as $k=>$v)
		{
			$this->addItem($v);
		
		}
		$this->output();
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
		$cond = " where 1 ";
		
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$cond .= ' AND '.DB_PREFIX.'cate.user_name LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		
        //1系统添加，2表示普通用户添加 
		if(isset($this->input['type']))
		{
			$cond .= ' and type='.intval($this->input['type']);
		}
		
        //0未审核，1审核通过 
		if(isset($this->input['state']))
		{
			$cond .= ' and state='.intval($this->input['state']);
		}
		
		if(isset($this->input['user_id']))
		{
			$cond .= ' and user_id='.intval($this->input['user_id']);
		}
		return $cond;
	}
    
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
}

$out = new Category();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'unknow';
}
$out-> $action();
?>

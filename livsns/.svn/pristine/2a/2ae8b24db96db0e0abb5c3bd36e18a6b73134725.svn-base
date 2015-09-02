<?php
/**
 * 分类类别
 * 
 */
define('MOD_UNIQUEID', 'Cate_mark'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class CateMark extends  adminReadBase
{
    private $obj=null;
    private $tbname = 'cate_mark';
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
        
        $data_limit = 'where id='.$id;
        
        $info = $this->obj->detail($this->tbname,$data_limit);
        
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
        $data_limit = $condition.' order by id desc LIMIT ' . $offset . ' , ' . $count;     
    
        $datas = $this->obj->show($this->tbname,$data_limit,$fields='*');
        
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
        //管理某用户标签
        if(isset($this->input['user_id']))
        {
            $cond .= " AND `user_id`=".intval($this->input['user_id']);
        }
        
        return $cond;
    }
    
   
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new CateMark();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

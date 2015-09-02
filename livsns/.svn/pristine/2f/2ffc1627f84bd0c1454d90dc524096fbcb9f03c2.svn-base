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
class OplogAPI extends  outerReadBase
{
    private $obj=null;
    private $tbname = 'op_log';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }
    public function detail()
    {
        $id = intval($this->input['type_id']);
        if(!$id)
        {
            $this->errorOutput(NO_ID);
        }
        if(!isset($this->input['type']))
        {
            $this->errorOutput(NO_TYPE);
        }
        
        $type = trim($this->input['type']);
        
        //用户本人
        $cond = " where 1 and type_id=$id and type='".$type."'";
        
        $info = $this->obj->detail($this->tbname,$cond);
        
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
        //只显示用户自定义的分类    
        $cond = " where 1 ";
        
        return $cond;
    }
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new OplogAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

<?php
/*******************************************************************
 * filename :Tag.class.php
 * function :标签管理类
 * Created  :2013年9月24日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'tag'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class Favor extends  adminReadBase
{
    private $obj=null;
    private $tbname = 'favor';
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
        //$condition = " and a.user_id=".$this->user['user_id'];
        
        $condition = " ";
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;          
        $count = $this->input['count'] ? intval($this->input['count']) : 20;                    
        $data_limit = $condition.' order by id desc LIMIT ' . $offset . ' , ' . $count;     
    
        $query = "select v.* from ".DB_PREFIX."favor f,".DB_PREFIX."video v where f.video_id=v.id ".$data_limit;
        
        //$datas = $this->obj->show($this->tbname,$data_limit,$fields='*');
        $datas = $this->obj->query($query);
        
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

$out = new Favor();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

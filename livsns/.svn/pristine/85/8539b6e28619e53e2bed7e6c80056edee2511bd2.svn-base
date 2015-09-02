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
class FavorAPI extends  outerReadBase
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
        
        $data_limit = 'where video_id='.$id." and user_id=".$this->user['user_id'];
        
        $info = $this->obj->detail($this->tbname,$data_limit);
        
        $re = 1;
        if(!$info)
        {
           $re = 0;
        }
        $this->addItem($re);
        $this->output();
    }
    
    public function show()
    {
        $condition = " and f.user_id=".$this->user['user_id'];
        
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;          
        $count = $this->input['count'] ? intval($this->input['count']) : 20;                    
        $data_limit = $condition.' order by id desc LIMIT ' . $offset . ' , ' . $count;     
    
        $query = "select v.* from ".DB_PREFIX."favor f,".DB_PREFIX."video v where f.video_id=v.id ".$data_limit;
        
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
        $cond = " where 1 AND `user_id`=".$this->user['user_id'];
        
        return $cond;
    }
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new FavorAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

<?php
/*******************************************************************
 * filename :Tag.class.php
 * function :标签管理类
 * Created  :2013年9月24日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
define('MOD_UNIQUEID', 'Comment'); //模块标识
require ('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class CommentAPI extends  outerReadBase
{
    private $obj=null;
    private $tbname = 'comment';
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
        if(isset($this->input['video_id']))
        {
            $this->errorOutput(NO_VIDEO_ID);
        }
        $id = intval($this->input['video_id']);
        $condition = " where video_id=$id";
        
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
        if(isset($this->input['video_id']))
        {
            $cond .= " and video_id=".intval($this->input['video_id']);
        }
        
        return $cond;
    }
    
    public function get_most_comment()
    {
        $cond = " where 1 and a.state=1 ";
        
        $query = "select count(a.id) as total ,a.* from ".DB_PREFIX."comment a $cond group by a.video_id  order by total desc";
        $result = $this->db->query($query);
        while (($r = $this->db->fetch_array($result))!=false)
        {
            $r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
            $r['update_time'] = date('Y-m-d H:i:s',$r['update_time']);
            $data[] = $r;
            $this->addItem($r);
        }
        $this->output();
    }
    
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new CommentAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

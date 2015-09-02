<?php
/**
 * 
 * 功能:
 * 1.从最新的100数据中获得没有过期的公告
 */
define('MOD_UNIQUEID', 'm2o_notice'); //模块标识
require ('global.php');
class m2o_noticeApi extends adminReadBase 
{
    private $auth=null;
    public function __construct() 
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/Core.class.php');
        $this->obj = new Core();
    }

    public function count()
    {
        $condition = $this->get_condition();
        $info = $this->obj->count('notice',$condition);     
        echo json_encode($info);
    }
    
    public function show() 
    {
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;          
        $count = $this->input['count'] ? intval($this->input['count']) : 100;                    
        $data_limit = ' LIMIT ' . $offset . ' , ' . $count;     
        $query = "select * from ".DB_PREFIX."notice a
                  left join ".DB_PREFIX."notice_content b
                  on a.notice_id=b.id ".$condition . $data_limit;
        $notice = $this->obj->query($query);
        if($notice && is_array($notice))
        {
            foreach($notice as $k => $v)
            {
                if($v['end_time']<time())
                {
                    continue;
                }
                if($v['start_time']>time())
                {
                    continue;
                }
                $v['start_time'] = date("Y-m-d H:i", $v['start_time']);
                $v['end_time'] = date("Y-m-d H:i", $v['end_time']);
                $this->addItem($v);
            }           
        }
        $this->output();
    }
    public function detail() 
    {
        $id = intval($this->input['id']);
        if(!$id)
        {
            $this->errorOutput('NO_ID');
        }
        $query = "select * from ".DB_PREFIX."notice a
                  left join ".DB_PREFIX."notice_content b
                  on a.notice_id=b.id where a.notice_id=$id";
        $notice = $this->obj->query($query);
        $info = $notice[$id];
        $info['start_time'] =  date("Y-m-d H:i", $info['start_time']);
        $info['end_time'] =  date("Y-m-d H:i", $info['end_time']);
        $this->addItem($info);
        $this->output();
                
    }
    
    
    public function index() 
    {
        

    }
    
    private function get_condition()
    {
        $condition = " WHERE 1 AND a.`user_type`='m2o' and a.is_deleted=2";
         //查询发布的时间
        if($this->input['date_search'])
        {
            $today = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
            switch(intval($this->input['date_search']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
                    $condition .= " AND  a.create_time > '".$yesterday."' AND a.create_time < '".$today."'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  a.create_time > '".$today."' AND a.create_time < '".$tomorrow."'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
                    $condition .= " AND  a.create_time > '".$last_threeday."' AND a.create_time < '".$tomorrow."'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
                    $condition .= " AND  a.create_time > '".$last_sevenday."' AND a.create_time < '".$tomorrow."'";
                    break;
                default://所有时间段
                    break;
            }
        }
        
        //根据时间
        $condition .=" ORDER BY create_time  ";
        //查询排序方式(升序或降序,默认为降序)
        $condition .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';

        return $condition;  
    }
    
    
    function unknow() 
    {
        $this->errorOutput("此方法不存在！");
        //echo "此方法不存在！";
    }

    public function __destruct() 
    {
        parent :: __destruct();
    }
}

$out = new m2o_noticeApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out-> $action ();
?>

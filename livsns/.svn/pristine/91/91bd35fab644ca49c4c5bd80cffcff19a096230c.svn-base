<?php
require('global.php');
define('MOD_UNIQUEID','searchtag');
class searchtagApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
        
        include_once(CUR_CONF_PATH . 'lib/searchtag.class.php');
        $this->mode = new searchtag();
    }
    
    public function index(){}
    public function detail(){}
    
    public function __destruct() {
        parent::__destruct();
    }

    /**
     * 根据条件获取会话列表接口
     */
    public function show() {
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = " ORDER BY id DESC LIMIT $offset, $count";
        $condition = $this->get_condition();
        $fields = 'id, title, app_uniqueid, mod_uniqueid, user_id, user_name, create_time';
        $tag = $this->mode->tag_list($condition . $limit, $fields);       
        
        foreach ((array)$tag as $k => $v) {
            if ($v) {
                isset($v['create_time']) && $v['create_time_show'] = date('Y-m-d H:i:s', $v['create_time']);
                $this->addItem($v);
            }
        }       
        $this->output();
    }
    
    /**
     * 根据条件查询会话总数
     */
    public function count() {
        $condition = $this->get_condition();
        $total = $this->mode->count($condition);
        echo json_encode($total);
    }
      
    
    function unknow()
    {
        $this->errorOutput("此方法不存在");
    }
    
    private function get_condition()
    {
        $condition = '';
        if ($this->input['key']) {
            $condition .= " AND title LIKE '%".$this->input['key']."%'";
        }
        
        if($this->input['user_name']) {
            $condition .= " AND user_name LIKE '%" . $this->input['user_name'] . "%'";
        }

        if ($this->input['app_uniqueid']) {
            $condition .= " AND app_uniqueid = '" . $this->input['app_uniqueid'] . "'";
        }
        
        if ($this->input['mod_uniqueid']) {
            $condition .= " AND mod_uniqueid = '".$this->input['mod_uniqueid']."'";
        }
        
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
                    $condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
                    $condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
                    $condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
                    break;
                default://所有时间段
                    break;
            }
        }
        
        return $condition;
    }   

}

$out = new searchtagApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();

?>
<?php

define('MOD_UNIQUEID', 'road');
require('global.php');

class roadApi extends adminReadBase
{

    public function __construct()
    {
        $this->mPrmsMethods = array(
            'manage' => '管理',
            '_node' => array(
                'name' => '路况分类',
                'filename' => 'cat.php',
                'node_uniqueid' => 'cat_node',
            ),
        );
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/road.class.php');
        $this->obj          = new road();
    }

    //2013.07.12 scala
    public function show_sort()
    {
        $ret = $this->obj->sort($this->input['id']);
        $this->addItem($ret);
        $this->output();
    }

    //2013.07.12 scala
    public function __destruct()
    {
        parent::__destruct();
    }

    public function index()
    {
        
    }

    public function show()
    {
        $con         = $this->get_condition();
        $offset      = $this->input['offset'] ? $this->input['offset'] : 0;
        $count       = $this->input['count'] ? intval($this->input['count']) : 20;
        $data_limit  = ' LIMIT ' . $offset . ' , ' . $count;
        $ret         = array();
//        if ($this->input['area'])
//            $ret['data'] = $this->obj->show_area($con . $data_limit);
//        else
//           $ret['data'] = $this->obj->show($con . $data_limit);
		$ret['data'] = $this->obj->show($con . $data_limit);
		
        $ret['cat']  = $this->obj->show_cat();
        $ret['area']  = $this->obj->get_all_area();
        $this->addItem($ret);
        $this->output();
    }

    public function detail()
    {
        if ($this->input['id'])
        {
            $data_limit = " AND r.id =" . intval($this->input['id']);
        }
        else
        {
            $data_limit = " LIMIT 1";
        }
        $ret = $this->obj->detail($data_limit);
        $this->addItem($ret);
        $this->output();
    }

    public function count()
    {
        $condition = $this->get_condition();
        $info      = $this->obj->count($condition);
        echo json_encode($info);
    }

    public function get_condition()
    {
        $condition = '';
        if ($this->input['_id'])
        {
            $condition .=" AND r.group_id = " . intval($this->input['_id']);
        }
        if ($this->input['k'])
        {
            $condition .= " AND r.content LIKE '%" . $this->input['k'] . "%'";
        }
        if($this->input['_scoure'])
        {
        	if($this->input['_scoure']==1)
        		$condition .= " AND r.user_id>0 ";
        	if($this->input['_scoure']==2)
        		$condition .= " AND r.user_id=0 ";
        }

        //查询文章的状态
        if (isset($this->input['status']))
        {
            switch (intval($this->input['status']))
            {
                case 1:
                    $condition .= " ";
                    break;
                case 2: //待审核
                    $condition .= " AND r.state= 0";
                    break;
                case 3://已审核
                    $condition .= " AND r.state = 1";
                    break;
                case 4: //已打回
                    $condition .=" AND r.state = 2";
                default:
                    break;
            }
        }

        if ($this->input['cat'])
        {
            $condition .= " AND r.group_id = " . intval($this->input['cat']);
        }
 
        //查询创建的起始时间
        if($this->input['start_time'])
        {
            $condition .= " AND r.create_time > " . strtotime($this->input['start_time']);
        }
        
        //查询创建的结束时间
        if($this->input['end_time'])
        {
            $condition .= " AND r.create_time < " . strtotime($this->input['end_time']);    
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
                    $condition .= " AND  r.create_time > '".$yesterday."' AND r.create_time < '".$today."'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  r.create_time > '".$today."' AND r.create_time < '".$tomorrow."'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
                    $condition .= " AND  r.create_time > '".$last_threeday."' AND r.create_time < '".$tomorrow."'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
                    $condition .= " AND  r.create_time > '".$last_sevenday."' AND r.create_time < '".$tomorrow."'";
                    break;
                default://所有时间段
                    break;
            }
        }        
        
        if ($this->input['user_name']) 
        {
            $condition .= " AND r.user_name LIKE '%".trim($this->input['user_name'])."%' ";
        }
        
                
        //2013.07.12 scala
        if ($this->input['is_hot'])
        {
            $condition .= " AND r.is_hot=1";
        }
        if ($this->input['area'])
        {
            $condition .= " AND ra.aid in (".$this->input['area'].") ";
        }
        //2013.07.12 scala end
        //查询排序方式(升序或降序,默认为降序)
        $hgupdown .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';
        //根据时间，order_id 和 istop字段排序，istop字段优先级高 create_time<order_id<istop
        $condition .=" ORDER BY r.create_time " . $hgupdown . ",r.orderid	" . $hgupdown;
        return $condition;
    }

}

$out    = new roadApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
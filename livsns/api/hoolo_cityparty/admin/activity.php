<?php

define('MOD_UNIQUEID', 'activity');
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
        include(CUR_CONF_PATH . 'lib/activity.class.php');
        $this->obj = new road();
    }

    //2013.07.12 scala
    public function show_sort()
    {
        //file_put_contents('obj.txt',var_export($this->obj->sort($this->input['id']),1));
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
        if ($this->input['area'])
            $ret['data'] = $this->obj->show_area($con . $data_limit);
        else
            $ret['data'] = $this->obj->show($con . $data_limit);
        //$ret['cat']  = $this->obj->show_cat();
        //$ret['area']  = $this->obj->get_all_area();
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
        //2013.07.12 scala
        if ($this->input['is_hot'])
        {
            $condition .= " AND r.is_hot=1";
        }
        if ($area = trim($this->input['area']))
        {
            $condition .= " AND ra.aid in ($area) ";
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
<?php
require('global.php');
define('MOD_UNIQUEID','js_mall');
class JfMall extends adminReadBase
{
    public function __construct()
    {
        $this->mPrmsMethods = array(
            'show'		=>'查看',
            'create'	=>'创建',
            'update'	=>'修改',
            'delete'	=>'删除',
            'audit'		=>'审核',
            '_node'         => array(
                'name'=>'商品分类',
                'filename'=>'node.php',
                'node_uniqueid'=>'node',
            ),
        );
        parent::__construct();
        include_once CUR_CONF_PATH . 'lib/good.class.php';
        $this->goods_mode = new GoodMode();
    }
    
    public function index(){}
    
    public function __destruct() {
        parent::__destruct();
    }

    public function show() {

        #####
        $this->verify_content_prms(array('_action' => 'show'));
        #####

        $condition = $this->get_condition();
        $order = ' order_id DESC ';
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = $offset . ', ' . $count;

        $ret = $this->goods_mode->select($condition, $order, $limit);
        foreach ((array)$ret as $key => $val) {
            isset($val['create_time']) && ($val['create_time_show'] = date('Y-m-d H:i:s', $val['create_time']));
            isset($val['update_time']) && $val['update_time_show'] = date('Y-m-d H:i:s', $val['update_time']);
            isset($val['start_date']) && $val['start_date_show'] = date('Y-m-d', strtotime($val['start_date']));
            $val['end_date'] && $val['end_date_show'] = date('Y-m-d', strtotime($val['end_date']));
            if (isset($val['start_time'])) {
                $val['start_time_show'] = $val['start_time'] ? date('H:i:s', strtotime($val['start_time'])) : '00:00:00';
            }
            if (isset($val['end_time'])) {
                $val['end_time_show'] = $val['end_time'] ? date('H:i:s', strtotime($val['end_time'])) : '00:00:00';
            }
            isset($val['status']) && $val['status_text'] = $this->settings['status_show'][$val['status']];
            isset($val['status']) && $val['state'] = $val['status'];
            $val['indexpic_url'] = $val['indexpic_url'] != '' ? json_decode($val['indexpic_url'],1) : array(); 
            $this->addItem($val);
        }
        $this->output();

    }

    public function detail(){

        $id = $this->input['id'];

        $this->verify_content_prms(array('_action' => 'show'));

        if(!$id) {
            $this->errorOutput('NOID');
        }

        $ret = $this->goods_mode->getOne(' AND g.id = ' . $id);

        if ($ret) {
            isset($ret['start_date']) && $ret['start_date_show'] = date('Y-m-d', strtotime($ret['start_date']));
            $ret['end_date'] && $ret['end_date_show'] = date('Y-m-d', strtotime($ret['end_date']));
            if (isset($ret['start_time'])) {
                $ret['start_time_show'] = $ret['start_time'] ? date('H:i:s', strtotime($ret['start_time'])) : '00:00:00';
            }
            if (isset($ret['end_time'])) {
                $ret['end_time_show'] = $ret['end_time'] ? date('H:i:s', strtotime($ret['end_time'])) : '00:00:00';
            }
            $ret['week_day'] && $ret['week_day'] = explode(',', $ret['week_day']);
            $ret['indexpic_url'] = $ret['indexpic_url'] != '' ? json_decode($ret['indexpic_url'], 1) : array(); 
            
            //查询图片信息
         	include_once CUR_CONF_PATH . 'lib/material.class.php';
        	$this->material_mode = new MaterialMode();  
        	$material = $this->material_mode->select(' AND good_id = ' . $id);        
        	foreach ((array)$material as $k => $v) {
        		$v['pic'] = $v['pic'] != '' ? json_decode($v['pic'], 1) : array();
        		$material[$k] = $v;
        	} 
        	$ret['material'] = $material;
            $this->addItem($ret);
        }

        $this->output();
    }
    

    public function count() {

        $this->verify_content_prms(array('_action' => show));

        $condition = $this->get_condition();

        $total = $this->goods_mode->count($condition);

        echo json_encode($total);
    }
    
    private function get_condition()
    {
        $condition = '';

        ####增加权限控制 用于显示####
        if($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if(!$this->user['prms']['default_setting']['show_other_data'])
            {
                $condition .= ' AND g.user_id = '.$this->user['user_id'];
            }
            else
            {
                //组织以内
                if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
                {
                    $condition .= ' AND g.org_id IN('.$this->user['slave_org'].')';
                }
            }
            if($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
            {
                $authnode_str = $authnode ? implode(',', $authnode) : '';
                if($authnode_str === '0')
                {
                    $condition .= ' AND g.node_id IN(' . $authnode_str . ')';
                }
                if($authnode_str)
                {
                    $authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
                    $sql = 'SELECT id,childs FROM '.DB_PREFIX.'node WHERE id IN('.$authnode_str.')';
                    $query = $this->db->query($sql);
                    $authnode_array = array();
                    while($row = $this->db->fetch_array($query))
                    {
                        $authnode_array[$row['id']]= explode(',', $row['childs']);
                    }
                    $authnode_str = '';
                    foreach ($authnode_array as $node_id=>$n)
                    {
                        if($node_id == intval($this->input['_id']))
                        {
                            $node_father_array = $n;
                            if(!in_array(intval($this->input['_id']), $authnode))
                            {
                                continue;
                            }
                        }
                        $authnode_str .= implode(',', $n) . ',';
                    }
                    $authnode_str = true ? $authnode_str . '0' : trim($authnode_str,',');
                    if(!$this->input['_id'])
                    {
                        $condition .= ' AND g.node_id IN(' . $authnode_str . ')';
                    }
                    else
                    {
                        $authnode_array = explode(',', $authnode_str);
                        if(!in_array($this->input['_id'], $authnode_array))
                        {
                            //
                            if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
                            {
                                $this->errorOutput(NO_PRIVILEGE);
                            }
                            //$this->errorOutput(var_export($auth_child_node_array,1));
                            $condition .= ' AND g.node_id IN(' . implode(',', $auth_child_node_array) . ')';
                        }
                    }
                }
            }
        }

        ####增加权限控制 用于显示####
        if($this->input['max_id'])//自动化任务用到.
        {
            $condition .= " AND g.id >".intval($this->input['max_id']);
        }

        if ($this->input['key']) {
            $condition .= " AND g.title LIKE '%".$this->input['key']."%'";
        }

        if ($this->input['status'])
        {
            switch (intval($this->input['status']))
            {
                case 1: //待审核
                    $status = 0;
                    break;
                case 2://已审核
                    $status = 1;
                    break;
                case 3: //已打回
                    $status = 2;
                    break;
                default:
                    break;
            }
            $condition .= " AND g.status= " . $status;
        }

        if ($this->input['_id'])
        {
            $condition .= " AND g.node_id = " . intval($this->input['_id']);
        }

        if ($this->input['user_name'])
        {
            $condition .= " AND g.user_name LIKE '%".trim($this->input['user_name'])."%' ";
        }

        //查询创建的起始时间
        if($this->input['start_time'])
        {
            $condition .= " AND g.create_time > " . strtotime($this->input['start_time']);
        }

        //查询创建的结束时间
        if($this->input['end_time'])
        {
            $condition .= " AND g.create_time < " . strtotime($this->input['end_time']);
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
                    $condition .= " AND  g.create_time > '".$yesterday."' AND g.create_time < '".$today."'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  g.create_time > '".$today."' AND g.create_time < '".$tomorrow."'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
                    $condition .= " AND  g.create_time > '".$last_threeday."' AND g.create_time < '".$tomorrow."'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
                    $condition .= " AND  g.create_time > '".$last_sevenday."' AND g.create_time < '".$tomorrow."'";
                    break;
                default://所有时间段
                    break;
            }
        }

        return $condition;
    }   

}

$out = new JfMall();
$action = $_INPUT['a'];
if (!method_exists($out,$action)) {
    $action = 'show';
}
$out->$action();
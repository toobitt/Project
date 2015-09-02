<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-5-7
 * Time: 下午10:58
 */
require('global.php');
define('MOD_UNIQUEID', 'jf_mall');
class Goods extends outerReadBase
{
    public function __construct()
    {
        parent::__construct();
        include_once CUR_CONF_PATH . 'lib/good.class.php';
        $this->good_mode = new GoodMode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show(){}
    
    /**
     * 数据源方法 商品详情
     */
    public function detail(){
    	$id = $this->input['id'];
    	if (!$id) {
    		$this->errorOutput('NOID');
    	}
        $ret = $this->good_mode->getOne(' AND g.id = ' . $id);
        if (empty($ret) || $ret['status'] != 1) { //商品未审核
        	$this->errorOutput('NOT EXISTS');
        }
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
        	//加入客户端链接字段
			if(is_file(CACHE_DIR . 'client_link.txt'))
			{
				$client_link = file_get_contents(CACHE_DIR . 'client_link.txt');
			}
			$ret['client_link'] = $client_link ? $client_link : '';
        	
            $this->addItem($ret);
        }
		
        $this->output();    	
    }
    public function count(){}

	/**
	 * 数据源方法 
	 */
    public function get_content() {
        $condition = $this->get_condition();
        $order = ($this->input['sort_field'] && in_array($this->input['sort_field'], array('order_id', 'start_date', 'selled_num'))) ?  ' ' . $this->input['sort_field'] : ' order_id ';
        $order .= ($this->input['descasc'] && in_array($this->input['descasc'], array('DESC', 'ASC'))) ? ' ' . $this->input['descasc'] : ' DESC ';
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = $offset . ', ' . $count;

        $ret = $this->good_mode->select($condition, $order, $limit);
        foreach ((array)$ret as $key => $val) {
            isset($val['create_time']) && ($ret[$key]['create_time_show'] = date('Y-m-d H:i:s', $val['create_time']));
            isset($val['update_time']) && $ret[$key]['update_time_show'] = date('Y-m-d H:i:s', $val['update_time']);
            isset($val['start_date']) && $ret[$key]['start_date_show'] = date('Y-m-d', strtotime($val['start_date']));
            $val['end_date'] && $ret[$key]['end_date_show'] = date('Y-m-d', strtotime($val['end_date']));
            if (isset($val['start_time'])) {
                $ret[$key]['start_time_show'] = $val['start_time'] ? date('H:i:s', strtotime($val['start_time'])) : '00:00:00';
            }
            if (isset($val['end_time'])) {
                $ret[$key]['end_time_show'] = $val['end_time'] ? date('H:i:s', strtotime($val['end_time'])) : '23:59:59';
            }
            isset($val['status']) && $ret[$key]['status_text'] = $this->settings['status_show'][$val['status']];
            isset($val['status']) && $ret[$key]['state'] = $val['status'];
            $ret[$key]['indexpic_url'] = $val['indexpic_url'] != '' ? json_decode($val['indexpic_url'], 1) : array(); 
        }
        if ($this->input['need_count'])
        {
            $totalcount = $this->get_count($condition);
            $this->addItem_withkey('total', $totalcount['total']);
            $this->addItem_withkey('data', $ret);
        }
        else
        {
            foreach ((array)$ret as $k => $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    private function get_count($condition)
    {
        return $this->good_mode->count($condition);
    }

    private function get_condition() {
        $condition = '';
        $condition .= ' AND status = 1';

        if($this->input['node_id'])
        {
            $sql = "SELECT childs FROM " . DB_PREFIX	. "node WHERE id = " . intval($this->input['node_id']);
            $ret =  $this->db->query_first($sql);
            $condition .=" AND  g.node_id in (" . $ret['childs'] . ")";
        }
        return $condition;
    }

    public function unknow() {
        $this->errorOutput('方法不存在');
    }
}

$out = new Goods();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();

/* End of file goods.php */
 
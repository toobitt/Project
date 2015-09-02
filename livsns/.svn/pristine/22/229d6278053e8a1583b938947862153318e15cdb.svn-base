<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-5-7
 * Time: 下午10:58
 */
require('global.php');
define('MOD_UNIQUEID', 'jf_mall');
class JfMall extends outerUpdateBase
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

    
 	public function show() 
 	{

        $condition = $this->get_condition();
        $order = ' order_id DESC ';
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = $offset . ', ' . $count;

        $ret = $this->good_mode->select($condition, $order, $limit);
        
        foreach ((array)$ret as $key => $val) 
        {
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
    
	private function get_condition()
    {
        $condition = '';

        if ($this->input['key']) {
            $condition .= " AND g.title LIKE '%".$this->input['key']."%'";
        }

        $pick_up_way = intval($this->input['pick_up_way']);
        
        $pick_up_way = $pick_up_way ? $pick_up_way : 0;
        
        $condition .= " AND g.pick_up_way = " . $pick_up_way;
        
        $condition .= " AND g.status= 1";

        return $condition;
    }   


    
    /**
     * 订单系统调用方法   减少  增加库存
     * @param operation  操作类型   minus 减少库存  plus 增加库存
     */
    public function updateStore(){
        if (!$this->user['user_id']) {
            $this->errorOutput('NOT LOGIN');
        }

        if(!is_array($this->input['goods']) && !count($this->input['goods']))
        {
            $this->errorOutput('数据异常');
        }

        if ($this->input['operation'] == 'minus') {
            $this->subStore();
        } else if ($this->input['operation'] == 'plus') {
            $this->addStore();
        } else {
            $this->errorOutput('ERROR OPERATION');
        }
    }

    private function subStore() {

        $data = $this->input['goods'];
        //开启事务
        $this->db->commit_begin();
        $commit_tag = 0;

        $date = date('Ymd', TIMENOW);
        $time = date('His', TIMENOW);
        $week_day = date('N', TIMENOW);
        foreach ((array)$data as $k => $v) {
            $id = intval($v['id']);
            $num = $v['goods_number'] ? intval(abs($v['goods_number'])) : 1;

            //查询商品详情详情
            $good = $this->good_mode->getOne(' AND g.id = ' . $id);
            

            if (!$good['id'] || $good['status'] != 1) {
                $commit_tag = 14;
                break;
            }

            /*****验证时间*****/
            //日期限制
            if ($good['start_date'] && $good['start_date'] > $date) {
                $commit_tag = 1;
                break;
            }

            if ($good['end_date'] && $good['end_date'] < $date) {
                $commit_tag = 2;
                break;
            }
            //时间限制

            if ($good['start_time'] && $good['start_time'] > $time) {
                $commit_tag = 3;
                break;
            }

            if ($good['end_time'] && $good['end_time'] < $time) {
                $commit_tag = 4;
                break;
            }

            //星期限制
            $good['week_day'] = $good['week_day'] ? explode(',', $good['week_day']) : array();
            if (!in_array($week_day, $good['week_day'])) {
                $commit_tag = 5;
                break;
            }
            /*****验证时间*****/

            /*****验证数量*****/
            //验证订单限制
            if ($good['order_limit'] && $good['order_limit'] < $num) {
                $commit_tag = 6;
                break;
            }
            //验证总库存限制
            if ($good['total_limit'] && ($good['selled_num'] + $num > $good['total_limit'])) {
                $commit_tag = 7;
                break;
            }
            //验证商品账号限制
            $good_user = array();
            $good_user = $this->good_mode->getGoodUser($this->user['user_id'], $id);
            if ($good['amount_limit']) {
                if ($good_user['num'] + $num > $good['amount_limit']) {
                    $commit_tag = 8;
                    break;
                }
            }

			//验证活动周期数量限制
            switch ($good['period_type']) {
                case 1:
                    $period = date('Ymd', TIMENOW);  //每天
                    $week = '';
                    break;
                case 2:
                	$period = date('Y', TIMENOW);  //每周  
                	$week = date('W', TIMENOW);
                    break; 
                case 3:
                    $period = date('Ym', TIMENOW);  //每月
                    $week = '';
                    break;
                default:
                    $period = date('Ymd', TIMENOW); //默认每天
                    $week = '';
            }            
            $good_period = array();
            $good_period = $this->good_mode->getGoodPeriod($id, $period, $week);
            if ($good['period_limit']) {
                if ($good_period['num'] + $num > $good['period_limit']) {
                    $commit_tag = 9;
                    break;
                }
            }
            /*****验证数量*****/

            /*****修改库存*****/

            //修改总销量
            if ( !$this->good_mode->update(array('selled_num' => $good['selled_num'] + $num), ' id = ' . $id) ){
                $commit_tag = 10;
                break;
            }

            //修改用户商品购买记录
            $arr = array(
                'user_id'   => $this->user['user_id'],
                'good_id'   => $id,
                'num'       => $good_user['num'] + $num,
            );
            if( !$this->good_mode->insert_amount($arr, true) ) {
                $commit_tag = 11;
                break;
            }

            //修改活动周期购买记录
            $arr = array(
                'good_id'       => $id,
                'period'        => $period,
                'week'			=> $week,
                'num'           => $good_period['num'] + $num,
            );
            if ( !$this->good_mode->insert_period($arr, true) ) {
                $commit_tag = 12;
                break;
            }
            /*****修改库存*****/
        }

        if ($commit_tag)
        {
            $this->db->rollback();
            $this->format_error($commit_tag);
            exit();
        }

        //提交事务
        $this->db->commit_end();
        $this->addItem_withkey('status', 1);
        $this->output();

    }

	/**
	 * 取消订单还原库存方法
	 */
    private function addStore() {

        $data = $this->input['goods'];
        $order_time = $this->input['order_info']['create_time'];
        if (!$order_time) {
            $this->errorOutput('请传入订单时间');
        }
        $order_time = strtotime($order_time);

        //开启事务
        $this->db->commit_begin();
        $commit_tag = 0;

        foreach ((array)$data as $k => $v) {
            $id = intval($v['id']);
            $num = $v['goods_number'] ? intval(abs($v['goods_number'])) : 1;

            //查询商品详情详情
            $good = $this->good_mode->getOne(' AND g.id = ' . $id);
            if (!$good['id']) {
                $commit_tag = 14;
                break;
            }

            //修改总销量
            if ( !$this->good_mode->update(array('selled_num' => $good['selled_num'] - $num), ' id = ' . $id) ) {
                $commit_tag = 13;
                break;
            }

            //修改用户商品购买记录
            $good_user = $this->good_mode->getGoodUser($this->user['user_id'], $id);
            if ($good_user['id']) {
                if ( !$this->good_mode->update_amount(array('num' => $good_user['num'] - $num), ' user_id = ' . $this->user['user_id'] . ' AND good_id = ' . $id)) {
                    $commit_tag = 13;
                    break;
                }
            }

            //修改活动周期商品购买记录
            switch ($good['period_type']) {
                case 1:
                    $period = date('Ymd', $order_time);  //每天
                    $week = '';
                    break;
                case 2:
                	$period = date('Y', $order_time);  //每周  
                	$week = date('W', $order_time);
                    break; 
                case 3:
                    $period = date('Ym', $order_time);  //每月
                    $week = '';
                    break;
                default:
                    $period = date('Ymd', $order_time); //默认每天
                    $week = '';

            }
            $good_period = $this->good_mode->getGoodPeriod($id, $period, $week);
            if ($good_period['id']) {
                if ( !$this->good_mode->update_period(array('num' => $good_period['num'] - $num), ' good_id = ' . $id . ' AND period = \'' . $period . '\' AND week = \''.$week.'\'') ) {
                    $commit_tag = 13;
                    break;
                }
            }     
            
        }

        if ($commit_tag)
        {
            $this->db->rollback();
            $this->format_error($commit_tag);
        }

        //提交事务
        $this->db->commit_end();
        $this->addItem_withkey('status', 1);
        $this->output();
    }

    private function format_error($errno) {
        $errMap = array(
            '1'     => '活动尚未开始',
            '2'     => '此活动已结束',
            '3'     => '活动尚未开始',
            '4'     => '此次活动已经结束',
            '5'     => '休息中,明天再试',
            '6'     => '已超过订单限制',
            '7'     => '库存不足',
            '8'     => '购买数量超过账号限制',
            '9'     => '此次活动商品已售完',
            '10'    => '下单失败，请稍后再试',
            '11'    => '下单失败，请稍后再试',
            '12'    => '下单失败，请稍后再试',
            '13'    => '订单取消失败',
            '14'    => '商品不存在',
        );
        $errmsg = $errMap[$errno];
        $this->addItem_withkey('status', 2);
        $this->addItem_withkey('data', array(1 => array('msg' => $errmsg)));
        $this->output();
    }
    
    /**
     * 商品详情接口 订单应用
     */
    public function getGoodsInfo() {
    	$goods = $this->input['goods'];
		if (!is_array($goods) && !count($goods)){
			$this->errorOutput('数据异常');
		}    	
    	$good_ids = implode(', ', array_keys((array)$goods));
    	if (!$good_ids) {
    		$this->errorOutput('NOID');
    	}
    	
    	$where = ' AND g.id IN('.$good_ids.')';
    	$ret = $this->good_mode->select($where);
    	$return = array();
    	foreach ((array)$ret as $k => $v) {
    		$tmp = array();
    		$tmp['goods_title'] = $v['title'];
    		$tmp['goods_brief'] = $v['brief'];
    		$tmp['goods_id']  = $v['id'];
    		$v['indexpic_url'] = $v['indexpic_url'] != '' ? json_decode($v['indexpic_url'],1) : array(); 
    		$tmp['index_img']  = $v['indexpic_url'];
    		$tmp['goods_all_info'][$v['id']] = $v;
    		$tmp['status'] = 1;			
    		$return[$v['id']] = $tmp;
    	}
    	
    	foreach ((array)$return as $key => $val) {
    		$this->addItem_withkey($key,$val);
    	}
    	$this->output();
    }
 
    /**
     * 获取商品库存接口 订单应用
     */    
    public function getStore() {
     	$goods = $this->input['goods'];
		if (!is_array($goods) && !count($goods)){
			$this->errorOutput('数据异常');
		}    	
    	$good_ids = implode(', ', array_keys((array)$goods));
    	if (!$good_ids) {
    		$this->errorOutput('NOID');
    	}  
    	$where = ' AND g.id IN('.$good_ids.')';
    	$ret = $this->good_mode->select($where);
    	$return = array();
    	foreach ((array)$ret as $k => $v) {  
			$return[$v['id']]['goods_number'] 	= $goods[$v['id']]['goods_number'];
			$return[$v['id']]['goods_value']	= $v['price'];  
			$return[$v['id']]['credits_value']	= $v['score']; 
			$total_goods_value += $v['price'] * $goods[$v['id']]['goods_number'];  	
			$total_credits_value += $v['score'] * $goods[$v['id']]['goods_number'];
    	}
    	$return['total_goods_value'] = $total_goods_value;
    	$return['total_credits_value'] = $total_credits_value;
    	
    	foreach ((array)$return as $key => $val) {
    		$this->addItem_withkey($key,$val);
    	}
    	$this->addItem_withkey('status',1);
    	$this->output();    			 	
    }
    
    public function get_node()
    {
    	$sql = "SELECT id,name FROM " .DB_PREFIX. 'node WHERE fid = 0';
    	$query = $this->db->query($sql);
    	while ($row = $this->db->fetch_array($query))
    	{
    		$this->addItem($row);
    	}
    	$this->output();
    }
    
    public function create(){}
    public function update(){}
    public function delete(){}
    public function unknow(){
        $this->errorOutput('方法不存在');
    }
}

$out = new JfMall();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
/* End of file jf_mall.php */
 
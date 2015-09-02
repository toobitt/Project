<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once (ROOT_PATH . 'global.php');
define('MOD_UNIQUEID', 'payments');
//模块标识
require_once (CUR_CONF_PATH . '/core/Core.class.php');
class OverdueorderAPI extends cronBase {
    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function initcron() {
        $array = array(
                'mod_uniqueid' => MOD_UNIQUEID, 
                'name' => '数据更新', 
                'brief' => '数据更新', 
                'space' => '1', //运行时间间隔，单位秒
                'is_use' => 1,  //默认是否启用
        );
        $this -> addItem($array);
        $this -> output();
    }

    public function test() {
        $datas = file_get_contents("http://10.0.2.50/cron/cron.php?a=test");
        $this -> addItem($datas);
        $this -> output();
    }

    public function overdueproceess() {
        $time = 2100;           //过期时间
        $db = new Core();
        /**
         * 查询与订单号相关的商品，订单中相关信息如积分等
         */
    	$query = "SELECT 
    	          g.*,
    	          o.order_id as new_order_id,
    	          o.pay_credits as pay_credits,
    	          o.user_id as user_id,
    		      o.integral_status as integral_status
    	          FROM " . DB_PREFIX . "goodslist g
                  LEFT JOIN " . DB_PREFIX . "order o
                  ON g.order_id=o.id
                  WHERE o.pay_status = 1 
                  and o.create_time<" . (time() - $time)." limit 0,100"
                 ;
        
        $goodses = $db -> query($query);
        
        if(!$goodses)
            return ;
        $ids = array();
        $newgoodses = array();
        foreach ($goodses as $goods) {
            $newgoodses[$goods['bundle_id']]['goods'][$goods['goods_id']]['id'] = $goods['goods_id'];
            $newgoodses[$goods['bundle_id']]['goods'][$goods['goods_id']]['goods_number'] += $goods['goods_number'];
            $newgoodses[$goods['bundle_id']]['goods'][$goods['goods_id']]['bundle_id'] = $goods['bundle_id'];
            $ids[] = $goods['order_id'];
            
            $credits[$goods['user_id']]['id'] = $goods['order_id'];
            $credits[$goods['user_id']]['order_id'] = $goods['new_order_id'];
            $credits[$goods['user_id']]['credit'] = $goods['pay_credits'];
            $credits[$goods['user_id']]['integral_status'] = $goods['integral_status'];   //积分的状态
        }
        
        $this->BundleGoods = $newgoodses;
        
        foreach($newgoodses as $bundle_id=>$bundlegoodses)
        {
            $curl =$bundle_id."curl";
            $this->$curl = $this->create_curl_obj($bundle_id);
            $this->init_curl($bundle_id);
            //$Re_Minus_updateStores = $this -> opBundle('updateStore', array('operation' => 'plus'));
        }
        $Re_Minus_updateStores = $this -> opBundle('updateStore', array('operation' => 'plus'));
        
        $orderids = implode(",", $ids);
        if(!$orderids)
        {
            return false;
        }
        
        require_once (CUR_CONF_PATH . 'lib/sms.class.php');
        require_once (ROOT_PATH . 'lib/class/members.class.php');
        
        $members = new members();

        foreach($credits as $user=>$v)
        {
            if(!$v['credit'])
                continue;
            $re = $members->return_credit(
                            $user,
                            $v['credit'],
                            $v['order_id'],
                            'payments',
                            'OrderUpdate',
                            'cancle',
                            //"订单被系统取消:".$v['order_id']
            		       '订单:'.$v['order_id'].'被系统取消:'.$v['title'],
            	            $v['integral_status'],
            	            '取消订单'
                             );
                            
            if(!$re['logid']){
                return false;
            }
       }
       
        
        $query = "UPDATE " . DB_PREFIX . "order 
                  SET order_status=24,pay_status=3,is_completed=23
                  WHERE pay_status=1 and id in(".$orderids.")";
        $result = $db -> query_update($query);
        
        
    }

    /**
     * 创建curl
     */
    private function create_curl_obj($app_name) {
        $key = 'App_' . $app_name;
        if (!$this -> settings[$key]) {
            return false;
        }
        return new curl($this -> settings[$key]['host'], $this -> settings[$key]['dir']);
    }

    private function init_curl($curlprefix = '') {
        $curl = $curlprefix . "curl";
        $this -> $curl -> setSubmitType('post');
        $this -> $curl -> setReturnFormat('json');
        $this -> $curl -> initPostData();

    }

    private function get_common_datas($params, $curlprefix = '') {
        $curl = $curlprefix . "curl";
        foreach ($params as $key => $val) {
            if ($key == 'r') {
                $re = $this -> $curl -> request($val . ".php");
                return $re;
            } else {
                $this -> $curl -> addRequestData($key, $val);
            }
        }
    }
    
     /**
     * 该方法主要针对其他应用的操作
     * @param  String $op  操作方法 如：获取库存的方法getStore /获取商品信息的getGoodsInfo等
     * @access private
     * @return mixed
     */
    private function opBundle($op, $params = array()) {
        foreach ($this->BundleGoods as $bundleid => $onebundle_datas) {
            if (!is_array($onebundle_datas))
                continue;
            foreach ($onebundle_datas as $key => $value) {
                if (is_array($value)) {
                    $this -> array_to_add($key, $value, $bundleid);
                } else {
                    $params[$key] = $value;
                }
            }
            $params['a'] = $op;
            $params['r'] = $bundleid;
            $Re[$bundleid] = $this -> get_common_datas($params, $bundleid);

        }
        $state = 1;
        foreach ($Re as $bundle_id => $values) {
            if ($values['status'] == 2) {
                $state = 2;
                break;
            }
        }
        if ($state == 2) {
            exit(json_encode($Re));
        }
        return $Re;
    }
    
    private function array_to_add($str, $data, $curlprefix = '') {
        $curl = $curlprefix . "curl";
        $str = $str ? $str : 'data';
        if (is_array($data)) {
            foreach ($data AS $kk => $vv) {
                if (is_array($vv)) {
                    $this -> array_to_add($str . "[$kk]", $vv, $curlprefix);
                } else if ( $this -> $curl ) {
                    $this -> $curl -> addRequestData($str . "[$kk]", $vv);
                }
            }
        }
    }

}

$out = new OverdueorderAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'overdueproceess';
}
$out -> $action();
?>

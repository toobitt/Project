<?php
class sms extends InitFrm {
    public function __construct($order_id = '') {
        parent::__construct();
        $this -> order_id = $order_id;
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function getGoodsinfo() {
        $goodsinfos = array();
        $db = new Core();
        $query = "SELECT g.*,
                  gv.field as gvkey,
                  gv.value as gvvalue,
                  o.telphone as mobile,
                  o.order_quantity as goods_number,
                  o.goods_value as goods_value
                  FROM " . DB_PREFIX . "goodslist g
                  LEFT JOIN  " . DB_PREFIX . "order o 
                  ON g.order_id=o.id
                  LEFT JOIN  " . DB_PREFIX . "goodsextensionvalue gv 
                  ON g.order_id=gv.id
                  WHERE 
                  1
                  and o.order_id='" . $this -> order_id . "'";
        $mresult = $db -> query($query);
        
        if (!$mresult) {
            $this -> BundleGoods = $goodsinfos;
            return;
        }

        foreach ($mresult as $result) {
            $goods = json_decode(urldecode($result['extensions']), 1);
            $goods['goods_number'] = $result['goods_number'];
            $goods['goods_value'] = $result['goods_value'];
            //$goods['mobile'] = $result['mobile'];
            if($result['gvkey']=='session')
            {
                $goods['session'] = $result['gvvalue'];
            }
            $goodsinfos[$result['bundle_id']]['goods'][] = $goods;
            
          
        }
        //file_put_contents('./cache/mobile.txt',var_export($result,1));
        $query = "SELECT *
                  FROM " . DB_PREFIX . "order 
                  
                  WHERE 
                  1
                  and order_id='" . $this -> order_id . "'";
        $re = $db -> query($query,'');
        
        $this -> mobile = $re[0]['telphone'];// = '18021806556';
        $this -> BundleGoods = $goodsinfos;
    }

    public function sendsms() {
        $this -> getGoodsinfo();
        $configs = $this -> settings;
        $mobile = $this -> mobile;

        if (!isset($configs['sms'])) {
            return 1;
        }

        if (!isset($configs['sms']['is_open']) || !$configs['sms']['is_open']) {
            return 1;
        }

        $send_fun = "send_sms_vendor_" . $this -> settings['sms']['vendor'];
        $this -> $send_fun($mobile);
    }

    /**
     * 获取用户信息
     */
    private function getuser() {
        $key = 'App_members';
        $url = "http://" . $this -> settings[$key]['host'] . "/" . $this -> settings[$key]['dir'];
        $url .= "/member.php?a=detail&access_token=" . $this -> user['token'];
        $re = file_get_contents($url);
        return json_decode($re, 1);
    }

    private function getsmscontent() {
        $str = $this -> settings['sms']['header'];
        //echo json_encode($this->BundleGoods);exit();
        if (isset($this -> settings['sms']['needsmsbody'])) {
            // $sendcontent = $this -> BundleGoods;
            foreach ($this->BundleGoods as $bundle => $value) {
                //票务处理
                //echo json_encode($value);exit();
                if ($bundle == 'ticket') {
                    foreach ($value['goods'] as $k => $v) {
                        //echo json_encode($v);exit();
                        //$session = $v['extension']['session'];
                        $session = $v['session'];
                        $title = $v['title'];
                        $number = $v['goods_number'];
                        $price = $v['goods_value'];
                        $str .= "\n\"{$session}、{$title}、{$price}*{$number}\"\n";
                        $sendcontent[$bundle]['goods'][$k]['goods_number'] = $v['goods_number'];
                        $sendcontent[$bundle]['goods'][$k]['session'] = $this -> GoodsInfos[$bundle][$v['id']]['extension']['session'];
                    }
                }

                //积分商城处理
                if ($bundle == 'jf_mall') {

                }
            }//end foreach
        }//end if
        $str .= $this -> settings['sms']['footer'];
        return $str;
    }

    private function send_sms_vendor_3tong($mobile) {
        $content = $this -> getsmscontent();
        $api_url = $this -> settings['sms']['api_url'];
        $sendSmsAddress = $this -> settings['sms']['api_url'];
        $account = $this -> settings['sms']['username'];
        $password = $this -> settings['sms']['password'];
        $message ="<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
                ."<message>"
                . "<account>"
                . $account
                . "</account><password>"
                . $password
                . "</password>"
                . "<msgid></msgid><phones>"
                . $mobile
                . "</phones><content>"
                . $content
                . "</content><subcode>"
                ."</subcode>"
                ."<sendtime></sendtime>"
                ."</message>";
        $params = array('message' => $message);
        $data = http_build_query($params);
        $context = array('http' => array('method' => 'POST', 'header' => 'Content-Type: application/x-www-form-urlencoded', 'content' => $data, ));
       //file_put_contents('./cache/message.txt',var_export($message,1));
        // header('Content-type: text/xml');
        // echo $message;
        // exit();
                
        $contents = file_get_contents($sendSmsAddress, false, stream_context_create($context));
        //echo $contents;
    }

}
?>
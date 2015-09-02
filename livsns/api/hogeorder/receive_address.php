<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/26
 * Time: 下午2:15
 */
require_once ('./global.php');
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'ReceiveAddressApi');
class ReceiveAddressApi extends outerReadBase {
    public function __construct()
    {
        parent::__construct();
        if (!$this->user['user_id'])
        {
            $this->errorOutput('NO USER_ID');
        }
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
//        $data = array(
//            0 => array(
//                'id' => 1,
//                'contact_name' => '王乐园',
//                'mobile' => '17712862787',
//                'prov'   => '江苏省',
//                'city'   => '南京市',
//                'area'   => '区县',
//                'address_detail' => '铁心桥春江新城',
//                'postcode'       => '253000',
//                'email'          => 'wangleyuan729@gmai.com',
//                'isdefault'        => '0'
//            ),
//            1 => array(
//                'id' => 2,
//                'contact_name' => '王乐园',
//                'mobile' => '17712862787',
//                'prov'   => '江苏省',
//                'city'   => '南京市',
//                'area'   => '区县',
//                'address_detail' => '邦宁科技园4楼厚建软件',
//                'postcode'       => '253000',
//                'email'          => 'wangleyuan729@gmai.com',
//                'isdefault'        => '1'
//            ),
//        );

        $sql = "SELECT * FROM ".DB_PREFIX."receive_address WHERE user_id = " . $this->user['user_id'];
        $q = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q))
        {
            $ret[] = $row;
        }
        foreach ((array)$ret as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }

    public function detail(){}
    public function count(){}
}

require_once (ROOT_PATH . 'excute.php');
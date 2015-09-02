<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/26
 * Time: 下午2:15
 */
require_once ('./global.php');
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'CouponApi');
class CouponApi extends outerReadBase {
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $sort_id = $this->input['sort_id'];  //优惠券分类id
//        $data = array(
//            0 => array('id' => 1, 'title' => '2元意外险','brief' => '我是描述','price' => '2',
//                'indexpic' => array('host'=> 'http://img.dev.hogesoft.com:233/','dir' => 'material/news/img/', 'filepath' => '2014/11/', 'filename' => '9db9e1a4153ab3cf31b78c32971e3642.jpg'),
//                'required' => 1,
//            ),
//            1 => array('id' => 2, 'title' => '3元意外险','brief' => '我是描述','price' => '3',
//                'indexpic' => array('host'=> 'http://img.dev.hogesoft.com:233/','dir' => 'material/news/img/', 'filepath' => '2014/11/', 'filename' => '9db9e1a4153ab3cf31b78c32971e3642.jpg'),
//                'required' => 0,
//            ),
//        );
        foreach ((array)$data as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }

    public function detail(){}
    public function count(){}
}

require_once (ROOT_PATH . 'excute.php');
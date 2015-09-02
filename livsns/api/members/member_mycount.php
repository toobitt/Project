<?php
/**
 * HOGE DingDone member_mycount.php
 *
 * 会员我的 统计
 *
 * @package DingDone
 * @author RDC3 - dxtan
 * @copyright Copyright (c) 2015, HOGE CO., LTD (http://hoge.cn/)
 * @since Version 0.0.1
 */
define('MOD_UNIQUEID', 'Mycount'); // 模块标识
require ('./global.php');
require_once CUR_CONF_PATH . 'lib/member_mycount_mode.php';
class Mycount extends outerReadBase{
    private $mycount;
    public function __construct()
    {
        parent::__construct();
        $this->mycount = new member_mycount_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
         
    }
    
    public function detail()
    {
        $memberId = intval($this->input['member_id']);
        if (!$memberId)
        {
            $this->errorOutput(NO_MEMBER_ID);
        }
        $result = $this->mycount->detail($memberId);
       
        $this->addItem($result);
        $this->output();
    }
    
    public function count(){}
}
$out = new Mycount();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();
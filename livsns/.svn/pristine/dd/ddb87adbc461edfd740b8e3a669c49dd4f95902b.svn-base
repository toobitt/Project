<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-5-7
 * Time: 下午10:58
 */
require('global.php');
define('MOD_UNIQUEID', 'jf_mall');
class GoodsUpdate extends outerUpdateBase
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

    public function create(){}
    public function update(){}
    public function delete(){}
    public function unknow(){
        $this->errorOutput('方法不存在');
    }
}

$out = new GoodsUpdate();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
/* End of file goods.php */
 
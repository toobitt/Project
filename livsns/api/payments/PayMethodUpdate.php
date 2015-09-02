<?php
/*******************************************************************
 * filename :consignee.php
 * 收货人
 * Created  :2013年8月9日,Writen by scala 
 * 
 ******************************************************************/
require('./global.php');
define('MOD_UNIQUEID','pay_order');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class PayMethodUpdateAPI extends  outerUpdateBase
{
    private $obj=null;
    private $tbname = 'order';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }
    
    public function create()
    {
       return ; 
    }
    
    
    public function update()
    {
       return ;
    }
    
    public function publish()
    {
        return;
    }
    
    public function delete()
    {
        return ;
    }
    
    
    public function audit()
    {
        return ;
    }
    
    public function sort()
    {
        return ;
    }
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new PayMethodUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

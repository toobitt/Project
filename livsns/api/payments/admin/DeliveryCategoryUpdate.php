<?php
/*******************************************************************
 * Filename :consignee.php
 * 收货人
 * Created  :2013年8月9日,Writen by scala 
 * 
 ******************************************************************/
define('MOD_UNIQUEID','pay_order');
require('global.php');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class DeliveryCategoryUpdateAPI extends  adminUpdateBase
{
    private $obj=null;
    private $tbname = 'deliverycategory';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }
    
    public function create()
    {
        
    }
    public function delete()
    {
        $id = intval($this->input['id']);
        if(!$id)
        {
            $this->errorOutput(NO_ID);
        }
        
        //用户本人
        $cond = " where 1 and id=$id and type=2 and user_id=".$this->user['user_id'];
        
        $info = $this->obj->detail($this->tbname,$cond);
        
        if(!$info)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
        $this->addItem($info);
        $this->output();
    }
    
    
    public function update()
    {
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;          
        $count = $this->input['count'] ? intval($this->input['count']) : 20;                    
        $data_limit = $condition.' order by id desc LIMIT ' . $offset . ' , ' . $count;     
        $datas = $this->obj->show($this->tbname,$data_limit,$fields='*');

        foreach($datas as $k=>$v)
        {
            $this->addItem($v);
        
        }
        $this->output();
    }
    
    
    public function audit()
    {
    }
    
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new DeliveryCategoryUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

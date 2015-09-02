<?php
/*******************************************************************
 * Filename :consignee.php
 * 收货人
 * Created  :2013年8月9日,Writen by scala 
 * 
 ******************************************************************/
define('MOD_UNIQUEID','pay_express_fee');
require('global.php');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class DeliveryFeeUpdateAPI extends  adminUpdateBase
{
    private $obj=null;
    private $tbname = 'delivery_fee';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }
    
    public function create()
    {
        if(!$this->input['province_id'])
        {
            $this->errorOutput("NO_PROVINCE_ID");
        }
        if(!$this->input['delivery_category_id'])
        {
            $this->errorOutput("NO_DELIVERY_CATEGORY_ID");
        }
        if(!$this->input['delivery_fee'])
        {
            $this->errorOutput("NO_DELIVERY_FEE");
        }
        $fee = (float)$this->input['delivery_fee'];
        if($fee<0)
        {
            $this->errorOutput("FEE_MUST_BLOW_ZERO");
        }
        $params['delivery_fee'] = $fee;
        $params['province_id'] = intval($this->input['province_id']);
        $params['delivery_category_id'] = intval($this->input['delivery_category_id']);
        
        $query = "SELECT * FROM ".DB_PREFIX."province 
                  WHERE id=".$params['province_id'];
        
        $re_province = $this->obj->query($query);
        
        $params['province_title'] = $re_province[$params['province_id']]['name'];
        
        $query = "SELECT * FROM ".DB_PREFIX."deliverycategory
                  WHERE id=".$params['delivery_category_id'];
        
        $re_deliverycategory  = $this->obj->query($query);
        
        $params['delivery_category_title'] = $re_deliverycategory[$params['delivery_category_id']]['title'];
        
        $params['id'] = $this->obj->insert($this->tbname,$params);
        
        if($params['id'])
        {
            $this->addItem($params);
        }
        $this->Output();
        
    }
    public function delete()
    {
        $id = $this->input['id'];
        if(is_string($id))
        {
            $ids = explode(',',$id);
            $newids = array();
            foreach($ids as $id)
            {
                $id = intval($id);
                if($id){
                    $newids[] = $id;
                }
                else {
                    $this->errorOutput("ERROR_ID");
                }
            }
            $id = implode(',', $newids);
        }
        if(is_numeric($id))
        {
            $id = intval($id);
        }
        
        //用户本人
        $cond = " where 1 and id in ($id) ";
        
        $info = $this->obj->delete($this->tbname,$cond);
        
        if(!$info)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
        $this->addItem($info);
        $this->output();
    }
    
    public function publish()
    {
        
    }
    
    public function sort()
    {
        
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

$out = new DeliveryFeeUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

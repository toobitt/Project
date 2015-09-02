<?php
/*******************************************************************
 * filename :Consignee.php
 * 收货人
 * Created  :2014年3月18日,Writen by gaoyuan 
 * 
 ******************************************************************/
require('./global.php');
define('MOD_UNIQUEID','pay_billcategory');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class BillCategoryAPI extends  outerReadBase
{
    private $obj=null;
    private $tbname = 'billcategory';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
        $this -> billconfig = $this -> settings['bill_config'];
    }
    
    //查看收货人信息详情
    public function detail()
    {
        $id = intval($this->input['id']);
        if(!$id)
        {
            $this->errorOutput(NO_ID);
        }
        
        $cond ='';
        //用户本人
        $cond = " where 1 and id=" .$id ." and user_id=".$this->user['user_id'];
        $cond = " where 1 and id=" .$id;
        
        $info = $this->obj->detail($this->tbname,$cond);
        if(!$info)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
        
        $info['bill_type_title'] = $this -> billconfig['bill_type'][$info['bill_type']];
        $info['bill_header_type_title'] = $this -> billconfig['bill_header_type'][$info['bill_header_type']];
        $info['bill_content_type_title'] = $this -> billconfig['bill_content_type'][$info['bill_content_type']];
        
        foreach($info as $key=>$val)
            $this->addItem_withkey($key,$val);
        
        $this->addItem_withkey('config',$this->billconfig);
        $this->output();
    }
    
    
    //查看收货人信息列表
    public function show()
    {
        $condition = $this->get_condition();
        $datas = array();
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;          
        $count = $this->input['count'] ? intval($this->input['count']) : 20;                    
        $data_limit = $condition.' order by id desc LIMIT ' . $offset . ' , ' . $count;  
           
        $datas = $this->obj->show($this->tbname,$data_limit,$fields='*');
        
        if(!is_array($datas)||!$datas)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
        foreach($datas as $key=>$val)
        {
            $val['bill_type_title'] = $this -> billconfig['bill_type'][$val['bill_type']];
            $val['bill_header_type_title'] = $this -> billconfig['bill_header_type'][$val['bill_header_type']];
            $val['bill_content_type_title'] = $this -> billconfig['bill_content_type'][$val['bill_content_type']];
     
            $this->addItem_withkey($key,$val);
        }
        $this->output();
    }
    
    public function count()
    {
        $condition = $this->get_condition();
        $info = $this->obj->count($this->tbname,$condition);
        echo json_encode($info);
    }
    
    
    public function index()
    {
        
    }
    
    private function get_condition()
    {
        //只显示用户自定义的分类    
        $condition = "  WHERE 1 ";
        /*
        if(isset($this->user['user_id']))
        {
            $condition .= ' and user_id='.intval($this->user['user_id']);
        }
        */
        return $condition;
    }
    
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new BillCategoryAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

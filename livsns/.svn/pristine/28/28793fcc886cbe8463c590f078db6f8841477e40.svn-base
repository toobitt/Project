<?php
/*******************************************************************
 * filename :ConsigneeUpdate.php
 * 收货人信息操作
 * Created  :2014年3月18日,Writen by gaoyuan
 * 
 ******************************************************************/
require('./global.php');
define('MOD_UNIQUEID','pay_consignee');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class BillCategoryUpdateAPI extends  outerUpdateBase
{
    private $obj=null;
    private $tbname = 'billcategory';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
        $this -> billconfig = $this -> settings['bill_config'];
    }
    
    //新增收货人信息
    public function create()
    {
        $params = array();
        if(!$this->input['bill_type'])
        {
            $this->errorOutput("NO_BILL_TYPE");
        }
        
        if(!$this->input['bill_header_type'])
        {
            $this->errorOutput("NO_BILL_HEADER_TYPE");
        }
        
        if(!$this->input['bill_content_type'])
        {
            $this->errorOutput("NO_BILL_CONTENT_TYPE");
        }
        
        if($this->input['bill_header'])
        {
            $params['bill_header'] = $this->input['bill_header'];
        }
        
        if($this->input['bill_title'])
        {
            $params['bill_title'] = $this->input['bill_title'];
        }
        
        $params['bill_type'] = intval($this->input['bill_type']);
        $params['bill_header_type'] = intval($this->input['bill_header_type']);
        $params['bill_content_type'] = intval($this->input['bill_content_type']);
        $params['bill_content'] = $this->billconfig['bill_content_type']['bill_content_type'];        
        
        $params['user_id'] = $this->user['user_id'];
        $params['user_name'] = $this->user['user_name'];
        
        $params['create_time'] = TIMENOW;
        $params['update_time'] = TIMENOW;
        
        $params['id'] = $this->obj->insert($this->tbname,$params);
        
        $this->addItem($params);
        $this->output();    
    }
    
    
    //更新收货人信息
    public function update()
    {
        if(!isset($this->input['id']))
        {
            $this->errorOutput("NO_ID");
        }
        $id = intval($this->input['id']);
        
        $cond = " WHERE `id`=$id ";
        
        $params = array();
        
        if(isset($this->input['bill_type']))
        {
            $params['bill_type'] = intval($this->input['bill_type']);
        }
        
        if(isset($this->input['bill_header_type']))
        {
            $params['bill_header_type'] = intval($this->input['bill_header_type']);
        }
        
        if(isset($this->input['bill_content_type']))
        {
            $params['bill_content_type'] = intval($this->input['bill_content_type']);
        }

        if(isset($this->input['bill_title']))
        {
            $params['bill_title'] = $this->input['bill_title'];
        }
        
        if(isset($this->input['bill_header']))
        {
            $params['bill_header'] = $this->input['bill_header'];
        }
        
        $params = $this->obj->update($this->tbname,$params,$cond);
        $this->addItem($params);
        $this->output();
    }
    
    public function delete()
    {
        if (empty ($this->input['id']))
        {
            $this->errorOutput("NO_DATA_ID");
        }
        $id = intval($this->input['id']);
        
        $cond = " where id in ($id) ";
        
        $re = $this->obj->delete($this->tbname,$cond);
        $this->addItem($re);
        $this->output();
    }
    
    public function publish()
    {
        return;
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

$out = new BillCategoryUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

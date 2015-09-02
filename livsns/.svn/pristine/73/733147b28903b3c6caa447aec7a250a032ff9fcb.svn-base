<?php
/*******************************************************************
 * Filename :ConsigneeUpdate.php
 * 收货人
 * Created  :2014年3月18日,Writen by gaoyuan
 * 
 ******************************************************************/
require('global.php');
define('MOD_UNIQUEID','pay_consignee');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class ConsigneeUpdateAPI extends  adminUpdateBase
{
    private $obj=null;
    private $tbname = 'consignee';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }
    
    //新增收货人信息
    public function create()
    {
      	$params = array();
      	
      	$params['ip'] = hg_getip();
        $params['user_id'] = $this->user['user_id'];
        $params['user_name'] = $this->user['user_name'];
        $params['province'] = $this->input['province'];
        $params['city'] = $this->input['city'];
        $params['area'] = $this->input['area'];
        $params['address'] = $this->input['address'];
        $params['telephone'] = $this->input['telephone'];
        $params['is_default'] = $this->input['is_default'];
        $params['create_time'] = TIMENOW;
        $params['update_time'] = TIMENOW;
		$params['consignee_name'] = $this->input['consignee_name'];
		         
      	if($params['is_default'] =='1')
    	{
    		$cond = " WHERE user_id = " .$this->user['user_id'] ;
	        $pa = array();
	        $pa['is_default'] = '0';
	        
	        //默认地址只有一个，若新增的默认，先将用户的其他收货地址设置为非默认
	        $par = $this->obj->update($this->tbname,$pa,$cond);
    	}
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
        $params['id'] = $id;
        $params['user_id'] = $this->user['user_id'];
        $params['user_name'] = $this->user['user_name'];
        $params['province'] = $this->input['province'];
        $params['city'] = $this->input['city'];
        $params['area'] = $this->input['area'];
        $params['address'] = $this->input['address'];
        $params['telephone'] = $this->input['telephone'];
        $params['is_default'] = $this->input['is_default'];
        $params['update_time'] = TIMENOW;
    
    	if($params['is_default'] =='1')
    	{
    		$con = " WHERE user_id = " .$this->user['user_id'] ;
	        $pa = array();
	        $pa['is_default'] = '0';
	        //默认地址只有一个，若更新的默认，先将用户的其他收货地址设置为非默认
	        $par = $this->obj->update($this->tbname,$pa,$con);
    	}
        $datas = $this->obj->update($this->tbname,$params,$cond);
        $this->addItem($datas);
        $this->output();
    }
    
    //删除收货人信息
    public function delete()
    {
    		/***************权限*****************/
    		$this->verify_content_prms(array('_action'=>'manage'));
    		/***********************************/
        if (empty ($this->input['id']))
        {
            $this->errorOutput("NO_DATA_ID");
        }
        $id = $this->input['id'];
        
        $cond = " where id in ($id) ";
        
        $re = $this->obj->delete($this->tbname,$cond);
        $this->addItem($re);
        $this->output();
    }
    
    public function publish()
    {
    }
    
    
    public function audit()
    {
    }
    
    public function sort()
    {
    }
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new ConsigneeUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

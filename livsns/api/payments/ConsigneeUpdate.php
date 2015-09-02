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
class ConsigneeUpdateAPI extends  outerUpdateBase
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
        if(!$this->user['user_id'])
        {
            $this->errorOutput('NO_LOGIN');
        }
        $params['user_name'] = $this->user['user_name'];
        
        if(!$this->input['province'])
        {
            $this->errorOutput('NO_SELECT_PROVINCE');
        }
        $params['province'] = $this->input['province'];
        if(!$this->input['city'])
        {
            $this->errorOutput('NO_SELECT_CITY');
        }
        $params['city'] = $this->input['city'];
        if(!$this->input['area'])
        {
            $this->errorOutput('NO_SELECT_AREA');
        }
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
        $params['province'] = $this->input['province'];
        $params['city'] = $this->input['city'];
        $params['area'] = $this->input['area'];
        $params['address'] = $this->input['address'];
        $params['telephone'] = $this->input['telephone'];
        $params['is_default'] = $this->input['is_default'];
        $params['update_time'] = TIMENOW;
    	$params['consignee_name'] = $this->input['consignee_name'];
    	
    	if($params['is_default'] =='1')
    	{
    		$con = " WHERE user_id = " .$this->user['user_id'] ;
	        $pa = array();
	        $pa['is_default'] = '0';
	        
	        //默认地址只有一个，若更新的默认，先将用户的其他收货地址设置为非默认
	        $par = $this->obj->update($this->tbname,$pa,$con);
    	}
    	
        $re = $this->obj->update($this->tbname,$params,$cond);
        
        if($re)
        {
        	$arr[] = 'success';
        }
        else
        {
        	$arr[] = 'false';
        }
        $this->addItem($arr);
        $this->output();
    }
    
    //删除收货人信息
    public function delete()
    {
        if (empty ($this->input['id']))
        {
            $this->errorOutput("NO_DATA_ID");
        }
        $id = $this->input['id'];
        
        $cond = " where id in ($id) ";
        
        $re = $this->obj->delete($this->tbname,$cond);
        if($re)
        {
        	$arr[] = 'success';
        }
        else
        {
        	$arr[] = 'false';
        }
        $this->addItem($arr);
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

$out = new ConsigneeUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

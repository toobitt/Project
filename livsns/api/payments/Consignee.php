<?php
/*******************************************************************
 * filename :Consignee.php
 * 收货人
 * Created  :2014年3月18日,Writen by gaoyuan 
 * 
 ******************************************************************/
require('./global.php');
define('MOD_UNIQUEID','pay_consignee');
require_once CUR_CONF_PATH . 'core/Core.class.php';
require_once CUR_CONF_PATH . 'lib/region.class.php';
class ConsigneeAPI extends  outerReadBase
{
    private $obj=null;
    private $tbname = 'consignee';
    public function __construct()
    {

        parent::__construct();
        $this->obj = new Core();
        $this->region = new regionClass();
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
        $cond = " where 1 and id=" .$id;
        
        $info = $this->obj->detail($this->tbname,$cond);
        if(!$info)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
		if($info['province'])
    	{
    		$province =  $this->region->get_province($info['province'],1);
    		$info['province_name'] = $province[$info['province']];
    	}
    	if($info['city'])
    	{
    		$city =  $this->region->get_city('',$info['city'],1);
    		$info['city_name'] = $city[$info['city']];
    	}
    	if($info['area'])
    	{
    		$area =  $this->region->get_area('',$info['area'],1);
    		$info['area_name'] = $area[$info['area']];
    	}
    	
        $this->addItem($info);
        $this->output();
    }
    
    
    //查看收货人信息列表
  	public function show()
    {
        //$condition = $this->get_condition();
        $condition = '';
        $user_id = intval($this->user['user_id']);
        if($user_id)
        {
            $condition .= ' and c.user_id='.$user_id;
            $datas = array();
	        $offset = $this->input['offset'] ? $this->input['offset'] : 0;          
	        $count = $this->input['count'] ? intval($this->input['count']) : 20;                    
	        $data_limit = $condition.' order by id desc LIMIT ' . $offset . ' , ' . $count;  
	        
	        $query = "SELECT c.*,d.delivery_fee as delivery_fee 
	                  FROM ".DB_PREFIX."$this->tbname c
	                  LEFT JOIN ".DB_PREFIX."delivery_fee d
	                  ON c.province=d.province_id
	                  WHERE c.state=1 ".$data_limit;
	        //$datas = $this->obj->show($this->tbname,$data_limit,$fields='*');
	        //echo $query.$data_limit;
	        $datas = $this->obj->query($query);
			if($datas && is_array($datas))
			{
				foreach($datas as $k=>$v)
		        {
		        	if($v['province'])
		        	{
		        		$province =  $this->region->get_province($v['province'],1);
		        		$v['province'] = $province[$v['province']];
		        	}
		        	if($v['city'])
		        	{
		        		$city =  $this->region->get_city('',$v['city'],1);
		        		$v['city'] = $city[$v['city']];
		        	}
		        	if($v['area'])
		        	{
		        		$area =  $this->region->get_area('',$v['area'],1);
		        		$v['area'] = $area[$v['area']];
		        	}
		            $this->addItem($v);
		        }
			}
			else
			{
				$arr = array();
        		$this->addItem($arr);
			}
        } 
        else
        {
        	$arr = array();
        	$this->addItem($arr);
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
        $condition = "  WHERE 1  and state=1";
        if(isset($this->user['user_id']))
        {
            $condition .= ' and user_id='.intval($this->user['user_id']);
        }

        return $condition;
    }
    
    
    //获取省份信息
    public function get_province()
    {
    	$datas = array();
        $datas = $this->region->get_province();
        $this->addItem($datas);
        $this->output();
    }
    
    //根据省份获取市
    public function get_city()
    {
    	$datas = array();
    	
    	$province_id = $this->input['province_id'];
    	if(!$province_id)
        {
            $this->errorOutput(NO_PROVINCE_ID);
        }
        
        $datas = $this->region->get_city($province_id);
        $this->addItem($datas);
        $this->output();
    }
    
    
    //根据市获取地区
    public function get_area()
    {
    	$datas = array();
    	
    	$city_id = $this->input['city_id'];
    	if(!$city_id)
        {
            $this->errorOutput(NO_CITY_ID);
        }
        $datas = $this->region->get_area($city_id);
        $this->addItem($datas);
        $this->output();
    }
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
}

$out = new ConsigneeAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out-> $action();
?>

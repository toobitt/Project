<?php
/*******************************************************************
 * Filename :Consignee.php
 * 收货人
 * Created  :2014年3月18日,Writen by gaoyuan
 * 
 ******************************************************************/
define('MOD_UNIQUEID','pay_consignee');
require('global.php');
require_once CUR_CONF_PATH . 'core/Core.class.php';
require_once CUR_CONF_PATH . 'lib/region.class.php';
class ConsigneeAPI extends  adminReadBase
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
        $cond = " where 1 and id=$id";
        
        $info = $this->obj->detail($this->tbname,$cond);
        
       if(!$info)
        {
            $this->errorOutput(NO_DATA_EXIST);
        }
		if($info['province'])
    	{
    		$province =  $this->region->get_province($info['province']);
    		$info['province'] = $province[$info['province']];
    	}
    	if($info['city'])
    	{
    		$city =  $this->region->get_city('',$info['city']);
    		$info['city'] = $city[$info['city']];
    	}
    	if($info['area'])
    	{
    		$area =  $this->region->get_area('',$info['area']);
    		$info['area'] = $area[$info['area']];
    	}
    	
        $this->addItem($info);
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
        $datas['user'] = $this->get_user();
		if($datas && is_array($datas))
		{
			foreach($datas as $k=>$v)
	        {
	        	if($v['province'])
	        	{
	        		$province =  $this->region->get_province($v['province']);
	        		$v['province'] = $province[$v['province']];
	        		$v['region'] = $v['province'];
	        	}
	        	if($v['city'])
	        	{
	        		$city =  $this->region->get_city('',$v['city']);
	        		$v['city'] = $city[$v['city']];
	        		$v['region'] .= ' '.$v['city'];
	        	}
	        	if($v['area'])
	        	{
	        		$area =  $this->region->get_area('',$v['area']);
	        		$v['area'] = $area[$v['area']];
	        		$v['region'] .= ' '.$v['area'];
	        	}
	        	$datas[$k] = $v;
	        	
	        }
		}
         $this->addItem($datas);
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
        $condition = " WHERE 1";
        
        /*if(isset($this->user['user_id']))
        {
            $condition .= ' and user_id='.intval($this->user['user_id']);
        }*/
        
        if(isset($this->input['user_id']) && $this->input['user_id'] !='-1')
        {
            $condition .= ' and user_id='.intval($this->input['user_id']);
        }
         //查询的时间
        if ($this->input['date_search'])
        {
            $today    = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('Y-m-d', TIMENOW + 24 * 3600));
            switch (intval($this->input['date_search']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday     = strtotime(date('y-m-d', TIMENOW - 24 * 3600));
                    $condition .= " AND  create_time > '" . $yesterday . "' AND create_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  create_time > '" . $today . "' AND create_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND create_time > '" . $last_threeday . "' AND create_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  create_time > '" . $last_sevenday . "' AND create_time < '" . $tomorrow . "'";
                    break;
                default://所有时间段
                    break;
            }
        }
        
        return $condition;
        /*if(isset($this->input['province']))
        {
            $condition .= ' and province='.intval($this->input['province']);
        }
        if(isset($this->input['city']))
        {
            $condition .= ' and city='.intval($this->input['city']);
        }
        if(isset($this->input['area']))
        {
            $condition .= ' and area='.intval($this->input['area']);
        }
        if(isset($this->input['postcode']))
        {
            $condition .= ' and postcode='.intval($this->input['postcode']);
        }
        if(isset($this->input['landline']))
        {
            $condition .= ' and landline='.intval($this->input['landline']);
        }
         //查询
        if ($this->input['key'])
        {
            $condition .= " AND address LIKE '%" . trim(urldecode($this->input['key'])) . "%' ";
        }

        //查询创建的起始时间
        if ($this->input['start_time'])
        {
            $condition .= " AND create_time > " . strtotime($this->input['start_time']);
        }

        //查询创建的结束时间
        if ($this->input['end_time'])
        {
            $condition .= " AND create_time < " . strtotime($this->input['end_time']);
        }

		if ($this->input['user_name'])
        {
            $condition .=" AND user_name = '" . $this->input['user_name']."'";
        }
        //查询发布的时间
        if ($this->input['date_search'])
        {
            $today    = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('Y-m-d', TIMENOW + 24 * 3600));
            switch (intval($this->input['date_search']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday     = strtotime(date('y-m-d', TIMENOW - 24 * 3600));
                    $condition .= " AND  create_time > '" . $yesterday . "' AND create_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  create_time > '" . $today . "' AND create_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND create_time > '" . $last_threeday . "' AND create_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  create_time > '" . $last_sevenday . "' AND create_time < '" . $tomorrow . "'";
                    break;
                default://所有时间段
                    break;
            }
        }

        //查询文章的状态
        if (isset($this->input['state']))
        {
            switch (intval($this->input['state']))
            {
                case 1:
                    $condition .= " ";
                    break;
                case 2: //待审核
                    $condition .= " AND state= 0";
                    break;
                case 3://已审核
                    $condition .= " AND state = 1";
                    break;
                case 4: //已打回
                    $condition .=" AND state = 2";
                default:
                    break;
            }
        }*/
    }
    
    public function get_user()
    {
       $name = $this->tbname;
       $cond = " WHERE 1";
       $sql = "SELECT DISTINCT user_id,user_name FROM ". DB_PREFIX . "$name $cond ";
       $q     = $this->db->query($sql);
       while ($row = $this->db->fetch_array($q))
       {	
       		if($row['user_id'])
       		{
       			$return[$row['user_id']] = $row['user_name'];
       		}
       }
       return $return;
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

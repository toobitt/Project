<?php
define('MOD_UNIQUEID','subway_site');//模块标识
require('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
class subwaySiteApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/subway_site.class.php');
		$this->obj = new subwaySite();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show($condition,$limit);	
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}		
	}

	public function detail()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'subway_site WHERE id = '.$this->input['id'];
		$ret = $this->db->query_first($sql);
		
		$sq = "select name from " . DB_PREFIX . "subway_site_sort where id = ".$ret['sort_id'];
		$sort_name = $this->db->query_first($sq);
		$ret['sort_name'] = $sort_name['name'];
		$re[] = $ret;
		$this->addItem($re);
		$this->output();
	}
	
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count()
	{	
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'subway_site WHERE 1 '.$this->get_condition();
		$templates_total = $this->db->query_first($sql);
		echo json_encode($templates_total);	
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function get_condition()
	{		
		$condition = '';
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		if(($this->input['state'] || trim(($this->input['state']))== '0') && $this->input['state']!='-1')
		{
			$condition .= ' AND  state ='. intval($this->input['state']) ;
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	
	//获取地铁线路
    public function get_subway()
    {
        $apps = $this->auth->get_app();
        if (is_array($apps))
        {
            foreach ($apps as $k => $v)
            {
                $ret[$v['bundle']] = $v['name'];
            }
        }
        $ret['0'] = '其他';
        $this->addItem($ret);
        $this->output();
    }
    
    //获取出入口
    public function get_expand_type()
    {
        $sqll = "select * from " . DB_PREFIX . "subway_site_type where type  = 1 ";
		$ret = $this->db->query($sqll);
		while($row = $this->db->fetch_array($ret))
		{
			$re['id']  	  = $row['id'];
			$re['title']  = $row['title'];
			$pre[] = $re;
		}
		
        $pre['other'] = '自定义新类型';
        $this->addItem($pre);
        $this->output();
    }
    
    //获取服务
    public function get_service_type()
    {
    	$sqll = "select * from " . DB_PREFIX . "subway_site_type where type  = 2 ";
		$ret = $this->db->query($sqll);
		while($row = $this->db->fetch_array($ret))
		{
			$re['id']  	  = $row['id'];
			$re['title']  = $row['title'];
			$pre[] = $re;
		}
		
        $pre['other'] = '自定义新类型';
        $this->addItem($pre);
        $this->output();
    }
    
	public function index()
	{	
	}
}

$out = new subwaySiteApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

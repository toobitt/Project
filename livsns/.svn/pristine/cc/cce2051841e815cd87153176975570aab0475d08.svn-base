<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'subway');
require_once(ROOT_PATH . "global.php");
require_once(CUR_CONF_PATH . "lib/functions.php");

class subwayApi extends adminBase
{
    public function __construct()
    {
        parent::__construct();
        require_once CUR_CONF_PATH . 'lib/subway.class.php';
        $this->subway   = new subway();
        require_once(ROOT_PATH . 'lib/class/news.class.php');
		$this->news = new news();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
    }

    public function get_condition()
    {
        $condition = '';
        return $condition;
    }

	public function get_subway_list()
    {
    	$offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 50;
        $limit     = " limit {$offset}, {$count}";
        $subway  = array();
        if($this->input['need_site'])
        {
        	 $need_site = $this->input['need_site'];
        }
       
        $condition = ' AND state = 1';
        $subway = $this->subway->get_subway_list($condition,$limit,$need_site);
        if(is_array($subway))
		{
			foreach($subway as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}		
    }
    
    
    public function get_subway_info()
    {
    	$id   = $this->input['id'];
    	
    	if(!$id)
    	{
    		$return = array();
    		$return = array('请输入线路id');
    		$this->addItem($return);
    		$this->output();exit;
    	}
    	
        $need_site    = $this->input['need_site'] ? intval(urldecode($this->input['need_site'])) : 0;
      	$subway = $this->subway->get_subway_info($id,$need_site);
      	
      	$this->addItem($subway);
		$this->output();
        /*$arr = array(
	        	'sign' =>'line1',
	        	'title' =>'1号线',
	        	'color' =>'#B0E0E6',
	        	'start' =>'迈皋桥',
	        	'end' =>'奥体中心',
	        	'start_egname' =>'maigaoqiao',
	        	'end_egname' =>'aotizhognxin',
	        	'sort_name' =>'南京',
	        	'runtime' =>'6:00－23:00',
	        	'is_operate' =>'1',
	        	'indexpic' =>array('0'=>array('host'=>'http://img.dev.hogesoft.com:233/','dir'=>'material/special/img/','filepath'=>'2014/02/','filename'=>'20140220151818TECG.jpg',)),
	        	'site_info' =>array(
						'start'=>array(
	    						'0'=>array('id'=>'1','title'=>'南京站','egname'=>'nanjing','has_toilet'=>'1','start_time'=>'6:10','end_time'=>'23:10','is_hub'=>'0','sub_color'=>array('#B0E0E6')),
	        				'1'=>array('id'=>'2','title'=>'新街口站','egname'=>'xinjiekou','has_toilet'=>'1','start_time'=>'6:20','end_time'=>'23:20','is_hub'=>'1','sub_color'=>array('#B0E0E6','#00FFFF'))
	        				),
	        			'end'=>array(
	        				'0'=>array('id'=>'3','title'=>'新街口','egname'=>'nanjing','has_toilet'=>'1','start_time'=>'6:10','end_time'=>'23:10','is_hub'=>'0','sub_color'=>array('#B0E0E6')),
	        				'1'=>array('id'=>'4','title'=>'南京站','egname'=>'xinjiekou','has_toilet'=>'1','start_time'=>'6:20','end_time'=>'23:20','is_hub'=>'1','sub_color'=>array('#B0E0E6','#00FFFF'))
	        				),
	        	),
        );*/
    }

	public function get_subway_site()
    {
    	$offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 100;
        $limit     = " limit {$offset}, {$count}";
        $info = $subway = array();
        $condition = '';
       // $this->input['title'] = '迈皋桥';
        if($this->input['title'])
		{
			$condition .= ' AND  a.title  LIKE "%'.trim(($this->input['title'])).'%"';
		}
		//$this->input['longitude'] =	'118.810206';
		//$this->input['latitude'] = '32.102944';
		
		if($this->input['longitude'] && $this->input['latitude'])
		{
			$info = array(
				'longitude'	=>	$this->input['longitude'],
				'latitude'	=>	$this->input['latitude'],
			);
		}
		else
		{
			if($this->input['x'] && $this->input['y'])
			{
				$gps =  $this->subway->GpsToBaidu($this->input['x'],$this->input['y']);
				$info = array(
					'longitude'	=>	$gps['x'],
					'latitude'	=>	$gps['y'],
				);
			}
		}
		
		if($info['latitude'] && $info['longitude'])
		{
			$distance = $this->input['distance'] ? intval($this->input['distance']) : DISTANCE;
			$jwd = hg_jwd_square(intval($info['latitude']),intval($info['longitude']),$distance);
			//$condition .= ' AND  latitude >='. $jwd['wd']['min'] .' AND  latitude <='. $jwd['wd']['max'] ;
			//$condition .= ' AND  longitude >='. $jwd['jd']['min'] .' AND  longitude <='. $jwd['jd']['max'] ;
		}
		
		
		$limit = '';
		
        $subway = $this->subway->get_subway_sites($condition,$limit,$info);
        if(is_array($subway))
		{
			foreach($subway as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}		
    }
    
    public function get_subway_site_info()
    {
    	$id   = $this->input['id'];
    	$site = array();
        
        $site = $this->subway->get_subway_site_info($id);
        
        $this->addItem($site);
        $this->output();
        
        /*$arr = array(
        	'id' =>'1',
        	'sign' =>'xinjiekou',
        	'title' =>'新街口站',
        	'sub_color' =>array('#B0E0E6','#00FFFF'),
        	'longitude' =>'10.000000',
        	'latitude' =>'20.00000',
        	'has_toilet' =>'1',
        	'site_x' =>'1591',
	        'site_y' =>'1414',
        	'indexpic' =>array('0'=>array('host'=>'http://img.dev.hogesoft.com:233/','dir'=>'material/special/img/','filepath'=>'2014/02/','filename'=>'20140220151818TECG.jpg',)),
        	'train' =>array('0'=>array('color'=>'#B0E0E6','start'=>array(
																'station'=>'奥体中心','start_time'=>'7:35','end_time'=>'21:05'),
														'end'=>array(
																'station'=>'迈皋桥','start_time'=>'7:35','end_time'=>'21:05'),
										),
							'1'=>array('color'=>'#00FFFF','start'=>array(
																'station'=>'经天路','start_time'=>'7:35','end_time'=>'21:05'),
														'end'=>array(
																'station'=>'油坊桥','start_time'=>'7:35','end_time'=>'21:05'),
							),
						),
			'peaktime' =>'周一至周五',
			'peakstart' =>'9:00',
			'peakend' =>'10:00',
			'peakbrief' =>'1雄镇楼、2抚宁巷、3望江门、4姚园寺巷、5城站香榭大、厦6章家桥浙医、二院井亭桥孩儿、巷延安新、村13武林门、西14松木场1、5杭大路16庆、丰17天目山路学、院路口18、古荡(万塘路以西)',
   		 ) ;*/
        
    }
    
    public function get_subway_site_gate()
    {
        $id   = $this->input['id'];
        $gate_info = array();
        $gate_info = $this->subway->get_subway_site_gate($id);
        
        if(is_array($gate_info))
		{
			foreach($gate_info as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}	
        
        /*file_put_contents('011',var_export($site,1));
        $arr = array(
        	'0' =>array(
	        	'sign' =>'5',
	        	'title' =>'中山路(北)',
	        	'color' =>'#B0E0E6',
	        	'brief' =>'1雄镇楼、2抚宁巷、3望江门、4姚园寺巷、5城站香榭大、厦6章家桥浙医、二院井亭桥孩儿、巷延安新、村13武林门、西14松木场1、5杭大路16庆、丰17天目山路学、院路口18、古荡(万塘路以西)',
	        	'has_toilet' =>'1',
	        	'indexpic' =>array('0'=>array('host'=>'http://img.dev.hogesoft.com:233/','dir'=>'material/special/img/','filepath'=>'2014/02/','filename'=>'20140220151818TECG.jpg',)),
	        	'longitude' =>'10.000000',
	        	'latitude' =>'20.00000',
	        	'expand' =>array('0'=>array(
									'indexpic' =>array('0'=>array('host'=>'http://img.dev.hogesoft.com:233/','dir'=>'material/special/img/','filepath'=>'2014/02/','filename'=>'20140220151818TECG.jpg',)),'title' =>'公交','sign' =>'gongjiao','station_name' =>'中央门东站','station_id' =>'2','brief'=>'1雄镇楼、2抚宁巷、3望江门、4姚园寺巷、5城站香榭大、厦6章家桥浙医、二院井亭桥孩儿、巷延安新、村13武林门、西14松木场1、5杭大路16庆、丰17天目山路学、院路口18、古荡(万塘路以西)'
										),
								 '1'=>array(
									'indexpic' =>array('0'=>array('host'=>'http://img.dev.hogesoft.com:233/','dir'=>'material/special/img/','filepath'=>'2014/02/','filename'=>'20140220151818TECG.jpg',)),'title' =>'公交','sign' =>'gongjiao','station_name' =>'大东方百货','station_id' =>'3','brief'=>'1雄镇楼、2抚宁巷、3望江门、4姚园寺巷、5城站香榭大、厦6章家桥浙医、二院井亭桥孩儿、巷延安新、村13武林门、西14松木场1、5杭大路16庆、丰17天目山路学、院路口18、古荡(万塘路以西)'
										)
				),
       		 ) ,
       		 '1' =>array(
	        	'sign' =>'23',
	        	'title' =>'中山路(11111北)',
	        	'color' =>'#B0E0E6',
	        	'brief' =>'1雄镇楼、2抚宁巷、3望江门、4姚园寺巷、5城站香榭大、厦6章家桥浙医、二院井亭桥孩儿、巷延安新、村13武林门、西14松木场1、5杭大路16庆、丰17天目山路学、院路口18、古荡(万塘路以西)',
	        	'has_toilet' =>'1',
	        	'indexpic' =>array('0'=>array('host'=>'http://img.dev.hogesoft.com:233/','dir'=>'material/special/img/','filepath'=>'2014/02/','filename'=>'20140220151818TECG.jpg',)),
	        	'longitude' =>'10.000000',
	        	'latitude' =>'20.00000',
	        	'expand' =>array('0'=>array(
									'indexpic' =>array('0'=>array('host'=>'http://img.dev.hogesoft.com:233/','dir'=>'material/special/img/','filepath'=>'2014/02/','filename'=>'20140220151818TECG.jpg',)),'title' =>'公交','sign' =>'gongjiao','station_name' =>'中央门东站','station_id' =>'2','brief'=>'1雄镇楼、2抚宁巷、3望江门、4姚园寺巷、5城站香榭大、厦6章家桥浙医、二院井亭桥孩儿、巷延安新、村13武林门、西14松木场1、5杭大路16庆、丰17天目山路学、院路口18、古荡(万塘路以西)'
										),
								 '1'=>array(
									'indexpic' =>array('0'=>array('host'=>'http://img.dev.hogesoft.com:233/','dir'=>'material/special/img/','filepath'=>'2014/02/','filename'=>'20140220151818TECG.jpg',)),'title' =>'公交','sign' =>'gongjiao','station_name' =>'大东方百货','station_id' =>'3','brief'=>'1雄镇楼、2抚宁巷、3望江门、4姚园寺巷、5城站香榭大、厦6章家桥浙医、二院井亭桥孩儿、巷延安新、村13武林门、西14松木场1、5杭大路16庆、丰17天目山路学、院路口18、古荡(万塘路以西)'
										)
				),
       		 ) ,
        );*/
    }
    
    public function get_subway_site_service()
    {
    	$id   = $this->input['id'];
    	$site_service = array();
        $site_service = $this->subway->get_subway_site_service($id);
        
        if(is_array($site_service))
		{
			foreach($site_service as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}	
        /*$arr = array(
	    	'0' =>array(
		        	'sign' =>'atm',
		        	'title' =>'ATM',
		        	'color' =>'#B0E0E6',
		        	'brief' =>'1雄镇楼、2抚宁巷、3望江门、4姚园寺巷、5城站香榭大、' .
		        			  '厦6章家桥浙医、二院井亭桥孩儿、巷延安新、村13武林门、西14松木场1、' .
		        			  '5杭大路16庆、丰17天目山路学、院路口18、古荡(万塘路以西)',
	       	) ,
	       	'1' =>array(
		        	'sign' =>'chongzhidian',
		        	'title' =>'充值点',
		        	'color' =>'#B0E0E6',
		        	'brief' =>'1雄镇楼、2抚宁巷、3望江门、4姚园寺巷、5城站香榭大、' .
		        			  '厦6章家桥浙医、二院井亭桥孩儿、巷延安新、村13武林门、西14松木场1、' .
		        			  '5杭大路16庆、丰17天目山路学、院路口18、古荡(万塘路以西)',
	       	) ,
	       	'2' =>array(
		        	'sign' =>'washroom',
		        	'title' =>'厕所',
		        	'color' =>'#B0E0E6',
		        	'brief' =>'1雄镇楼、2抚宁巷、3望江门、4姚园寺巷、5城站香榭大、' .
		        			  '厦6章家桥浙医、二院井亭桥孩儿、巷延安新、村13武林门、西14松木场1、' .
		        			  '5杭大路16庆、丰17天目山路学、院路口18、古荡(万塘路以西)',
	       	) ,
        );*/
        
    }   
    
    public function get_subway_service_sort()
    {
    	$offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
        $limit     = " limit {$offset}, {$count}";
    	$sorts = array();
		$sorts = $this->subway->get_subway_service_sort($limit);
        
        if(is_array($sorts))
		{
			foreach($sorts as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}	
    }
    
    //从发布库取内容
    public function get_subway_service_list()
    {
    	
    	require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->puscont = new publishcontent();
		
		$offset     = $this->input['offset'] ? intval($this->input['offset']) : 0;//如果没有传第几页，默认是第一页	
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$data = array(
			'offset'	  		=> $offset,
			'count'		  		=> $count,
			'client_type'		=>	'2',
			'need_count'		=> '1',
		);
		if ($this->input['sort_id'])
		{
			$data['column_id'] = intval($this->input['sort_id']);
		}
            
		if($this->input['need_count'])
		{
			$sql = "SELECT column_id FROM ". DB_PREFIX ."subway_service_sort WHERE sign='notice'";
			$col = $this->db->query_first($sql);
			$data['column_id'] = $col['column_id'];
			$re = $this->puscont->get_content($data);
			$return = array();
			$return['count'] = $re['total'];
			$this->addItem($return);
			$this->output();
		}
		else
		{
			$re = $this->puscont->get_content($data);
			$return = array();
			if($re['data'] && is_array($re['data']))
			{
				foreach($re['data'] as $k=>$v)
				{
					$return[$k]['id']	 = $v['id'];
					$return[$k]['title'] = $v['title'];
					$return[$k]['create_time'] = $v['create_time'];
					$return[$k]['indexpic'] = $v['indexpic'];
				}
			}
			if(is_array($return))
			{
				foreach($return as $k => $v)
				{
					$this->addItem($v);
				}
				$this->output();
			}	
		}
    }
    
    public function get_subway_service_info()
    {

		$id   = $this->input['id'];
		$service_info = array();
		$service_info = $this->news->detail($id);
		
		$this->addItem($service_info);
		$this->output();
    }
    
    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new subwayApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>

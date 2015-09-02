<?php
require('global.php');
define('MOD_UNIQUEID','gongjiao');//模块标识
class lineApi extends adminReadBase
{
	public function __construct()
	{
		
		$this->mPrmsMethods = array(
		'manage'	=>'管理',
		'_node'=>array(
			'name'=>'公交',
			//'filename'=>'logs_node.php',
			//'node_uniqueid'=>'logs_node',
			),
		);
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/line.class.php');
		$this->obj = new line();
		
		include(CUR_CONF_PATH . 'lib/line_info.class.php');
		$this->lineinfo = new lineInfo();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function  show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show($condition,$limit);
		$this->addItem($ret);		
		$this->output();		
	}

	function detail()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'line WHERE id = '.$this->input['id'];
		$r = $this->db->query_first($sql);
		
		$ret[] = $r;
		$this->addItem($ret);
		$this->output();
	}
	
	function  show_stand()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$ret = $this->obj->show_stand($condition,$limit);
		$this->addItem($ret);	
		$this->output();		
	}
	
	
	function  update_linfo()
	{	
		$line_info = $this->lineinfo->get_singleline_8684(intval($this->input['routeid']));
		$info = array();
		$stands_info = array();
		$stands = array();
		$upinfo = array();
		$downinfo = array();
		$up_name = array();
		$down_name = array();
		$up_stands = array();
		$down_stands = array();
		$restation = array();
		$info = array(
				'city_id'		=> $this->settings['city']['id'],
	            'city_name'		=> $this->settings['city']['name'],
				'routeid'		=> $this->input['routeid'],
	            'name'			=> $line_info[0]['name'],
				'time'			=> $line_info[0]['time'],
				'price'			=> $line_info[0]['price'],
				'gjgs'			=> $line_info[0]['gjgs'],
				'kind'			=> $line_info[0]['kind'],
		);
		foreach($line_info['stations'] as $k=>$v)
		{
			$stands[] = $v;
		}
		
		$stands_info =  array(
				'1'	=> $stands[0],
				'2'	=> $stands[1],
		);
		//$info['stands'] = addslashes(json_encode($stands_info));
		
		$bus_info = $this->lineinfo->get_singleline_bus($this->input['routeid']);
		if($bus_info[0])
		{
			foreach($bus_info as $k=>$v)
			{
				$bus_stand[] = $this->lineinfo->get_bus_line_stand($this->input['routeid'],$v['SEGMENTID']);
				$segmentid[] = $v['SEGMENTID'];
				$segmentname[] = $v['SEGMENTNAME'];
			}
			foreach($bus_stand[0] as $k =>$v)
			{
				$upinfo[$v['stationseq']] = array(
					'city_id'		=> $this->settings['city']['id'],
	            	'city_name'		=> $this->settings['city']['name'],
					'name'			=> $v['STATIONNAME'],
					'routeid'		=> $this->input['routeid'],
					'segmentid'		=> $v['SEGMENTID'],
					'stationseq'	=> $v['stationseq'],
					'longitude'		=> $v['LONGITUDE'],
					'latitude '		=> $v['LATITUDE'],
					'stationtype'	=> $v['STATIONTYPEID'],
					'type'			=> '1',
				);
				$up_name[] = $v['STATIONNAME'];
				$up_stands[] = $v['stationseq'];
				$flag = $v['stationseq'];
			}
			foreach($bus_stand[1] as $k =>$v)
			{
				$downinfo[$v['stationseq']] = array(
					'city_id'		=> $this->settings['city']['id'],
	            	'city_name'		=> $this->settings['city']['name'],
					'name'			=> $v['STATIONNAME'],
					'routeid'		=> $this->input['routeid'],
					'segmentid'		=> $v['SEGMENTID'],
					'stationseq'	=> $v['stationseq'],
					'longitude'		=> $v['LONGITUDE'],
					'latitude '		=> $v['LATITUDE'],
					'stationtype'	=> $v['STATIONTYPEID'],
					'type'			=> '1',
				);
				$down_name[] = $v['STATIONNAME'];
				$down_stands[] = $v['stationseq'];
			}
			$info['segmentname'] = implode(',',$segmentname);
			$info['segmentid'] = implode(',',$segmentid);
			$upnames = implode(',',$up_name);
			$dnames = implode(',',$down_name);
			$upstands = implode(',',$up_stands);
			$dstands = implode(',',$down_stands);
			$bus_stands = array(
						'1'		=> $upnames,
						'2'		=> $dnames,
				);
			$busstandsseq = array(
						'1'		=> $dstands,
						'2'		=> $upstands,
				);
			
			//$info['busstands'] = addslashes(json_encode($bus_stands));
			$info['busstandsseq'] = addslashes(json_encode($busstandsseq));
			//print_r($info['busstandsseq']);exit;
		}
 		else
 		{
 			//print_r('一维');exit;
 			$info['segmentname'] = $bus_info['SEGMENTNAME'];
			$info['segmentid '] = $bus_info['SEGMENTID'];
			
			$bus_stand = $this->lineinfo->get_bus_line_stand($this->input['routeid'],$bus_info['SEGMENTID']);
			
			foreach($bus_stand as $k=>$v)
			{
				$upinfo[$v['stationseq']] = array(
					'city_id'		=> $this->settings['city']['id'],
	            	'city_name'		=> $this->settings['city']['name'],
					'name'			=> $v['STATIONNAME'],
					'routeid'		=> $this->input['routeid'],
					'segmentid'		=> $bus_info['SEGMENTID'],
					'stationseq'	=> $v['stationseq'],
					'longitude'		=> $v['LONGITUDE'],
					'latitude '		=> $v['LATITUDE'],
					'stationtype'	=> $v['STATIONTYPEID'],
					'type'			=> '1',
				);
				$up_name[] = $v['STATIONNAME'];
				$up_stands[] = $v['stationseq'];
				
				if($v['STATIONTYPEID'] == '12')
				{
					$restation[$v['stationseq']] = array(
							'city_id'		=> $this->settings['city']['id'],
	            			'city_name'		=> $this->settings['city']['name'],
							'name'			=> $v['STATIONNAME'],
							'routeid'		=> $this->input['routeid'],
							'segmentid'		=> $bus_info['SEGMENTID'],
							'stationseq'	=> $v['stationseq'],
							'longitude'		=> $v['LONGITUDE'],
							'latitude '		=> $v['LATITUDE'],
							'stationtype'	=> $v['STATIONTYPEID'],
							'type'			=> '2',
					);
					$restation_name = $v['STATIONNAME'];
					$restationseq = $v['stationseq'];
					break;
				}
				
			}
			foreach($bus_stand as $k=>$v)
			{	
				if(in_array($v['stationseq'],array_keys($upinfo)))
				{
					continue;
				}
				$downinfo[$v['stationseq']] = array(
					'city_id'		=> $this->settings['city']['id'],
	            	'city_name'		=> $this->settings['city']['name'],
					'name'			=> $v['STATIONNAME'],
					'routeid'		=> $this->input['routeid'],
					'segmentid'		=> $bus_info['SEGMENTID'],
					'stationseq'	=> $v['stationseq'],
					'longitude'		=> $v['LONGITUDE'],
					'latitude '		=> $v['LATITUDE'],
					'stationtype'	=> $v['STATIONTYPEID'],
					'type'			=> '2',
				);
				$down_name[] = $v['STATIONNAME'];
				$down_stands[] = $v['stationseq'];
			}
			$upnames = implode(',',$up_name);
			$dnames = implode(',',$down_name);
			$dnames =  $restation_name.','.$dnames;
			$bus_stands = array(
						'1'		=> $upnames,
						'2'		=> $dnames,
				);
			//$info['busstands'] = addslashes(json_encode($bus_stands));
			
			$downinfo = array_merge($restation,$downinfo);
			
			$upstands = implode(',',$up_stands);
			$dstands = implode(',',$down_stands);
			$dstands = $restationseq.','.$dstands;
			$busstandsseq = array(
						'1'		=> $upstands,
						'2'		=> $dstands,
				);
			$info['busstandsseq'] = addslashes(json_encode($busstandsseq));
 		}
 		//$stands = json_decode($info['stands'],ture);	
		foreach($stands_info as $k => $v)
		{
			$sinfo[$k] =  explode(',',$v);
		}
		//$busstands = json_decode($info['busstands'],ture);	
		foreach($bus_stands as $k => $v)
		{
			$buinfo[$k] =  explode(',',$v);
		}
		$ret['stands'] = $sinfo;
		$ret['busstands'] = $buinfo;
		$ret['linfo'] = serialize($info);
		$this->addItem($ret);	
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
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'line WHERE 1 '.$this->get_condition();
		$templates_total = $this->db->query_first($sql);
		echo json_encode($templates_total);	
	}
	public function index()
	{
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function get_condition()
	{		
		$condition = '';
		//查询应用分组
		if($this->input['k'])
		{
			$condition .= ' AND name LIKE "%'.trim($this->input['k']).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND id = ' .$this->input['id'];
		}
		return $condition;
	}
	
	function get_linfo()
	{	
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'line WHERE routeid = '.intval($this->input['routeid']);
		$r = $this->db->query_first($sql);
		if($r['id']&&$r['stands'])
		{
			$this->errorOutput("线路已存在");
		}
		$line_info = $this->lineinfo->get_singleline_8684(intval($this->input['routeid']));
		if(!$line_info)
		{
			$info = array(
				'city_id'		=> $this->settings['city']['id'],
	            'city_name'		=> $this->settings['city']['name'],
				'name'			=> $this->input['routeid'],
				'routeid'		=> intval($this->input['routeid']),
	            'status'		=> 0,
			);
			
			$line_id = $this->obj->create($info);
			$sql = 'SELECT *
					FROM '.DB_PREFIX.'line WHERE id = '.$line_id;
			$r = $this->db->query_first($sql);
			
			$ret[] = $r;
			$this->addItem($ret);
			$this->output();
			//$this->errorOutput(" 无线路信息");
		}
		$info = array();
		$stands_info = array();
		$stands = array();
		$upinfo = array();
		$downinfo = array();
		$up_name = array();
		$down_name = array();
		$up_stands = array();
		$down_stands = array();
		$restation = array();
		$info = array(
				'city_id'		=> $this->settings['city']['id'],
	            'city_name'		=> $this->settings['city']['name'],
				'routeid'		=> $this->input['routeid'],
	            'status'		=> 0,
	            'name'			=> $line_info[0]['name'],
				'time'			=> $line_info[0]['time'],
				'price'			=> $line_info[0]['price'],
				'gjgs'			=> $line_info[0]['gjgs'],
				'kind'			=> $line_info[0]['kind'],
		);
		foreach($line_info['stations'] as $k=>$v)
		{
			$stands[] = $v;
		}
		
		$stands_info =  array(
				'1'	=> $stands[0],
				'2'	=> $stands[1],
		);
		$info['stands'] = addslashes(json_encode($stands_info));
		
		$bus_info = $this->lineinfo->get_singleline_bus($this->input['routeid']);
		//print_r($bus_info);exit();
		if($bus_info[0])
		{
			foreach($bus_info as $k=>$v)
			{
				$bus_stand[] = $this->lineinfo->get_bus_line_stand($this->input['routeid'],$v['SEGMENTID']);
				$segmentid[] = $v['SEGMENTID'];
				$segmentname[] = $v['SEGMENTNAME'];
			}
			foreach($bus_stand[0] as $k =>$v)
			{
				$upinfo[$v['stationseq']] = array(
					'city_id'		=> $this->settings['city']['id'],
	            	'city_name'		=> $this->settings['city']['name'],
					'name'			=> $v['STATIONNAME'],
					'routeid'		=> $this->input['routeid'],
					'segmentid'		=> $v['SEGMENTID'],
					'stationseq'	=> $v['stationseq'],
					'longitude'		=> $v['LONGITUDE'],
					'latitude '		=> $v['LATITUDE'],
					'stationtype'	=> $v['STATIONTYPEID'],
					'type'			=> '1',
				);
				$up_name[] = $v['STATIONNAME'];
				$up_stands[] = $v['stationseq'];
				$flag = $v['stationseq'];
			}
			foreach($bus_stand[1] as $k =>$v)
			{
				$downinfo[$v['stationseq']] = array(
					'city_id'		=> $this->settings['city']['id'],
	            	'city_name'		=> $this->settings['city']['name'],
					'name'			=> $v['STATIONNAME'],
					'routeid'		=> $this->input['routeid'],
					'segmentid'		=> $v['SEGMENTID'],
					'stationseq'	=> $v['stationseq'],
					'longitude'		=> $v['LONGITUDE'],
					'latitude '		=> $v['LATITUDE'],
					'stationtype'	=> $v['STATIONTYPEID'],
					'type'			=> '1',
				);
				$down_name[] = $v['STATIONNAME'];
				$down_stands[] = $v['stationseq'];
			}
			$info['segmentname'] = implode(',',$segmentname);
			$info['segmentid'] = implode(',',$segmentid);
			$upnames = implode(',',$up_name);
			$dnames = implode(',',$down_name);
			$upstands = implode(',',$up_stands);
			$dstands = implode(',',$down_stands);
			$bus_stands = array(
						'1'		=> $upnames,
						'2'		=> $dnames,
				);
			$busstandsseq = array(
						'1'		=> $dstands,
						'2'		=> $upstands,
				);
			
			$info['busstands'] = addslashes(json_encode($bus_stands));
			$info['busstandsseq'] = addslashes(json_encode($busstandsseq));
			//print_r($info['busstandsseq']);exit;
		}
 		else
 		{
 			//print_r('一维');exit;
 			$info['segmentname'] = $bus_info['SEGMENTNAME'];
			$info['segmentid '] = $bus_info['SEGMENTID'];
			
			$bus_stand = $this->lineinfo->get_bus_line_stand($this->input['routeid'],$bus_info['SEGMENTID']);
			
			foreach($bus_stand as $k=>$v)
			{
				$upinfo[$v['stationseq']] = array(
					'city_id'		=> $this->settings['city']['id'],
	            	'city_name'		=> $this->settings['city']['name'],
					'name'			=> $v['STATIONNAME'],
					'routeid'		=> $this->input['routeid'],
					'segmentid'		=> $bus_info['SEGMENTID'],
					'stationseq'	=> $v['stationseq'],
					'longitude'		=> $v['LONGITUDE'],
					'latitude '		=> $v['LATITUDE'],
					'stationtype'	=> $v['STATIONTYPEID'],
					'type'			=> '1',
				);
				$up_name[] = $v['STATIONNAME'];
				$up_stands[] = $v['stationseq'];
				
				if($v['STATIONTYPEID'] == '12')
				{
					$restation[$v['stationseq']] = array(
							'city_id'		=> $this->settings['city']['id'],
	            			'city_name'		=> $this->settings['city']['name'],
							'name'			=> $v['STATIONNAME'],
							'routeid'		=> $this->input['routeid'],
							'segmentid'		=> $bus_info['SEGMENTID'],
							'stationseq'	=> $v['stationseq'],
							'longitude'		=> $v['LONGITUDE'],
							'latitude '		=> $v['LATITUDE'],
							'stationtype'	=> $v['STATIONTYPEID'],
							'type'			=> '2',
					);
					$restation_name = $v['STATIONNAME'];
					$restationseq = $v['stationseq'];
					break;
				}
				
			}
			foreach($bus_stand as $k=>$v)
			{	
				if(in_array($v['stationseq'],array_keys($upinfo)))
				{
					continue;
				}
				$downinfo[$v['stationseq']] = array(
					'city_id'		=> $this->settings['city']['id'],
	            	'city_name'		=> $this->settings['city']['name'],
					'name'			=> $v['STATIONNAME'],
					'routeid'		=> $this->input['routeid'],
					'segmentid'		=> $bus_info['SEGMENTID'],
					'stationseq'	=> $v['stationseq'],
					'longitude'		=> $v['LONGITUDE'],
					'latitude '		=> $v['LATITUDE'],
					'stationtype'	=> $v['STATIONTYPEID'],
					'type'			=> '2',
				);
				$down_name[] = $v['STATIONNAME'];
				$down_stands[] = $v['stationseq'];
			}
			$upnames = implode(',',$up_name);
			$dnames = implode(',',$down_name);
			$dnames =  $restation_name.','.$dnames;
			$bus_stands = array(
						'1'		=> $upnames,
						'2'		=> $dnames,
				);
			$info['busstands'] = addslashes(json_encode($bus_stands));
			
			$downinfo = array_merge($restation,$downinfo);
			
			$upstands = implode(',',$up_stands);
			$dstands = implode(',',$down_stands);
			$dstands = $restationseq.','.$dstands;
			$busstandsseq = array(
						'1'		=> $upstands,
						'2'		=> $dstands,
				);
			$info['busstandsseq'] = addslashes(json_encode($busstandsseq));
 		}
		$re = $this->obj->create_stand($upinfo);	
		$ret = $this->obj->create_stand($downinfo);	
		$line_id = $this->obj->create($info);
		$sql = 'SELECT *
				FROM '.DB_PREFIX.'line WHERE id = '.$line_id;
		$r = $this->db->query_first($sql);
		
		$ret[] = $r;
		$this->addItem($ret);
		$this->output();
	}
	
}

$out = new lineApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

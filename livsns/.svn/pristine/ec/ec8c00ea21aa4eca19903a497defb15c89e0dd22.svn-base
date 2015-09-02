<?php
define('MOD_UNIQUEID','airport');
define('SCRIPT_NAME', 'airportapi');
define('ROOT_DIR', '../../');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
require_once(CUR_CONF_PATH . 'lib/buffer.class.php');
class airportapi extends adminBase
{
	private $buffer;
	
	function __construct()
	{
		parent::__construct();
		$this->buffer = new buffercore();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	//查询数据
	public function show()
	{
		$postdata = $this->get_query_parameters();
		$buffer_key = md5(serialize($postdata));
		$output = $this->buffer->select($buffer_key);
		$output = $output ? json_decode($output,1) : array();
		if(!$output)
		{
			$json_data = postCurl(AIRLINE_API, $postdata);
			$result = json_decode($json_data,1);
			if($result['Message'])
			{
				$this->errorOutput($result['Message']);
			}
			$output = array();
			$output['total'] = count($result['Data']);
			
			$output['data'] = $result['Data'] ? $result['Data'] : array();
			
			if($output['total'])
			{
				foreach($result['Data'] as $key=>$val)
				{
					$output['data'][$key]['EstimateArriveTime'] = date('H:i',strtotime($val['EstimateArriveTime']));
					$output['data'][$key]['EstimateDeparTime'] = date('H:i',strtotime($val['EstimateDeparTime']));
					switch ($output['data'][$key]['StatusRemark'])
					{
						case '到达' : $statu_color = '14A0C8'; break;
						case '起飞' : $statu_color = '6DCA7E'; break;
						case '计划' : $statu_color = '7582B5'; break;
						case '延误' : $statu_color = 'E1453C'; break;
						case '取消' : $statu_color = 'ABABAB'; break;
						default : $statu_color = '14a0c8';
					}
					$output['data'][$key]['StatusRemarkColor'] = $statu_color;
				}
			}
			
			$this->buffer->replace($buffer_key, json_encode($output));
		}
		$this->addItem($output);
		$this->output();
	}
	
	protected function get_query_parameters()
	{
		$parameters = array(
		'DepartDate'		=>$this->input['depart_date'],
		'DepartAirCode'		=>$this->input['depart_aircode'],
		'ArriveAirCode'		=>$this->input['arrive_aircode'],
		'FlightNo'			=>$this->input['flight_no'],
		'Offset'			=>$this->input['offset'],
		);
		if(!$parameters['FlightNo'] && (!$parameters['DepartAirCode'] || !$parameters['ArriveAirCode']))
		{
			$this->errorOutput(PARAMETERS_ERROR);
		}
		return $parameters;
	}
	
	public function get_all_airline()
	{
		$page = floor($this->input['offset']/10)+1;
		if($this->input['offset']%10 != 0) $page = ceil($this->input['offset']/10)+1;
		
		$parameters = array(
		'dep'=>$this->input['depart_citycode'] ? $this->input['depart_citycode'] : '',
		'arr'=>$this->input['arrive_citycode'] ? $this->input['arrive_citycode'] : '',
		'airline'=>$this->input['airline'] ? $this->input['airline'] : 'ALL',
		'page'=>$page,
		);
				
		$buffer_key = md5(serialize($parameters));
		$outdata = $this->buffer->select($buffer_key);
		$outdata = $outdata ? json_decode($outdata,1) : array();
		if(!$outdata)
		{
			$url = AIRLINE_API2 . '?dep=' . $parameters['dep'] . '&arr=' . $parameters['arr']  .'&airline=' .$parameters['airline']  . '&page=' . $page;
		    $html = curlRequest($url);
		    if(!$html)
		    {
			    $this->errorOutput(GET_DATA_ERROR);
		    }
		    $match = array();
		    preg_match_all('/(.*?)<div\s{0,}class="comm">(.*?)<\/div><\/div>/is', $html, $match);
		    $match[2][0] = $match[2][0].'</div>';
		    $output = array();
		    if($match)
		    {
			    //匹配航班信息
			    $_tmpx = array();
			    preg_match_all('/\s{0,}<div\s{0,}class="line"><span\s{0,}style="color:\s{0,}\#\w{6};">\s{0,}&nbsp;(.*?)<\/span>(.*?)<\/div>/is', $match[2][0], $_tmpx);			
			    if($_tmpx)
			    {
			        foreach ($_tmpx[2] as $key=>$val)
			        {			        	
			    	    preg_match_all('/(?:.*?)<td\s{0,}(?:.*?)>\s{0,}(?:<span\s{0,}(?:.*?)>){0,1}(.*?)(?:<\/span>){0,1}<\/td>(?:.*?)/is', $val, $_tmp1[]);		
			        }
			    }
			    if(is_array($_tmp1) && $_tmp1)
			    {
				    foreach ($_tmp1 as $key=>$val)
				    {
				        $vv=$val[1];					        
					    $output[$key] = array(
					        'FlightNo'           => $_tmpx[1][$key], 
				        	'DepartAirportCode'  => trim($vv[0]),
					        'EstimateDeparTime'  => '--:--',
					        'EstimateArriveTime' => '--:--',
					        'ArriveAirportCode'  => trim($vv[5]),
					        'DepartTerminal'     => trim($vv[1]),
					        'ActualDepartTime'   => trim($vv[3]),
					        'ActualArriveTime'   => trim($vv[8]),
					        'ArriveTerminal'     => trim($vv[6]),
					        'PlanDepartTime'     => trim($vv[2]),
					        'PlanArriveTime'     => trim($vv[7]),
					        'StopAirport'        => '',
					        'StopCity'           => '',
					        'AirCompanyName'     => '',
					        'StatusRemark'       => trim($vv[9]),			        	
				        );
				    }
			    }
			    //匹配航班状态的颜色
			    preg_match_all('/(?:.*?)<div\s{0,}class="line">((?:.*?)<span\s{0,}class="(.*?)">(?:.*?)<\/span>){3}(?:.*?)<\/div>(?:.*?)/is', $match[2][0], $_tmp);
		        if($_tmp[2] && is_array($_tmp[2]))
			    {
				    foreach ($_tmp[2] as $key=>$val)
				    {
				        switch ($val)
					    {
						    case 'fly'  : $StatusRemarkColor = '95B23D'; break;
						    case 'cacel': $StatusRemarkColor = 'D81921'; break;
						    case 'delay': $StatusRemarkColor = 'F7931E'; break;
						    default: $StatusRemarkColor = '95B23D';
					    }
                        $output[$key]['StatusRemarkColor']     = $StatusRemarkColor;
				    }
			    }

			    /*****计算数据总数*******/
		    	preg_match_all('/\s*<div\s{0,}id="Panel1">(?:.*?)&nbsp;(\d+)\/(\d+)(?:.*?)<\/div>(?:.*?)/is', $html, $page);
		    	if($page[2][0])  //有多页的情况>10条
		    	{
		    		$total_page = $page[2][0]; //总页数
		            $last_url = AIRLINE_API2 . '?dep=' . $parameters['dep'] . '&arr=' . $parameters['arr']  .'&airline=' .$parameters['airline']  . '&page=' . $total_page;
		            $last_html = curlRequest($last_url);
		            preg_match_all('/(?:.*?)<div\s{0,}class="line">(.*?)<\/div>(?:.*?)/is', $last_html, $last_page_match);
		            $last_page_number= count($last_page_match[1]);
		        
		            if($total_page > 0 ) $total = ( $total_page - 1 ) * 10 + $last_page_number;  //计算total
		    	}
		    	else //只有一页<=10条
		    	{
		    		$total = count($output);
		    	}
                /*****计算数据总数*******/  
		    }		    
	        $outdata['total'] = $total;
		    $outdata['data'] = $output;
		    $this->buffer->replace($buffer_key, json_encode($outdata));
		}
		if(!$outdata)
		{
			$this->errorOutput(NO_DATA);
		}
	    $this->addItem($outdata);
		$this->output();
	}
	
}
include(ROOT_PATH . 'excute.php');
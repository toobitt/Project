<?php
class ClassServiceGGFW extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = array(), $offset, $count)
	{
		$arr = array();
		$pagesize  = $count;
		$currentpage = floor($offset/$count)+1;
		$xml = $this->ggfw_xml($condition, $pagesize, $currentpage);
		//echo $xml;exit();
		$cilentOptions = array( 'trace'=> true, 'exceptions' => true, 'cache_wsdl' => WSDL_CACHE_NONE,);
		$client = new SoapClient(WEB_URL,$cilentOptions);
		$ret_str = $client->SelectCaseInfoList_GGFW(array('xmlCaseInfo'=>$xml));
		//var_dump($ret_str);exit();
		$ret_str = $ret_str->SelectCaseInfoList_GGFWResult;
		$ret_str = xml2Array($ret_str);	
		//hg_pre($ret_str);exit();	
		if (!$ret_str['DATA']['ReturnInfo']['Status'])
		{
			return $arr;
		}
		$data = $ret_str['DATA']['UserArea']['CaseList']['CaseInfo'];
		//hg_pre($data);exit();
		//字段重新命名
		if (!empty($data))
		{
			foreach ($data as $val)
			{
				$arr[] = array(
					'id'			=> $val['CaseGuid'],
					'create_time'	=> $val['RequestDate'] ? strtotime($val['RequestDate']) : $val['RequestDate'],
					'title'			=> $val['RequestTitle'],	
					'tel'			=> $val['RequestNumber'],
					'user_name'		=> $val['RequestPerson'],			
				);
			}
		}
		//hg_pre($arr);exit();
		return $arr;
	}
	
	public function count($condition = array())
	{
		$total = 0;
		$pagesize = 1;
		$currentpage = 1;
		$xml = $this->ggfw_xml($condition, $pagesize, $currentpage);
		$cilentOptions = array( 'trace'=> true, 'exceptions' => true, 'cache_wsdl' => WSDL_CACHE_NONE,);
		$client = new SoapClient(WEB_URL,$cilentOptions);
		//echo $xml;exit();
		$ret_str = $client->SelectCaseInfoList_GGFW(array('xmlCaseInfo'=>$xml));
		$ret_str = $ret_str->SelectCaseInfoList_GGFWResult;
		$ret_str = xml2Array($ret_str);
		//hg_pre($ret_str);exit();
		if (!$ret_str['DATA']['ReturnInfo']['Status'])
		{
			return $total;
		}
		$Page = $ret_str['DATA']['PageInfo'];
		$total = $Page['TotalNumCount'];
		return $total;
	}
	
	public function detail($id)
	{
		$arr = array();
		$xml  = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<paras>';
		$xml .= '<IdentityGuid>Epoint_WebSerivce_**##0601</IdentityGuid>';
		$xml .= '<CaseGuid>'. $id .'</CaseGuid>';
		$xml .= '</paras>';
		$cilentOptions = array( 'trace'=> true, 'exceptions' => true, 'cache_wsdl' => WSDL_CACHE_NONE,);
		$client = new SoapClient(WEB_URL,$cilentOptions);
		$ret_str = $client->SelectCaseInfoDetail_GGFW(array('xmlCaseInfo'=>$xml));
		//var_dump($ret_str);exit();
		$ret_str = $ret_str->SelectCaseInfoDetail_GGFWResult;
		$ret_str = xml2Array($ret_str);
		//hg_pre($ret_str);exit();
		if (!$ret_str['DATA']['ReturnInfo']['Status'])
		{
			return $arr;
		}
		$data = $ret_str['DATA']['UserArea'];
		//hg_pre($data);exit();
		$arr['id']				= $data['CaseGuid'];
		$arr['case_serial']		= $data['CaseSerial'];//编号
		$arr['create_time']		= $data['RequestDate'] ? strtotime($data['RequestDate']) : $data['RequestDate'];
		$arr['tel']				= $data['RequestNumber'];
		$arr['user_name']		= $data['RequestPerson'];
		$arr['email']			= $data['Email'];
		$arr['address']			= $data['Address'];
		$arr['area']			= $this->settings['service_area'][$data['RequestAddress']];;
		$arr['title']			= $data['RequestTitle'];
		$arr['content']			= cdata($data['Description']);
		$arr['note']			= $data['RequestNote'];//备注;
		$arr['case_status']		= $this->settings['service_case_status'][$data['CaseStatus']];//工单状态
		$arr['case_type']		= $this->settings['service_case_type'][$data['CaseType']];	//工单类型
		$arr['answer_date']		= $data['AnswerDate'] ? strtotime($data['AnswerDate']) : $data['AnswerDate'];
		$arr['answer_content']	= cdata($data['AnswerContent']);
		$arr['manyidu']         = $data['ManYiDu'];
		return $arr;
	}
	
	
	public function ggfw_xml($condition = array(), $pagesize = 10, $currentpage = 1)
	{
		$xml  = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<paras>';
		$xml .= '<IdentityGuid>Epoint_WebSerivce_**##0601</IdentityGuid>';
		$xml .= '<RequestNumber>'.$condition['tel'].'</RequestNumber>';
		$xml .= '<RequestAddress>'.$condition['area'].'</RequestAddress>';
		$xml .= '<Address>'.$condition['address'].'</Address>';
		$xml .= '<RequestTitle>'.$condition['title'].'</RequestTitle>';
		$start_time = '';
		if ($condition['start_time'])
		{
			$start_time = @date('Y/m/d H:i:s', $condition['start_time']);
		}
		$xml .= '<SearchDateFrom>'.$start_time.'</SearchDateFrom>';
		$end_time = '';
		if ($condition['end_time'])
		{
			$end_time = @date('Y/m/d H:i:s', $condition['end_time']);
		}
		$xml .= '<SearchDateTo>'.$end_time.'</SearchDateTo>';
		$xml .= '<RequestPassWord>'.$condition['password'].'</RequestPassWord>';
		$xml .= '<PageSize>'. $pagesize .'</PageSize>';
		$xml .= '<CurrentPageIndex>'. $currentpage .'</CurrentPageIndex>';
		$xml .= '</paras>';
		return $xml;
	}

}
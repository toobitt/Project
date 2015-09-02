<?php
class ClassServiceBMZX extends InitFrm
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
		$xml = $this->bmzx_xml($condition, $pagesize, $currentpage);
		//echo $xml;exit();
		$cilentOptions = array( 'trace'=> true, 'exceptions' => true, 'cache_wsdl' => WSDL_CACHE_NONE,);
		$client = new SoapClient(WEB_URL,$cilentOptions);
		$ret_str = $client->SelectBMZXList(array('xmlWebInfo'=>$xml));
		$ret_str = $ret_str->SelectBMZXListResult;
		$ret_str = xml2Array($ret_str);	
		//hg_pre($ret_str);exit();	
		if (!$ret_str['DATA']['ReturnInfo']['Status'])
		{
			return $arr;
		}
		$data = $ret_str['DATA']['UserArea']['InfoList']['WebInfo'];
		//hg_pre($data);exit();
		//字段重新命名
		if (!empty($data))
		{
			foreach ($data as $val)
			{
				$arr[] = array(
					'id'			=> $val['infoid'],
					'create_time'	=> strtotime($val['infodate']),
					'title'			=> $val['title'],				
				);
			}
		}
		return $arr;
	}
	
	public function count($condition = array())
	{
		$total = 0;
		$pagesize = 1;
		$currentpage = 1;
		$xml = $this->bmzx_xml($condition, $pagesize, $currentpage);
		$cilentOptions = array( 'trace'=> true, 'exceptions' => true, 'cache_wsdl' => WSDL_CACHE_NONE,);
		$client = new SoapClient(WEB_URL,$cilentOptions);
		//echo $xml;exit();
		$ret_str = $client->SelectBMZXList(array('xmlWebInfo'=>$xml));
		$ret_str = $ret_str->SelectBMZXListResult;
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
		$xml .= '<infoid>'. $id .'</infoid>';
		$xml .= '</paras>';
		$cilentOptions = array( 'trace'=> true, 'exceptions' => true, 'cache_wsdl' => WSDL_CACHE_NONE,);
		$client = new SoapClient(WEB_URL,$cilentOptions);
		$ret_str = $client->SelectBMZX(array('xmlWebInfo'=>$xml));
		//var_dump($ret_str);exit();
		$ret_str = $ret_str->SelectBMZXResult;
		$ret_str = xml2Array($ret_str);
		//hg_pre($ret_str);exit();
		if (!$ret_str['DATA']['ReturnInfo']['Status'])
		{
			return $arr;
		}
		$data = $ret_str['DATA']['UserArea'];
		//hg_pre($data);exit();
		$arr['id']				= $data['infoid'];
		$arr['create_time']		= strtotime($data['infodate']);
		$arr['title']			= $data['title'];
		$arr['content']			= str_replace("&nbsp;","",strip_tags($data['InfoContent']));
		$arr['click_times']		= $data['ClickTimes'];
		//hg_pre($arr);exit();
		return $arr;
	}
	
	
	public function bmzx_xml($condition = array(), $pagesize = 10, $currentpage = 1)
	{
		$xml  = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<paras>';
		$xml .= '<IdentityGuid>Epoint_WebSerivce_**##0601</IdentityGuid>';
		$xml .= '<RequestTitle>'.$condition['title'].'</RequestTitle>';
		$xml .= '<SearchDateFrom>'.$condition['start_time'].'</SearchDateFrom>';
		$xml .= '<SearchDateTo>'.$condition['end_time'].'</SearchDateTo>';
		$xml .= '<PageSize>'. $pagesize .'</PageSize>';
		$xml .= '<CurrentPageIndex>'. $currentpage .'</CurrentPageIndex>';
		$xml .= '<Category>1</Category>';
		$xml .= '</paras>';
		return $xml;
	}
	

	
}
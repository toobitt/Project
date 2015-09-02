<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/member.class.php');
class ClassService extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		$this->member = new member();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//查询个人诉求
	public function service_self($offset, $count, $tel = '', $password='')
	{
		$xml  = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<paras>';
		$xml .= '<IdentityGuid>Epoint_WebSerivce_**##0601</IdentityGuid>';
		$xml .= '<RequestNumber>'.$tel.'</RequestNumber>';
		$xml .= '<RequestAddress></RequestAddress>';
		$xml .= '<Address></Address>';
		$xml .= '<RequestTitle></RequestTitle>';
		$xml .= '<SearchDateFrom></SearchDateFrom>';
		$xml .= '<SearchDateTo></SearchDateTo>';
		$xml .= '<RequestPassWord>'.$password.'</RequestPassWord>';
		$xml .= '<PageSize>10</PageSize>';
		$xml .= '<CurrentPageIndex>1</CurrentPageIndex>';
		$xml .= '</paras>';
		$cilentOptions = array( 'trace'=> true, 'exceptions' => true, 'cache_wsdl' => WSDL_CACHE_NONE,);
		$client = new SoapClient(WEB_URL,$cilentOptions);
		//echo $xml;exit();
		$ret_str = $client->SelectCaseInfoList(array('xmlCaseInfo'=>$xml));
		$ret_str = $ret_str->SelectCaseInfoListResult;
		$ret_str = xml2Array($ret_str);
		if (!$ret_str['EpointDataBody']['DATA']['ReturnInfo']['Status'])
		{
			return false;
		}
		$data = $ret_str['EpointDataBody']['DATA']['UserArea'];
		$Page = $ret_str['EpointDataBody']['DATA']['PageInfo'];
		$arr = array();
		$pageInfo = 
		var_dump($ret_str);exit();

		
	}
	
	public function people_show($offset,$count)
	{
		
		
		$xml	= '<?xml version="1.0" encoding="utf-8"?>';
		$xml   .= '<paras>';
		$xml   .= '<IdentityGuid>Epoint_WebSerivce_**##0601</IdentityGuid>';
		$xml   .= '<SearchDateFrom></SearchDateFrom>';
		$xml   .= '<SearchDateTo></SearchDateTo>';
		$xml   .= '<PageSize>10</PageSize>';
		$xml   .= '<CurrentPageIndex>1</CurrentPageIndex>';
		$xml   .= '</paras>';
		
		$cilentOptions = array( 'trace'=> true, 'exceptions' => true, 'cache_wsdl' => WSDL_CACHE_NONE,);
		$client = new SoapClient(WEB_URL,$cilentOptions);
		echo $xml;exit();
		$ret_str = $client->SelectBMZXList(array('xmlWebInfo'=>$xml));
		var_dump($ret_str);exit();
	}
	
	
	public function common_show($condition,$orderby,$offset,$count)
	{
		
	}
	
	
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'seekhelp sh WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function add_service($data)
	{
		if (empty($data) || !is_array($data))
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'service SET ';
		foreach ($data as $key=>$val)
		{
			$sql .=  $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$sql = 'UPDATE '.DB_PREFIX.'service set order_id = '.$id.' WHERE id = '.$id;
		$this->db->query($sql);
		$data['id'] = $id;
		return $data;
	}
	
	public function add_content($content, $id)
	{
		if (!$id || !$content)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'content (id, content) VALUES ('.$id.',"'.addslashes($content).'")';
		$this->db->query($sql);
		return $content;
	}
	
	//百度坐标转换为GPS坐标
	public function FromBaiduToGpsXY($x,$y)
	{
	    $Baidu_Server = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
	    $result = @file_get_contents($Baidu_Server);
	    $json = json_decode($result);  
	    if($json->error == 0)
	    {
	        $bx = base64_decode($json->x);     
	        $by = base64_decode($json->y);  
	        $GPS_x = 2 * $x - $bx;  
	        $GPS_y = 2 * $y - $by;
	        return array('GPS_x' => $GPS_x,'GPS_y' => $GPS_y);//经度,纬度
	    }
	    else
	    {
	    	return false;//转换失败
	    }
	}
	
	//GPS坐标转换为百度坐标
	public function FromGpsToBaiduXY($x,$y)
	{
		$url = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$info = json_decode($response,1);
		if($info && !$info['error'])
		{
			unset($info['error']);
			$info['x'] = base64_decode($info['x']);
			$info['y'] = base64_decode($info['y']);
			return $info;
		}
	}
	//提供便民服务
	public function forward_people($data)
	{
		if (!$data)
		{
			return false;
		}
		$xml = $this->_people_xml($data);
		//echo $xml;exit();
		try {
	   		$cilentOptions = array( 'trace'=> true, 'exceptions' => true, 'cache_wsdl' => WSDL_CACHE_NONE,);
			$client = new SoapClient(WEB_URL,$cilentOptions);
			$ret_str = $client->InsertCaseInfo_BMFW(array('xmlCaseInfo'=>$xml));			
			$ret_str = $ret_str->InsertCaseInfo_BMFWResult;
			$ret_str = xml2Array($ret_str);
			if (!$ret_str['DATA']['ReturnInfo']['Status'])
			{
				$this->failed_data($data['id']);
				return false;
			}
		} catch (SoapFault $fault){
			$this->failed_data($data['id']);
			return false;
    		//echo "Error: ",$fault->faultcode,", string: ",$fault->faultstring;
    		
		}
		return true;
	}
	//提交公共服务
	public function forward_common($data)
	{
		if (!$data)
		{
			return false;
		}
		$xml = $this->_common_xml($data);
		try {
	   		$cilentOptions = array( 'trace'=> true, 'exceptions' => true, 'cache_wsdl' => WSDL_CACHE_NONE,);
			$client = new SoapClient(WEB_URL,$cilentOptions);
			//echo $xml;exit();
			$ret_str = $client->InsertCaseInfo_GGFW(array('xmlCaseInfo'=>$xml));
			$ret_str = $ret_str->InsertCaseInfo_GGFWResult;
			$ret_str = xml2Array($ret_str);
			if (!$ret_str['DATA']['ReturnInfo']['Status'])
			{
				$this->failed_data($data['id']);
				return false;
			}
		} catch (SoapFault $fault){
			$this->failed_data($data['id']);
			return false;
    		//echo "Error: ",$fault->faultcode,", string: ",$fault->faultstring;exit();
		}
		return true;
	}
	
	public function _common_xml($data)
	{		
		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<paras>';
		$xml .= '<IdentityGuid>Epoint_WebSerivce_**##0601</IdentityGuid>';
		$xml .= '<RequestDate>'.@date('Y/m/d H:i:s', $data['create_time']).'</RequestDate>';
		$xml .= '<RequestNumber>'.$data['tel'].'</RequestNumber>';
		$xml .= '<RequestPerson>'.$data['user_name'].'</RequestPerson>';
		$xml .= '<RequestAddress>10</RequestAddress>';
		$xml .= '<Address>'.$data['address'].'</Address>';
		$xml .= '<Tel>'.$data['tel'].'</Tel>';
		$xml .= '<Email>'.$data['email'].'</Email>';
		$xml .= '<RequestTitle>'.$data['title'].'</RequestTitle>';
		$xml .= '<Description><![CDATA['.$data['content'].']]></Description>';
		$xml .= '<RequestPassWord>'.$data['password'].'</RequestPassWord>';
		$xml .= '<CutomerID>'.$data['cutomer_id'].'</CutomerID>';
		$xml .= '<IDType>'.$data['id_type'].'</IDType>';
		$xml .= '<RequestNote>无</RequestNote>';
		$xml .= '<CaseType>10</CaseType>';
		$xml .= '</paras>';
		return $xml;
	}
	
	public function _people_xml($data)
	{
		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml .= '<paras>';
		$xml .= '<IdentityGuid>Epoint_WebSerivce_**##0601</IdentityGuid>';
		$xml .= '<RequestDate>'.@date('Y/m/d H:i:s', $data['create_time']).'</RequestDate>';
		$xml .= '<RequestNumber>'.$data['tel'].'</RequestNumber>';
		$xml .= '<RequestPerson>'.$data['user_name'].'</RequestPerson>';
		$xml .= '<RequestAddress>'.$data['area'].'</RequestAddress>';
		$xml .= '<Address>'.$data['address'].'</Address>';
		$xml .= '<Tel>'.$data['tel'].'</Tel>';
		$xml .= '<Email>'.$data['email'].'</Email>';
		$xml .= '<RequestTitle>'.$data['title'].'</RequestTitle>';
		$xml .= '<Description><![CDATA['.$data['content'].']]></Description>';
		$xml .= '<RequestPassWord>'.$data['password'].'</RequestPassWord>';
		$xml .= '<RequestNote>无</RequestNote>';
		$xml .= '<CaseType>10</CaseType>';
		$xml .= '</paras>';
		return $xml;
	}
	
	public function failed_data($ids)
	{
		if (!$ids)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'service SET is_failed = 1 WHERE id  IN ('.$ids.')';
		$this->db->query($sql);
		return true;
	}
}
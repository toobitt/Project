<?php
/*
 * 视频技术审核回调
 * */
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
define('MOD_UNIQUEID', 'vod');
class video_technical_review extends outerReadBase
{
    public function __construct()
    {
       parent::__construct();
    }
    
    /*
    public function EMBCallback_1($xml)
    {
		//file_put_contents('admin/1back.txt', $xml);
    	$data = $this->mediaXml2Array($xml);//获取提交过来xml技术审核信息，并且直接转换为数组
    	$callback_request = $data['soapenv_Body']['EMBCallback']['EMBCallbackRequest'];
    	$video_id = $callback_request['ns1_AsynResponse']['ns1_RequestID'];
    	$status   = $callback_request['ns1_AsynResponse']['ns1_Status'];
    	$result = $callback_request['ns2_TaskResult']['ns2_TaskFile']['ns2_AuditInfo']['InspectResult'];
    	$media_info = $result['MediaInfo'];
    	$all_media_info = $media_info['Video']['@attributes'] + $media_info['Audio']['@attributes'];
    	$video_result = array();
    	foreach($result['VideoResult']['VideoItem'] AS $k => $v)
    	{
    		$video_result[] = $v['@attributes'];
    	}
    	$all_media_info['VideoResult'] = serialize($video_result);
    	$audio_result = array();
    	foreach($result['AudioResult']['AudioItem'] AS $k => $v)
    	{
    		$audio_result[] = $v['@attributes'];
    	}
    	$all_media_info['AudioResult'] = serialize($audio_result);
    	$this->storage_data($all_media_info);
    	return 'success';
    }
    */
    
	public function EMBCallback($obj)
	{
		//file_put_contents(ROOT_PATH . 'uploads/1.txt', var_export($obj, 1));
		$request_id = $obj->AsynResponse->RequestID;
		if(!$request_id)
		{
			return;
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."vodinfo WHERE EMBTaskID = '" . $request_id . "'";
		$video = $this->db->query_first($sql);
		if(!$video)
		{
			return;
		}
		
		$status 	= $obj->AsynResponse->Status;
		if(!$status)
		{
			$sql = "UPDATE " .DB_PREFIX. "vodinfo SET EMBTaskID = '' ,technical_status = 3 WHERE id = '" .$video['id']."'";
		}
		else 
		{
			$sql = "UPDATE " .DB_PREFIX. "vodinfo SET EMBTaskID = '' ,technical_status = -1 WHERE id = '" .$video['id']."'";
		}
		$this->db->query($sql);
		
		$auditInfo  = $this->mediaXml2Array(strval($obj->TaskResult->TaskFile->AuditInfo));
		$media_info = $auditInfo['MediaInfo'];
    	$all_media_info = $media_info['Video']['@attributes'] + $media_info['Audio']['@attributes'];
    	$video_result = array();
    	if($auditInfo['VideoResult'])
    	{
    		foreach($auditInfo['VideoResult']['VideoItem'] AS $k => $v)
	    	{
	    		$video_result[] = $v['@attributes'];
	    	}
    	}
    	$all_media_info['VideoResult'] = serialize($video_result);
    	$audio_result = array();
    	if($auditInfo['AudioResult'])
    	{
	    	foreach($auditInfo['AudioResult']['AudioItem'] AS $k => $v)
	    	{
	    		$audio_result[] = $v['@attributes'];
	    	}
    	}
    	$all_media_info['AudioResult'] = serialize($audio_result);
    	$all_media_info['video_id'] = $video['id'];
		//file_put_contents('1back.txt', var_export($obj->AsynResponse, 1).$sql);
    	$this->storage_data($all_media_info);
	}
    
    private function storage_data($data)
    {
    	$sql = "REPLACE INTO " . DB_PREFIX ."mediainfo SET ";
    	foreach($data AS $k => $v)
    	{
    		$sql .= " {$k} = '{$v}',";
    	}
    	$sql = rtrim($sql,',');
    	$this->db->query($sql);
    }

	private function mediaXml2Array($xmlstr)
	{
	    $xmlstr = preg_replace('/\sxmlns="(.*?)"/', ' _xmlns="${1}"', $xmlstr);
	    $xmlstr = preg_replace('/<(\/)?(\w+):(\w+)/', '<${1}${2}_${3}', $xmlstr);
	    $xmlstr = preg_replace('/(\w+):(\w+)="(.*?)"/', '${1}_${2}="${3}"', $xmlstr);
		file_put_contents('1back.txt', $sxmlstrql);
	    $xmlobj = @simplexml_load_string($xmlstr);
	    return json_decode(json_encode($xmlobj), true);
	}

	function verifyToken()
	{
		
	}
	
	public function detail()
	{
		
	}
	
	public function count()
	{
	
	}
	
	public function show()
	{
	
	}
	
}

$objSoapServer = new SoapServer('./dyemb/EMBCallbackService.wsdl');
$objSoapServer->setClass("video_technical_review"); 
$objSoapServer->handle();
?>
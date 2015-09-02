<?php
require_once('global.php');
class VodWebservice
{	
  	public function create($xml = '')
  	{
  		global $MediaserverConfig;
  	    $video = para_xml($xml);//解析xml数据
  	    if(!$video)
  	    {
  	    	return false;
  	    }
  	    $curl = new curl($MediaserverConfig['host'],$MediaserverConfig['dir']);
  	    $curl->setSubmitType('get');
		$curl->initPostData();
		$xml_node = array(
		   'title' => $video['Name'],
		   'comment' => $video['Description'],
		   'filepath' => $video['FileName'],
		   'vod_leixing' => VODLEIXING,
		   'content_id' => $video['ContentID'],
		   'start' => $video['FileInpoint'],
		   'end' => $video['FileOutpoint'],
		   'create_time' => $video['CreateDate'],
		   'ts_need_preprocess' =>1,
		   'mp4_from_sobey' =>1,
		   'notcheck' =>1,
		);

		foreach ($xml_node AS $k => $v)
		{
			$curl->addRequestData($k,$v);
		}
		$ret = $curl->request('create.php');
		if($ret[0])
		{
			return $ret[0]['id'];
		}
		else
		{
		}
		return false;
  	}
}

$Server = new SoapServer('VodWebservice.wsdl');
$Server->setClass("VodWebservice");
$Server->handle();

?>
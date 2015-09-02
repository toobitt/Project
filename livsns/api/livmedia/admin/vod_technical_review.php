<?php
//技术审核
require_once('global.php');
define('MOD_UNIQUEID','vod');
require(CUR_CONF_PATH."conf/technical.conf.php");
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  vod_technical_review extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	private function technical_audit($video)
	{
		$data = array(
			'SourceID' => 'xmlspy',
			'Priority' => 5,
			'RequestID' => strval($video['id']),
			'CallbackAddressInfo' => 'http://' . $this->settings['App_livmedia']['host'] . '/' . $this->settings['App_livmedia']['dir'] . 'technical_review_callback.php',
			'TaskName' => $video['title'],
			'StorageType' => 1,
			'StoragePath' => $this->settings['technical_swdl']['sharedir']. rtrim(str_replace('/','\\',$video['video_path']),'\\'),
			'FileName' => $video['video_filename'],
		);
		$wsdl = "../dyemb/AddEMBTaskService.wsdl";

		$client = new SoapClient($wsdl,array('trace'=>true,'cache_wsdl'=>WSDL_CACHE_NONE, 'soap_version' => SOAP_1_2));
		$struct = array(
			'AsynRequest' => array(
				'SourceID' => $data['SourceID'],
				'Priority' => $data['Priority'],
				'RequestID' => $data['RequestID'],
				'CallbackAddressInfo' => $data['CallbackAddressInfo']
			),
			'TaskName' => $data['TaskName'],
			'UserID' => strval($this->user['user_id']),
			'TransferTasks' => array(
				'TransferInfo' => array(
					'SourceFile' => array(
							'StorageInfo' => array(
								'SystemID' => 'livmcp',
								'StorageType' => $data['StorageType'],
								'StoragePath' => $data['StoragePath'],
							),
							'FileName' => $data['FileName'],
					),
					'TaskOption' => array(
						'AutoFileAudit' => 4,
						'IsAddLogoInfo' => false,
					),
				),
			),
		);
		
		try
		{
			$ret = $client->AddEMBTask($struct);
			$EMBTaskID = $ret->EMBTaskID;
		}
		catch (Exception $soapFault)
		{
			//var_export($soapFault);
		}
		
		return $EMBTaskID;
	}
	
	public function review()
	{
		$this->input['id'] = intval($this->input['id']);
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX ."vodinfo WHERE id = '" . $this->input['id'] . "'";
		$video = $this->db->query_first($sql);
		$ret = array();
		// -1 => "技审失败", 1 => "待技审",2  => "技审中",3 => "技审完成"
		if ($video['EMBTaskID'])
		{
			$this->errorOutput('正在技术审核中....');
		}
		if($video['technical_status'] != 3)
		{
			$return = $this->technical_audit($video);
			if(!$return)
			{
				$sql = " UPDATE ". DB_PREFIX . "vodinfo SET technical_status = -1 WHERE id = '" .$this->input['id']. "'";
				$ret['info'] = -1;
			}
			else 
			{
				$sql = " UPDATE ". DB_PREFIX . "vodinfo SET EMBTaskID = '" . $return . "',technical_status = 2 WHERE id = '" .$this->input['id']. "'";
				$ret['info'] = 2;
			}
			$this->db->query($sql);
		}
		else
		{
			$sql = "SELECT * FROM ". DB_PREFIX ."mediainfo WHERE video_id = '".intval($this->input['id'])."'";
			$ret['info'] = $this->db->query_first($sql);
			unset($ret['info']['id'],$ret['info']['video_id']);
			//$ret['info']['ColorFormat'] = $this->settings['fileformattype'][$ret['info']['ColorFormat']];
			$ret['info']['ColorFormat'] = $this->settings['ColorFormat'][$ret['info']['ColorFormat']];
			$ret['info']['ScanMode'] = $this->settings['ScanMode'][$ret['info']['ScanMode']];
			$ret['info']['VideoType'] = $this->settings['VideoCodingFormat'][$ret['info']['VideoType']];
			$ret['info']['AudioType'] = $this->settings['AudioCodingFormat'][$ret['info']['AudioType']];
			$ret['info']['VideoResult'] = unserialize($ret['info']['VideoResult']);
			$ret['info']['AudioResult'] = unserialize($ret['info']['AudioResult']);
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new vod_technical_review();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$out->$action();
?>
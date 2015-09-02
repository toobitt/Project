<?php
ini_set("soap.wsdl_cache_enabled", "0");
require('./global.php');
require(ROOT_PATH . 'lib/class/ftp.class.php');
require(ROOT_PATH . 'lib/class/curl.class.php');
define('MOD_UNIQUEID', 'mmobject');
class mmobject extends adminBase
{
	protected $ftp;
	
	//视频数据
	protected $vdata;
	
	function __construct()
	{
		parent::__construct();
		if(!class_exists('DOMDocument'))
		{
			$this->errorOutput('PHPextention DOMDocument is not Exists!');
		}
		$this->xml_config = array(
		'version'		=> '1.0',
		'charset'		=> 'UTF-8',
		'root'			=> 'Auto.NET',
		);
		$this->ftp = new Ftp($this->settings['ftp']);
		$this->getVideoData();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	protected function getVideoData()
	{
		//请求单个视频路径为上传xml做准备
		if(!$this->settings['App_livmedia']['host'])
		{
			$this->errorOutput('请先安装视频应用！');
		}
		$c = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir']);
		$c->initPostData();
		$c->addRequestData('id', $this->input['id']);
		$c->addRequestData('a', 'get_videos');
		$re = $c->request('vod.php');
		$this->vdata = $re[0];
		$this->vdata['23211']['test'] = array('hoge','hogesoft');
		if(!is_array($this->vdata))
		{
			$this->errorOutput('无效的视频数据！');
		}
	}
	public function registerMmobjct()
	{
		//$this->video2ftp();
		$this->xml2ftp();
		$this->buildSoapXml();
	}
	//调用视频上传接口 发送视频文件至ftp
	public function video2ftp()
	{
		if(!USE_FTP_UPLOAD)
		{
			return;
		}
		if(!$this->settings['App_mediaserver'])
		{
			$this->errorOutput('请先安装转码服务器！');
		}
		$c = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . '/admin/');
		$c->initPostData();
		$c->addRequestData('video_id', implode(',', array_keys($this->vdata)));
		$c->addRequestData('a', 'upload');
		foreach ($this->settings['ftp'] as $key=>$value)
		{
			$c->addRequestData($key, $value);
		}
		$re = $c->request('ftp_upload.php');
		if(!$re || $re == 'null')
		{
			$this->errorOutput('无效的视频！');
		}
		if($re['ErrorCode'] || $re['ErrorText'])
		{
			$this->errorOutput('Mediaserver:' . $re['ErrorText']);
		}
		foreach ($re as $key=>$val)
		{
			if($val['id'])
			{
				$this->vdata[$val['id']]['ftp_path'] = $val['dir'] . '/';
			}
			/*
			else
			{
				$this->vdata[$val['id']]['ftp_path'] = $this->vdata[$val['id']]['video_path'];
			}
			*/
		}
	}
	public function xml2ftp()
	{
		//$this->ftp->connect();
		foreach ($this->vdata as $vid=>$val)
		{
			//metaxml ftp upload
			$val['ftp_path'] = $val['ftp_path'] ? $val['ftp_path'] : $val['video_path'];
			//$this->errorOutput($val['ftp_path']);
			/*
			if(!$this->ftp->mkdir($val['ftp_path']))
			{
				$this->errorOutput('Ftp目录创建失败！');
			}
			*/
			$this->buildMetaXml($vid, $val);
			$metaxml = CACHE_DIR . $vid . '.MTA';
			
			/*
			if($this->ftp->upload($metaxml, $val['ftp_path'] . $vid . '.MTA'))
			{
				@unlink($metaxml);
			}
			else
			{
				$this->errorOutput('META上传失败！');
			}
			*/
			//adixml ftp upload
			$this->buildAdiXml($vid, $val);
			$adixml = CACHE_DIR . $vid . '.xml';
			/*
			if($this->ftp->upload($adixml, $val['ftp_path'] . $vid . '.xml'))
			{
				@unlink($adixml);
			}
			else
			{
				$this->errorOutput('ADI上传失败！');
			}
			*/
		}
		//var_dump($this->ftp->upload('/Users/zhuld/develop_6.p12', '/develop_6.p12'));
	}
	public function requestSoapServer($method = 'show', $arg = array())
	{	
		try {  
    	$x = new SoapClient(IMS_WSDL,array("exceptions" => 1));  
		} catch (SoapFault $E) {  
		    echo $E->faultstring; 
		}
		$re = $x->__soapCall($method, $arg);
		print_r($re);exit;
	}
	private function buildSoapXml($root = 'RegisterMMObjectRequest')
	{
		if(!$this->vdata)
		{
			$this->errorOutput('无效的视频数据');
		}
		$soap_xml = '';
		//
		foreach($this->vdata as $vid=>$val)
		{
			$mmobject = array(
			'GUID'				=> '#id#',
			'Source' 			=> 'IMS上载',
			'Name' 				=> '#title#',
			'ProgramType'		=> '',
			'Provider'			=> '南京厚健软件科技',
			'Productline'		=> '',
			'SeriesCount'		=> '',
			'VolumeCount'		=> '',
			'PartNumber'		=> '',
			'Length'			=> '',
			'ClassType'			=> '',
			'Director'			=> '',
			'LeadingRole'		=> '',
			'Definition'		=> '',
			'Area'				=> '',
			'Abstract'			=> '',
			'Recommend'			=> '',
			'SeriesName'		=> '',
			'ColumnName'		=> '',
			'PublishYear'		=> '',
			'TrackLanguages'	=> '',
			'SubtitleLanguages'	=> '',
			'HDformat'			=> '',
			'Editer'			=> '',
			'RewardsType'		=> '',
			'Channelname'		=> '',
			'Alternative_Title'	=> '',
			'Premiere_Data'		=> '',
			'ParentName'		=> '',
			'ParentGUID'		=> '',
			'Starttime'			=> '',
			'Trimin'			=> '',
			'Trimout'			=> '',
			'OnlineDriver'		=> '',
			'OnlineFile'		=> '',
			'MetaFile'			=> '',
			'ADIFile'			=> '',
			'PreviewsMTA'		=> array('PreviewMTA'=>'#test#'),
			);
			$dom = new DOMDocument('1.0', 'UTF-8');
			$root = $dom->createElement("RegisterMMObjectRequest");
			$dom->appendChild($root);
			foreach($mmobject as $xnode=>$xvalue)
			{
				$child = $dom->createElement($xnode);
				if(!is_array($xvalue) && $xvalue && preg_match('/^#.*?#$/i', $xvalue))
				{
					$xvalue = $val[trim($xvalue, '#')];
				}
				if(is_array($xvalue))
				{
					list($cnode, $cvalue) = each($xvalue);
					if(preg_match('/^#.*?#$/i', $cvalue))
					{
						$array_text = $val[trim($cvalue, '#')];
					}
					if($array_text)
					{
						foreach($array_text as $t)
						{
						$_child = $dom->createElement($cnode);
						$_text = $dom->createTextNode($t);
						$_child->appendChild($_text);
						$child->appendChild($_child);
						}
					}
				}
				else
				{
					$text = $dom->createTextNode($xvalue);
					$child->appendChild($text);
				}
				$root->appendChild($child);
			}
		}
		$dom->save(CACHE_DIR . $vid . '.soap.xml');
	}
	private function buildMetaXml($vid, $val)
	{
		$meta = array(
		'Version'				=> '1.00',
		'ClipInfo'=>array(
			'Format'			=> array('value'=>'test','_attr'=>array('caption'=>'播出码流视频格式')),
			'BitRate'			=> array('value'=>'','_attr'=>array('caption'=>'压缩码率')),
			'TypeCaption'		=> array('value'=>'','_attr'=>array('caption'=>'类型名称')), 
			'Name'				=> array('value'=>'','_attr'=>array('caption'=>'素材名称')), 
			'KeyWord'			=> array('value'=>'','_attr'=>array('caption'=>'关键词')), 
			'Origin'			=> array('value'=>'','_attr'=>array('caption'=>'来源')), 
			'Owner'				=> array('value'=>'','_attr'=>array('caption'=>'拥有者')), 
			'CreateTime'		=> array('value'=>'','_attr'=>array('caption'=>'创建时间')), 
			'Frames'			=> array('value'=>'','_attr'=>array('caption'=>'素材长度（帧）')), 
			'Size'				=> array('value'=>'','_attr'=>array('caption'=>'素材大小（字节）')),
		),
		'ClipMeta'=>array(
			'Files'				=> array(
				'File'			=> array('value'=>'','_attr'=> array('caption'=>'','compressformat'=>'','compressrate'=>'','mode'=>'','frames'=>'','size'=>'','filename'=>'','companyfilename'=>'')), 
				'_attr'			=> array('caption'=>'播出码流文件列表','channel'=>'1','mtafilename'=>'c:\\ftp')),
			'_attr'				=> array('channelcount'=>'1'),
		),
		'Posters'=>array(
			'Poster'			=> '',
			'_attr'				=> array('GUID'=>'','filename'=>'', 'postertype'=>''),
		),
		'CGs'=>array(
			'CG'				=> '',
			'_attr'				=> array('GUID'=>'','filename'=>'','CGtype'=>'')
		),
		);
		$this->setting_xml_root(array(
			'version'		=> '1.0',
			'charset'		=> 'gb2312',
			'root'			=> 'Auto.NET',
			'data'			=> $val,
			'path'			=> CACHE_DIR . $vid . '.meta.xml',
			)
		);
		$this->array2xml($meta);
	}
	public function setting_xml_root($config = array())
	{
		$this->xml_config = $config;
	}
	protected function array2xml($arr,$dom=0,$item=0)
	{
		if(!$arr)
		{
			return;
		}
		if (!$dom)
		{
			$dom = new DOMDocument($this->xml_config['version'], $this->xml_config['charset']);
		}
		if(!$item)
		{
			$item = $dom->createElement($this->xml_config['root']);
			$dom->appendChild($item);
		}
		foreach ($arr as $key=>$val)
		{
			$itemx = $dom->createElement($key);
			$item->appendChild($itemx);
			if (!is_array($val))
			{
				$text = $dom->createTextNode($val);
				$itemx->appendChild($text);

			}
			else 
			{
				if($val['_attr'])
				{
					foreach($val['_attr'] as $attrk=>$attrv)
					{
						$xmlattr = $dom->createAttribute($attrk);
						$xmlattr->value = $attrv;
						$itemx->appendChild($xmlattr);
					}
					unset($val['_attr']);
				}
				if(isset($val['value']))
				{
					$item_text = $dom->createTextNode($val['value']);
					$itemx->appendChild($item_text);
					unset($val['value']);
				}
				$this->array2xml($val,$dom,$itemx);
			}
		}
		$dom->save($this->xml_config['path']);
	}
	private function buildAdiXml($vid, $val)
	{
		$adi = array(
			'Metadata'			=> array(
				'AMS'			=> array('value'=>"test", '_attr'=>array('Asset_Name'=>'', 'Provider'=>'','Product'=>'','Version_Major'=>'','Version_Minor'=>'', 'Description'=>'','Creation_Date'=>'','Provider_ID'=>'','Asset_ID'=>'','Asset_Class'=>'')),
				'App_Data'		=> array('value'=>"", '_attr'=>array('App'=>'','Name'=>'')),
			),
			
			'Asset'				=> array(
				'Metadata'		=> array(
					'AMS'		=> array('value'=>"", '_attr'=>array('Asset_Name'=>'','Provider'=>'', 'Product'=>'','Version_Major'=>'','Version_Minor'=>'','Description'=>'', 'Creation_Date'=>'','Provider_ID'=>'','Asset_ID'=>'','Asset_Class'=>'')),
					'App_Data'	=> array('value'=>"", '_attr'=>array('App'=>'','Name'=>'')),
				),
				'Asset'			=> array(
					'Metadata'	=> array(
						'AMS'	=> array('value'=>"", '_attr'=>array('Asset_Name'=>'', 'Provider'=>'','Product'=>'','Version_Major'=>'','Version_Minor'=>'', 'Description'=>'','Creation_Date'=>'','Provider_ID'=>'','Asset_ID'=>'','Asset_Class'=>''))
					),
					'Content'	=> array('value'=>"", '_attr'=>array('value'=>''))
				),
			),
			
		);
		$this->setting_xml_root(array(
			'version'		=> '1.0',
			'charset'		=> 'UTF-8',
			'root'			=> 'ADI',
			'data'			=> $val,
			'path'			=> CACHE_DIR . $vid . '.adi.xml',
			)
		);
		$this->array2xml($adi);
		//hg_file_write(CACHE_DIR . $vid . '.xml', var_export($val, 1));
	}

	function unknown()
	{
		//
	}
	
}
$o = new mmobject();
$action = $_INPUT['a'];
$action = $action && method_exists($o, $action) ? $action : 'unknown';
$o->$action();
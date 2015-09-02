<?php
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class lineInfo extends InitFrm
{
	public $buscurl;
	public $curl8684;
	function __construct()
	{
		parent::__construct();
		$this->curl8684 = new curl($this->settings['8684api']['host'], $this->settings['8684api']['dir']);
		$this->buscurl = new curl($this->settings['busapi']['host'], $this->settings['busapi']['dir']);
	}

	public function __destruct()
	{
		parent::__destruct();
	}
		
	
	public function get_singleline_bus($line)
	{
		$this->buscurl->initPostData();
		$this->buscurl->setReturnFormat('str');
		$this->buscurl->addRequestData('routeid', $line);
		$ret = $this->buscurl->request('bus.php');
		$ret = str_replace(array("!diffgr:id", "!msdata:rowOrder", "!diffgr:hasChanges"), array('diffgrid', 'msdatarowOrder', 'diffgrhasChanges'), $ret);
		$ret = json_decode($ret, true);
		return $ret;
	}
	
	public function get_bus_line_stand($line, $segmentid)
	{
		$this->buscurl->initPostData();
		$this->buscurl->setReturnFormat('str');
		$this->buscurl->addRequestData('routeid', $line);
		$this->buscurl->addRequestData('segmentid', $segmentid);
		$ret = $this->buscurl->request('bus.php');
		$ret = str_replace(array("!diffgr:id", "!msdata:rowOrder", "!diffgr:hasChanges"), array('diffgrid', 'msdatarowOrder', 'diffgrhasChanges'), $ret);
		$ret = json_decode($ret, true);
		return $ret;
	}

	public function get_singleline_8684($line, $city = '无锡')
	{
		$this->curl8684->initPostData();
		$this->curl8684->setReturnFormat('json');
		$this->curl8684->setSubmitType('post');
		$this->curl8684->addRequestData('key', $this->settings['8684api']['key']);
		$this->curl8684->addRequestData('city', $city);
		$this->curl8684->addRequestData('q', $line);
		$this->curl8684->addRequestData('k', 'pp');
		$line = $this->curl8684->request('api.php');
		$ret = array();
		if ($line['data'])
		{
			$ret = $line['data'];
		}
		return $ret;
	}
	
}


?>
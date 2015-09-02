<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: drag_order.php 12397 2012-10-11 06:01:46Z wangleyuan $
***************************************************************************/
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','gongjiao');
require(ROOT_PATH."global.php");
class line extends adminBase
{
	private $buscurl;
	private $curl8684;
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$line = $this->input['q'];
		$city = $this->input['city'] ? $this->input['city'] : '无锡';
		if (is_numeric($line))
		{
			$line .= '路';
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'line WHERE name=\'' . $line . "'";
		$lineinfo = $this->db->query_first($sql);
		if (!$lineinfo)
		{
			$data = $this->get_singleline_8684($this->input['q'], $city);
			if (!$data)
			{
				$data = array(
					'error_message'	=> '没有该线路信息',
					'data'	=> array(),
					'date'	=> array(),
				);
			}
			else
			{
				$info = array(
					$data['data'][0],
					'stations'	=> $data['stations'] ? $data['stations'] : array(1 => '', 2 => ''),
					'segmentid'	=> $data['segmentid'] ? $data['segmentid'] : array(1 => '', 2 => ''),
					'seq'	=> $data['seq'] ? $data['seq'] : array(1 => '', 2 => ''),
					);
				$data['data'] = $info;
				$data['date'] = $info;
			}
			exit(json_encode($data));
		}
		if ($lineinfo['busstands'])
		{
			$stands = json_decode($lineinfo['busstands']);
			$seq = json_decode($lineinfo['busstandsseq']);
		}
		else
		{
			$stands = json_decode($lineinfo['stands']);
			$seq = array();
		}
		$segid = explode(',', $lineinfo['segmentid']);
		$info = array(
			array(
				'routeid'	=> $lineinfo['routeid'],
				'name'	=> $lineinfo['name'],
				'time'	=> $lineinfo['time'],
				'price'	=> $lineinfo['price'],
				'gjgs'	=> $lineinfo['gjgs'],
				'kind'	=> $lineinfo['kind'],
			),
			'stations'	=> $stands,
			'segmentid'	=> array(1 => $segid[0], 2 => $segid[1] ? $segid[1] : $segid[0]),
			'seq'	=> $seq,
		);
		$data = array(
			'error_message'	=> '0',
			'data'	=> $info,
			'date'	=> $info,
		);
		exit(json_encode($data));
	}
	private function get_singleline_8684($line, $city = '无锡')
	{
		$this->curl8684 = new curl($this->settings['8684api']['host'], $this->settings['8684api']['dir']);
		$this->curl8684->initPostData();
		$this->curl8684->setReturnFormat('json');
		$this->curl8684->setSubmitType('post');
		$this->curl8684->addRequestData('key', $this->settings['8684api']['key']);
		$this->curl8684->addRequestData('city', $city);
		$this->curl8684->addRequestData('q', $line);
		$this->curl8684->addRequestData('k', 'pp');
		$line = $this->curl8684->request('api.php');
		return $line;
	}
}
$out = new line();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
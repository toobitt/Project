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
class stand extends adminBase
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
		$stand = $this->input['q'];
		$city = $this->input['city'] ? $this->input['city'] : '无锡';
		$lines = $this->get_stand_8684($stand, $city);
		if (!$lines['error_message'])
		{
			if ($lines['data'])
			{
				$sql = 'SELECT name,routeid,segmentid,stationseq,type FROM ' . DB_PREFIX . "stand WHERE name ='{$stand}'";
				$q = $this->db->query($sql);
				$stands = array();
				while($r = $this->db->fetch_array($q))
				{
					$stands[$r['routeid']][$r['type']] = array(
							'segmentid' => $r['segmentid'],
							'seq' => $r['stationseq']
					);
				}
				$tlines = array();
				foreach ($lines['data'] AS $k => $v)
				{
					$tlines[] = $v['name'];
				}
				$sql = 'SELECT name,routeid FROM ' . DB_PREFIX . "line WHERE name IN('" . implode("','", $tlines). "')";
				$q = $this->db->query($sql);
				$routeid = array();
				while($r = $this->db->fetch_array($q))
				{
					$routeid[$r['name']] = $r['routeid'];
				}
				foreach ($lines['data'] AS $k => $v)
				{
					$lines['data'][$k]['routeid'] = $routeid[$v['name']];
					$lines['date'][$k]['routeid'] = $routeid[$v['name']];
					$lines['data'][$k]['segmentid'] = $stands[$routeid[$v['name']]];
					$lines['date'][$k]['segmentid'] = $stands[$routeid[$v['name']]];
				}
			}
		}
		exit(json_encode($lines));
	}
	private function get_stand_8684($stand, $city = '无锡')
	{
		$this->curl8684 = new curl($this->settings['8684api']['host'], $this->settings['8684api']['dir']);
		$this->curl8684->initPostData();
		$this->curl8684->setReturnFormat('json');
		$this->curl8684->setSubmitType('post');
		$this->curl8684->addRequestData('key', $this->settings['8684api']['key']);
		$this->curl8684->addRequestData('city', $city);
		$this->curl8684->addRequestData('q', $stand);
		$this->curl8684->addRequestData('k', 'p');
		$line = $this->curl8684->request('api.php');
		return $line;
	}
}
$out = new stand();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
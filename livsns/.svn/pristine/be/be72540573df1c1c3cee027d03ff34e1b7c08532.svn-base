<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: drag_order.php 12397 2012-10-11 06:01:46Z wangleyuan $
***************************************************************************/

require_once('global.php');
define('MOD_UNIQUEID','gongjiao');//模块标识
function hg_fetch_query_sql($queryvalues, $table, $condition = '', $db_pre = DB_PREFIX, $insert_type = 'INSERT')
{
	global $gDB;
	$numfields = count($queryvalues);
	if (empty($condition))
	{
		$fieldlist_arr = array();
		$valuelist_arr = array();
		foreach($queryvalues AS $fieldname => $value)
		{
			$fieldlist_arr[] = $fieldname;
			$fieldvalue = (is_numeric($value) AND intval($value) == $value) ? "'$value'" : "'" . addslashes($value) . "'";
			$valuelist_arr[] = $fieldvalue;
		}
		$fieldlist  = implode(", ", $fieldlist_arr);
		$valuelist = implode(", ", $valuelist_arr);
		unset($fieldlist_arr, $valuelist_arr);
		$sql = $insert_type . " INTO " . $db_pre. "$table ($fieldlist) VALUES ($valuelist)";
	}
	else
	{
		$qs_arr = array();
		foreach($queryvalues AS $fieldname => $value)
		{
			$fieldvalue = (is_numeric($value) AND intval($value) == $value) ? "'$value'" : "'" . addslashes($value) . "'";
			$qs_arr[] = $fieldname." = ".$fieldvalue;
		}
		$querystring = implode(', ', $qs_arr);
		unset($qs_arr);
		$sql = "UPDATE " . $db_pre. "$table SET $querystring WHERE $condition";
	}
    $gDB->query($sql);
}
class updateData extends BaseFrm
{
	private $buscurl;
	private $curl8684;
	function __construct()
	{
		parent::__construct();
		$this->curl8684 = new curl($this->settings['8684api']['host'], $this->settings['8684api']['dir']);
		$this->buscurl = new curl($this->settings['busapi']['host'], $this->settings['busapi']['dir']);
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function update_line_time()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'line';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$line8684 = $this->get_singleline_8684($r['name']);
			$line8684data = $line8684[0];
			if (!trim($line8684data['time']))
			{
				continue;
			}
			$data = array(
				//'city_id'	=> $city['id'],
				//'city_name'	=> $city['name'],
				//'name'	=> $v['ROUTENAME'],
				//'brief'	=> '',
				'time'	=> $line8684data['time'],
				//'price'	=> $line8684data['price'],
				//'gjgs'	=> $line8684data['gjgs'],
				//'kind'	=> $line8684data['kind'],
				//'routeid'	=> $v['ROUTEID'],
				//'segmentid'	=> $segmentid,
				//'segmentname'	=> $segmentname,
				//'stands'	=> json_encode($line8684['stations']),
			);
			hg_fetch_query_sql($data, 'line', 'id=' . $r['id']);
			echo $r['name'] . '已更新<br />';
		}
	}
	public function resort_stand()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'line';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$tstands = $tseq = array();
			$segid = explode(',', $r['segmentid']);
			if (count($segid) > 1)
			{
				$i = 1;
				foreach ($segid AS $k => $id)
				{
					if ($i == 1)
					{
						$i++;
						continue;
					}
					$stands = $this->get_stand($r['routeid'], $id);
					$j = 1;
					foreach ($stands AS $kk => $v)
					{
						$data = array('stationseq' =>  $j);
						echo $v['id'] . ':' . $v['name'] . '[' . $v['stationseq'] . ']=>' .$j . '<br />';
						hg_fetch_query_sql($data, 'stand', 'id=' . $v['id']);
						$j++;
					}
					$i++;
				}
			}
		}
		$this->output(array('done'));
	}

	public function line_stand()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'line';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$tstands = $tseq = array();
			$segid = explode(',', $r['segmentid']);
			if (count($segid) > 1)
			{
				$i = 1;
				foreach ($segid AS $k => $id)
				{
					$stands = $this->get_stand($r['routeid'], $id);
					foreach ($stands AS $kk => $v)
					{
						$tstands[$i][] = $v['name'];
						$tseq[$i][] = $v['stationseq'];
					}
					$i++;
				}
			}
			else
			{
				$stands = $this->get_stand($r['routeid'], 0);
				foreach ($stands AS $k => $v)
				{
					$tstands[$v['type']][] = $v['name'];
					$tseq[$v['type']][] = $v['stationseq'];
				}
			}
			$data = array();
			$dataseq = array();
			foreach ($tstands AS $k => $v)
			{
				$data[$k] = implode(',', $v);
				$dataseq[$k] = implode(',', $tseq[$k]);
			}
			$data = array(
				'busstands' => json_encode($data),	
				'busstandsseq' => json_encode($dataseq)	
			);
			hg_fetch_query_sql($data, 'line', 'id=' . $r['id']);
		}
		$this->output(array('done'));
	}
	public function stand()
	{
		if ($this->bus_stand_exists())
		{
			$this->errorOutput('bus数据已更新');
		}
		$city = array(
			'id' => 1,
			'name' => '无锡'
		);
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'line';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$segid = explode(',', $r['segmentid']);
			foreach ($segid AS $seid)
			{
				$stands = $this->get_bus_line_stand($r['routeid'], $seid);
				if (is_array($stands))
				{
					$type = 1;
					foreach ($stands AS $k => $v)
					{
						if ($v['STATIONTYPEID'] == 12)
						{
							$data = array(
								'city_id'	=> $city['id'],
								'city_name'	=> $city['name'],
								'name'	=> $v['STATIONNAME'],
								'stationseq'	=> $v['stationseq'],
								'routeid'	=> $r['routeid'],
								'longitude'	=> $v['LONGITUDE'],
								'latitude'	=> $v['LATITUDE'],
								'stationtype'	=> $v['STATIONTYPEID'],
								'segmentid'	=> $seid,
								'type'	=> $type,
							);
							hg_fetch_query_sql($data, 'stand');
							$type++;
						}
						$data = array(
							'city_id'	=> $city['id'],
							'city_name'	=> $city['name'],
							'name'	=> $v['STATIONNAME'],
							'stationseq'	=> $v['stationseq'],
							'routeid'	=> $r['routeid'],
							'longitude'	=> $v['LONGITUDE'],
							'latitude'	=> $v['LATITUDE'],
							'stationtype'	=> $v['STATIONTYPEID'],
							'segmentid'	=> $seid,
							'type'	=> $type,
						);
						hg_fetch_query_sql($data, 'stand');
					}
				}
			}
		}
		$this->output(array('done'));
	}
	public function show()
	{
		if ($this->bus_line_exists())
		{
			$this->errorOutput('bus数据已更新');
		}
		$lines = $this->get_lines();
		$city = array(
			'id' => 1,
			'name' => '无锡'
		);
		foreach ($lines AS $k => $v)
		{
			$line = $this->get_singleline_bus($v['ROUTEID']);
			if (!$line['SEGMENTID'])
			{
				$seg = array();
				foreach($line AS $kk => $vv)
				{
					$seg['id'][] = $vv['SEGMENTID'];
					$seg['name'][] = $vv['SEGMENTNAME'];
				}
				$segmentid = implode(',', $seg['id']);
				$segmentname = implode(',', $seg['name']);
			}
			else
			{
				$segmentid = $line['SEGMENTID'];
				$segmentname = $line['SEGMENTNAME'];
			}
			$line8684 = $this->get_singleline_8684($v['ROUTENAME'], $city['name']);
			$line8684data = $line8684[0];
			$data = array(
				'city_id'	=> $city['id'],
				'city_name'	=> $city['name'],
				'name'	=> $v['ROUTENAME'],
				'brief'	=> '',
				'time'	=> $line8684data['time'],
				'price'	=> $line8684data['price'],
				'gjgs'	=> $line8684data['gjgs'],
				'kind'	=> $line8684data['kind'],
				'routeid'	=> $v['ROUTEID'],
				'segmentid'	=> $segmentid,
				'segmentname'	=> $segmentname,
				'stands'	=> json_encode($line8684['stations']),
			);
			hg_fetch_query_sql($data, 'line');
			//exit;
		}
		$this->output(array('done'));
	}
	private function get_lines()
	{
		$this->buscurl->initPostData();
		$this->buscurl->setReturnFormat('str');
		$ret = $this->buscurl->request('bus.php');
		$ret = str_replace(array("!diffgr:id", "!msdata:rowOrder", "!diffgr:hasChanges"), array('diffgrid', 'msdatarowOrder', 'diffgrhasChanges'), $ret);
		$ret = json_decode($ret, true);
		return $ret;
	}
	private function get_singleline_bus($line)
	{
		$this->buscurl->initPostData();
		$this->buscurl->setReturnFormat('str');
		$this->buscurl->addRequestData('routeid', $line);
		$ret = $this->buscurl->request('bus.php');
		$ret = str_replace(array("!diffgr:id", "!msdata:rowOrder", "!diffgr:hasChanges"), array('diffgrid', 'msdatarowOrder', 'diffgrhasChanges'), $ret);
		$ret = json_decode($ret, true);
		return $ret;
	}
	private function get_bus_line_stand($line, $segmentid)
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

	private function get_singleline_8684($line, $city = '无锡')
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
	private function bus_line_exists()
	{
		$sql = 'SELECT count(*) AS c FROM ' . DB_PREFIX . 'line';
		$ret = $this->db->query_first($sql);
		return intval($ret['c']);
	}
	private function bus_stand_exists()
	{
		$sql = 'SELECT count(*) AS c FROM ' . DB_PREFIX . 'stand';
		$ret = $this->db->query_first($sql);
		return intval($ret['c']);
	}
	private function get_stand($line, $segid)
	{
		if ($segid)
		{
			$cond = ' AND segmentid=' . $segid;
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'stand WHERE routeid=' . $line . $cond;
		$q = $this->db->query($sql);
		$stands = array();
		while($r = $this->db->fetch_array($q))
		{
			$stands[] = $r;
		}
		return $stands;
	}
}
$out = new updateData();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
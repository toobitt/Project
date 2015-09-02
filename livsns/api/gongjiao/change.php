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
class change extends adminBase
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
		$q = $this->input['q'];
		$q1 = $this->input['q1'];
		$city = $this->input['city'] ? $this->input['city'] : '无锡';
		$change8684 = $this->get_change_8684($q, $q1, $city);
		//print_r($change);
		$all_lines = $all_stands = array();
		if (!$change8684['error_message'])
		{
			$change = $change8684['data'];
			if ($change['result'])
			{
				$change_case = array();
				switch ($change['type'])
				{
					case 0:
						foreach ($change['result'] AS $result)
						{
							$lines = $result['line'];
							//print_r($lines);
							foreach ($lines AS $k => $v)
							{
								$change_case[$k][$v['line_name']] = array($result['start'], $result['end']);
								$all_lines[$v['line_name']] = $v['line_name'];
							}
							$all_stands[$result['start']] = $result['start'];
							$all_stands[$result['end']] = $result['end'];
						}
					break;
					case 1:
						foreach ($change['result'] AS $k => $result)
						{
							$line = $result['line1'][0];
							
							if (!is_array($result['station1'][0]))
							{
								$end = $result['station1'][0];
								$nextstart = $end;
								$all_stands[$end] = $end;
							}
							else
							{
								$end = $result['station1'][0]['mid1'];
								$all_stands[$end] = $end;
								$nextstart = $result['station1'][0]['mid2'];
								$all_stands[$nextstart] = $nextstart;
							}
							$all_stands[$result['start']] = $result['start'];
							$all_stands[$result['end']] = $result['end'];

							$all_lines[$line] = $line;
							$change_case[$k][$line] = array($result['start'], $end);
							$line = $result['line2'][0];
							$all_lines[$line] = $line;
							$change_case[$k][$line] = array($nextstart, $result['end']);
						}
					break;
					case 2:
						foreach ($change['result'] AS $k => $result)
						{
							$all_stands[$result['start']] = $result['start'];
							$all_stands[$result['end']] = $result['end'];
							$line = $result['line1'][0];
							
							if (!is_array($result['station1'][0]))
							{
								$end = $result['station1'][0];
								$secondstart = $end;
								$all_stands[$end] = $end;
							}
							else
							{
								$end = $result['station1'][0]['mid1'];
								$secondstart = $result['station1'][0]['mid2'];
								$all_stands[$end] = $end;
								$all_stands[$secondstart] = $secondstart;
							}
							if (!is_array($result['station2'][0]))
							{
								$secondend = $result['station2'][0];
								$nextstart = $result['station2'][0];
								$all_stands[$secondend] = $secondend;
							}
							else
							{
								$secondend = $result['station2'][0]['mid1'];
								$nextstart = $result['station2'][0]['mid2'];
								$all_stands[$secondend] = $secondend;
								$all_stands[$nextstart] = $nextstart;
							}
							$all_stands[$result['start']] = $result['start'];
							$all_stands[$result['end']] = $result['end'];
							$all_lines[$line] = $line;
							$change_case[$k][$line] = array($result['start'], $end);
							$line = $result['line2'][0];
							$all_lines[$line] = $line;
							$change_case[$k][$line] = array($secondstart, $secondend);
							$line = $result['line3'][0];
							$all_lines[$line] = $line;
							$change_case[$k][$line] = array($nextstart, $result['end']);
						}
					break;
				}
			}
		}
		$routeid = array();
		if ($all_lines)
		{
			$sql = 'SELECT name,routeid FROM ' . DB_PREFIX . "line WHERE name IN('" . implode("','", $all_lines). "')";
			$q = $this->db->query($sql);
			$routeid = array();
			while($r = $this->db->fetch_array($q))
			{
				$routeid[$r['name']] = $r['routeid'];
			}
		}
		$stands = $stand_seq = array();
		if ($all_stands)
		{
			$sql = 'SELECT name,routeid,segmentid,stationseq,type FROM ' . DB_PREFIX . "stand WHERE name IN('" . implode("','", $all_stands). "')";
			$q = $this->db->query($sql);
			
			while($r = $this->db->fetch_array($q))
			{
				$stand_seq[$r['routeid']][$r['name']] = $r['stationseq'];
				$stands[$r['routeid']][$r['type']][$r['name']] = array(
						'segmentid' => $r['segmentid'],
						'seq' => $r['stationseq']
				);
			}

		}
		$realinfo = array();
		if ($change_case)
		{
			foreach ($change_case as $k => $v) 
			{
				foreach ($v as $kk => $vv) 
				{
					if ($stand_seq[$routeid[$kk]][$vv[1]] > $stand_seq[$routeid[$kk]][$vv[0]])
					{
						$type = 2;
					}
					else
					{
						$type = 1;
					}
					$segid = $stands[$routeid[$kk]][$type][$vv[0]]['segmentid'];
					$seq = $stands[$routeid[$kk]][$type][$vv[0]]['seq'];
					$realinfo[$k][] = array(
						'routeid' => intval($routeid[$kk]),
						'segmentid' => intval($segid),
						'seq' => intval($seq)
					);
					$segid = $stands[$routeid[$kk]][$type][$vv[1]]['segmentid'];
					$seq = $stands[$routeid[$kk]][$type][$vv[1]]['seq'];
					$realinfo[$k][] = array(
						'routeid' => intval($routeid[$kk]),
						'segmentid' => intval($segid),
						'seq' => intval($seq)
					);
				}
			}

			$change8684['realinfo'] = $realinfo;
		}
		exit(json_encode($change8684));
	}
	private function get_change_8684($q, $q1, $city = '无锡')
	{
		$this->curl8684 = new curl($this->settings['8684api']['host'], $this->settings['8684api']['dir']);
		$this->curl8684->initPostData();
		$this->curl8684->setReturnFormat('json');
		$this->curl8684->setSubmitType('post');
		$this->curl8684->addRequestData('key', $this->settings['8684api']['key']);
		$this->curl8684->addRequestData('city', $city);
		$this->curl8684->addRequestData('q', $q);
		$this->curl8684->addRequestData('q1', $q1);
		$this->curl8684->addRequestData('k', 'p2p');
		$line = $this->curl8684->request('api.php');
		return $line;
	}
}
$out = new change();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
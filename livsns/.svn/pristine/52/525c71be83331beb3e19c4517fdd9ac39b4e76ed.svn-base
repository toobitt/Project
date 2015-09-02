<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: upload_program.php 4618 2011-10-08 09:17:21Z lijiaying $
***************************************************************************/
require('global.php');
class uploadProgramApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	
//支持txt和xml 建议不要分号
	function file2array($file_name, $type,$split = ';')
	{
		if(!$file_name)
		{
			return;
		}
		$return = array();
		switch($type)
		{
			case 'txt':
			{
				$return = $this->txt2array($file_name, $split);
				break;
			}
			case 'xml':
			{
				$return = $this->xml2array($file_name, $split);
				break;
			}
			default:exit('暂不支持此类型节目单上传');
		}
		return $return;
	}

	function txt2array($file_name, $split = ';')
	{
		$return = array();
		$date_arr = array();
		if(function_exists('file'))
		{
			$txt = file($file_name);
			
			$config = $this->settings['txt_conf'];
			foreach($txt as $k => $v)
			{
				$v = trim($v);
				$v = preg_replace('/\s{1,}/s', ',', trim($v));
				if(!$v) continue;
				
				if(preg_match('/^\d{4}(-|\/)\d{1,2}(-|\/)\d{1,2}$/si', $v))
				{
					$date = $v;
					$date_arr[] = strtotime(str_replace('/', '-', $date));
					$return[$date] = array();
					continue;
				}
				$config_data = array();
				$item = explode(',', $v);
				$preg = '/[0-9]{1,}:[0-9]{1,}(:[0-9])?/i';
				
				if(isset($item[0]))
				{
					$time_arr = explode('-',$item[0]);
					$config_data[$config[0]] = $time_arr[0];
					if(count($time_arr) > 1)
					{
						$config_data[$config[1]] = $time_arr[1];
					}
				}

				if(isset($item[1]))
				{
					$config_data[$config[2]] = $item[1];
				}

				if(isset($item[2]))
				{
					$config_data[$config[3]] = $item[2];
				}
				$return[$date][] = $config_data;
			}
		
			if(count($return) == count(array_unique($date_arr)))
			{
				$ret = array();	
				foreach($return as $key => $value)
				{
					$ret[date('Y-m-d',strtotime($key))] = $value;
				}
				return $ret;
			}
			else
			{
				exit('节目单中有相同日期的节目！');
			}
			
		}
		else
		{
			exit('节目单文件不存在或已被删除！');
		}
	}
	function xml2array($file_name, $split = ';')
	{
		if(function_exists('simplexml_load_file'))
		{
			$xml = simplexml_load_file($file_name);
			if(!$xml)
			{
				return array();
			}
			$return = array();
			//xml的配置文件
			$config = $this->settings['xml_conf'];
			$config_data = array();
			foreach($xml as $k=>$v)
			{
				$v = (array)$v;
				foreach($v[$config[0]] as $kk=>$vv)
				{
					foreach($config as $label)
					{
						$config_data[$kk][$label] = $v[$label][$kk];
					}
				}
				$return[$v['@attributes']['value']] = $config_data;
			}
		}
		return $return;
	}

	private function getPlan($channel_id,$dates)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE 1 and p.channel_id=" . $channel_id . " AND r.week_num=" . date("N",strtotime($dates)) . " ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$program_plan = array();
		while($r = $this->db->fetch_array($q))
		{
			$program_plan[] = array(
					//'id' => hg_rand_num(10),	
					//'channel_id' => $r['channel_id'],
					'start_time' => strtotime($dates . " " . date("H:i:s",$r['start_time'])),	
					'toff' =>  $r['toff'],	
					'theme' => $r['program_name'],	
					'subtopic' => '',	
					'type_id' => '',	
					'dates' => $dates,	
					'weeks' => date('W',strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'start' => date("H:i",strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'end' => date("H:i",strtotime($dates . " " . date("H:i:s",$r['start_time'])) + $r['toff']),	
					'is_plan' => 1,
				);
		}
		return $program_plan;
	}

	private function getProgram($channel_id,$dates)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program WHERE 1 and channel_id=" . $channel_id . " AND dates='" . $dates . "' ORDER BY start_time ASC";
		$q = $this->db->query($sql);
		$program = array();
		while($r = $this->db->fetch_array($q))
		{
			$program[] = array(
					'id' => $r['id'],	
					'channel_id' => $r['channel_id'],
					'start_time' => $r['start_time'],	
					'toff' =>  $r['toff'],	
					'theme' => $r['theme'],	
					'subtopic' => $r['subtopic'],	
					'type_id' => $r['type_id'],	
					'dates' => $r['dates'],	
					'weeks' => $r['weeks'],	
					'start' => date("H:i",$r['start_time']),	
					'end' => date("H:i",$r['start_time'] + $r['toff']),	
					'is_program' => 1,
				);
		}
		if(empty($program))
		{
			return false;
		}
		return $program;
	}

	private function verify_plan($plan,$start_time,$end_time)
	{
		$program_plan = array();
		if(!empty($plan))
		{
			foreach($plan as $k => $v)
			{
				if($v['start_time'] >= $start_time && ($v['start_time']+$v['toff']) <= $end_time)
				{
					$program_plan[] = $v;
				}
			}
			return $program_plan;
		}
	}

	function uploads()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput('未传入频道ID');
		}
		$channel_id = $this->input['channel_id'];
		
/**/	if($_FILES)
		{
			if($_FILES['program']['error'] != 0)
			{
				exit('文件上传出错');
			}
		}
		else 
		{
			$this->errorOutput();
		}
		$file = $_FILES['program'];
	//	$file['name'] = 'http://localhost/livsns/uploads/tmp/sdf.txt';
		$type = substr($file['name'], strrpos($file['name'], '.')+1);
		$programs = $this->file2array($file['tmp_name'], $type);
		
	//	$programs = $this->file2array($file['name'], $type);
	
		foreach($programs as $dates => $value)
		{
		    foreach($value as $v)
			{
				$timestamp = strtotime(str_replace('：', ':', ($dates . ' ' . $v['start_time'])));
		        $r['start_time'] = $timestamp;
		        $r['toff'] = $v['toff'] ? $dates . ' ' . $v['toff'] : 0;
				$r['theme'] = $v['theme'];
				$r['subtopic'] = $v['subtopic'];
				$r['type_id'] = $v['type_id'];
				$r['weeks'] = date('W', strtotime($dates));;
				$r['dates'] = $dates;
				$return[$dates][] = $r;
			}
		}

		if(!empty($return))
		{
			foreach($return as $dat => $items)
			{
				$last_end = 0;
				for($i = 0;$i< count($items);$i++)
				{
					if($i == count($items)-1)
					{
						$return[$dat][$i]['toff'] = (strtotime($items[$i]['dates'])+86399) - $items[$i]['start_time'];
					}
					else
					{
						if($return[$dat][$i]['toff'])
						{
							$return[$dat][$i]['toff'] = strtotime($items[$i]['toff']) - $items[$i]['start_time'];
						}
						else
						{
							$return[$dat][$i]['toff'] = $items[$i+1]['start_time'] - $items[$i]['start_time'];
						}
					}
					$return[$dat][$i]['start'] = date("H:i:s",$items[$i]['start_time']);
					$return[$dat][$i]['end'] = date("H:i:s",$items[$i]['start_time']+$return[$dat][$i]['toff']);

					if($return[$dat][$i]['toff'] < 0 || ($last_end && $last_end > $items[$i]['start_time']))
					{
						$this->errorOutput('时间线不正确，请检查确认后重新提交！');
					}
					$last_end = $items[$i]['start_time']+$return[$dat][$i]['toff'];
				}
			}
		}

		$info = array(); //与节目计划合并，并保证在同一时间线
		foreach($return as $key => $value)
		{
			$program_plan = $this->getPlan($channel_id,$key);
			$start_time = strtotime($key . " 00:00:00");
			$end_time = strtotime($key . " 23:59:59");
			$program = array();
			$com_time = 0;//取节目的最大时间和最小时间
			foreach($value as $k => $r)
			{
				if(!$com_time && $r['start_time'] > $start_time)//头
				{
					$plan = $this->verify_plan($program_plan,$start_time,$r['start_time']);
					if($plan)
					{
						foreach($plan as $k => $v)
						{
							$program[] = $v;
						}
					}
				}
				if($com_time && $com_time != $r['start_time'])//中
				{
					$plan = $this->verify_plan($program_plan,$com_time,$r['start_time']);
					if($plan)
					{
						foreach($plan as $k => $v)
						{
							$program[] = $v;
						}
					}
				}
				$com_time = $r['start_time']+$r['toff'];
				$program[] = $r;
			}

			if($com_time && $com_time < $end_time)//尾
			{
				$plan = $this->verify_plan($program_plan,$com_time,$end_time);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
			}
			$info[$key] = $program;
		}

		if(!empty($info))
		{
			$del_id = $space = "";
			foreach($info as $key => $value)
			{
				$program_single = $this->getProgram($channel_id,$key);
				foreach($value as $kk => $vv)
				{
					$start = $vv['start_time'];
					$end = $vv['start_time']+$vv['toff'];
					if(!empty($program_single))
					{
						foreach($program_single as $k => $r)
						{
							if($start == $r['start_time'] && $end == ($r['start_time']+$r['toff'])) //相同的直接删除
							{
							//	unset($info[$key][$kk]);
									$del_id .= $space . $r["id"];
									$space = ",";
							}
							else //节目单数据与原有节目冲突，删除原有节目
							{
								if($r['start_time'] >= $start && $r['start_time'] < $end)
								{
									$del_id .= $space . $r["id"];
									$space = ",";
								}
								if($r['start_time'] < $start)
								{
									if(($r['start_time']+$r['toff']) > $start)
									{
										$del_id .= $space . $r["id"];
										$space = ",";
									}
								}
							}
						}
					}
				}
			}
		}

		if($del_id)
		{
			$sql = "DELETE FROM " . DB_PREFIX . "program WHERE id IN(" . $del_id . ")";
			$this->db->query($sql);
		}
	//	file_put_contents('1.php',json_encode($info));
		//数据入库
		if(!empty($info))
		{
			foreach($info as $kk => $vv)
			{
				$sql = "INSERT INTO " . DB_PREFIX . "program(channel_id,start_time,toff,theme,subtopic,type_id,weeks,dates,create_time,update_time,ip,is_show) values";
				$space = $sql_extra = "";
				foreach ($vv as $k=>$v)
				{
					$sql_extra .=  $space . "(" . $channel_id . "," . $v['start_time'] . "," . $v['toff'] . ",'" . $v['theme'] . "','" . $v['subtopic'] . "'," . ($v['type_id']?$v['type_id']:0) . "," . intval($v['weeks']) . ",'" . $v['dates'] . "'," . TIMENOW . "," . TIMENOW . ",'" . hg_getip() . "',1)";
					$space = ",";	
				}
				if($sql_extra)
				{
					$sql .= $sql_extra;
					$this->db->query($sql);
				}
			}
		}
		$this->addItem('success');
		$this->output();
		
	}
	function check_program_date($programs)
	{
		$programs = array_keys($programs);
		foreach ($programs as $v)
		{
			$sql = "SELECT dates FROM " . DB_PREFIX . "program where dates='" . $v . "' and channel_id=" . $this->input['channel_id'];
			$q = $this->db->query_first($sql);
			if($v == $q['dates'])
			{
				$this->addItem('error');
				$this->output();
			}
			$this->program2db();
		}
	}
	function program2db()
	{
		$file = '';
		$tmpdir = UPLOAD_DIR . 'tmp/';
		if(file_exists($tmpdir.$this->input['channel_id'].'.xml'))
		{
			$file = $tmpdir.$this->input['channel_id'].'.xml';
			$programs = $this->file2array($file, 'xml');
		}
		else if(file_exists($tmpdir.$this->input['channel_id'].'.txt'))
		{
			$file = $tmpdir.$this->input['channel_id'].'.txt';	
			$programs = $this->file2array($file, 'txt');
		}
		else
		{
			$this->errorOutput('节目单丢失，请重新上传！');
		}
		@unlink($file);
	}
}
$out = new uploadProgramApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'uploads';
}
$out->$action();
?>
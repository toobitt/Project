<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: upload_program.php 4618 2011-10-08 09:17:21Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program');
class programUploadApi extends adminBase
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
			case 'xls':
			{
				$return = $this->xls2array($file_name);
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
			//hg_pre($txt);exit;
			$config = $this->settings['txt_conf'];
			foreach($txt as $k => $v)
			{
				$v = trim($v);
				if(preg_match('/^\d{4}(-|\/)\d{1,2}(-|\/)\d{1,2}$/si', $v))
				{
					$date = $v;
					$date_arr[] = strtotime(str_replace('/', '-', $date));
					$return[$date] = array();
					continue;
				}
				$v = preg_replace('/\s{1,}/s', ',', trim($v));
				if(!$v) continue;
				
				
				$config_data = array();
				$item = explode(',', $v);
				$preg = '/[0-9]{1,}:[0-9]{1,}(:[0-9])?/i';
				
				if(isset($item[0]))
				{
					$time_arr = explode('-',$item[0]);
					$config_data[$config[0]] = $time_arr[0];
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

	public function xls2array($file_name)
	{
		if (!$file_name)
		{
			return false;
		}
		
		require_once CUR_CONF_PATH . 'lib/PHPExcel.php';
		require_once CUR_CONF_PATH . 'lib/PHPExcel/IOFactory.php';
		require_once CUR_CONF_PATH . 'lib/PHPExcel/Reader/Excel5.php';
		
		$objReader		= PHPExcel_IOFactory::createReader('Excel5');
		$objPHPExcel 	= $objReader->load($file_name); 
		$sheet 			= $objPHPExcel->getSheet(0); 
		$highestRow 	= $sheet->getHighestRow();    //取得总行数 
		$highestColumn 	= $sheet->getHighestColumn(); //取得总列数
		
		$A1 = trim($objPHPExcel->getActiveSheet()->getCell("A1")->getValue());
		$A2 = trim($objPHPExcel->getActiveSheet()->getCell("A2")->getValue());
		$A3 = trim($objPHPExcel->getActiveSheet()->getCell("A3")->getValue());
		$B1 = trim($objPHPExcel->getActiveSheet()->getCell("B1")->getValue());
		$B2 = trim($objPHPExcel->getActiveSheet()->getCell("B2")->getValue());
		$D2 = trim($objPHPExcel->getActiveSheet()->getCell("D2")->getValue());
		
		$type = 0;
		if (!$A1 && !$A2 && $B1)
		{
			$type = 1;
			$offset_hight = 1;
		}
		else if ($A1 && $A2 && !$B1 && $D2)
		{
			$type = 2;
			$offset_hight = 2;
		}
		
		if ($type)
		{
			if ($B2)
			{
				$first_date = $this->set_date($B2);
			}
			
			if ($A3)
			{
				$first_time = $this->excelTime($A3, date('Y-m-d'));
			}
			$year = date('Y');
		}
		//file_put_contents(CACHE_DIR . '7.txt', var_export($type,1));
		//循环读取excel文件
		$return = $dates = $times = array();
		$config = $this->settings['xls_conf'];
		switch ($type)
		{
			//第二行、第二列开始取日期
			case '1' || '2':
				for ($j = 'B'; $j <= $highestColumn; $j ++)
				{
					$date = trim($objPHPExcel->getActiveSheet()->getCell("{$j}{$type}")->getValue());
					if ($date)
					{
						$date = $this->set_date($date);
                        $tmp_month = explode('-',$date);
                        $now_month = date('n',time());
                        //计算跨年
                        if ($tmp_month[0] < $now_month)
						{
							$year = date('Y', strtotime('+1 year'));
						}
						$date = $year . '-' . $date;
						$dates[$j] = $date;
						for ($i = 3; $i <= $highestRow - $offset_hight; $i ++)
						{
							$time = trim($objPHPExcel->getActiveSheet()->getCell("A{$i}")->getValue());
							if (!$time && $type == 1)
							{
								continue;
							}
							//if ($time)
							{
							//	$times[] = $time;
								$theme = trim($objPHPExcel->getActiveSheet()->getCell("{$j}{$i}")->getValue());
								
								if ($theme)
								{
									$time = $this->excelTime($time, $date);
									$item[$config[0]] = $time;
									$item[$config[2]] = $theme;
									
									if ($time < $first_time)
									{
										$_date = date('Y-m-d', (strtotime($date) + 86400));
										$flag = 1;
									}
									else 
									{
										$_date = $date;
										$flag = 0;
									}
									
									$return[$_date][] = $item;
								}
							}
						}
					}
				}
			break;
			default:
				for ($i = 1; $i <= $highestRow; $i++)                        //从第一行开始读取数据
				{
					$a = trim($objPHPExcel->getActiveSheet()->getCell("A{$i}")->getValue());
					$b = trim($objPHPExcel->getActiveSheet()->getCell("B{$i}")->getValue());
					$c = trim($objPHPExcel->getActiveSheet()->getCell("C{$i}")->getValue());
					
					if ($a && !$b && !$c)
					{
						$date = $this->excelDate($a);
						$j = $i + 1;
						$first_time = trim($objPHPExcel->getActiveSheet()->getCell("A{$j}")->getValue());
						$first_time = $this->excelTime($first_time, $date);
						$dates[] = $date;
					}
					
					if ($b)
					{
						$time = $this->excelTime($a, $date);
						$item[$config[0]] = $time;
						$item[$config[2]] = $b;
						$item[$config[3]] = $c;
						
						if ($time < $first_time)
						{
							$_date = date('Y-m-d', (strtotime($date) + 86400));
							$flag = 1;
						}
						else 
						{
							$_date = $date;
							$flag = 0;
						}
						
						$return[$_date][] = $item;
					}
				}
			break;
		}

		$days = $flag ? count($dates) + 1 : count($dates);
		
		if (count($return) != $days)
		{
			exit('节目单日期存在重复');
		}

	//	hg_pre($dates);
	//	hg_pre($times);
	//	hg_pre($return);
		return $return;
	}
	
	function excelDate($date, $time = false)
	{
	    if(function_exists('GregorianToJD'))
	    {  
	        if (is_numeric($date))
	        {
		        $jd = GregorianToJD( 1, 1, 1970 );
		        $gregorian = JDToGregorian($jd + intval($date) - 25569);  
		        $date = explode('/', $gregorian);  
		        $date_str = str_pad($date[2], 4, '0', STR_PAD_LEFT)
		        ."-". str_pad( $date[0], 2, '0', STR_PAD_LEFT)
		        ."-". str_pad( $date[1], 2, '0', STR_PAD_LEFT)
		        . ($time ? " 00:00:00" : '');  
		        return $date_str;
	        }
	    }
	    else
	    {
	        $date = $date > 25568 ? $date + 1 : 25569;
	        /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
	        $ofs = (70 * 365 + 17 + 3) * 86400;
	        $date = date("Y-m-d",($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
	    }
		return $date;
	}
	
	function excelTime($time, $date)
	{
	//	$time = 0.0007060185185185185;
		$time = $time * 86400;
		return date('H:i:s', (strtotime($date) + $time));
	}
	
	function set_date($str, $type = '-')
	{
		$mon_pos  = strpos($str, '月');
		$day_pos  = strpos($str, '日');
		$word_len = strlen('月');
		
		if (!$mon_pos || !$day_pos)
		{
			$mon_pos  = strpos($str, '-');
			
			if (strpos($str, '（'))
			{
				$day_pos  = strpos($str, '（');
			}
			else if (strpos($str, '('))
			{
				$day_pos  = strpos($str, '(');
			}
			else if (strpos($str, ' '))
			{
				$day_pos  = strpos($str, ' ');
			}
			
			$word_len = strlen('-');
		}
		
		if (!$mon_pos || !$day_pos)
		{
			return false;
		}
		
		$offset = $day_pos - $mon_pos;
		$mon = intval(substr($str, 0, $mon_pos));
		$day = intval(substr($str, $mon_pos + $word_len, $offset - $word_len));
		
		if (!is_numeric($mon) || !is_numeric($day))
		{
			return false;
		}
		
		$mon = $mon < 10 ? '0' . $mon : $mon;
		$day = $day < 10 ? '0' . $day : $day;
		$return = $mon . $type . $day;
		
		return $return;
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

	function uploads()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput('未传入频道ID');
		}
		$channel_id = intval($this->input['channel_id']);
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}

		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$nodes['_action'] = 'manage';
		$nodes['nodes'][$channel_id] = $channel_id;
		
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
/**/	if($_FILES)
		{
			if($_FILES['program']['error'] != 0)
			{
				exit('文件上传出错');
			}
		}
		else 
		{
			//$this->errorOutput();
		}
		$file = $_FILES['program'];
		//$file['name'] = $file['tmp_name'] = '../cache/20130424161051.txt';
		$type = substr($file['name'], strrpos($file['name'], '.')+1);
		$programs = $this->file2array($file['tmp_name'], $type);
		
		foreach($programs as $dates => $value)
		{
		    foreach($value as $v)
			{
				$timestamp = strtotime(str_replace('：', ':', ($dates . ' ' . $v['start_time'])));
		        $r['start_time'] = $timestamp;
		      //  $r['start'] = date("Y-m-d H:i:s",$r['start_time']);
		      	if($v['toff'])
		      	{
		      		//if()
		        	$r['toff'] = strtotime($dates . ' ' . $v['toff']);
		        	if($r['toff'] <= $r['start_time'])
		        	{
			        	$r['toff'] = strtotime(date("Y-m-d",strtotime($dates)+86400) . ' ' . $v['toff']);
		        	}
		      	}
		      	else
		      	{
			      	$r['toff'] = 0;	
		      	}
				$r['theme'] = $v['theme'];
				$r['subtopic'] = $v['subtopic'];
				$r['type_id'] = $v['type_id'];
				$r['weeks'] = date('W', strtotime($dates));;
				$r['dates'] = $dates;
				$return[$dates][] = $r;
			}
		}
		$next_day = array();
		if(!empty($return))
		{
			foreach($return as $dat => $items)
			{
				if($next_day[$dat])
				{
					if($next_day[$dat]['toff'] > $items[0]['start_time'])
					{
						$this->addItem($dat . '的跨天数据有误！');
						$this->output();
					}
					else
					{
						array_unshift($return[$dat],$next_day[$dat]);
						array_unshift($items,$next_day[$dat]);
						unset($next_day[$dat]);
					}
				}
				$last_end = 0;
				foreach($items as $i => $v)
				{
					if($i == count($items)-1)
					{
						$tmp_start = strtotime($v['dates'])+86399;
						if($return[$dat][$i]['toff'])
						{
							if($v['toff'] > $tmp_start)
							{
								$tmp_time = $items[$i];
								$tmp_time['start_time'] = strtotime($v['dates'])+86400;
								$tmp_time['dates'] = date("Y-m-d",strtotime($v['dates'])+86400);
								$next_day[date("Y-m-d",$v['toff'])] = $tmp_time;
							}
						}
						$return[$dat][$i]['toff'] = $tmp_start - $v['start_time'];
					}
					else
					{
						$tmp_start = $items[$i+1]['start_time'];
						if($return[$dat][$i]['toff'])
						{
							$tmp_start = $v['toff'];
						}
						$return[$dat][$i]['toff'] = $items[$i+1]['start_time'] - $v['start_time'];
					}
					$return[$dat][$i]['start'] = date("H:i:s",$v['start_time']);
					$return[$dat][$i]['end'] = date("H:i:s",$v['start_time'] + $return[$dat][$i]['toff']);

					if($return[$dat][$i]['toff'] < 0 || ($last_end && $last_end > $v['start_time']))
					{
						//$this->errorOutput('时间线不正确，请检查确认后重新提交！');
						$this->addItem('时间线不正确，请检查确认后重新提交！');
						$this->output();
					}
					$last_end = $v['start_time'] + $return[$dat][$i]['toff'];
				}
			}
		}
		if(!empty($next_day))
		{
			$i = 0;
			foreach($next_day as $dat => $v)
			{
				$return[$dat][$i] = $v;
				$return[$dat][$i]['toff'] = $v['toff'] - $v['start_time'];
				$return[$dat][$i]['start'] = date("H:i:s",$v['start_time']);
				$return[$dat][$i]['end'] = date("H:i:s",$v['start_time'] + $return[$dat][$i]['toff']);
				$i++;
			}
		}
		$info = $return;
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
		
		//数据入库
		if(!empty($info))
		{
			foreach($info as $kk => $vv)
			{			
				$sql = "INSERT INTO " . DB_PREFIX . "program(channel_id,start_time,toff,theme,subtopic,type_id,weeks,dates,create_time,update_time,ip,is_show,user_id,user_name,org_id,appid,appname) values";
				$space = $sql_extra = "";
				foreach ($vv as $k=>$v)
				{
					$sql_extra .=  $space . "(" . $channel_id . "," . $v['start_time'] . "," . $v['toff'] . ",'" . $v['theme'] . "','" . $v['subtopic'] . "'," . ($v['type_id']?$v['type_id']:0) . "," . intval($v['weeks']) . ",'" . $v['dates'] . "'," . TIMENOW . "," . TIMENOW . ",'" . hg_getip() . "',1,'" . $this->user['user_id'] . "','" . $this->user['user_name'] . "','" . $this->user['org_id'] . "','" . $this->user['appid'] . "','" . $this->user['display_name'] . "')";
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
	
	public function download_xls()
	{
		ob_clean();
		$file_dir = '../data/';
		$filename = 'program.xls';
		$new_name = date('YmdHis') . '.xls';
		if (!file_exists($file_dir . $filename)) 
		{ 
			echo 'program.xls文件不存在！';
			exit;
		}
		else
		{
			$file = fopen($file_dir . $filename,"r"); // 打开文件
			header("Content-Type: application/force-download");
			header("Content-Disposition: attachment; filename=".$new_name);
			echo fread($file,filesize($file_dir . $filename));
			fclose($file);
		}
	}
}
$out = new programUploadApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'uploads';
}
$out->$action();
?>
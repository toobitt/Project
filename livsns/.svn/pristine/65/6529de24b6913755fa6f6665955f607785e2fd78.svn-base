<?php
	function _shuffle($array = array())
	{
		$return = array();
		if(!$array)
		{
			return $return;
		}
		$keys = @array_keys($array);
		shuffle($keys);
		foreach ($keys as $key)
		{
			$return[$key] = $array[$key];
		}
		return $return;
	}
	//生成上传目录所在的文件夹名称
	function format_ad_video($vodinfo = array())
	{
		$vodinfo = unserialize($vodinfo) ? unserialize($vodinfo) : $vodinfo;
		if(!$vodinfo)
		{
			return;
		}
		//return $vurl = $vodinfo['host'] . '/' . $vodinfo['dir'] . MANIFEST;
		if(defined('MANIFEST'))
		{
			return $vodinfo['host'] . '/' . $vodinfo['dir'] . MANIFEST;
		}
		return $vodinfo['host'] . '/' . $vodinfo['dir'] . str_replace(strstr($vodinfo['file_name'], '.'), '', $vodinfo['file_name']) . '.m3u8';
	}
	//格式化素材
	function format_ad_material($mat, $mtype='',$imgwidth='', $imgheight='')
	{
		//素材路径
		$murl = '';
		//素材原始数组
		if (!is_array($mat))
		{
			$isserial = unserialize($mat);
		}
		if($isserial)
		{
			$mat = $isserial;
		}
		switch($mtype)
		{
			case 'image':
				{
					$murl = hg_fetchimgurl($mat, $imgwidth, $imgheight);
					break;
				}
			case 'flash':
				{
					$murl = hg_fetchimgurl($mat);
					break;
				}
			case 'video':
				{
					$murl = hg_fetchimgurl($mat['img'], $imgwidth, $imgheight);
					break;
				}
			case 'text':
			case 'javascript':
				{
					return $mat;
				}
			default:
				{
					return '';
				}
		}
		return $murl;
	}
	//获取广告的状态
	function get_ad_status($start_time='', $end_time='')
	{
		$status = 0;
		if(!$start_time && $end_time)
		{
			$start_time = TIMENOW;
		}
		if(!$end_time)
		{
			return $status = 1;
		}
		if($start_time > TIMENOW)
		{
			//将要投放
			$status = 4;
		}
		else if($start_time <= TIMENOW && $end_time > TIMENOW)
		{
			//投放中
			$status = 1;
		}
		else
		{
			//过期
			$status = 2;
		}
		return $status;
	}
	//获取广告发布的时间段 默认传递的是时间戳 无需格式化
	function get_ad_publishTime($start_time = array(), $end_time = array(), $isUnixTimestamp=true)
	{
		$return = array();
		if(!is_array($start_time) || !is_array($end_time))
		{
			return false;
		}
		//开始和结束时间段不匹配
		if(count($start_time)!=count($end_time))
		{
			return false;
		}
		//转换为时间戳
		if(!$isUnixTimestamp && !empty($start_time))
		{
			foreach($start_time as $kk=>$vv)
			{
				$start_time[$kk] = $vv ? strtotime($vv) : '';
				$end_time[$kk] = $end_time[$kk] ? strtotime($end_time[$kk]) : '';
			}
		}
		for($i=0;$i<count($start_time);$i++)
		{
			//只存在一个时间段或者未填写任何时间段 或者只填写了结束时间
			if($i == 0 && count($start_time)==1)
			{
				if(!$end_time[$i])
				{
					if(!$start_time[$i])
					{
						$return = array('start_time'=>TIMENOW, 'end_time'=>'');
					}
					else
					{
						$return = array('start_time'=>$start_time[$i], 'end_time'=>'');
					}
				}
				else
				{
					//if($end_time[$i]<TIMENOW)
					//{
						//return false;
					//}
					if(!$start_time[$i])
					{
						$return = array('start_time'=>TIMENOW, 'end_time'=>$end_time[$i]);
					}
					else
					{
						$return = array('start_time'=>$start_time[$i], 'end_time'=>$end_time[$i]);
					}
				}
				return $return;
			}
			else
			{
				//多段时间不允许留空
				if(!$start_time[$i] || !$end_time[$i])
				{
					return false;
				}
				//开始时间大于结束时间
				if($start_time[$i]>$end_time[$i])
				{
					return false;
				}
				//不连续
				if(isset($start_time[$i+1]) && ($end_time[$i] > $start_time[$i+1]))
				{
					return false;
				}
				if(!$return['start_time'] || $return['start_time']>$start_time[$i])
				{
					if($end_time[$i] > TIMENOW)
					{
						$return['start_time'] = $start_time[$i];
						$return['end_time'] = $end_time[$i];
					}
				}
			}
		}
		return $return;
	}
	function get_material_type($filename='')
	{
		global $gGlobalConfig;
		$filetype = array();
		if(!$filename)
		{
			return $filetype;
		}
		$_suffix = strtolower(substr($filename, strrpos($filename,'.')));
		if(in_array('*' . $_suffix, $gGlobalConfig['allow_upload_types']['img']))
		{
			if($_suffix=='.swf')
			{
				$filetype = array('mtype'=>'flash','suffix'=>$_suffix);
			}
			else
			{
				$filetype = array('mtype'=>'image','suffix'=>$_suffix);
			}
		}
		else if(in_array('*' . $_suffix, $gGlobalConfig['allow_upload_types']['video']))
		{
			$filetype = array('mtype'=>'video', 'suffix'=>$_suffix);
		}
		return $filetype;
	}
	//根据广告类型输出不同的广告模板
	function build_ad_tpl($mcontent = '', $mtype = '', $param = array())
	{
		global $gGlobalConfig;
		if(!$mtype)
		{
			return false;
		}
		if(!$mcontent)
		{
			return false;
		}
		$tpl = $style = '';
		if($param['width'])
		{
			$style.=' width="'.$param['width'].'"';
		}
		if($param['height'])
		{
			$style.=' height="'.$param['height'].'"';
		}
		switch ($mtype)
		{
			case 'image':
				{
					$tpl = '<img src="'.$mcontent.'" alt="'.$param['title'].'" '.$style.'/>';
					break;
				}
			case 'flash':
				{
					$tpl = '<object '.$style.' type="application/x-shockwave-flash" data="'.$mcontent.'"><param name="movie" value="'.$mcontent.'"><param value="transparent" name="wmode" align="center"></object>';
					break;
				}
			case 'video':
				{
					$tpl = '<object data="' . ADV_DATA_URL . 'vodPlayer.swf" '.$style.' type="application/x-shockwave-flash"><param name="movie" value="' . ADV_DATA_URL . 'vodPlayer.swf"><param name="allowscriptaccess" value="always"><param name="allowFullScreen" value="true"><param name="wmode" value="transparent"><param name="flashvars" value="videoUrl='.$mcontent.'&autoHide=true&autoPlay=true"></object>';
					break;
				}
			case 'text':
			case 'javascript':
				{
					return '<span>'.$mcontent.'</span>';
				}
			default:
				{
					return '';
				}
		}
		return $tpl;
	}
	function adtime2unixtime($time = '')
	{
		if(!$time)
		{
			return TIMENOW;
		}
		$time = explode('@', $time);
		switch (intval($time[1]))
		{
			case 1:
				{
					return strtotime(date('Y-m-d', TIMENOW) . ' ' . $time[0]);
					break;
				}
			case 3:
				{
					if(date('j', TIMENOW)%2==0)
					{
						return strtotime(date('Y-m-d', TIMENOW) . ' ' . $time[0]);
					}
					else
					{
						return '';
					}
					break;
				}
			case 2:
				{
					if(date('j', TIMENOW)%2==1)
					{
						return strtotime(date('Y-m-d', TIMENOW) . ' ' . $time[0]);
					}
					else
					{
						return '';
					}
					break;
				}
			case 4:
				{
					if(date('W', TIMENOW)%2==1)
					{
						return strtotime(date('Y-m-d', TIMENOW) . ' ' . $time[0]);
					}
					else
					{
						return '';
					}
					break;
				}
			case 5:
				{
					if(date('W', TIMENOW)%2==0)
					{
						return strtotime(date('Y-m-d', TIMENOW) . ' ' . $time[0]);
					}
					else
					{
						return '';
					}
					break;
				}
			case 6:
				{
					if(in_array(date('N',TIMENOW), explode(',',$time[2])))
					{
						return strtotime(date('Y-m-d', TIMENOW) . ' ' . $time[0]);
					}
					else
					{
						return '';
					}
					break;
				}
			case 7:
				{
					if(floor(TIMENOW-$time[3]/(3600*24))>=$time[2])
					{
						return strtotime(date('Y-m-d', TIMENOW) . ' ' . $time[0]);
					}
					else
					{
						return '';
					}
					break;
				}
			case 8:
				{
					$iscurrentday = date('Y-m-d', TIMENOW) == date('Y-m-d', $time[3]);
					if(floor(!$iscurrentday && TIMENOW-$time[3]/(3600*24))>=$time[2])
					{
						return strtotime(date('Y-m-d', TIMENOW) . ' ' . $time[0]);
					}
					else
					{
						return '';
					}
					break;
				}
			case 9:
				{
					return strtotime($time[2] . ' ' . $time[0]);
					break;
				}
			default:break;
		}
	}
	function get_auth_group_or_pos($mod='', $a='', $gUser = array())
	{
		$return = array();
		if(!$gUser || !$mod || !$a)
		{
			return $return;
		}
		global $gGlobalConfig;
		if($gUser['group_type'] > MAX_ADMIN_TYPE)
		{
			$auth_node = $gUser['prms'][$mod][$a]['node']['adv_node'];
			if(!$auth_node)
			{
				return $return;
			}
			foreach ($auth_node as $k=>$v)
			{
				if(in_array($v, $gGlobalConfig['hg_ad_flag']))
				{
					$return['first'][] = $v;
				}
				else
				{
					$tmp = explode(SPLIT_FLAG, $v);
					$return['second'][] = $tmp[0];
					$return['three'][$tmp[0]][] = $tmp[1];
				}
			}
		}
		return $return;
	}
if (!function_exists('hg_filter_ids'))
{
	function hg_filter_ids($id, $split = ',')
	{
		$pattern = '/^[\d]+(\\'.$split.'\d+){0,}$/';
		if(!preg_match($pattern, $id))
		{
			return -1;
		}
		return $id;
	}
}
?>
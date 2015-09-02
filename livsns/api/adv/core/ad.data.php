<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
define('WITH_LOGIN', false);
require(ROOT_DIR . 'global.php');
require('lib/functions.php');
require('statistics.php');
class adv extends InitFrm
{
	//广告统计对象
	private $sta;
	function __construct()
	{
		$this->sta = new statistics();
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	//$dostatistic是否做统计
	function getAdDatas($conditions = array(), $dostatistic = true)
	{
		$adDatas = array();
		$order = ' ORDER BY c.id DESC ';
		$limit = " limit {$conditions['offset']}, {$conditions['count']}";
		$sql = 'SELECT p.*,c.*,c.start_time,c.end_time,pos.name,pos.id,pos.multi,g.is_use,ani.tpl,ani.js_para,ani.include_js,p.id pubid FROM '.DB_PREFIX.'advpub p'.
		' LEFT JOIN '.DB_PREFIX.'advcontent c ON p.ad_id = c.id'.
		' LEFT JOIN '.DB_PREFIX.'advgroup g ON p.group = g.flag'.
		' LEFT JOIN '.DB_PREFIX.'animation ani ON p.ani_id = ani.id'.
		' LEFT JOIN '.DB_PREFIX.'advpos pos ON p.pos_id = pos.id WHERE 1 '.$conditions['where']. $order . $limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			//广告栏目投放策略
			$pub_columns = @unserialize($r['columnid']);
			if(is_array($pub_columns) && $pub_columns)
			{
				//栏目投放策略模式
				list($pub_mode, $pub_columns_array) = each($pub_columns);
				if($pub_columns_array)
				{
					$colids = @array_keys($pub_columns_array); 
				}
				if($conditions['colid'] && $colids && !in_array($conditions['colid'], $colids))
				{
					continue;
				}
			}
			
			//通过内容过滤广告
			if($conditions['arcinfo'])
			{
				$arcinfo = $conditions['arcinfo'];
				//兼容处理频道返回的信息格式已经变化
				if($this->input['group'] == 'liv_player')
				{
					$arcinfo = array('id'=>$arcinfo['id'], 'name'=>$arcinfo['channel']['name']);
				}
				if(is_array($arcinfo) && $arcinfo && $r['conditions'])
				{
					$r['conditions'] = @unserialize($r['conditions']);
					if($r['conditions'])
					{
						$flag = true;
						foreach($r['conditions'] as $k=>$v)
						{
							$op = htmlspecialchars_decode($v['simbol']);
							if($op == -1)
							{
								continue;
							}
							switch($op)
							{
								case '>':
								case '>=':
								case '<':
								case '<=':
								case '!=':
								case '==':
								{
									$php = '$flag = "'.$arcinfo[$k] .'"'. $op .'"'. $v['value'].'";';
									eval($php);
									break;
								}
								case 'like':
								{
									$php = '$flag = stristr("'.$arcinfo[$k].'", "'.$v['value'].'");';
									eval($php);
									break;
								}
								case 'IN':
								{
									$php = '$flag = in_array("'.$arcinfo[$k].'", explode(",","'.$v['value'].'"));';
									eval($php);
									break;
								}
							}	
							if(!$flag)
							{
								break;
							}
						}
						if(!$flag)
						{
							continue;
						}
					}
				}
			}
			$r['param'] = unserialize($r['param']);
			$mheight = $mwidth = '';
			if($r['param'])
			{
				$mheight = $r['param']['pos']['height'];
				$mwidth = $r['param']['pos']['width'];
			}
			//播放器广告位几个参数的特殊处理的特殊处理
			if(in_array($r['group'], array($this->settings['hg_ad_flag']['liv_player_flag'],$this->settings['hg_ad_flag']['vod_player_flag'])))
			{
				if(in_array($r['pos_flag'],array('liv_captions','liv_float')))
				{
					if($r['param']['pos']['time'])
					{
						$r['param']['pos']['time'] = adtime2unixtime($r['param']['pos']['time'])*1000;
					}
				}
				if(in_array($r['pos_flag'],array('captions','float', 'liv_captions', 'liv_float')))
				{
					if($r['param']['pos']['interval'])
					{
						$r['param']['pos']['interval'] = intval($r['param']['pos']['duration'])+intval($r['param']['pos']['interval']);
					}
				}	
			}
			
			if($r['mtype'] == 'video')
			{
				//视频播放地址
				$material = unserialize($r['material']);
				$r['url'] = format_ad_video($r['material']);
				$r['m3u8'] = $material['host'] . '/' . $material['dir']. str_ireplace('.mp4', '.m3u8', $material['file_name']);
				$r['material'] = $material;
			}
			else
			{
				$r['url'] = format_ad_material($r['material'], $r['mtype'],$mwidth,$mheight);
				$mtmp = unserialize($r['material']);
				$r['material'] = array(
					'host' => $mtmp['host'],
					'dir' => $mtmp['dir'],
					'filepath' => $mtmp['filepath'],
					'filename' => $mtmp['filename'],
				);
			}
			
			if($r['mtype'] == 'text')
			{
				$r['param']['pos']['htmltext'] = $r['url'];
				unset($r['url']);
			}
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);


			$adDatas[$r['id']]['row'][] = $r;

			//广告位广告的所有广告权重数组
			$adDatas[$r['id']]['weight'][] = $r['weight'];

			//广告位广告的所有广告权重数组
			$adDatas[$r['id']]['priority'][] = $r['priority'];
		}
		//根据权重优先级过滤广告 确定返回
		$return = array();
		if($adDatas)
		{
			foreach($adDatas as $pos_id=>$ad)
			{
				if($ad['row'][0]['multi'])
				{
					$return[$pos_id] = $ad['row'];
				}
				elseif(count($ad['row'])>1)
				{
					//数字越小优先级越高
					$max = min($ad['priority']);
					$statistic = array_count_values($ad['priority']);
					if($statistic[$max] == 1)
					{
						$kk = array_keys($ad['priority'], $max);
						$return[$pos_id] = $ad['row'][$kk[0]];
					}
					else
					{
						//权重判定
						if(array_sum($ad['weight']))
						{
							//筛选高优先级
							foreach($ad['weight'] as $kp=>$p)
							{
								if($ad['priority'][$kp] != $max)
								{
									$ad['weight'][$kp] = 0;
								}
							}
							$duration = 0;
							$rand = rand(0,array_sum($ad['weight']));
							$ad['weight'] = _shuffle($ad['weight']);
							foreach($ad['weight'] as $kw=>$w)
							{
								$duration += $w;
								if($rand<=$duration && $w)
								{
									$return[$pos_id] = $ad['row'][$kw];
									continue 2;
								}
							}
							
						}
						else
						{
							$rand = rand(0,count($ad['row'])-1);
							$return[$pos_id] = $ad['row'][$rand]; 
						}
					}
				}
				else
				{
					$return[$pos_id] = $ad['row'][0];
				}
			}
		}
		//返回之前做输出统计
		if($dostatistic)
		{
			//$this->dostatistics($return);
		}
		return $return;
	}
	function dostatistics($adDatas = array())
	{
		if($adDatas)
		{
			foreach($adDatas as $k=>$v)
			{
				$this->sta->increase($v['pubid']);
			}
		}
	}
}
?>
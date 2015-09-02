<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID','adv');//模块标识
include('./core/ad.data.php');
class jsAdDatas extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$hg_ad_js = ADV_DATA_DIR . 'script/hg_ad.js';
		if(!file_exists($hg_ad_js) || $this->input['forcejs'])
		{
			if(!is_dir(ADV_DATA_DIR . 'script/'))
			{
				hg_mkdir(ADV_DATA_DIR . 'script/');
			}
			$adjs = file_get_contents('./core/ad.js');
			hg_file_write($hg_ad_js, str_replace('{$addomain}', AD_DOMAIN, $adjs));
		}
		$para = array();
		$para['offset'] = $this->input['offset']?intval($this->input['offset']):0;
		$para['count'] = $this->input['count']?intval($this->input['count']):100;
		$para['where'] = urldecode($this->get_condition());
		$para['arcinfo'] = json_decode(urldecode($this->input['vinfo']), true);
		
		$para['colid'] = hg_filter_ids($this->input['colid']);
		$para['colid'] = $para['colid'] == -1 ? 0 : $para['colid'];
		
		$dostatistic = true;
		if($this->input['preview'])
		{
			$dostatistic = false;
		}
		$ad = new adv();
		$ads = $ad->getAdDatas($para, $dostatistic);
		if($ads)
		{
			$outputjs = '';
			foreach($ads as $k=>$r)
			{
				$_ad = $r;
				if(!is_array($r[0]))
				{
					//不存在广告位多个广告
					$_ad = array(0=>$r);
					unset($r);
				}
				$_ad_tpl = '';
				foreach($_ad as $r)
				{
					if($r['mtype'] != 'javascript')
					{
						$is_js = 0;
					}
					else
					{
						$is_js = 1;
					}
					$r['param'] = array_merge((array)$r['param']['pos'], (array)$r['param']['ani']);
					$r['param']['title'] = $r['title'];
					$r['param']['content'] = build_ad_tpl($r['url'],$r['mtype'], $r['param']);
					foreach($r as $k=>$v)
					{
						if(is_array($v))
						{
							foreach($v as $kk=>$vv)
							{
								$$kk = $vv;
							}
						}
						else
						{
							$$k = $v;
						}
					}
					if(!$tpl)
					{
						$tpl = '{$content}';
					}
					$ad_tpl = stripslashes(preg_replace("/{(\\$[a-zA-Z0-9_\[\]\-\'\"\$\>\.]+)}/ies",'${1}',$tpl));
					$ad_tpl = preg_replace("/[\n]+/is",'',$ad_tpl);
	
					//通过API进行统计
					if($link)
					{
						if($r['mtype']!='javascript')
						{
							$_ad_tpl .= '<a href="'.AD_DOMAIN .'click.php?a=doclick&url='.urlencode($link).'&pubid='.$pubid.'" target="_blank">'.$ad_tpl.'</a>';
						}
					}
					else
					{
						$_ad_tpl .= $ad_tpl;
					}
					if(!$ad_js_para)
					{
						$ad_js_para = stripslashes(preg_replace("/{\\$([a-zA-Z0-9_\[\]\-\'\"\$\>\.]+)}/ies",'${1} . ":\"" . $${1} . "\""',str_replace("\r\n", '',$js_para)));
					}
				}
				
				$outputjs .=  'hg_AD_AddHtml({para:{'.$ad_js_para.'}, html:"'.addslashes($_ad_tpl).'",box:"ad_'.$id.'",loadjs:"'.$include_js.'",loadurl:"'.ADV_DATA_URL.'script/",isjs:'.$is_js.'});';
			}
			header('Content-Type:application/x-javascript');
			echo $outputjs;
		}
	}
	function get_condition()
	{
		$condition = '';
		//默认只搜索出所有全局的广告内容
		if($this->input['group'])
		{
			$group = explode(',',trim(urldecode($this->input['group'])));
			if($group && !array_diff($group, $this->settings['hg_ad_flag']))
			{
				$condition .= ' AND `group` in("'.implode('","',$group).'")';
			}
			else
			{
				$condition .= ' AND `group` in("website")';
			}
		}
		//广告位ID条件
		if($this->input['pid'])
		{
			$pids_str = hg_filter_ids($this->input['pid']);
			$condition .= ' AND p.pos_id in('.$pids_str.')';
		}
		/*
		//广告位英文标识
		if($this->input['flag'])
		{
			$condition .= ' AND p.pos_flag in("'.str_replace(',', '","',trim(urldecode($this->input['flag']))).'")';
		}
		//广告位内容ID
		if($this->input['aid'])
		{
			$condition .= ' AND p.ad_id in('.trim(urldecode($this->input['aid'])).')';
		}
		//广告动画ID
		if($this->input['aniid'])
		{
			$condition .= ' AND p.ani_id in('.trim(urldecode($this->input['aniid'])).')';
		}
		//发布策略ID
		if($this->input['id'])
		{
			$condition .= ' AND p.id in('.trim(urldecode($this->input['id'])).')';
		}
		//有效广告 时间段
		if(!$this->input['preview'])
		{
			$condition .= ' AND c.start_time <= '.TIMENOW;
			$condition .= ' AND c.status=1 ';
		}
		*/
		//只读取分组启用的数据
		$condition .= ' AND g.is_use = 1 and c.status IN(1,3)';
		return $condition;
	}
	public function verifyToken()
	{
		
	}
}
$out = new jsAdDatas();
$out->show(); 
?>
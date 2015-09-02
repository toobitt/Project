<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID','adv');//模块标识
include('./core/ad.data.php');
class adForPlayer extends outerReadBase
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
		
	}
	function detail()
	{
		
	}
	function count()
	{
		
	}
	function outputPlayerAds()
	{
		$para = array();
		$fileds = array_flip(array('pubid','mtype','brief','link','title','url','param','name', 'material','id','ad_id','pos_id', 'm3u8'));
		$para['offset'] = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$para['count'] = $this->input['count']?intval(urldecode($this->input['count'])):100;
		if($this->input['is_one'])//是否只显示一条广告
		{
			$para['count'] = 1;
		}
		$para['where'] = urldecode($this->get_condition());
		$para['arcinfo'] = json_decode(urldecode(html_entity_decode($this->input['vinfo'])), true);
		$para['colid'] = $this->input['colid'];
		$ad = new adv();
		$dostatistic = true;
		if($this->input['preview'])
		{
			$dostatistic = false;
		}
		$ads = $ad->getAdDatas($para, $dostatistic);
		$this->setXmlNode('ads', 'ad');
		if($ads)
		{
			foreach($ads as $k=>$r)
			{
				$r = array_intersect_key($r, $fileds);
				//链接不存在输出id=0
				if(!$r['link'])
				{
					$r['pubid'] = 0;
				}
				if($r['mtype'] == 'javascript')
				{
					continue;
				}
				$this->addItem($r);
			}
		}
		$this->output();
	}
	function get_condition()
	{
		$condition = '';
		//默认只搜索出所有全局的广告内容
		if($this->input['group'])
		{
			$group = explode(',',trim(urldecode($this->input['group'])));
			if($group)
			{
				$condition .= ' AND `group` in("'.implode('","',$group).'")';
			}
		}
		//广告位ID条件
		if($this->input['pid'])
		{
			$condition .= ' AND p.pos_id in('.intval($this->input['pid']).')';
		}
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
		if($this->input['columnid'])
		{
			$condition .= ' AND g.columnid in('.trim(urldecode($this->input['columnid'])).')';
		}
		//有效广告 时间段
		$condition .= ' AND c.start_time <= '.TIMENOW;
		//只读取分组启用的数据
		$condition .= ' AND g.is_use = 1 and c.status IN(1,3)';
		return $condition;
	}
}
$ad = new adForPlayer();
$ad->outputPlayerAds();
?>
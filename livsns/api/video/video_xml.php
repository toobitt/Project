<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: video_xml.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class videoXml extends adminBase
{
	function __construct()
	{
		parent::__construct();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
		
	public function create()
	{
		global $video_xml;
		$xml = '<?xml version="1.0" encoding="utf-8"?><document><webSite>http://v.hoolo.tv/</webSite><webMaster>'.$video_xml['author'].'</webMaster><updatePeri>'.$video_xml['cycle'].'</updatePeri>';
		$array = array(1 => '资讯',2 => '原创',3 => '电视',4 => '娱乐',5 => '电影',6 => '体育',
			7 => '音乐',8 => '游戏',9 => '动漫',10 => '时尚',11 => '母婴',12 => '汽车',13 => '旅游',14 => '科技',15 => '教育',
			16 => '生活',17 => '搞笑',18 => '广告',19 => '其他',);
		$sql = "SELECT v.* , u.username FROM " . DB_PREFIX . "video AS v LEFT JOIN " . DB_PREFIX . "user AS u ON v.user_id = u.id WHERE 1 AND state = 1 AND (is_show = 2 OR is_show = 3) ORDER BY create_time DESC LIMIT 0,200";
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q)) {
			$item_list .='<item><op>add</op>';
			$item_list .='<title><![CDATA['.$this->xml_filter($row['title']).']]></title>';
			if($this->settings['rewrite'])
			{
				$vurl = SNS_VIDEO . "video-" . $row['id'] .".html";	
			}
			else 
			{
				$vurl = SNS_VIDEO . "video_play.php?id=" . $row['id'];	
			}
			$item_list .='<playLink><![CDATA[' . $vurl . ']]></playLink>';
			$item_list .='<imageLink>'.$row['schematic'].'</imageLink>';
//			$item_list .='<videoLink>'.SNS_VIDEO.'video_play.php?id='.$row['id'].'</videoLink>';
			$tag = explode(",", $row['tags']);
			foreach($tag as $k => $v)
			{
				$item_list .='<tag><![CDATA['.$this->xml_filter($v).']]></tag>';
			}
			$item_list .='<comment><![CDATA['.$this->xml_filter($row['brief']).']]></comment>';
			$item_list .='<pubDate>'.$row['update_time'].'</pubDate>';
			$item_list .='<create_time>'.$row['create_time'].'</create_time>';
			$item_list .='<duration>'.$row['toff'].'</duration>';
			$item_list .='<category><![CDATA['.$array[$row['sort_id']].']]></category></item>';
		}
		$xml .=$item_list.'</document>';
//		file_put_contents("/data/web/api.hcrt.cn/uploads/videos.xml",$xml);
		$this->setXmlNode('video_xml' , 'info');
		$this->addItem($xml);
		$this->output();
	}
	
	private function  xml_filter($str)
	{
		$str = preg_replace('/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/','',$str);
		return $str;
	}
}

$out = new videoXml();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>
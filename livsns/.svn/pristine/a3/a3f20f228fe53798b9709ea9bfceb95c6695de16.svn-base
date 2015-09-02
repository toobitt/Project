<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID', 'site');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
class siteApi extends adminBase
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include site.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		$this->pub_content = new publishcontent();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
    
    public function show()
    {
        $site_id = intval($this->input['site_id']);
        $video_record_count = intval($this->input['video_record_count']);
        $sql = "SELECT id,sub_weburl,weburl,site_dir FROM ".DB_PREFIX."site WHERE  id = " . $site_id;
        $site_info = $this->db->query_first($sql);
        if (!$site_info) {
            $this->errorOutput('NO_SITE');
        }
        $sitedata[] = array('site_id' => $site_id, 'video_record_count' => $video_record_count);
        $video_data = $this->pub_content->video_record($sitedata);
        $ret = array('site_info' => $site_info, 'content_data' => $video_data['content_data']);
        $this->addItem($ret);
        $this->output();
    }
	
	public function showBak()
	{
		//先取出支持百度视频收录站点
		$sql = "SELECT id,video_record_url,video_record_count,sub_weburl,weburl,site_dir,user_email,video_update_peri,video_record_filename FROM ".DB_PREFIX."site WHERE is_video_record=1 ORDER BY id";
		$info = $this->db->query($sql);
		while($row = $this->db->fetch_array($info))
		{
			$site[$row['id']]['site_id'] = $row['id'];
			$site[$row['id']]['video_record_count'] = $row['video_record_count'];
			$sitedata[] = $row;
		}
		if(!$sitedata)
		{
			$this->errorOutput('NO_SITE');
		}
		
		//publishcontent取出视频收录数据
		$video_data = $this->pub_content->video_record($site);

		foreach($sitedata as $k=>$v)
		{
			$xml = $item_list = '';
			$xml = '<?xml version="1.0" encoding="utf-8"?><document><webSite>'.$v['weburl'].'</webSite><webMaster>'.$v['user_email'].'</webMaster><updatePeri>'.$v['video_update_peri'].'</updatePeri>';
			
			if(empty($video_data['video_record'][$v['id']]))
			{
				continue;
			}
			
			foreach($video_data['video_record'][$v['id']] as $kk=>$vv)
			{
				$title = $video_data['content_data'][$vv['relation_id']]['title'];
				$content_url = $video_data['content_data'][$vv['relation_id']]['content_url'];
				$indexpic = $video_data['content_data'][$vv['relation_id']]['indexpic'];
				$keywords = $video_data['content_data'][$vv['relation_id']]['keywords'];
				$brief = $video_data['content_data'][$vv['relation_id']]['brief'];
				$create_time = $video_data['content_data'][$vv['relation_id']]['create_time'];
				$duration = $video_data['content_data'][$vv['relation_id']]['video']['duration'];
				if(empty($video_data['content_data'][$vv['relation_id']]))
				{
					continue;
				}
				$item_list .='<item><op>'.$vv['opration'].'</op>';
				if($title)
				{
					$item_list .='<title><![CDATA['.xml_filter($title).']]></title>';
				}
				
				$item_list .='<playLink><![CDATA[' . $content_url . ']]></playLink>';
				if(!empty($indexpic))
				{
					$item_list .='<imageLink>'.hg_fetchimgurl($indexpic).'</imageLink>';
				}
				if(!empty($content_url))
				{
					$item_list .='<videoLink>'.$content_url.'</videoLink>';
				}
				if(!empty($keywords))
				{
					$tag = explode(",", $keywords);
					foreach($tag as $kkk => $vvv)
					{
						$item_list .='<tag><![CDATA['.xml_filter($vvv).']]></tag>';
					}
				}
				$item_list .='<comment><![CDATA['.xml_filter($brief).']]></comment>';
				$item_list .='<pubDate>'.date('Y-m-d H:i:s',$create_time).'</pubDate>';
				if(!empty($duration))
				{
					$item_list .='<duration>'.$duration.'</duration>';
				}
				$item_list .= '</item>';
			}
			
			$xml .=$item_list.'</document>';
			$row = array();
			$row['xml'] = $xml;
			$row['video_record_url'] = $v['video_record_url'];
			$row['video_record_filename'] = $v['video_record_filename'];
			$this->addItem($row);
		}
		$this->output();
	}
	
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new siteApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>

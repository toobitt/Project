<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: public_timeline.php 3545 2011-04-12 05:42:14Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class timeline extends BaseFrm
{
	var $trans = array();
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	/*获取最新的公共微薄消息*/
	public function getTimeline()
	{
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->user = new user();
		$userinfo = array();
		$media = array();
		//获取用户参数
		if(!$this->input['count'])
		{
			$this->input['count'] =  50;
		}
		elseif ($this->input['count'] > 200)
		{
			$this->input['count'] = 200;
		}
		$count = intval($this->input['count']);
		$page = intval($this->input['page']);
		$offset = $page * $count;
		
		//取最近的博客信息
		$this->end = "limit $offset , $count";
		$statusids = array(0 => 'recent');
		$all = $this->getblog($statusids,1);		
		//取得本人相关的媒体信息
		foreach($all as $key => $value)
		{
			$statusid .= $value['id'].","; 
		}
		$statusid = rtrim($statusid,",");	
		if($statusid)
		{
			$media = $this ->getMedia($statusid);
		}
		
		//取得转发我的点滴信息
		if(count($this->trans))
		{
			$alltran = $this->getblog($this->trans,2);
			$mediatran = $this ->getMedia($this->trans);
		}					
		//博客用户信息和转发用户信息合并
		$this->setXmlNode('statuses','status');
		foreach ($all as $key =>$values)
		{
			$alltran[$values['reply_status_id']]['medias'] = $mediatran[$values['reply_status_id']];
			$values['retweeted_status'] = $alltran[$values['reply_status_id']];
			$values['medias'] = $media[$values['id']];
			$this->addItem($values);
		}
		$this->output();		
	}
	public function getblog($ids,$flag)
	{
		include ('getblog.php');
		return $all;
	}
	public function getMedia($id)
	{
		if(is_array($id))
		{
			$ids = implode(",", $id);
		}
		else 
		{
			$ids = $id;			
		}	
		$sql = "SELECT * FROM ".DB_PREFIX."media WHERE status_id IN (".$ids.")" ;
		$query = $this->db->query($sql);
		$i = 0;
		while ($array = $this->db->fetch_array($query))
		{
			$info[$array['status_id']][$i] = $array;
			str_replace($this->settings['video_api'],"",$array['link'],$cnt);
			if($cnt)
			{
				$info[$array['status_id']][$i]['self'] = 1;
			}
			else 
			{
				$info[$array['status_id']][$i]['self'] = 0;
			}
			$info[$array['status_id']][$i]['ori'] = UPLOAD_URL.$array['dir'].$array['url'];
			$info[$array['status_id']][$i]['larger'] = UPLOAD_URL.$array['dir']."l_".$array['url'];
			$info[$array['status_id']][$i]['middle'] = UPLOAD_URL.$array['dir']."m_".$array['url'];
			$info[$array['status_id']][$i]['small'] = UPLOAD_URL.$array['dir']."s_".$array['url'];
			$i++;
		}
		return $info;
	}
	/**
	 * 入口
	 */
	public function show()
	{
			$this->getTimeline();
		
	}
	
	
}
$out = new timeline();
$out->show();
?>
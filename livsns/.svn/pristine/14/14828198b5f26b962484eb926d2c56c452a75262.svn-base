<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: search.php 4321 2011-08-01 07:23:02Z lijiaying $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class search extends BaseFrm
{
	var $trans = array();
	var $total;
	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	/*获取最新的公共微薄消息*/
	public function search()
	{
		//$this ->input['keywords'] ='啊斯蒂芬撒地方';
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		//初始化数组
		$total_count = array();
		$userinfo = array();
		
		$this->user = new user();
		$userinfo = $this->user->verify_credentials();

		
		//获取用户参数
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		elseif ($this->input['count'] > 200)
		{
			$this->input['count'] = 200;
		}
		$count = intval($this->input['count']);
		$page = intval($this->input['page']);
		//判断是否对搜索数据进行管理
		$isadmin = intval($this->input['is_admin']);
		//返回待审核的信息
		$isverify = $this->input['is_verify'];
		$istotal= $this->input['is_total'];
		$stime = intval($this->input['s_time']);
		$etime = intval($this->input['e_time']);
		$offset = $page * $count;
		$this->keywords =urldecode(trim($this ->input['q']));
		
		
		/*if($stime)
		{
			$dtime=" and create_at >".$stime;
		}
		elseif($etime)
		{
			$dtime = " and create_at <".$etime;
		}
		elseif ($stime&&$etime)
		{
			$dtime = " and create_at >$stime and create_at <$etime";
		}
		else
		{
			$dtime="";
		}*/
		
		/* 时间搜索条件处理  */
		$dtime = '';
		
		if($stime)
		{
			$dtime .= ' AND create_at >' . $stime;	
		}
		
		if($dtime)
		{
			$dtime .= ' AND create_at <' . $etime;	
		}
		
		
		if($this->keywords)
		{
			$this->search = " and  text LIKE '%{$this->keywords}%'".$dtime;
		}
		else 
		{
			if($istotal)
			{
				$this->search = $dtime;	
			}
			else
			{
				$this->search = " and  text LIKE ''".$dtime;	
			}
			
		}
		if(!$isverify)
		{
			
			//判断用户的身份
			if($userinfo['is_admin']&&$isadmin)
			{
				//搜索所有含有关键字所有博客信息
				$this->search .="";
			}
			elseif (!$userinfo['is_admin']&&$isadmin)
			{
				//搜索所有含有关键字当前用户的所有博客信息
				$this->search .=" and sta.member_id=".$userinfo['id'];
			}
			else 
			{
				//搜索所有含有关键字且通过审核的博客信息
				$this->search .=" and sta.status=0";
			}
		}
		else
		{
			//判断用户的身份
						
			if($userinfo['is_admin']&&$isadmin)
			{
				//搜索所有含有关键字所有博客信息
				$this->search .=" and sta.status=1";
			}
			elseif (!$userinfo['is_admin']&&$isadmin)
			{
				//搜索所有含有关键字当前用户的所有博客信息
				$this->search .=" and sta.member_id=".$userinfo['id']."and sta.status=1";
			}
		}
		
		$this->end = " limit $offset , $count";
		$statusids = array(0 => 'count');
		
		$all = $this->getblog($statusids,1);
		foreach($all as $key => $value)
		{
			$statusid .= $value['id'].","; 
		}
		$statusid = rtrim($statusid,",");	
		//取得相关的媒体信息
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
		$total_count = $this->db->query_first($this->sqlcount);
		$this->setXmlNode('statuses','status');
		$this->addItem($total_count);
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
	
	public function verifystatus()
	{
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->user = new user();
		$userinfo = $this->user->verify_credentials(); 
		$sql = "SELECT * FROM ".DB_PREFIX."status WHERE member_id=".$userinfo['id']." ORDER BY id DESC";
		$first = $this->db->query_first($sql);
		if(!$first['pic']||!$first['video'])
		{
			$first['total'] = count($first);
		}
		$this->setXmlNode('statuses','status');
		$this->addItem($first);
		$this->output();	
	}

	
}
$out = new search();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'search';
}
$out->$action();
?>
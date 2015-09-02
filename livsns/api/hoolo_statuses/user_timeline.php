<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user_timeline.php 5369 2011-12-17 01:54:21Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
require_once (ROOT_DIR . 'global.php');
require(ROOT_DIR . '/lib/class/curl.class.php');

class user_timeline extends BaseFrm
{
	private $trans = array();
	private	$total= array();
	private $curl;
	
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl(); 
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	/**
	 *  获取当前登录用户发布的点滴消息列表
	 */
	public function user_timeline()
	{	
	    $userinfo = array();
		$ids = array();
		$media = array();
		$this->input['user_id'] = intval($this->input['user_id']);
		require_once(ROOT_DIR.'lib/user/user.class.php');
		$this->user = new user();
		$userinfo = $this->user->verify_credentials();
			
		//$userinfo['id'] = 1; 
		//error_reporting(0); 
		//$this->input['user_id'] =2; 
		//$this->input['gettoal']='gettotal';
		$u_id = intval($userinfo['id']);
		//若指定此参数，则只返回传进来的用户id的点滴消息。
		if($this->input['user_id'])
		{
			$u_id =  $this->input['user_id'];
		}
		//指定每页返回的记录条数。
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		elseif ($this->input['count'] > 200)
		{
			$this->input['count'] = 200;
		}
		//取总数时需传入数值gettotal
		$gettotal = trim($this->input['gettoal']);
		$count = intval($this->input['count']);
		//页码
		$page = intval($this->input['page']);
		$offset = $page * $count;
		
		//取得本人用户的点滴总数
		if($gettotal)
		{
			$this->gettotal = $gettotal;
			$this->getblog($u_id,1);
			//清空gettotal
			$this->gettotal = '';			
		}
		//取得本人用户的点滴信息
		$this->end = "limit $offset , $count";

		/**
		 * 权限判断
		 */
						
		if($this->input['user_id'])
		{
			if($this->input['user_id'] == $userinfo['id'])
			{
				$all = $this->getblog($u_id,1);
				foreach($all as $key => $value)
				{
					$statusids .= $value['id'].","; 
				}
				$statusids = rtrim($statusids,",");	
				if($statusids)
				{
					$media = $this ->getMedia($statusids);
				}
				
			}
			else
			{
				/**
				 * 获取用户权限
				 */
				
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->addRequestData('id', $this->input['user_id']);
				$tmp = $this->curl->request('users/get_authority.php');				
				$authority = $tmp[0];
						
				//访问页面权限
				$visit_user_info = intval($authority[14]);
	
				//任何人可访问
				if($visit_user_info == 0)
				{
					$all = $this->getblog($u_id,1);
					foreach($all as $key => $value)
					{
						$statusids .= $value['id'].","; 
					}
					$statusids = rtrim($statusids,",");	
					if($statusids)
					{
						$media = $this ->getMedia($statusids);
					}
				}
				
				//关注的人可获取点滴信息
				if($visit_user_info == 1)
				{
					/**
					 * 获取用户和传入ID的关系
					 */
					$this->curl->setSubmitType('post');
					$this->curl->setReturnFormat('json');
					$this->curl->addRequestData('source_id', $userinfo['id']);
					$this->curl->addRequestData('target_id', $this->input['user_id']);
					$relation = $this->curl->request('friendships/show.php');
					
					//关注
					if($relation == 3 || $relation == 1)
					{
						$all = $this->getblog($u_id,1);	
						foreach($all as $key => $value)
						{
							$statusids .= $value['id'].","; 
						}
						$statusids = rtrim($statusids,",");	
						if($statusids)
						{
							$media = $this ->getMedia($statusids);
						}
					}
					else
					{
						$this->errorOutput(NO_AUTHORITY);	
					} 	
				}
				
				//任何人不可访问
				if($visit_user_info == 2)
				{
					$this->errorOutput(NO_AUTHORITY);	
				}			  			
			} 
		}

//		$all = $this->getblog($u_id,1);
						
		//取得转发我的点滴信息					
		if(count($this->trans))
		{
			$alltran = $this->getblog($this->trans,2);
			$mediatran = $this ->getMedia($this->trans);
		}
		
		//博客用户信息和转发用户信息合并
		
		$this->setXmlNode('statuses','status');
		if($this->total)
		{
			$this->addItem($this->total);
		}
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
		
		$this->user_timeline();
		
	}
	
	
}
$out = new user_timeline();
$out->show();
?>
<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user_timeline.php 17941 2013-02-26 02:20:49Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require_once (ROOT_DIR . 'global.php');
class user_timeline extends appCommonFrm
{
	private $trans = array();
	private	$total= array();
	private $curl;
	function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . "lib/mblog.class.php");
		$this->obj = new mblog();
		include_once(ROOT_DIR . '/lib/class/member.class.php');
		$this->member = new member(); 
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
		$this->input['user_id'] = intval($this->input['user_id']) ? intval($this->input['user_id']) : $this->user['user_id'];
		
		//$userinfo['id'] = 1; 
		//error_reporting(0); 
		//$this->input['user_id'] =2; 
		//$this->input['gettoal']='gettotal';
		$u_id = intval($this->user['user_id']);
		
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
			if($this->input['user_id'] == $this->user['user_id'])
			{
				$all = $this->getblog($u_id,1);
				if(is_array($all))
				{
					foreach($all as $key => $value)
					{
						$statusids .= $value['id'].","; 
					}
					$statusids = rtrim($statusids,",");	
				}
				if($statusids)
				{
					$media = $this->obj->getMedia($statusids);
				}
			}
			else
			{
				/**
				 * 获取用户权限
				 */
				
				$authority = $this->member->get_authority($this->input['user_id']);
				
				//访问页面权限
				$visit_user_info = intval($authority[14]);
						
				//任何人可访问
				if($visit_user_info == 0)
				{
					$all = $this->getblog($u_id,1);
					if(is_array($all))
					{
						foreach($all as $key => $value)
						{
							$statusids .= $value['id'].","; 
						}
						$statusids = rtrim($statusids,",");	
						if($statusids)
						{
							$media = $this->obj->getMedia($statusids);
						}
					}
				}
				
				//关注的人可获取点滴信息
				if($visit_user_info == 1)
				{
					/**
					 * 获取用户和传入ID的关系
					 */
					 
					include_once(ROOT_DIR . '/lib/class/member.class.php');
					$this->member = new member(); 
					$relation = $this->member->show_relation($this->user['user_id'],$this->input['user_id']);
					
					//关注
					if($relation == 3 || $relation == 1)
					{
						$all = $this->getblog($u_id,1);
						if(is_array($all))
						{
							foreach($all as $key => $value)
							{
								$statusids .= $value['id'].","; 
							}
							$statusids = rtrim($statusids,",");	
							if($statusids)
							{
								$media = $this->obj->getMedia($statusids);
							}
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
		//取得转发我的点滴信息					
		if(count($this->trans))
		{
			$alltran = $this->getblog($this->trans,2);
			$mediatran = $this->obj->getMedia($this->trans);
		}
		//博客用户信息和转发用户信息合并
		
		$this->setXmlNode('statuses','status');
		if($this->total)
		{
			$this->addItem($this->total);
		}
		if(is_array($all))
		{
			foreach ($all as $key =>$values)
			{
				$alltran[$values['reply_status_id']]['medias'] = $mediatran[$values['reply_status_id']];
				$values['retweeted_status'] = $alltran[$values['reply_status_id']];
				$values['medias'] = $media[$values['id']];
				$this->addItem($values);
			}	
		}
		$this->output();
	}
	
	public function getblog($ids,$flag)
	{
		include('getblog.php');
		return $all;
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
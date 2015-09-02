<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: destroy.php 6098 2012-03-16 01:27:22Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class destroy extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
			
	}
	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 删除点滴信息
	*/
	public function destroy() 
	{	
		//$this->input['id'] = '54114,54117';
		//$userinfo['id'] =3 ;
		//$userinfo['is_admin']=1;
		//验证用户是否登录
		require_once(ROOT_DIR.'lib/user/user.class.php');
		$this->user = new user();
		
		$userinfo = $this->user->verify_credentials(); 
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		//获取用户参数
		if(!$this->input['id'])
		{
			return ;
		}
		//一次性最多批量删除二十个点滴信息
				
		$sta = explode(',',urldecode($this->input['id']));
	
		if(count($sta)>20)
		{
			return ;
		}
		//判断是否是管理员
		if(!$userinfo['is_admin'])
		{
			$extion = " and member_id=".$userinfo['id'];
		}
		//删除单条记录时候返回本条记录信息
		if(count($sta)==1)
		{
			//查询出要删除的信息
			//$sql = "SELECT sta.* , mea.source FROM ".DB_PREFIX."status sta  LEFT JOIN ".DB_PREFIX."media mea ON sta.id = mea.status_id ORDER BY sta.id DESC  limit $offset , $count";
			$sql = "SELECT sta.* , exl.transmit_count,exl.reply_count,exl.comment_count FROM ".DB_PREFIX.
			"status sta  LEFT JOIN ".DB_PREFIX."status_extra exl ON sta.id = exl.status_id where sta.id=".$this->input['id'].$extion;
			$row = $this->db->query_first($sql);

			if(!$row)
			{
				$this -> errorOutput(DELETE_FALES);
			}
			$members = $this->user->getUserById($row['member_id']);
			$members = $members[0];
			$last_status_id = $members['last_status_id'];
			$member_id = $members['member_id'];			
			//对应user的键值
			foreach ($members as $key => $values)
			{
				$mem[$values['id']] = $values;
			}
		}
		else
		{
			if(!$userinfo['is_admin'])
			{
				$sql = "SELECT id  FROM ".DB_PREFIX."status where id in(".$this->input['id'].")".$extion;
				$result = $this->db->query($sql);
				while($r = $this->db->fetch_array($result))
				{		
					$row[]=$r['id'];
				}
				if(!$row)
				{
					$row = array();
				}
				//求补集
				$dsta = array_diff($sta,$row);
				if(count($dsta))
				{
					$this -> errorOutput(DELETE_FALES);
				}	
			}
		}
		
		/**
		 * 添加微博删除积分(消耗)
		 */
		$this->user->add_credit_log(DELETE_STATUS);
		
				
		//删除push表中的数据
		include_once(ROOT_DIR . 'lib/class/push.class.php');
		$push = new push();
		$stat = $push->delete($this->input['id']);
		
		//删除各表中的数据
		if($stat)
		{
			$sql = "delete ".DB_PREFIX."status ,".DB_PREFIX."status_extra , ".DB_PREFIX."status_comments ,
			".DB_PREFIX."status_member , ".DB_PREFIX."status_topic , ".DB_PREFIX."status_favorites from ".DB_PREFIX."status
			left join ".DB_PREFIX."status_extra on ".DB_PREFIX."status.id = ".DB_PREFIX."status_extra.status_id 
			left join ".DB_PREFIX."status_comments on ".DB_PREFIX."status.id = ".DB_PREFIX."status_comments.status_id 
			left join ".DB_PREFIX."status_member on ".DB_PREFIX."status.id = ".DB_PREFIX."status_member.status_id
			left join ".DB_PREFIX."status_topic on ".DB_PREFIX."status.id = ".DB_PREFIX."status_topic.status_id
			left join ".DB_PREFIX."status_favorites on ".DB_PREFIX."status.id = ".DB_PREFIX."status_favorites.status_id  
			where ".DB_PREFIX."status.id in(".urldecode($this->input['id']).")";
			$reply = $this->db->query($sql);
			
			if($last_status_id == $this->input['id'])
			{
				$sql = "SELECT id FROM ".DB_PREFIX. "status where member_id=" . $member_id . " ORDER BY create_at DESC";
				$first = $this->db->query_first($sql);
				if(!empty($first))
				{
					$this->user->update_last_status($member_id,$first['id']);
				}
			}
		}
		//删除所有的转发信息
		if($reply)
		{
			$sql = "delete  from ".DB_PREFIX."status where reply_status_id in(".urldecode($this->input['id']).")";
			$rowd = $this->db->query($sql);
		}
		//如果删除成功则返回删除的数据
		if($rowd&&count($sta)==1)
		{
			//博客信息和用户信息合并
			$this->setXmlNode('statuses','status');
			$row['user'] = $mem[$row['member_id']];
			$this->addItem($row);
			
			/**
			 * 同步删除点滴
			 */
						
			$bind_info = $this->user->get_bind_info(); //获取绑定信息
			
			$bind_info = $bind_info[0];
			
			if($bind_info['state'] == 1 && $bind_info['last_key'])
			{
				$status_id = urldecode($this->input['id']);				
				$is_syn = $this->check_syn_status($status_id , 1);
							
				//该条点滴是同步发送的点滴
				if($is_syn)
				{					
					include_once (ROOT_PATH . 'lib/class/weibooauth.class.php');
					$last_key = unserialize($bind_info['last_key']);
					$oauth = new WeiboClient( WB_AKEY , WB_SKEY , $last_key['oauth_token'] , $last_key['oauth_token_secret'] );
					//$oauth = new WeiboOAuth( WB_AKEY , WB_SKEY , 'e9b1d743a687550cec725e65fd204b6c' , '119934aabf1632d426533505c0f02e70' );								
					//同步删除点滴
				
					$content = $oauth->destroy($is_syn['syn_id']);
				}
			}
			$this->output();
		}
		elseif($rowd)
		{
			$this->setXmlNode('statuses','status');
			$this->addItem('sucess');
			$this->output();
		}
		else 
		{
			$this -> errorOutput(DELETE_FALES);
		}			
	}
	
	
	/**
	 * 检测这条点滴是否是同步发送的点滴
	 */
	public function check_syn_status($status_id , $type)
	{		
		$status_id = intval($status_id);
		$type = intval($type);
		$sql = "SELECT * FROM " . DB_PREFIX . "member_syn_relation WHERE status_id = " . $status_id . " AND type = " . $type;
		$r = $this->db->query_first($sql);
		if(count($r) == 0)
		{
			return 0;
		}
		else
		{
			return $r;
		}
	}	
}
$out = new destroy();
$out->destroy();
?>
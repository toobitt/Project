<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','share_get_access_plat');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
class get_commentsAPI extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->obj = new share();
		include_once(CUR_CONF_PATH . 'lib/public.class.php');
		$this->pub = new publicapi();
		include_once(CUR_CONF_PATH . 'lib/get_user.class.php');
		$this->get_user = new get_user();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_comments()
	{
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$token = urldecode($this->input['access_plat_token']);
		$weibo_id = urldecode($this->input['weibo_id']);
		$since_id  = urldecode($this->input['since_id']);
		$page  = urldecode($this->input['page']);
		$count  = urldecode($this->input['count']);
		$since_time  = urldecode($this->input['since_time']);
		//先判断token有没有过期
		if($token)
		{
			$checktoken = $this->pub->share_check_token($token);
		}
		else
		{
			$checktoken['msg'] = false;
		}
		
		if($checktoken['msg'] !== 'new' && $checktoken['msg'])
		{
			$platdata = $this->obj->get_by_app_plat($checktoken['data']['appid'],$checktoken['data']['platId']);
			include_once(CUR_CONF_PATH . 'lib/'.$this->settings['share_plat'][$platdata['type']]['name'].'_oauth.php');
			$action = $this->settings['share_plat'][$platdata['type']]['name'].'_get_comments';
			$ret = $this->$action($checktoken,$weibo_id,$since_id,$since_time,$page,$count);
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			include(CUR_CONF_PATH.'lib/oauthlogin.class.php');
			$oauthlogin = new oauthlogin();
			if($appid && $platid)
			{
				$ret = $oauthlogin->oauthlogin($appid,$platid,$token);
			}
			else
			{
				$this->errorOutput('NO_APPID_PLATID');
			}
			$ret['error'] = 1;
			$this->addItem($ret);
			$this->output();			
		}
	}
	
	public function sinaweibo_get_comments($checktoken,$weibo_id,$since_id='',$since_time='',$page='',$count='')
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			$result = $c->get_comments( $this->settings['share_plat'][$checktoken['data']['type']]['comment_showurl'],$weibo_id,$since_id,$since_time,$page,$count,'',true);
//			print_r($result);exit;
			$ret = array();
			if(empty($result['error']) && $result['comments'])
			{
				foreach($result['comments'] as $k=>$v)
				{
					$ret[$k]['id'] = $v['id'];
					$ret[$k]['mid'] = $v['mid'];
					$ret[$k]['created_at'] = empty($v['created_at'])?'':strtotime($v['created_at']);
					$ret[$k]['text'] = empty($v['text'])?'':$v['text'];
					if(!empty($v['user']))
					{
						$ret[$k]['user']['id'] = empty($v['user']['id'])?'':$v['user']['id'];
						$ret[$k]['user']['screen_name'] = empty($v['user']['screen_name'])?'':$v['user']['screen_name'];
						$ret[$k]['user']['name'] = empty($v['user']['name'])?'':$v['user']['name'];
						$ret[$k]['user']['location'] = empty($v['user']['location'])?'':$v['user']['location'];
						$ret[$k]['user']['avatar'] = empty($v['user']['avatar_large'])?'':$v['user']['avatar_large'];
						$ret[$k]['user']['followers_count'] = empty($v['user']['followers_count'])?'':$v['user']['followers_count'];
						$ret[$k]['user']['friends_count'] = empty($v['user']['friends_count'])?'':$v['user']['friends_count'];
						$ret[$k]['user']['statuses_count'] = empty($v['user']['statuses_count'])?'':$v['user']['statuses_count'];
					}
					if(!empty($v['status']))
					{
						$ret[$k]['status']['id'] = empty($v['status']['id'])?'':$v['status']['id'];
						$ret[$k]['status']['mid'] = empty($v['status']['mid'])?'':$v['status']['mid'];
						$ret[$k]['status']['text'] = empty($v['status']['text'])?'':$v['status']['text'];
						if(!empty($v['status']['user']))
						{
							$ret[$k]['status']['user']['id'] = empty($v['status']['user']['id'])?'':$v['status']['user']['id'];
							$ret[$k]['status']['user']['screen_name'] = empty($v['status']['user']['screen_name'])?'':$v['status']['user']['screen_name'];
							$ret[$k]['status']['user']['name'] = empty($v['status']['user']['name'])?'':$v['status']['user']['name'];
							$ret[$k]['status']['user']['location'] = empty($v['status']['user']['location'])?'':$v['status']['user']['location'];
							$ret[$k]['status']['user']['description'] = empty($v['status']['user']['description'])?'':$v['status']['user']['description'];
							$ret[$k]['status']['user']['avatar'] = empty($v['status']['user']['avatar_large'])?'':$v['status']['user']['avatar_large'];
							$ret[$k]['status']['user']['followers_count'] = empty($v['status']['user']['followers_count'])?'':$v['status']['user']['followers_count'];
							$ret[$k]['status']['user']['friends_count'] = empty($v['status']['user']['friends_count'])?'':$v['status']['user']['friends_count'];
							$ret[$k]['status']['user']['statuses_count'] = empty($v['status']['user']['statuses_count'])?'':$v['status']['user']['statuses_count'];
						}
						$ret[$k]['status']['reposts_count'] = empty($v['status']['reposts_count'])?0:$v['status']['reposts_count'];
						$ret[$k]['status']['comments_count'] = empty($v['status']['comments_count'])?0:$v['status']['comments_count'];
						$ret[$k]['status']['attitudes_count'] = empty($v['status']['attitudes_count'])?0:$v['status']['attitudes_count'];
						$ret[$k]['status']['created_at'] = empty($v['status']['created_at'])?'':strtotime($v['status']['created_at']);
					}
				}
			}
			else
			{
				$ret['error'] = empty($result['error'])?'emtpy':$result['error'];
			}
//			print_r($ret);exit;
			return $ret;
		}
		else
		{
			return "NO_TOKEN_DATA";
		}
	}
	
	public function txweibo_get_comments($checktoken,$weibo_id,$since_id='',$since_time='',$page='',$count='')
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			$result = $c->get_comments( $this->settings['share_plat'][$checktoken['data']['type']]['comment_showurl'],$weibo_id,$since_id,$since_time,$page,$count,$checktoken['data']['openid'],'tx');
//			print_r($result);exit;
			$ret = array();
			if(empty($result['data']['errcode']))
			{
				if(!empty($result['data']['info']))
				{
					foreach($result['data']['info'] as $k=>$v)
					{
						$ret[$k]['id'] = empty($v['id'])?'':$v['id'];
						$ret[$k]['user']['screen_name'] = empty($v['nick'])?'':$v['nick'];
						$ret[$k]['user']['name'] = empty($v['name'])?'':$v['name'];
						$ret[$k]['text'] = empty($v['text'])?'':$v['text'];
						$ret[$k]['origtext'] = empty($v['origtext'])?'':$v['origtext'];
						$ret[$k]['user']['avatar'] = empty($v['head'])?'':($v['head'].'/180');
						$ret[$k]['user']['fromurl'] = empty($v['fromurl'])?'':$v['fromurl'];
						$ret[$k]['user']['location'] = empty($v['location'])?'':$v['location'];
						$ret[$k]['image'] = empty($v['image'])?'':$v['image'];
						$ret[$k]['video'] = empty($v['video'])?'':$v['video'];
						$ret[$k]['created_at'] = empty($v['timestamp'])?'':$v['timestamp'];
						$ret[$k]['picsize'] = array('thumbnail'=>'120','bmiddle'=>'400','large'=>'2000');
						if(!empty($v['source']))
						{
							$ret[$k]['status']['id'] = empty($v['source']['id'])?'':$v['source']['id'];
							$ret[$k]['status']['user']['screen_name'] = empty($v['source']['nick'])?'':$v['source']['nick'];
							$ret[$k]['status']['user']['name'] = empty($v['source']['name'])?'':$v['source']['name'];
							$ret[$k]['status']['text'] = empty($v['source']['text'])?'':$v['source']['text'];
							$ret[$k]['status']['origtext'] = empty($v['source']['origtext'])?'':$v['source']['origtext'];
							$ret[$k]['status']['user']['avatar'] = empty($v['source']['head'])?'':($v['source']['head'].'/180');
							$ret[$k]['status']['user']['fromurl'] = empty($v['source']['fromurl'])?'':$v['source']['fromurl'];
							$ret[$k]['status']['user']['location'] = empty($v['source']['location'])?'':$v['source']['location'];
							$ret[$k]['status']['image'] = empty($v['source']['image'])?'':$v['source']['image'];
							$ret[$k]['status']['video'] = empty($v['source']['video'])?'':$v['source']['video'];
							$ret[$k]['status']['created_at'] = empty($v['source']['timestamp'])?'':$v['source']['timestamp'];
							$ret[$k]['status']['picsize'] = array('thumbnail'=>'120','bmiddle'=>'400','large'=>'2000');
						}
					}
				}
			}
			else
			{
				$ret['error'] = empty($result['errcode'])?'emtpy':$result['errcode'];
			}
//			print_r($ret);exit;
			return $ret;
		}
		else
		{
			return "无可用分享信息，请重新登录";
		}
	
	}
	
	public function renren_user_timeline($tokendata,$platdata)
	{
		if($tokendata)
		{
			$access_token = $tokendata['access_token'];
			$c = new ClientV2( $tokendata['akey'],$tokendata['skey'],$tokendata['oauthurl'],$tokendata['accessurl'],$tokendata['callback'],$tokendata['userurl'],$tokendata['response_type'],$access_token['access_token'] );
			$result = $c->weibo_get_user_timeline( $tokendata['user_timelineurl']);
			
			//				print_r($userdata);exit;
			return $result;
		}
		else
		{
			return "无可用分享信息，请重新登录";
		}
	}
	
	public function douban_user_timeline($tokendata,$platdata)
	{
		return $this->sinaweibo_toshare($tokendata,$platdata);
	}
	
	public function wangyi_user_timeline($tokendata,$platdata)
	{
		return $this->sinaweibo_toshare($tokendata,$platdata);
	}
	
	public function other_get_comments($checktoken,$weibo_id,$since_id='',$since_time='',$page='',$count='')
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			$result = $c->get_comments( $checktoken['data']['platdata']['comment_showurl'],$weibo_id,$since_id,$since_time,$page,$count,'',true);
//			print_r($result);exit;
			$ret = array();
			if(empty($result['error']) && $result['comments'])
			{
				foreach($result['comments'] as $k=>$v)
				{
					$ret[$k]['id'] = $v['id'];
					$ret[$k]['mid'] = $v['mid'];
					$ret[$k]['created_at'] = empty($v['created_at'])?'':strtotime($v['created_at']);
					$ret[$k]['text'] = empty($v['text'])?'':$v['text'];
					if(!empty($v['user']))
					{
						$ret[$k]['user']['id'] = empty($v['user']['id'])?'':$v['user']['id'];
						$ret[$k]['user']['screen_name'] = empty($v['user']['screen_name'])?'':$v['user']['screen_name'];
						$ret[$k]['user']['name'] = empty($v['user']['name'])?'':$v['user']['name'];
						$ret[$k]['user']['location'] = empty($v['user']['location'])?'':$v['user']['location'];
						$ret[$k]['user']['avatar'] = empty($v['user']['avatar_large'])?'':$v['user']['avatar_large'];
						$ret[$k]['user']['followers_count'] = empty($v['user']['followers_count'])?'':$v['user']['followers_count'];
						$ret[$k]['user']['friends_count'] = empty($v['user']['friends_count'])?'':$v['user']['friends_count'];
						$ret[$k]['user']['statuses_count'] = empty($v['user']['statuses_count'])?'':$v['user']['statuses_count'];
					}
					if(!empty($v['status']))
					{
						$ret[$k]['status']['id'] = empty($v['status']['id'])?'':$v['status']['id'];
						$ret[$k]['status']['mid'] = empty($v['status']['mid'])?'':$v['status']['mid'];
						$ret[$k]['status']['text'] = empty($v['status']['text'])?'':$v['status']['text'];
						if(!empty($v['status']['user']))
						{
							$ret[$k]['status']['user']['id'] = empty($v['status']['user']['id'])?'':$v['status']['user']['id'];
							$ret[$k]['status']['user']['screen_name'] = empty($v['status']['user']['screen_name'])?'':$v['status']['user']['screen_name'];
							$ret[$k]['status']['user']['name'] = empty($v['status']['user']['name'])?'':$v['status']['user']['name'];
							$ret[$k]['status']['user']['location'] = empty($v['status']['user']['location'])?'':$v['status']['user']['location'];
							$ret[$k]['status']['user']['description'] = empty($v['status']['user']['description'])?'':$v['status']['user']['description'];
							$ret[$k]['status']['user']['avatar'] = empty($v['status']['user']['avatar_large'])?'':$v['status']['user']['avatar_large'];
							$ret[$k]['status']['user']['followers_count'] = empty($v['status']['user']['followers_count'])?'':$v['status']['user']['followers_count'];
							$ret[$k]['status']['user']['friends_count'] = empty($v['status']['user']['friends_count'])?'':$v['status']['user']['friends_count'];
							$ret[$k]['status']['user']['statuses_count'] = empty($v['status']['user']['statuses_count'])?'':$v['status']['user']['statuses_count'];
						}
						$ret[$k]['status']['reposts_count'] = empty($v['status']['reposts_count'])?0:$v['status']['reposts_count'];
						$ret[$k]['status']['comments_count'] = empty($v['status']['comments_count'])?0:$v['status']['comments_count'];
						$ret[$k]['status']['attitudes_count'] = empty($v['status']['attitudes_count'])?0:$v['status']['attitudes_count'];
						$ret[$k]['status']['created_at'] = empty($v['status']['created_at'])?'':strtotime($v['status']['created_at']);
					}
				}
			}
			else
			{
				$ret['error'] = empty($result['error'])?'emtpy':$result['error'];
			}
//			print_r($ret);exit;
			return $ret;
		}
		else
		{
			return "NO_TOKEN_DATA";
		}
	}
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright 	ho	gesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new get_commentsAPI();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'get_comments';
}
$out->$action();
?>

<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','share_get_access_plat');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
class get_detailAPI extends adminBase
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
	
	public function get_detail()
	{
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$token = urldecode($this->input['access_plat_token']);
		$weibo_id = urldecode($this->input['weibo_id']);
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
			include_once(CUR_CONF_PATH . 'lib/'.$this->settings['share_plat'][$checktoken['data']['type']]['name'].'_oauth.php');
			$action = $this->settings['share_plat'][$platdata['type']]['name'].'_get_detail';
			$ret = $this->$action($checktoken,$weibo_id);
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
	
	public function sinaweibo_get_detail($checktoken,$weibo_id)
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			$v = $c->detail_show( $this->settings['share_plat'][$checktoken['data']['type']]['detailurl'],$weibo_id,'',true);
//			print_r($v);exit;
			$ret = array();
			if(empty($v['error']))
			{
				$ret['id'] = empty($v['id'])?'':$v['id'];
				$ret['text'] = empty($v['text'])?'':$v['text'];
				$ret['screen_name'] = empty($v['user']['screen_name'])?'':$v['user']['screen_name'];
				$ret['name'] = empty($v['user']['name'])?'':$v['user']['name'];
				$ret['avatar'] = empty($v['user']['avatar_large'])?'':$v['user']['avatar_large'];
				$ret['created_at'] = empty($v['created_at'])?'':strtotime($v['created_at']);
				$ret['original_pic'] = empty($v['original_pic'])?'':$v['original_pic'];
				$ret['from'] = empty($v['source'])?'':$v['source'];
				$ret['reposts_count'] = empty($v['reposts_count'])?0:$v['reposts_count'];
				$ret['comments_count'] = empty($v['comments_count'])?0:$v['comments_count'];
				$ret['from'] = empty($v['source'])?'':$v['source'];
				$ret['picsize'] = array('thumbnail'=>'thumbnail','bmiddle'=>'bmiddle','large'=>'large');
				if(!empty($v['retweeted_status']))
				{
					$ret['retweeted_status']['id'] = empty($v['retweeted_status']['id'])?'':$v['retweeted_status']['id'];
					$ret['retweeted_status']['created_at'] = empty($v['retweeted_status']['created_at'])?'':strtotime($v['retweeted_status']['created_at']);
					$ret['retweeted_status']['text'] = empty($v['retweeted_status']['text'])?'':$v['retweeted_status']['text'];
					$ret['retweeted_status']['original_pic'] = empty($v['retweeted_status']['original_pic'])?'':$v['retweeted_status']['original_pic'];
					$ret['retweeted_status']['screen_name'] = empty($v['retweeted_status']['user']['screen_name'])?'':$v['retweeted_status']['user']['screen_name'];
					$ret['retweeted_status']['avatar'] = empty($v['retweeted_status']['user']['avatar_large'])?'':$v['retweeted_status']['user']['avatar_large'];
					$ret['retweeted_status']['name'] = empty($v['retweeted_status']['user']['name'])?'':$v['retweeted_status']['user']['name'];
					$ret['retweeted_status']['reposts_count'] = empty($v['retweeted_status']['reposts_count'])?0:$v['retweeted_status']['reposts_count'];
					$ret['retweeted_status']['comments_count'] = empty($v['retweeted_status']['comments_count'])?0:$v['retweeted_status']['comments_count'];
				}
			}
			else
			{
				$ret['error'] = empty($v['error'])?'empty':$v['error'];
			}
//			print_r($ret);exit;
			return $ret;
		}
		else
		{
			return "NO_TOKEN_DATA";
		}
	}
	
	public function txweibo_get_detail($checktoken,$weibo_id)
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			$result = $c->detail_show( $this->settings['share_plat'][$checktoken['data']['type']]['detailurl'],$weibo_id,$checktoken['data']['openid'],'tx');
//			print_r($result);exit;
			$ret = array();
			if(empty($result['errcode']))
			{
				$v = $result['data'];
				$ret['id'] = empty($v['id'])?'':$v['id'];
				$ret['from'] = empty($v['from'])?'':$v['from'];
				$ret['fromurl'] = empty($v['fromurl'])?'':$v['fromurl'];
				$ret['text'] = empty($v['text'])?'':$v['text'];
				$ret['origtext'] = empty($v['origtext'])?'':$v['origtext'];
				$ret['screen_name'] = empty($v['nick'])?'':$v['nick'];
				$ret['name'] = empty($v['name'])?'':$v['name'];
				$ret['video'] = empty($v['video'])?'':$v['video'];
				$ret['created_at'] = empty($v['timestamp'])?'':$v['timestamp'];
				$ret['original_pic'] = empty($v['image'])?'':$v['image'];
				$ret['avatar'] = empty($v['head'])?'':($v['head'].'/180');
				$ret['reposts_count'] = empty($v['count'])?0:$v['count'];
				$ret['comments_count'] = empty($v['mcount'])?0:$v['mcount'];
				$ret['picsize'] = array('thumbnail'=>'120','bmiddle'=>'400','large'=>'2000');
				if(!empty($v['source']))
				{
					$ret['retweeted_status']['id'] = empty($v['source']['id'])?'':$v['source']['id'];
					$ret['retweeted_status']['text'] = empty($v['source']['text'])?'':$v['source']['text'];
					$ret['retweeted_status']['origtext'] = empty($v['source']['origtext'])?'':$v['source']['origtext'];
					$ret['retweeted_status']['video'] = empty($v['source']['video'])?'':$v['source']['video'];
					$ret['retweeted_status']['name'] = empty($v['source']['name'])?'':$v['source']['name'];
					$ret['retweeted_status']['screen_name'] = empty($v['source']['nick'])?'':$v['source']['nick'];
					$ret['retweeted_status']['created_at'] = empty($v['source']['timestamp'])?'':$v['source']['timestamp'];
					$ret['retweeted_status']['avatar'] = empty($v['source']['head'])?'':($v['source']['head'].'/180');
					$ret['retweeted_status']['original_pic'] = empty($v['source']['image'])?'':($v['source']['image']);
					$ret['retweeted_status']['reposts_count'] = empty($v['source']['count'])?0:$v['source']['count'];
					$ret['retweeted_status']['comments_count'] = empty($v['source']['mcount'])?0:$v['source']['mcount'];
				}
			}
			else
			{
				$ret['error'] = empty($result['errcode'])?'empty':$result['errcode'];
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
	
	
	public function other_get_detail($checktoken,$weibo_id)
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			$v = $c->detail_show( $checktoken['data']['platdata']['detailurl'],$weibo_id,'',true);
//			print_r($v);exit;
			$ret = array();
			if(empty($v['error']))
			{
				$ret['id'] = empty($v['id'])?'':$v['id'];
				$ret['text'] = empty($v['text'])?'':$v['text'];
				$ret['screen_name'] = empty($v['user']['screen_name'])?'':$v['user']['screen_name'];
				$ret['name'] = empty($v['user']['name'])?'':$v['user']['name'];
				$ret['avatar'] = empty($v['user']['avatar_large'])?'':$v['user']['avatar_large'];
				$ret['created_at'] = empty($v['created_at'])?'':strtotime($v['created_at']);
				$ret['original_pic'] = empty($v['original_pic'])?'':$v['original_pic'];
				$ret['from'] = empty($v['source'])?'':$v['source'];
				$ret['reposts_count'] = empty($v['reposts_count'])?0:$v['reposts_count'];
				$ret['comments_count'] = empty($v['comments_count'])?0:$v['comments_count'];
				$ret['from'] = empty($v['source'])?'':$v['source'];
				$ret['picsize'] = array('thumbnail'=>'thumbnail','bmiddle'=>'bmiddle','large'=>'large');
				if(!empty($v['retweeted_status']))
				{
					$ret['retweeted_status']['id'] = empty($v['retweeted_status']['id'])?'':$v['retweeted_status']['id'];
					$ret['retweeted_status']['created_at'] = empty($v['retweeted_status']['created_at'])?'':strtotime($v['retweeted_status']['created_at']);
					$ret['retweeted_status']['text'] = empty($v['retweeted_status']['text'])?'':$v['retweeted_status']['text'];
					$ret['retweeted_status']['original_pic'] = empty($v['retweeted_status']['original_pic'])?'':$v['retweeted_status']['original_pic'];
					$ret['retweeted_status']['screen_name'] = empty($v['retweeted_status']['user']['screen_name'])?'':$v['retweeted_status']['user']['screen_name'];
					$ret['retweeted_status']['avatar'] = empty($v['retweeted_status']['user']['avatar_large'])?'':$v['retweeted_status']['user']['avatar_large'];
					$ret['retweeted_status']['name'] = empty($v['retweeted_status']['user']['name'])?'':$v['retweeted_status']['user']['name'];
					$ret['retweeted_status']['reposts_count'] = empty($v['retweeted_status']['reposts_count'])?0:$v['retweeted_status']['reposts_count'];
					$ret['retweeted_status']['comments_count'] = empty($v['retweeted_status']['comments_count'])?0:$v['retweeted_status']['comments_count'];
				}
			}
			else
			{
				$ret['error'] = empty($v['error'])?'empty':$v['error'];
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

$out = new get_detailAPI();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'get_detail';
}
$out->$action();
?>

<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','share_plat_type');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
class get_user_timelineAPI extends adminBase
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
	
	public function get_user_timeline()
	{
	    // $this->input = array (
  // 'format' => 'json',
  // 'appid' => '55',
  // 'id' => '88',
  // 'uid' => '',
  // 'name' => '易建联',
  // 'access_plat_token' => '9224ec0aadf60c124d3007ec352a3cac',
  // 'since_id' => '0',
  // 'page' => '1',
  // 'count' => '50',
  // 'lpip' => '',
  // 'm2o_ckey' => 'OjEN52E9LieIe9yx8mfDZEpDlUnxuya9',
  // 'access_token' => 'b786098710ac38134ec0249ee8ff5b6b',
// );
	   // file_put_contents('./cache/111.txt',var_export($this->input,1));
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$token = urldecode($this->input['access_plat_token']);
		$uid = urldecode($this->input['uid']);
		$name = urldecode($this->input['name']);
		$since_id  = urldecode($this->input['since_id']);
		$page  = urldecode($this->input['page']); //如果是tx  则表示是pagetime
		$count  = urldecode($this->input['count']);
		//先判断token有没有过期
		if($token)
		{
			$checktoken = $this->pub->share_check_token($token);
		}
		else
		{
			$checktoken['msg'] = false;
		}
        // print_r($checktoken);
		
		if($checktoken['msg'] !== 'new' && $checktoken['msg'])
		{
			$platdata = $this->obj->get_by_app_plat($checktoken['data']['appid'],$checktoken['data']['platId']);
			include_once(CUR_CONF_PATH . 'lib/'.$this->settings['share_plat'][$checktoken['data']['type']]['name'].'_oauth.php');
			$action = $this->settings['share_plat'][$platdata['type']]['name'].'_user_timeline';
			$ret = $this->$action($checktoken,$uid,$name,$since_id,$page,$count);
            // file_put_contents('./cache/222.txt',var_export($ret,1));
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
	
	public function sinaweibo_user_timeline($checktoken,$uid,$name,$since_id,$page,$count)
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			$result = $c->get_user_timeline( $this->settings['share_plat'][$checktoken['data']['type']]['user_timelineurl'],$uid,$name,$since_id,$page,$count,'',true);
            // print_r($result);
			$ret = array();
			if(!empty($result['statuses']))
			{
				foreach($result['statuses'] as $k=>$v)
				{
					$ret[$k]['id'] = empty($v['id'])?'':$v['id'];
					$ret[$k]['text'] = empty($v['text'])?'':$v['text'];
					$ret[$k]['screen_name'] = empty($v['user']['screen_name'])?'':$v['user']['screen_name'];
					$ret[$k]['name'] = empty($v['user']['name'])?'':$v['user']['name'];
					$ret[$k]['avatar'] = empty($v['user']['avatar_large'])?'':$v['user']['avatar_large'];
					$ret[$k]['created_at'] = empty($v['created_at'])?'':strtotime($v['created_at']);
					$ret[$k]['original_pic'] = empty($v['original_pic'])?'':$v['original_pic'];
					$ret[$k]['from'] = empty($v['source'])?'':$v['source'];
					$ret[$k]['reposts_count'] = empty($v['reposts_count'])?0:$v['reposts_count'];
					$ret[$k]['comments_count'] = empty($v['comments_count'])?0:$v['comments_count'];
					$ret[$k]['from'] = empty($v['source'])?'':$v['source'];
					$ret[$k]['picsize'] = array('thumbnail'=>'thumbnail','bmiddle'=>'bmiddle','large'=>'large');
					if(!empty($v['retweeted_status']))
					{
						$ret[$k]['retweeted_status']['id'] = empty($v['retweeted_status']['id'])?'':$v['retweeted_status']['id'];
						$ret[$k]['retweeted_status']['created_at'] = empty($v['retweeted_status']['created_at'])?'':strtotime($v['retweeted_status']['created_at']);
						$ret[$k]['retweeted_status']['text'] = empty($v['retweeted_status']['text'])?'':$v['retweeted_status']['text'];
						$ret[$k]['retweeted_status']['original_pic'] = empty($v['retweeted_status']['original_pic'])?'':$v['retweeted_status']['original_pic'];
						$ret[$k]['retweeted_status']['screen_name'] = empty($v['retweeted_status']['user']['screen_name'])?'':$v['retweeted_status']['user']['screen_name'];
						$ret[$k]['retweeted_status']['avatar'] = empty($v['retweeted_status']['user']['avatar_large'])?'':$v['retweeted_status']['user']['avatar_large'];
						$ret[$k]['retweeted_status']['name'] = empty($v['retweeted_status']['user']['name'])?'':$v['retweeted_status']['user']['name'];
						$ret[$k]['retweeted_status']['reposts_count'] = empty($v['retweeted_status']['reposts_count'])?0:$v['retweeted_status']['reposts_count'];
						$ret[$k]['retweeted_status']['comments_count'] = empty($v['retweeted_status']['comments_count'])?0:$v['retweeted_status']['comments_count'];
					}
				}
			}
			else
			{
				$ret['error'] = empty($result['error'])?"empty":$result['error'];
			}
//			print_r($ret);exit;
			return $ret;
		}
		else
		{
			return "NO_TOKEN_DATA";
		}
	}
	
	public function txweibo_user_timeline($checktoken,$uid,$name,$since_id,$page,$count)
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			$result = $c->get_user_timeline( $this->settings['share_plat'][$checktoken['data']['type']]['user_timelineurl'],$uid,$name,$since_id,$page,$count,$checktoken['data']['access_token']['openid'],'tx');
			$ret = array();
			if(!empty($result['data']['info']))
			{
				foreach($result['data']['info'] as $kk=>$vv)
				{
					$ids[] = $vv['id'];
				}
				
				if(!in_array($since_id,$ids))
				{
					foreach($result['data']['info'] as $k=>$v)
					{
						/*
						if($v['id']<=$since_id)
						{
							continue;
						}
						*/
						$ret[$k]['id'] = empty($v['id'])?'':$v['id'];
						$ret[$k]['from'] = empty($v['from'])?'':$v['from'];
						$ret[$k]['fromurl'] = empty($v['fromurl'])?'':$v['fromurl'];
						$ret[$k]['text'] = empty($v['text'])?'':$v['text'];
						$ret[$k]['origtext'] = empty($v['origtext'])?'':$v['origtext'];
						$ret[$k]['screen_name'] = empty($v['nick'])?'':$v['nick'];
						$ret[$k]['name'] = empty($v['name'])?'':$v['name'];
						$ret[$k]['video'] = empty($v['video'])?'':$v['video'];
						$ret[$k]['created_at'] = empty($v['timestamp'])?'':$v['timestamp'];
						$ret[$k]['original_pic'] = empty($v['image'])?'':$v['image'];
						$ret[$k]['avatar'] = empty($v['head'])?'':($v['head'].'/180');
						$ret[$k]['reposts_count'] = empty($v['count'])?0:$v['count'];
						$ret[$k]['comments_count'] = empty($v['mcount'])?0:$v['mcount'];
						$ret[$k]['picsize'] = array('thumbnail'=>'120','bmiddle'=>'400','large'=>'2000');
						if(!empty($v['source']))
						{
							$ret[$k]['retweeted_status']['id'] = empty($v['source']['id'])?'':$v['source']['id'];
							$ret[$k]['retweeted_status']['text'] = empty($v['source']['text'])?'':$v['source']['text'];
							$ret[$k]['retweeted_status']['origtext'] = empty($v['source']['origtext'])?'':$v['source']['origtext'];
							$ret[$k]['retweeted_status']['video'] = empty($v['source']['video'])?'':$v['source']['video'];
							$ret[$k]['retweeted_status']['name'] = empty($v['source']['name'])?'':$v['source']['name'];
							$ret[$k]['retweeted_status']['screen_name'] = empty($v['source']['nick'])?'':$v['source']['nick'];
							$ret[$k]['retweeted_status']['created_at'] = empty($v['source']['timestamp'])?'':$v['source']['timestamp'];
							$ret[$k]['retweeted_status']['avatar'] = empty($v['source']['head'])?'':($v['source']['head'].'/180');
							$ret[$k]['retweeted_status']['original_pic'] = empty($v['source']['image'])?'':($v['source']['image']);
							$ret[$k]['retweeted_status']['reposts_count'] = empty($v['source']['count'])?0:$v['source']['count'];
							$ret[$k]['retweeted_status']['comments_count'] = empty($v['source']['mcount'])?0:$v['source']['mcount'];
						}
					}
				}
				else
				{
					foreach($result['data']['info'] as $k=>$v)
					{
						if($v['id'] == $since_id)
						{
							break;
						}
						else 
						{
							$ret[$k]['id'] = empty($v['id'])?'':$v['id'];
							$ret[$k]['from'] = empty($v['from'])?'':$v['from'];
							$ret[$k]['fromurl'] = empty($v['fromurl'])?'':$v['fromurl'];
							$ret[$k]['text'] = empty($v['text'])?'':$v['text'];
							$ret[$k]['origtext'] = empty($v['origtext'])?'':$v['origtext'];
							$ret[$k]['screen_name'] = empty($v['nick'])?'':$v['nick'];
							$ret[$k]['name'] = empty($v['name'])?'':$v['name'];
							$ret[$k]['video'] = empty($v['video'])?'':$v['video'];
							$ret[$k]['created_at'] = empty($v['timestamp'])?'':$v['timestamp'];
							$ret[$k]['original_pic'] = empty($v['image'])?'':$v['image'];
							$ret[$k]['avatar'] = empty($v['head'])?'':($v['head'].'/180');
							$ret[$k]['reposts_count'] = empty($v['count'])?0:$v['count'];
							$ret[$k]['comments_count'] = empty($v['mcount'])?0:$v['mcount'];
							$ret[$k]['picsize'] = array('thumbnail'=>'120','bmiddle'=>'400','large'=>'2000');
							if(!empty($v['source']))
							{
								$ret[$k]['retweeted_status']['id'] = empty($v['source']['id'])?'':$v['source']['id'];
								$ret[$k]['retweeted_status']['text'] = empty($v['source']['text'])?'':$v['source']['text'];
								$ret[$k]['retweeted_status']['origtext'] = empty($v['source']['origtext'])?'':$v['source']['origtext'];
								$ret[$k]['retweeted_status']['video'] = empty($v['source']['video'])?'':$v['source']['video'];
								$ret[$k]['retweeted_status']['name'] = empty($v['source']['name'])?'':$v['source']['name'];
								$ret[$k]['retweeted_status']['screen_name'] = empty($v['source']['nick'])?'':$v['source']['nick'];
								$ret[$k]['retweeted_status']['created_at'] = empty($v['source']['timestamp'])?'':$v['source']['timestamp'];
								$ret[$k]['retweeted_status']['avatar'] = empty($v['source']['head'])?'':($v['source']['head'].'/180');
								$ret[$k]['retweeted_status']['original_pic'] = empty($v['source']['image'])?'':($v['source']['image']);
								$ret[$k]['retweeted_status']['reposts_count'] = empty($v['source']['count'])?0:$v['source']['count'];
								$ret[$k]['retweeted_status']['comments_count'] = empty($v['source']['mcount'])?0:$v['source']['mcount'];
							}							
						}					
					}
				}
			}
			else
			{
				$ret['error'] = empty($result['errcode'])?"empty":$result['errcode'];
			}
//			print_r($result);exit;
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
	
	public function other_user_timeline($checktoken,$uid,$name,$since_id,$page,$count)
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			$result = $c->get_user_timeline( $checktoken['data']['platdata']['user_timelineurl'],$uid,$name,$since_id,$page,$count,'',true);
			$ret = array();
			if(!empty($result['statuses']))
			{
				foreach($result['statuses'] as $k=>$v)
				{
					$ret[$k]['id'] = empty($v['id'])?'':$v['id'];
					$ret[$k]['text'] = empty($v['text'])?'':$v['text'];
					$ret[$k]['screen_name'] = empty($v['user']['screen_name'])?'':$v['user']['screen_name'];
					$ret[$k]['name'] = empty($v['user']['name'])?'':$v['user']['name'];
					$ret[$k]['avatar'] = empty($v['user']['avatar_large'])?'':$v['user']['avatar_large'];
					$ret[$k]['created_at'] = empty($v['created_at'])?'':strtotime($v['created_at']);
					$ret[$k]['original_pic'] = empty($v['original_pic'])?'':$v['original_pic'];
					$ret[$k]['from'] = empty($v['source'])?'':$v['source'];
					$ret[$k]['reposts_count'] = empty($v['reposts_count'])?0:$v['reposts_count'];
					$ret[$k]['comments_count'] = empty($v['comments_count'])?0:$v['comments_count'];
					$ret[$k]['from'] = empty($v['source'])?'':$v['source'];
					$ret[$k]['picsize'] = array('thumbnail'=>'thumbnail','bmiddle'=>'bmiddle','large'=>'large');
					if(!empty($v['retweeted_status']))
					{
						$ret[$k]['retweeted_status']['id'] = empty($v['retweeted_status']['id'])?'':$v['retweeted_status']['id'];
						$ret[$k]['retweeted_status']['created_at'] = empty($v['retweeted_status']['created_at'])?'':strtotime($v['retweeted_status']['created_at']);
						$ret[$k]['retweeted_status']['text'] = empty($v['retweeted_status']['text'])?'':$v['retweeted_status']['text'];
						$ret[$k]['retweeted_status']['original_pic'] = empty($v['retweeted_status']['original_pic'])?'':$v['retweeted_status']['original_pic'];
						$ret[$k]['retweeted_status']['screen_name'] = empty($v['retweeted_status']['user']['screen_name'])?'':$v['retweeted_status']['user']['screen_name'];
						$ret[$k]['retweeted_status']['avatar'] = empty($v['retweeted_status']['user']['avatar_large'])?'':$v['retweeted_status']['user']['avatar_large'];
						$ret[$k]['retweeted_status']['name'] = empty($v['retweeted_status']['user']['name'])?'':$v['retweeted_status']['user']['name'];
						$ret[$k]['retweeted_status']['reposts_count'] = empty($v['retweeted_status']['reposts_count'])?0:$v['retweeted_status']['reposts_count'];
						$ret[$k]['retweeted_status']['comments_count'] = empty($v['retweeted_status']['comments_count'])?0:$v['retweeted_status']['comments_count'];
					}
				}
			}
			else
			{
				$ret['error'] = empty($result['error'])?"empty":$result['error'];
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

$out = new get_user_timelineAPI();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'get_user_timeline';
}
$out->$action();
?>

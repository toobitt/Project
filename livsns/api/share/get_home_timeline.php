<?php
/**
 * 获取当前登录用户及其所关注用户的最新微博
 */
 
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','share_plat_type');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php"); 

class get_home_timeline_api extends adminBase {
	
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
	
	public function get_home_timeline()
	{
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$token = urldecode($this->input['access_plat_token']);
		$since_id  = urldecode($this->input['since_id']);
		$page  = urldecode($this->input['page']); //如果是tx  则表示是pagetime
		$count  = urldecode($this->input['count']);
		//先判断token有没有过期
		if($token) {
			$checktoken = $this->pub->share_check_token($token);
		}
		else {
			$checktoken['msg'] = false;
		}
//		print_r($checktoken);
		
		if($checktoken['msg'] !== 'new' && $checktoken['msg'])
		{
			$platdata = $this->obj->get_by_app_plat($checktoken['data']['appid'],$checktoken['data']['platId']);
			include_once(CUR_CONF_PATH . 'lib/'.$this->settings['share_plat'][$checktoken['data']['type']]['name'].'_oauth.php');
			$action = $this->settings['share_plat'][$platdata['type']]['name'].'_home_timeline';
			$ret = $this->$action($checktoken, $since_id, $page, $count);
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
	
	public function sinaweibo_home_timeline($checktoken, $since_id, $page, $count)
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			if ($this->settings['share_plat'][$checktoken['data']['type']]['home_timelineurl']) {
				$url = $this->settings['share_plat'][$checktoken['data']['type']]['hmoe_timelineurl'];
			} else {
				$url = 'https://api.weibo.com/2/statuses/home_timeline';
			}
			$result = $c->get_home_timeline( $url,$since_id,$page,$count);
//             print_r($result);
			$ret = array();
			if(!empty($result['statuses']))
			{
				foreach($result['statuses'] as $k=>$v)
				{
					$ret[$k]['id'] = empty($v['id'])?'':$v['id'];
					$ret[$k]['text'] = empty($v['text'])?'':$v['text'];
					$ret[$k]['screen_name'] = empty($v['user']['screen_name'])?'':$v['user']['screen_name'];
					$ret[$k]['name'] = empty($v['user']['name'])?'':$v['user']['name'];
					$ret[$k]['uid'] = empty($v['user']['id'])?'':$v['user']['id'];
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
	
	public function txweibo_home_timeline($checktoken,$since_id,$page,$count)
	{
		if($checktoken)
		{
			$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
			if ($this->settings['share_plat'][$checktoken['data']['type']]['home_timelineurl']) {
				$url = $this->settings['share_plat'][$checktoken['data']['type']]['home_timelineurl'];
			} else {
				$url = 'https://open.t.qq.com/api/statuses/home_timeline';
			}			
			//新浪微博页码从1开始算  腾讯从0开始算
			$page = $page > 0 ? ($page - 1) : $page;
			$result = $c->get_home_timeline($url,$since_id,$page,$count,$checktoken['data']['access_token']['openid'],'tx');
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
						$ret[$k]['uid'] = empty($v['openid'])?'':$v['openid'];
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
							$ret[$k]['uid'] = empty($v['openid'])?'':$v['openid'];
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

$out = new get_home_timeline_api();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'get_home_timeline';
}
$out->$action();	
		
?>

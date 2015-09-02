<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','share_plat_type');
require_once(ROOT_PATH."global.php");
require_once(CUR_CONF_PATH."lib/functions.php");
class repostApi extends adminBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/public.class.php');
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->pub = new publicapi();
		$this->obj = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 根据系统id,分享平台id  
	 * @name share
	 * @access public
	 * @author 
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return 
	 */
	public function repost()
	{
		$appid = intval($this->input['appid']);
		$platid = intval($this->input['id']);
		$plat_type = intval($this->input['plat_type']);
		$weibo_id = urldecode($this->input['weibo_id']);
		$text = urldecode($this->input['text']);
		$is_comment = intval($this->input['is_comment']);//是否在转发的同时发表评论，0：否、1：评论给当前微博、2：评论给原微博、3：都评论，默认为0 。 
		$token = urldecode($this->input['access_plat_token']);
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
			include_once(CUR_CONF_PATH . 'lib/'.$this->settings['share_plat'][$checktoken['data']['type']]['name'].'_oauth.php');
			$action = $this->settings['share_plat'][$checktoken['data']['type']]['name'].'_repost';
			$ret = $this->$action($checktoken,$weibo_id,$text,$is_comment);
			if(!empty($ret['error']))
			{
				$this->errorOutput($ret['error']);
			}
			else
			{
				$this->addItem($ret);
				$this->output();
			}
			
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
				if($platid || !$plat_type)
				{
					$this->errorOutput('NO_APPID_PLATID');
				}
				else
				{
					$plat = $this->obj->get_plat_by_type($appid,$plat_type);
					if(empty($plat))
					{
						$this->errorOutput('NO_ANY_PLAT');
					}
					$ret = $oauthlogin->oauthlogin($appid,$plat['id'],$token);
				}
			}
			$ret['error'] = 1;
			$this->addItem($ret);
			$this->output();			
		}
	}
	
	public function sinaweibo_repost($checktoken,$weibo_id,$text,$is_comment)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$result = $c->repost($this->settings['share_plat'][$checktoken['data']['type']]['reposturl'],$weibo_id,$text,$is_comment,'',true);
		if(empty($result['created_at']))
		{
			$ret['error'] = empty($result['error'])?'empty':$result['error'];
		}
		else
		{
			$ret['msg'] = 'ok';
		}
		return $ret;
	}
	
	public function txweibo_repost($checktoken,$weibo_id,$text,$is_comment)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$result = $c->repost($this->settings['share_plat'][$checktoken['data']['type']]['reposturl'],$weibo_id,$text,$is_comment,$checktoken['data']['openid'],'tx');
		if(!empty($result['errcode']))
		{
			$ret['error'] = empty($result['msg'])?'empty':$result['msg'];
		}
		else
		{
			$ret['msg'] = 'ok';
		}
		return $ret;
	}
	
	public function other_repost($checktoken,$weibo_id,$text,$is_comment)
	{
		$c = new ClientV2( $checktoken['data']['akey'],$checktoken['data']['skey'],$checktoken['data']['response_type'],$checktoken['data']['access_token']['access_token'] );
		$result = $c->repost($checktoken['data']['platdata']['reposturl'],$weibo_id,$text,$is_comment,'',true);
		if(empty($result['created_at']))
		{
			$ret['error'] = empty($result['error'])?'empty':$result['error'];
		}
		else
		{
			$ret['msg'] = 'ok';
		}
		return $ret;
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

$out = new repostApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'repost';
}
$out->$action();
?>
